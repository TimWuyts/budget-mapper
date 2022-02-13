<?php

namespace App\Http\Controllers;

use App\Classes\CleanDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class FileController extends Controller
{
    private array $importData = [];
    private array $exportData = [];

    public function __construct()
    {
        $this->columnMapping = collect([
            (object) [
                'input' => 'Details',
                'output' => 'Category Name',
                //'conversions' => [new CategoryDetection()]
            ],
            (object) [
                'input' => 'Uitvoeringsdatum',
                'output' => 'Date',
                //'conversions' => [new DateFormatter()],
            ],
            (object) [
                'input' => 'Bedrag',
                'output' => 'Amount',
                'conversions' => null
            ],
            (object) [
                'input' => 'Details',
                'output' => 'Note',
                'conversions' => [CleanDescription::class]
            ]
        ]);

        $this->categoryMapping = collect([
            (object) [
                'name' => 'Boodschappen',
                'income' => false,
                'expense' => true,
                'keywords' => [
                    'delhaize',
                    'aldi',
                    'albert heijn',
                    'okay',
                    'colruyt'
                ]
            ],
            (object) [
                'name' => 'Eten & Drinken',
                'income' => false,
                'expense' => true,
                'keywords' => [
                    'vitta',
                    'frietjes',
                    'donalds',
                    'burger'
                ]
            ],
            (object) [
                'name' => 'Gezondheidszorg',
                'income' => false,
                'expense' => true,
                'keywords' => [
                    'apotheek',
                    'dokter',
                    'specialist',
                    'psycholoog',
                    'winandy',
                    'elisabeth'
                ]
            ],
            (object) [
                'name' => 'Nutsvoorzieningen',
                'income' => false,
                'expense' => true,
                'keywords' => [
                    'telenet',
                    'mega',
                    'pidpa',
                    'fluvius'
                ]
            ],
            (object) [
                'name' => 'Onderwijs',
                'income' => false,
                'expense' => true,
                'keywords' => [
                    'klavertje',
                    'fluxus',
                    'school'
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

        $this->importData = $document->getActiveSheet()->toArray();
        $this->process();

        return back();
    }

    private function process() {
        $this->parseHeader();
        $this->parseContent();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        // return Excel::download(new TransactionsExport, 'users-collection.xlsx');
    }

    private function parseHeader() {
        $importHeaderColumns = array_map('strtolower', $this->importData[0]);
        $exportHeaderColumns = [];

        foreach ($this->columnMapping as $mapping) {
            $columnIndex =  array_search(strtolower($mapping->input), $importHeaderColumns, true);

            if ($columnIndex === false) {
                continue;
            }

            $mapping->inputColumn = $columnIndex;
            $exportHeaderColumns[] = $mapping->output;
        }

        $this->exportData[] = $exportHeaderColumns;
    }


    private function parseContent() {
        $rows = array_splice($this->importData, 1);

        foreach($rows as $columns) {
            $exportRowColumns = [];

            foreach($this->columnMapping as $mapping) {
                $data = $columns[$mapping->inputColumn];
                $conversions = isset($mapping->conversions) ? $mapping->conversions : null;

                if (!empty($conversions)) {
                    foreach($conversions as $conversion) {
                        $data = (new $conversion($data))->getValue();
                    }
                }

                $exportRowColumns[] = $data;
            }

            $this->exportData[] = $exportRowColumns;
        }
    }
}
