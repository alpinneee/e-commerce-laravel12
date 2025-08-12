<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Http\Request;

class ProductSizeController extends Controller
{
    public function index()
    {
        $products = Product::with('sizes')->get();
        return view('admin.product-sizes.index', compact('products'));
    }

    public function show(Product $product)
    {
        $product->load('sizes');
        $availableSizes = range(36, 44);
        return view('admin.product-sizes.show', compact('product', 'availableSizes'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'size' => 'required|integer|min:36|max:44',
            'stock' => 'required|integer|min:0',
        ]);

        $product->sizes()->updateOrCreate(
            ['size' => $request->size],
            ['stock' => $request->stock]
        );

        return redirect()->back()->with('success', 'Ukuran berhasil ditambahkan/diperbarui');
    }

    public function destroy(Product $product, ProductSize $size)
    {
        $size->delete();
        return redirect()->back()->with('success', 'Ukuran berhasil dihapus');
    }
}