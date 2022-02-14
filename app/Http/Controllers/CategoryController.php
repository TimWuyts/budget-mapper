<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Keyword;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(Request $request)
    {
        Category::create([
            'name' => $request->get('name')
        ]);

        return back();
    }

    public function update(Request $request, $id) {
        $category = Category::find($id);

        $category->name = $request->get('name');
        $category->income = $request->has('income');
        $category->expense = $request->has('expense');
        $category->save();

        $keywords = array_filter(explode(',', $request->get('keywords')));

        if (!empty($keywords)) {
            $collection = [];

            foreach ($keywords as $keyword) {
                $collection[] = new Keyword(['label' => trim($keyword)]);
            }

            $category->keywords()->delete();
            $category->keywords()->saveMany($collection);
        }

        return back();
    }

    public function delete(Request $request, $id) {
        Category::destroy($id);

        return back();
    }
}
