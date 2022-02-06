<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;

class TransactionsExport implements FromCollection
{
    public function collection()
    {
        return User::all();
    }
}
