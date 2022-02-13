<?php

namespace App\Classes;

class Conversion {
    protected $originalValue;
    protected $convertedValue;

    public function __construct($value) {
        $this->originalValue = $value;
        $this->convertedValue = $this->originalValue;

        $this->convert();
    }

    protected function convert() {
        $this->convertedValue = $this->originalValue;
    }

    public function getOriginalValue() {
        return $this->originalValue;
    }

    public function getValue() {
        return $this->convertedValue;
    }
}
