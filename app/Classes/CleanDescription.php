<?php

namespace App\Classes;

use App\Classes\Conversion;

class CleanDescription extends Conversion {
    protected function convert() {
        $replaceMultipleSpaces = preg_replace('/ +/', ' ', $this->originalValue);
        $replaceSemicolonSpaces = preg_replace('/ :/', ':', $replaceMultipleSpaces);

        $this->convertedValue = $replaceSemicolonSpaces;
    }
}
