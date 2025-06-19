<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Member;
use App\Models\Province;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Laravel\Socialite\Facades\Socialite;
use App\Models\LoginHistory;

class AuthenticationController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function login()
    {
        if (Auth::guard('member')->check()) {
            $user = Auth::guard('member')->user();

            if ($user->status_phone_number_verification == 1) {
                $this->recordLoginHistory($user);
                return redirect()->intended('/');
            }

            Auth::guard('member')->logout();
            $this->request->session()->invalidate();
            $this->request->session()->regenerateToken();

            return redirect('login')->withErrors([
                'email' => 'Segera verifikasi nomor telepon anda.',
            ]);
        }

        $credentials = $this->request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $credentials['email'];
        $password = $credentials['password'];

        $potentialUsers = Member::where('email', $email)
            ->orderBy('created_at', 'desc')
            ->get();

        $authenticated = false;
        $user = null;

        foreach ($potentialUsers as $potentialUser) {
            if (Hash::check($password, $potentialUser->password)) {
                $user = $potentialUser;
                $authenticated = true;
                break;
            }
        }

        if ($authenticated) {
            if ($user->status_phone_number_verification == 1) {
                Auth::guard('member')->login($user);
                $this->request->session()->regenerate();
                $this->recordLoginHistory($user);
                return redirect()->intended('/');
            }

            if ($user->status_aktif == 0 && $user->status_hapus == 1) {
                return back()->withErrors([
                    'email' => 'Email yang anda masukan tidak aktif.',
                ])->onlyInput('email');
            }

            return redirect('login')->withErrors([
                'email' => 'Segera verifikasi nomor telepon anda.',
            ])->onlyInput('email');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    private function recordLoginHistory(Member $user): void
    {
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
        if (Auth::guard('member')->check()) {
            return redirect('/');
        }
        
        $provinces = Province::get();

        return view('registrasi')->with([
            'provinces' => $provinces
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'google_id' => ['nullable'],
            'nama' => ['required', 'array'],
            'nama.0' => ['required', 'string', 'max:255'],
            'nama.1' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                Rule::unique('m_peserta')->where(function ($query) {
                    return $query->where('status_aktif', 1);
                })
            ],
            'password' => ['required', 'min:8'],
            'confirmpassword' => 'required|same:password',
            'alamat' => ['required', 'string', 'max:255'],
            'no_hp' => ['required', 'string', 'max:20'],
            'provinsi' => ['required'],
            'kota' => ['required'],
            'kecamatan' => ['required'],
            'kelurahan' => ['required'],
            'kode_pos' => ['nullable', 'string', 'max:10'],
        ], [
            'nama.0.required' => 'Nama depan wajib diisi.',
            'nama.1.required' => 'Nama belakang wajib diisi.',
            'nama.0.max' => 'Nama depan tidak boleh lebih dari 255 karakter.',
            'nama.1.max' => 'Nama belakang tidak boleh lebih dari 255 karakter.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'confirmpassword.required' => 'Konfirmasi password wajib diisi.',
            'confirmpassword.same' => 'Konfirmasi password tidak cocok dengan password.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_hp.required' => 'No. Handphone wajib diisi.',
            'provinsi.required' => 'Provinsi wajib diisi.',
            'kota.required' => 'Kota wajib diisi.',
            'kecamatan.required' => 'Kecamatan wajib diisi.',
            'kelurahan.required' => 'Kelurahan wajib diisi.',
        ]);

        $name = $request->input('nama');

        $data = $request->only([
            'google_id',
            'email',
            'password',
            'alamat',
            'no_hp',
            'provinsi',
            'kota',
            'kecamatan',
            'kelurahan',
            'kode_pos'
        ]);

        $firstName = $name[0];
        $lastName = $name[1];

        $data['nama'] = "$firstName $lastName";
        $data['nama_depan'] = $firstName;
        $data['nama_belakang'] = $lastName;
        $data['password'] = Hash::make($data['password']);

        $data['status_aktif'] = 0;

        $verificationToken = Member::generateVerificationToken();
        $verificationCode = Member::generateVerificationCode();

        $data['verification_token'] = $verificationToken;
        $data['verification_code'] = $verificationCode;
        $data['verification_code_expires_at'] = Carbon::now()->addMinutes(10);

        $google_id = $data['google_id'];

        $member = Member::where('google_id', $google_id)->first();

        try {
            if ($member) {
                $member->update($data);
            } else {
                $member = Member::create($data);
            }

            $this->sendVerificationCodeViaQontak($member, $verificationCode);

            return redirect()->route('phone-number-verification', ['token' => $verificationToken]);

        } catch (\Exception $e) {
            return redirect()->back()->with([
                'error' => $e->getMessage()
            ]);
        }
    }

    public function confirmPhoneNumber(Request $request)
    {
        $member = Member::where('email', $request->email)->where('no_hp', $request->no_hp)->where('status_aktif', 1)->where('status_phone_number_verification', 0)->first();

        if (!$member) {
            return back()->withErrors(['message' => 'User tidak ditemukan.']);
        }

        return view('confirm-phone-number', compact('member'));
    }

    public function postConfirmPhoneNumber(Request $request)
    {
        $request->validate([
            'no_hp' => ['required', 'string', 'max:20'],
        ]);

        $member = Member::where('email', $request->email)->where('status_aktif', 1)->where('status_phone_number_verification', 0)->first();

        if (!$member) {
            return back()->withErrors(['message' => 'User tidak ditemukan.']);
        }

        $member->no_hp = $request->no_hp;
        $member->save();

        $verificationCode = Member::generateVerificationCode();
        $member->verification_code = $verificationCode;
        $member->verification_code_expires_at = \Carbon\Carbon::now()->addMinutes(10);
        $member->verification_token = Member::generateVerificationToken();
        $member->save();

        $this->sendVerificationCodeViaQontak($member, $verificationCode);

        return redirect()->route('phone-number-verification', ['token' => $member->verification_token]);
    }

    public function phoneNumberVerification($token)
    {
        $member = Member::where('verification_token', $token)->first();

        if ($member->status_phone_number_verification == 1) {
            Auth::guard('member')->login($member);
            
            return redirect('/')->with('success', 'Nomor telepon berhasil diverifikasi dan akun Anda aktif!');
        }

        if (!$member) {
            abort(404, 'Token verifikasi tidak valid.');
        }
        return view('phone-number-verification', compact('member'));
    }

    public function postPhoneNumberVerification(Request $request, $token)
    {
        $request->validate([
            'verification_code' => 'required|digits:6',
        ], [
            'verification_code.required' => 'Kode verifikasi wajib diisi.',
            'verification_code.digits' => 'Kode verifikasi harus 6 digit.',
        ]);

        $member = Member::where('verification_token', $token)->first();

        if (!$member) {
            return redirect()->back()->withErrors(['message' => 'Token verifikasi tidak valid.']);
        }

        if ($request->verification_code !== $member->verification_code) {
            return redirect()->back()->withErrors(['verification_code' => 'Kode verifikasi tidak cocok.']);
        }

        if (Carbon::now()->gt($member->verification_code_expires_at)) {
            return redirect()->back()->withErrors(['verification_code' => 'Kode verifikasi sudah kadaluarsa.']);
        }

        $member->status_aktif = 1;
        $member->status_phone_number_verification = 1;
        $member->save();

        Auth::guard('member')->login($member);

        return redirect('/')->with('success', 'Nomor telepon berhasil diverifikasi dan akun Anda aktif!');
    }

    public function requestVerificationCode(Request $request, $token)
    {
        $member = Member::where('verification_token', $token)->first();

        if (!$member) {
            return response()->json(['message' => 'Token verifikasi tidak valid.'], 404);
        }

        if (Carbon::now()->lte($member->verification_code_expires_at)) {
            return response()->json(['message' => 'Kode verifikasi masih berlaku. Silakan periksa WhatsApp Anda.', 'redirect' => route('phone-number-verification', ['token' => $token])], 200);
        }

        $verificationCode = Member::generateVerificationCode();
        $member->verification_code = $verificationCode;
        $member->verification_code_expires_at = Carbon::now()->addMinutes(10);
        $member->save();

        $this->sendVerificationCodeViaQontak($member, $verificationCode);

        return response()->json(['message' => 'Kode verifikasi baru telah dikirim.'], 200);

    }

    private function sendVerificationCodeViaQontak(Member $member, $verificationCode)
    {
        $url = 'https://service-chat.qontak.com/api/open/v1/broadcasts/whatsapp/direct';
        $token = env('QONTAK_API_KEY');

        $phoneNumber = $member->no_hp;
        $phoneNumber = preg_replace('/\D/', '', $phoneNumber);
        if (strpos($phoneNumber, '0') === 0) {
            $phoneNumber = '62' . substr($phoneNumber, 1);
        } else if(strpos($phoneNumber, '62') !== 0) {
            $phoneNumber = '62' . $phoneNumber;
        }

        $data_qontak = [
            "to_name" => $member->nama,
            "to_number" => $phoneNumber,
            "message_template_id" => "b122ad63-435a-4d73-aaf2-409d50e6bca5",
            "channel_integration_id" => env('QONTAK_CHANNEL_INTEGRATION_ID'),
            "language" => [
                "code" => "id",
            ],
            "parameters" => [
                "header" => [
                    "format" => "DOCUMENT",
                    "params" => [],
                ],
                "body" => [
                    [
                        "key" => "0",
                        "value_text" => (string) $verificationCode,
                        "value" => "code",
                    ]
                ],
                "buttons" => [
                    [
                        "index" => "0",
                        "type" => "url",
                        "value" => (string) $verificationCode,
                    ]
                ]
            ]
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/json',
        ])->post($url, $data_qontak);

        if ($response->failed()) {
           return ['message' => "Gagal mengirim notifikasi WhatsApp: " . $response->body()];
        } else {
            return ['message' => "Berhasil mengirim notifikasi WhatsApp"];
        }
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $userFromGoogle = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            report($e);
            return redirect('/login')->withErrors(['google' => 'Google login failed. Please try again.']);
        }

        $userToLogin = Member::where('google_id', $userFromGoogle->getId())->first();

        if (!$userToLogin) {
            $userToLogin = Member::where('email', $userFromGoogle->getEmail())
                                ->where('status_aktif', 1)
                                ->first();

            if ($userToLogin) {
                $userToLogin->google_id = $userFromGoogle->getId();
                $userToLogin->save();
            } else {
                $fullName = $userFromGoogle->getName();
                $nameParts = explode(' ', $fullName, 2);

                return redirect()->route('registration', [
                    'google_id' => $userFromGoogle->getId(),
                    'firstName' => $nameParts[0],
                    'lastName' => $nameParts[1] ?? '',
                    'email' => $userFromGoogle->getEmail(),
                ]);
            }
        }


        if ($userToLogin->status_phone_number_verification == 0) {
            if ($userToLogin->verification_code_expires_at && \Carbon\Carbon::now()->lte($userToLogin->verification_code_expires_at)) {
                return redirect()->route('phone-number-verification', ['token' => $userToLogin->verification_token]);
            } else {
                $verificationCode = Member::generateVerificationCode();
                $verificationToken = Member::generateVerificationToken();
                $userToLogin->verification_code = $verificationCode;
                $userToLogin->verification_token = $verificationToken;
                $userToLogin->verification_code_expires_at = \Carbon\Carbon::now()->addMinutes(10);
                $userToLogin->save();

                $this->sendVerificationCodeViaQontak($userToLogin, $verificationCode);

                return redirect()->route('phone-number-verification', ['token' => $userToLogin->verification_token]);
            }
        }

        Auth::guard('member')->login($userToLogin);
        return redirect('/');
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
        return view('reqreset');
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

        // Mail::to($email)->send(new EmailResetPassword($email));

        return back()->with("success", "Email reset password dikirim");
    }
}