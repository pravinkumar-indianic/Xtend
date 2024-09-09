<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\ShippingRate;
use Seeder;

class ShippingRateSeeder extends Seeder
{
    public function run()
    {

        $shippingrate1 = ShippingRate::create([
            'detail'			=> '40',
            'shipping_base'		=> 'country',
            'base_id'			=> 1,
        ]);

        $shippingrate2 = ShippingRate::create([
            'detail'			=> '20',
            'shipping_base'		=> 'organizations',
            'base_id'			=> 1
        ]);

        $shippingrate2 = ShippingRate::create([
            'detail'			=> '25',
            'shipping_base'		=> 'programs',
            'base_id'			=> 1
        ]);
    }
}