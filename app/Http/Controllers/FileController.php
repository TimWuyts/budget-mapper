<?php

namespace App\Http\Controllers;

use App\Classes\CleanDescription;
use App\Classes\DetectCategory;
use App\Classes\FormatDate;
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
                'conversions' => [DetectCategory::class]
            ],
            (object) [
                'input' => 'Uitvoeringsdatum',
                'output' => 'Date',
                'conversions' => [FormatDate::class],
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
        $reader->setReadDataOnly(true);

        $document = $reader->load($file);

        $this->importData = $document->getActiveSheet()->toArray();
        $this->process();
        $this->export();

        return back();
    }

    private function process() {
        $this->parseHeader();
        $this->parseContent();

        // dd($this->exportData);
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function export()
    {
        $sheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet->getActiveSheet()->fromArray($this->exportData);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode('budget-mapping.csv').'"');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Csv($sheet);
        $writer->save('php://output');

        exit();
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
