<?php

namespace App\Classes;

use App\Classes\Conversion;

class DetectCategory extends Conversion {
    public function __construct($value)
    {
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
                    'vita',
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
            if (stripos($str,$a) !== false) return true;
        }

        return false;
    }
}
