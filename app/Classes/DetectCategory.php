<?php

namespace App\Classes;

use App\Models\Category;
use App\Classes\Conversion;

class DetectCategory extends Conversion {
    public function __construct($value)
    {
        $this->categoryMapping = Category::with('keywords')->get()->toArray();

        // TODO: flatten keywords
        dd($this->categoryMapping);

        parent::__construct($value);
    }

    protected function convert()
    {
        $description = strtolower($this->originalValue);

        $this->convertedValue = null;
        $this->categoryMapping->each(function($category) use($description) {
            if ($this->contains($description, $category->keywords)) {
                $this->convertedValue = $category->name;

                return false;
            }
        });
    }

    function contains($str, array $arr): bool
    {
        foreach($arr as $a) {
            if (stripos($str, $a) !== false) return true;
        }

        return false;
    }
}
