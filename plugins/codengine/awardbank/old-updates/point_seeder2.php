<?php namespace Codengine\Awardbank\Updates;

use Codengine\Awardbank\Models\Point as Point;
use Codengine\Awardbank\Models\PointAllocation;
use System\Helpers\DateTime as DateTime;
use Seeder;

class PointSeeder2 extends Seeder
{
    public function run()
    { 
    	Point::truncate();
    	PointAllocation::truncate();
    	for ($i=0; $i<50;$i++){
	        $point = Point::create([
	            'transaction_id' => '1',
	            'credit_id'     => '0',
	            'spent'   => 0
	        ]);
	        $poal = PointAllocation::create([
	            'point_id' => $point->id,
	            'pointallocatable_type'		=> 'user',
	            'pointallocatable_id'	=> '2',
	            'previous_allocation_id'	=> '0',
	            'current_allocation'	=> true,
	            'allocator' => 1 ,	            
	        ]);
	    }
	    for ($i=45; $i<50;$i++){
	    	$poal = PointAllocation::find($i);
	    	$poal->current_allocation = false;
	    	$poal->save();
	    	$poal = PointAllocation::create([
	    		'point_id' => $i,
	            'pointallocatable_type'		=> 'user',
	            'pointallocatable_id'	=> '1',
	            'previous_allocation_id'	=> $i,
	            'current_allocation'	=> true,
	            'allocator' => 1 ,	            
	        ]);
		}

    	for ($i=0; $i<700;$i++){
	        $point = Point::create([
	            'transaction_id' => '2',
	            'credit_id'     => '0',
	            'spent'   => 0
	        ]);
	        $poal = PointAllocation::create([
	            'point_id' => $point->id,
	            'pointallocatable_type'		=> 'user',
	            'pointallocatable_id'	=> '1',
	            'previous_allocation_id'	=> '0',
	            'current_allocation'	=> true,
	            'allocator' => 1 ,
	        ]);
	    }
	    for ($i=150; $i<160;$i++){
	    	$poal = PointAllocation::find($i);
	    	$poal->current_allocation = false;
	    	$poal->save();
	    	$poal = PointAllocation::create([
	    		'point_id' => $i,
	            'pointallocatable_type'		=> 'user',
	            'pointallocatable_id'	=> '2',
	            'previous_allocation_id'	=> $i,
	            'current_allocation'	=> true,
	            'allocator' => 1 ,
	        ]);
		}

        for ($i=0; $i<20;$i++){
	        $pointcredit = Point::create([
	            'transaction_id' => '0',
	            'credit_id'     => '1',
	            'spent'   => 0
	        ]);
	        $poal = PointAllocation::create([
	            'point_id' => $pointcredit->id,
	            'pointallocatable_type'		=> 'user',
	            'pointallocatable_id'	=> '1',
	            'previous_allocation_id'	=> '0',
	            'current_allocation'	=> true,
	            'allocator' => 1 ,
	        ]);
	    }

	    for ($i=0; $i<15;$i++){
	        $point = Point::create([
	            'transaction_id' => '0',
	            'credit_id'     => '2',
	            'spent'   => 0
	        ]);
	        $poal = PointAllocation::create([
	            'point_id' => $point->id,
	            'pointallocatable_type'		=> 'user',
	            'pointallocatable_id'	=> '2',
	            'previous_allocation_id'	=> '0',
	            'current_allocation'	=> true,
	            'allocator' => 1 ,
	        ]);
	    }
        
    }
}