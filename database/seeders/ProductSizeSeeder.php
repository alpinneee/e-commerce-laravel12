<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductSize;
use Illuminate\Database\Seeder;

class ProductSizeSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $sizes = [36, 37, 38, 39, 40, 41, 42, 43, 44];

        foreach ($products as $product) {
            // Tambahkan beberapa ukuran random untuk setiap produk
            $randomSizes = collect($sizes)->random(rand(3, 6));
            
            foreach ($randomSizes as $size) {
                ProductSize::create([
                    'product_id' => $product->id,
                    'size' => $size,
                    'stock' => rand(5, 20),
                ]);
            }
        }
    }
}