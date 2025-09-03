<?php

$arr = [
	'id'=>'custom_settings',
	'title'=>'AI Summary',
	'callback'=>'',
	'ptype'=>array('cmd'),
	'context'=>'normal',
	'repeatable'=>false,
	'priority'=>'high',
	'options'=>[
		    array(
		        'type'=>'button',
		        'id'=>'get_text',
		        'title'=>'Generate AI Summary',
	        ),	    	    
		    array(
		        'type'=>'textarea',
		        'id'=>'generated_text',
		        'title'=>'Generated Text by AI',
	        ),	    	    
 		    	    
	     
     ],
	

];



return $arr;