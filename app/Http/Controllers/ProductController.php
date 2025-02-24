<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();
        return view('admin.all-products', compact('products'));
    }

    public function destroy(Product $product)
    {
        $product->clearMediaCollection('image');
        $product->delete();
        return redirect()->route('all-products')->with([
            'status' => 'success',
            'message' => 'Product deleted successfully!',
        ]);
    }

    public function products()
    {
        $products = Product::latest()->get();
        return response()->json([
            'status' => 'success',
            'products' => $products,
        ], 200);
    }
}
