<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();

        foreach ($products as $product) {
            $seedBase = $product->slug;

            $images = [
                [
                    'image_path' => "https://picsum.photos/seed/{$seedBase}-1/600/600",
                    'is_primary' => true,
                    'sort_order' => 1,
                    'alt_text' => $product->name . ' primary image',
                ],
                [
                    'image_path' => "https://picsum.photos/seed/{$seedBase}-2/600/600",
                    'is_primary' => false,
                    'sort_order' => 2,
                    'alt_text' => $product->name . ' secondary image',
                ],
            ];

            foreach ($images as $image) {
                ProductImage::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'sort_order' => $image['sort_order'],
                    ],
                    [
                        'image_path' => $image['image_path'],
                        'is_primary' => $image['is_primary'],
                        'alt_text' => $image['alt_text'],
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]
                );
            }
        }
    }
}