<?php 

echo '<container>';

	echo '<field--options>';

		require FOTPATH.'temp/aside.php';

		require FOTPATH.'temp/fields.php';

	echo '</field--options>';



	echo '<div id="save_fields" data-url="'.admin_url('admin-ajax.php' ).'">';

		echo is_rtl() ? 'حفظ' : 'Save Settings';

	echo '</div>';

echo '</container>';