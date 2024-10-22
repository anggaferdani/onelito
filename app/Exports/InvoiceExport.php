<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class InvoiceExport implements FromView
{
    private $data;

    public function __construct($data) {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('admin.pages.auction-bot.invoice.index', $this->data);
    }
}
