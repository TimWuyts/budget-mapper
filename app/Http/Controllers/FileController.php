<?php

namespace App\Http\Controllers;

use App\Imports\TransactionsImport;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FileController extends Controller
{
    public function __construct()
    {
        $this->columnMapping = collect([
            [
                'input' => 'Details',
                'output' => 'Category Name',
                //'conversions' => [new CategoryDetection()]
            ],
            [
                'input' => 'Uitvoeringsdatum',
                'output' => 'Date',
                //'conversions' => [new DateFormatter()],
            ],
            [
                'input' => 'Bedrag',
                'output' => 'Amount',
                'conversions' => null
            ],
            [
                'input' => 'Details',
                'output' => 'Note',
                //'conversions' => [new CleanDescription()]
            ]
        ]);

        $this->categoryMapping = collect([
            [
                'name' => 'Groceries',
                'income' => false,
                'expense' => true,
                'keywords' => [
                    'delhaize',
                    'aldi',
                    'albert heijn',
                    'okay',
                    'colruyt'
                ]
            ]
        ]);
    }

    /**
     * @return Response
     */
    public function form()
    {
        return view('form');
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function import(Request $request)
    {
        $file = $request->file('file');

        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        $document = $reader->load($file);
        $sheet = $document->getActiveSheet();

        foreach($sheet->toArray() as $index => $row) {
            if ($index === 0) {
                $this->parseHeader($row);
            } else {
                $this->parseContent($row);
            }
        };

        return back();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        // return Excel::download(new TransactionsExport, 'users-collection.xlsx');
    }

    private function parseHeader($columns) {
        foreach ($columns as $index => $column) {
            $this->columnMapping->filter(function ($definition, $column) {
                return ($definition['input'] === $column);
            });

            dd($this->columnMapping);
        }
    }

    private function parseContent($columns) {

    }
}
