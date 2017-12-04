<?php

namespace Denmasyarikin\Sales\Product\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Sales\Product\Product;
use Denmasyarikin\Sales\Product\ProductProcess;

class ProductsTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Product::create([
            'name' => 'Kartu Nama',
            'description' => 'Kartu nama pribadi atau perusahaan untuk keperluan bisnis',
            'unit_id' => 1,
            'min_order' => 1,
            'base_price' => 54000,
            'per_unit_price' => 54000,
            'process_service_count' => 3,
            'status' => 'active'
        ]);

        ProductProcess::create([
            'product_id' => 1,
            'type' => 'manual',
            'type_as' => 'service',
            'name' => 'Setting',
            'specific' => 'Kartu nama',
            'quantity' => 1,
            'base_price' => 30000,
            'required' => false,
            'unit_id' => 10,
        ]);

        ProductProcess::create([
            'product_id' => 1,
            'type' => 'manual',
            'type_as' => 'service',
            'name' => 'Print Digital',
            'specific' => 'Art Carton',
            'quantity' => 4,
            'base_price' => 4000,
            'static_price' => false,
            'static_to_order_count' => 1,
            'unit_id' => 9,
        ]);

        ProductProcess::create([
            'product_id' => 1,
            'type' => 'manual',
            'type_as' => 'service',
            'name' => 'Finishing Digital',
            'specific' => 'Laminating Gloys',
            'quantity' => 4,
            'base_price' => 2000,
            'required' => false,
            'static_price' => false,
            'static_to_order_count' => 1,
            'unit_id' => 9,
        ]);

        ProductProcess::create([
            'product_id' => 1,
            'parent_id' => 3,
            'type' => 'manual',
            'type_as' => 'service',
            'name' => 'Finishing Digital',
            'specific' => 'Laminating Doff',
            'quantity' => 4,
            'base_price' => 2000,
            'required' => false,
            'static_price' => false,
            'static_to_order_count' => 1,
            'unit_id' => 9,
        ]);
    }
}
