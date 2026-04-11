<?php

namespace Database\Seeders;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $items = [
            ['name' => 'Urban Classic Loafers', 'category_id' => 2, 'brand' => 'StrideMax', 'model' => 'UCL-100', 'price' => 28500, 'compare_price' => 32000, 'cost_price' => 18000],
            ['name' => 'Executive Leather Oxford', 'category_id' => 2, 'brand' => 'StrideMax', 'model' => 'ELO-210', 'price' => 42000, 'compare_price' => 46500, 'cost_price' => 27000],
            ['name' => 'Street Runner Sneakers', 'category_id' => 2, 'brand' => 'FlexRun', 'model' => 'SRS-330', 'price' => 23500, 'compare_price' => 27500, 'cost_price' => 15000],
            ['name' => 'Classic Suede Slip-On', 'category_id' => 2, 'brand' => 'StrideMax', 'model' => 'CSS-140', 'price' => 25500, 'compare_price' => 30000, 'cost_price' => 16500],

            ['name' => 'Women Elegant Heels', 'category_id' => 3, 'brand' => 'BelleWalk', 'model' => 'WEH-110', 'price' => 29500, 'compare_price' => 34500, 'cost_price' => 19000],
            ['name' => 'Comfort Flat Sandals', 'category_id' => 3, 'brand' => 'BelleWalk', 'model' => 'CFS-220', 'price' => 16500, 'compare_price' => 19800, 'cost_price' => 9800],
            ['name' => 'Luxury Ladies Pumps', 'category_id' => 3, 'brand' => 'BelleWalk', 'model' => 'LLP-310', 'price' => 35500, 'compare_price' => 39900, 'cost_price' => 22500],
            ['name' => 'Modern Wedge Sandals', 'category_id' => 3, 'brand' => 'BelleWalk', 'model' => 'MWS-410', 'price' => 21500, 'compare_price' => 25000, 'cost_price' => 14000],

            ['name' => 'Minimalist Leather Wallet', 'category_id' => 4, 'brand' => 'CarryFine', 'model' => 'MLW-101', 'price' => 9500, 'compare_price' => 12000, 'cost_price' => 5500],
            ['name' => 'Premium Wrist Watch', 'category_id' => 4, 'brand' => 'Chronex', 'model' => 'PWW-450', 'price' => 48000, 'compare_price' => 54000, 'cost_price' => 30500],
            ['name' => 'Polarized Sun Glasses', 'category_id' => 4, 'brand' => 'ShadeX', 'model' => 'PSG-210', 'price' => 14500, 'compare_price' => 17500, 'cost_price' => 8200],
            ['name' => 'Signature Leather Belt', 'category_id' => 4, 'brand' => 'CarryFine', 'model' => 'SLB-111', 'price' => 12000, 'compare_price' => 15000, 'cost_price' => 7000],

            ['name' => 'Men Casual Polo Shirt', 'category_id' => 5, 'brand' => 'UrbanThread', 'model' => 'MCPS-510', 'price' => 12500, 'compare_price' => 15500, 'cost_price' => 7600],
            ['name' => 'Slim Fit Chinos', 'category_id' => 5, 'brand' => 'UrbanThread', 'model' => 'SFC-610', 'price' => 18500, 'compare_price' => 22000, 'cost_price' => 11500],
            ['name' => 'Classic Denim Jacket', 'category_id' => 5, 'brand' => 'DenimCore', 'model' => 'CDJ-710', 'price' => 28500, 'compare_price' => 32500, 'cost_price' => 18000],
            ['name' => 'Cotton Graphic T-Shirt', 'category_id' => 5, 'brand' => 'UrbanThread', 'model' => 'CGT-810', 'price' => 9500, 'compare_price' => 12000, 'cost_price' => 5200],

            ['name' => 'Elegant Tote Bag', 'category_id' => 6, 'brand' => 'BagMuse', 'model' => 'ETB-120', 'price' => 26500, 'compare_price' => 31000, 'cost_price' => 16800],
            ['name' => 'Mini Crossbody Bag', 'category_id' => 6, 'brand' => 'BagMuse', 'model' => 'MCB-220', 'price' => 18500, 'compare_price' => 22500, 'cost_price' => 11200],
            ['name' => 'Luxury Office Handbag', 'category_id' => 6, 'brand' => 'BagMuse', 'model' => 'LOH-320', 'price' => 36500, 'compare_price' => 42000, 'cost_price' => 23500],
            ['name' => 'Everyday Shoulder Bag', 'category_id' => 6, 'brand' => 'BagMuse', 'model' => 'ESB-420', 'price' => 22500, 'compare_price' => 26000, 'cost_price' => 14200],
        ];

        foreach ($items as $index => $item) {
            $name = $item['name'];
            $slug = Str::slug($name);

            Product::updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $name,
                    'slug' => $slug,
                    'description' => $name . ' is crafted for style, comfort, and everyday durability. It offers a balanced blend of quality finishing, practical design, and dependable performance for customers looking for premium value.',
                    'short_description' => 'A stylish and durable ' . strtolower($name) . ' designed for everyday use.',
                    'price' => $item['price'],
                    'compare_price' => $item['compare_price'],
                    'cost_price' => $item['cost_price'],
                    'stock_quantity' => rand(8, 50),
                    'sku' => 'SKU-' . strtoupper(Str::random(8)) . '-' . ($index + 1),
                    'barcode' => '2000000000' . str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT),
                    'track_quantity' => true,
                    'low_stock_threshold' => 5,
                    'weight' => rand(1, 5),
                    'length' => rand(10, 35),
                    'width' => rand(8, 25),
                    'height' => rand(3, 15),
                    'category_id' => $item['category_id'],
                    'status' => 'active',
                    'is_featured' => $index < 6,
                    'is_new_arrival' => in_array($index, [2, 5, 10, 17]),
                    'is_hot_selling' => in_array($index, [0, 4, 8, 12, 16]),
                    'is_best_seller' => in_array($index, [1, 6, 9, 18]),
                    'is_trending' => in_array($index, [2, 7, 13, 19]),
                    'is_clearance' => in_array($index, [3, 11]),
                    'is_back_in_stock' => in_array($index, [14]),
                    'is_pre_order' => false,
                    'is_flash_sale' => in_array($index, [4, 10, 15]),
                    'has_free_shipping' => $index % 2 === 0,
                    'is_eco_friendly' => in_array($index, [8, 12, 17]),
                    'is_sustainable' => in_array($index, [8, 15, 19]),
                    'is_handmade' => in_array($index, [8, 16]),
                    'is_customizable' => in_array($index, [11, 18]),
                    'condition' => 'new',
                    'sale_start_date' => Carbon::now()->subDays(3),
                    'sale_end_date' => Carbon::now()->addDays(10),
                    'sale_percentage' => rand(5, 25),
                    'is_available_for_vendors' => true,
                    'published_at' => $now,
                    'meta_title' => $name . ' | Your Store',
                    'meta_description' => 'Buy ' . $name . ' at the best price with quality assurance and fast delivery.',
                    'meta_keywords' => strtolower($name) . ', ' . strtolower($item['brand']) . ', fashion, ecommerce, store',
                    'brand' => $item['brand'],
                    'model' => $item['model'],
                    'warranty' => '6 months limited warranty',
                    'allow_backorder' => false,
                    'is_virtual' => false,
                    'is_downloadable' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                    'deleted_at' => null,
                ]
            );
        }
    }
}