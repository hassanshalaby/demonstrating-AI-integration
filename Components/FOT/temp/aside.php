<?php 

	require FOTPATH.'temp/options.php';

	$options = (new FOTOPT)->options();

	echo '<aside>';

		echo '<logo>';

			echo '<i class="fa-solid fa-code"></i>';

		echo '</logo>';

		echo '<tabs>';

			$i = 0;

			foreach ($options as $class => $f) {

				echo '<tab '.( $i == 0 ? 'class="active"' : '' ).' data-class=".'.$class.'">'.$f['icon'].' '.$f['name'].'</tab>';

				$i++;

			}

		echo '</tabs>';

	echo '</aside>';