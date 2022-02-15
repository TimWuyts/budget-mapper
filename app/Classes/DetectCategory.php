<?php

namespace App\Classes;

use App\Models\Category;
use App\Classes\Conversion;
use Illuminate\Database\Eloquent\Collection;

class DetectCategory extends Conversion {

    private Collection $categoryMapping;

    public function __construct($value)
    {
        $this->categoryMapping = Category::with('keywords')->get();
        $this->categoryMapping->each(function($category) {
            $category->keywords = $category->keywords->pluck('label');
        });

        parent::__construct($value);
    }

    protected function convert()
    {
        $description = strtolower($this->originalValue);

        $this->convertedValue = null;
        $this->categoryMapping->each(function($category) use($description) {
            if ($this->contains($description, $category->keywords->toArray())) {
                $this->convertedValue = $category->name;

                return false;
            }
        });
    }

    private function contains($str, array $arr): bool
    {
        foreach($arr as $a) {
            if (stripos($str, $a) !== false) return true;
        }

        return false;
    }
}
