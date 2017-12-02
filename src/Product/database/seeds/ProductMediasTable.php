<?php

namespace Denmasyarikin\Sales\Product\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Sales\Product\Product;

class ProductMediasTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $product = Product::find(1);

        $product->medias()->create([
            'type' => 'image',
            'content' => 'api/media/image/sales/product/image/mdia1.jpeg',
            'sequence' => 1,
            'primary' => true,
        ]);

        $product->medias()->create([
            'type' => 'image',
            'content' => 'api/media/image/sales/product/image/mdia2.jpeg',
            'sequence' => 2,
            'primary' => false,
        ]);

        $product->medias()->create([
            'type' => 'image',
            'content' => 'api/media/image/sales/product/image/mdia3.jpeg',
            'sequence' => 3,
            'primary' => false,
        ]);
    }
}
