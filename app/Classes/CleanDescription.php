<?php

namespace App\Classes;

use App\Classes\Conversion;

class CleanDescription extends Conversion {
    protected function convert() {
        $parts = array_filter(explode(' ', strtolower($this->originalValue)));

        if (!is_array($parts) || count($parts) === 0) {
            return;
        }

        if ($parts[0] === 'nummer') {
            $subset = array_slice($parts, 5, -7);
        }

        if (array_search('bic', $parts)) {
            $subset = array_slice($parts, 5, -12);
        }

        if (isset($subset)) {
            $this->convertedValue = implode(' ', $subset);
        }
    }
}
