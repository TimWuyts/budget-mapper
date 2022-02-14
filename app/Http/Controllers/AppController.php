<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class AppController extends Controller
{
    /**
     * @return Response
     */
    public function app()
    {
        $categories = Category::all();

        return view('app', [
            'categories' => $categories
        ]);
    }
}
