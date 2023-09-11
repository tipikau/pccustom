<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        return view('auth.categories.index', compact('categories'));
    }
    public function create():string
    {
        return view('auth.categories.form');
    }
    public function store(CategoryRequest $request) : string
    {
        $params = $request->all();
        unset($params['image']);
        if ($request->has('image')) {
            $params['image'] = $request->file('image')->store('categories');
        }

        Category::create($params);
        return redirect()->route('categories.index');
    }
}
