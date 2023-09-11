<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::get();
        return response()->json(['data' => $products]);
    }

    public function create()
    {
        $categories = Category::get();
        return response()->json(['data' => $categories]);
    }

    public function store(ProductRequest $request)
    {
        $params = $request->all();
        unset($params['image']);
        if ($request->has('image')) {
            $params['image'] = $request->file('image')->store('products');
        }
        $product = Product::create($params);
        return response()->json(['message' => 'Product created', 'data' => $product], 201);
    }

    public function show(Product $product)
    {
        return response()->json(['data' => $product]);
    }

    public function edit(Product $product)
    {
        $categories = Category::get();
        return response()->json(['data' => ['product' => $product, 'categories' => $categories]]);
    }

    public function update(ProductRequest $request, Product $product)
    {
        $params = $request->all();
        unset($params['image']);
        if ($request->has('image')) {
            Storage::delete($product->image);
            $params['image'] = $request->file('image')->store('products');
        }
        $product->update($params);
        return response()->json(['message' => 'Product updated', 'data' => $product]);
    }

    public function destroy(Product $product)
    {
        Storage::delete($product->image);
        $product->delete();
        return response()->json(['message' => 'Product deleted']);
    }







}
