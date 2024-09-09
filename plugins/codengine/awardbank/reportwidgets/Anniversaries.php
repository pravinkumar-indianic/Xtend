<?php namespace Codengine\Awardbank\ReportWidgets;

use Backend\Classes\ReportWidgetBase;
use Event;
use Carbon\Carbon;
use RainLab\User\Models\User;

class Anniversaries extends ReportWidgetBase
{
	public function defineProperties()
	{
	    return [
	        'type' => [
	            'title'             => 'Report Type',
	            'default'           => 'birth_date',
	            'type'              => 'dropdown',
	            'options' => [
		        	'birth_date' => "Birthday's",
		        	'commencement_date' => "Tenure's",
	            ],
	        ],
	    ];
	}

    public function render()
    {
    	$type = $this->property('type');
    	return $this->buildWidget($type);

    }

    public function buildWidget($type)
    {
    	$typename = $this->returnTypeName($type);
    	$systemdate = Carbon::now();
    	$table = $this->buildTable($type);
        return $this->makePartial('widget',[
        	'systemdate' => $systemdate,
        	'table' => $table,
        	'type' => $type,
        	'typename' => $typename,
        ]);
    }

    public function buildTable($type)
    {
    	$typename = $this->returnTypeName($type);
    	$results = Event::fire('xtend.getUserAnniversaries', [$type], true);
    	$results = User::whereIn('id',$results)->get(['id','full_name',$type,'today_'.$type,'updated_'.$type]);
        return $this->makePartial('table',[
        	'results' => $results,
        	'type' => $type,
        	'typename' => $typename,
        ]);	
    }

    public function returnTypeName($type){
    	$array = [
        	'birth_date' => "Birthday's",
        	'commencement_date' => "Tenure's",
        ];
        return $array[$type];
    }

    public function onReprocess()
    {
    	$type = post('type');
    	$typename = $this->returnTypeName($type);
    	Event::fire('xtend.updateAnniversaryFlags', [$type]);	
	 	\Flash::success($typename." updated!");
	 	$key = '.'.$type.'_table';
	    return [
	        $key => $this->buildTable($type),
	    ];
    }
}