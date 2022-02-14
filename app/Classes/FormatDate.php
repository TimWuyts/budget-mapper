<?php

namespace App\Classes;

use App\Classes\Conversion;

class FormatDate extends Conversion {
    private $inputFormat = 'd/m/Y';
    private $outputFormat = 'd/m/Y';

    protected function convert() {
        $date = new \DateTime();
        $date->createFromFormat($this->inputFormat, $this->originalValue);

        $this->convertedValue = $date->format($this->outputFormat);
    }
}
