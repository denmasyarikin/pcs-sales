<?php

namespace Denmasyarikin\Sales\Product\database\seeds;

use Illuminate\Database\Seeder;
use Denmasyarikin\Sales\Product\ProductGroup;

class ProductGroupsTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $groups = ProductGroup::create(['name' => 'Office']);
    }
}
