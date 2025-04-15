<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Province;
use App\Mail\UserVerified;
use App\Models\LoginHistory;
use App\Mail\EmailVerification;
use Illuminate\Validation\Rule;
use App\Mail\EmailResetPassword;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class AuthenticationController extends Controller
{
    public function login()
    {
        if (Auth::guard('member')->check()) {
            $user = Auth::guard('member')->user();

            if ($user->email_verified_at !== null && $user->status_hapus == 0) {
                $ipAddress = $this->request->ip();
                $userAgent = $this->request->header('User-Agent');
                $sessionId = session()->getId();

                $existingHistory = LoginHistory::where('peserta_id', $user->id_peserta)
                    ->where('ip_address', $ipAddress)
                    ->where('user_agent', $userAgent)
                    ->where('session_id', $sessionId)
                    ->first();

                if (!$existingHistory) {
                    LoginHistory::create([
                        'peserta_id' => $user->id_peserta,
                        'ip_address' => $ipAddress,
                        'user_agent' => $userAgent,
                        'session_id' => $sessionId,
                    ]);
                }
                
                return redirect()->intended('/');
            }

            Auth::guard('member')->logout();

            $this->request->session()->invalidate();

            $this->request->session()->regenerateToken();

            if ($user->status_hapus == 1) {
                return redirect('login')->withErrors([
                    'email' => 'Your account has been deleted.',
                ]);
            }

            return redirect('login')->withErrors([
                'email' => 'Segera verifikasi email anda.',
            ]);
        }

        $credentials = $this->request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::guard('member')->attempt($credentials)) {
            $user = Auth::guard('member')->user();

            if ($user->email_verified_at !== null && $user->status_aktif == 1 && $user->status_hapus == 0) {

                $this->request->session()->regenerate();

                $ipAddress = $this->request->ip();
                $userAgent = $this->request->header('User-Agent');
                $sessionId = session()->getId();

                $existingHistory = LoginHistory::where('peserta_id', $user->id_peserta)
                    ->where('ip_address', $ipAddress)
                    ->where('user_agent', $userAgent)
                    ->where('session_id', $sessionId)
                    ->first();

                if (!$existingHistory) {
                    LoginHistory::create([
                        'peserta_id' => $user->id_peserta,
                        'ip_address' => $ipAddress,
                        'user_agent' => $userAgent,
                        'session_id' => $sessionId,
                    ]);
                }

                return redirect()->intended('/');
            }

            Auth::guard('member')->logout();

            $this->request->session()->invalidate();

            $this->request->session()->regenerateToken();

            if ($user->status_hapus == 1) {
                return back()->withErrors([
                    'email' => 'Your account has been deleted.',
                ])->onlyInput('email');
            }

            if ($user->status_aktif == 0) {
                return back()->withErrors([
                    'email' => 'Your account is inactive.',
                ])->onlyInput('email');
            }

            return redirect('login')->withErrors([
                'email' => 'Segera verifikasi email anda',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout()
    {
        Auth::guard('member')->logout();

        $this->request->session()->invalidate();

        $this->request->session()->regenerateToken();

        return redirect('/');
    }

    public function logoutFromDevice($sessionId)
    {
        $loginHistory = LoginHistory::where('session_id', $sessionId)->first();

        if ($loginHistory) {
            Session::getHandler()->destroy($sessionId);

            $loginHistory->update([
                'status' => 2,
            ]);

            return redirect()->back()->with('success', 'Anda telah berhasil keluar dari perangkat lain.');
        }

        return redirect()->back()->with('error', 'Sesi tidak ditemukan.');
    }

    public function registration()
    {
        $provinces = Province::get();

        return view('registrasi')->with([
            'provinces' => $provinces
        ]);
    }

    public function register()
    {
        $this->request->validate([
            'nama' => ['required'],
            'email' => [
                'required',
                'email',
                Rule::unique('m_peserta')->where(function ($query) {
                    return $query->where('status_hapus', 0);
                })
            ],
            // 'email' => ['required', 'email', 'unique:m_peserta,email'],
            'password' => ['required'],
            'alamat' => ['required'],
            'no_hp' => ['required'],
            'provinsi' => ['required'],
            'kota' => ['required'],
            'kecamatan' => ['required'],
            'kelurahan' => ['required'],
        ]);

        $name = $this->request->input('nama');

        $data = $this->request->only([
            'nama',
            'email',
            'password',
            'alamat',
            'no_hp',
            'provinsi',
            'kota',
            'kecamatan',
            'kelurahan',
        ]);

        $data['status_aktif'] = 0;

        $firstName = $name[0];
        $lastName = $name[1];

        $data['nama'] = "$firstName $lastName";
        $data['nama_depan'] = $firstName;
        $data['nama_belakang'] = $lastName;

        $createMember = Member::create($data);

        Mail::to($data['email'])->send(new EmailVerification($data['email']));

        if ($createMember) {
            return redirect()->back()->with([
                'success' => true,
                'message' => 'Sukses Menambahkan Peserta',

            ], 200);
        } else {
            return redirect()->back()->with([
                'success' => false,
                'message' => 'Gagal Menambahkan Peserta'
            ], 500);
        }
    }

    public function adminLogin()
    {
        $credentials = $this->request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::guard('admin')->attempt($credentials)) {
            $this->request->session()->regenerate();

            return redirect('/admin/dashboard');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function adminLogout()
    {
        Auth::guard('admin')->logout();

        $this->request->session()->invalidate();

        $this->request->session()->regenerateToken();

        return redirect('/admin-login');
    }

    public function loginPage()
    {
        if (Auth::guard('member')->check()) {
            return redirect()->intended('/');
        }

        return view('login', [
            "title" => "login"
        ]);
    }

    public function emailVerification()
    {
        $token = $this->request->click;

        try {
            $data = Crypt::decrypt($token);
            if ($data) {
                $user = Member::where('email', $data['email'])
                    ->where('id_peserta', $data['id'])->first();

                if (!$user) {
                    return response()->json(['message' => 'User Not Found']);
                }

                if ($user->email_verified_at !== null) {
                    return redirect('login')->with([
                        'message' => 'Your Email Already Verified',
                    ]);
                }

                $user->email_verified_at = Carbon::now();
                $user->status_aktif = 1;
                $user->save();

                Mail::to('onelito.koi@gmail.com')->send(new UserVerified($user));

                session()->flash('message', 'Your Email Successfully Verified',);

                return redirect('login')->with([
                    'message' => 'Your Email Successfully Verified',
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function changePassword()
    {
        $this->request->validate([
            'password' => 'required|confirmed',
        ]);

        $member = Member::findOrFail(Auth::guard('member')->user()->id_peserta);
        $member->password = $this->request->password;
        $member->save();

        return back()->with("status", "Password changed successfully!");
    }

    public function reqreset()
    {

        return view('reqreset')->with([
            // 'provinces' => $provinces
        ]);
    }

    public function reqresetProsses()
    {
        $this->request->validate([
            'email' => 'required',
        ]);

        $email = $this->request->input('email');

        $user = Member::where('email', $email)->first();

        if ($user === null) {
            return back()->withErrors("Email belum terdaftar");
        }

        Mail::to($email)->send(new EmailResetPassword($email));

        return back()->with("success", "Email reset password dikirim");
    }

    public function emailChangePassword()
    {
        $token = $this->request->click;

        try {
            $data = Crypt::decrypt($token);
            if ($data) {
                $user = Member::where('email', $data['email'])
                    ->where('id_peserta', $data['id'])->first();

                if (!$user) {
                    return redirect('login')
                        ->with(['message' => 'User Not Found']);
                }

                // $user->email_verified_at = Carbon::now();
                // $user->save();

                // session()->flash('message','Your Email Successfully Verified',);

                return view('reqreset_change_password')->with([
                    // 'provinces' => $provinces
                ]);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function emailChangePasswordProsess()
    {
        $token = $this->request->token;

        try {
            $data = Crypt::decrypt($token);
            if ($data) {
                $user = Member::where('email', $data['email'])
                    ->where('id_peserta', $data['id'])->first();

                if (!$user) {
                    return redirect()->back()
                        ->with(['message' => 'User Not Found']);
                }

                $user->password = $this->request->password;
                $user->save();

                return redirect('login')->with("password", "Password berhasil diubah, silahkan login menggunakan password baru");
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
