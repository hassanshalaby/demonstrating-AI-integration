<?php 

	require FOTPATH.'temp/types.php';

	

	$field = new FOT_TYPES;

	$options = (new FOTOPT)->options();

	echo '<fields>';



		$i = 0;

		foreach ($options as $class => $fields) {

			foreach ($fields['options'] as $key => $f) {

				if (isset($f['type'])) {

					$name = $f['name'];

					$id = $f['id'];

					$type = $f['type'];

					$desc = isset($f['desc']) ? $f['desc'] : '';

					$note = isset($f['note']) ? $f['note'] : false;

					$attr = isset($f['attributes']) ? $f['attributes'] : '';

					$options = isset($f['options']) ? $f['options'] : '';

					$tax = isset($f['taxonomy']) ? $f['taxonomy'] : '';

					$class .= $i == 0 ? ' active' : ''; 

					$field->field($name,$id,$type,$class,$desc,$note,$attr,$options,$tax);



					$i++;

				}

			}

		}



	echo '</fields>';



