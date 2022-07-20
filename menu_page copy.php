<?php 

// as_toplevel_page() displays the page content for the custom as Toplevel menu
function as_menu_page() {
	// variables for the field and option names 
	$opt_name = 'order_email';
	$hidden_field_name = 'as_submit_hidden';
	$data_field_name = 'order_email';
	global $wpdb;

	// Read in existing option value from database
	$opt_val = get_option( $opt_name );

	// See if the user has posted us some information
	// If they did, this hidden field will be set to 'Y'
	if( $_POST[ $hidden_field_name ] == 'Y' ) {
		// Read their posted value
		$opt_val = $_POST[ $data_field_name ];

		// Save the posted value in the database
		update_option( $opt_name, $opt_val );

		// Put an options updated message on the screen
		?>
		<div class="updated"><p><strong>Изменения сохранены</strong></p></div>
		<?php

	}

	$regions_prices = $wpdb->get_results("SELECT id, region_name,cost, cost1 FROM as_regions");
	  // echo "<pre>";
    // echo var_dump($_SERVER['REQUEST_URI']);
    // echo "</pre>";



	// Now display the options editing screen
	?>
	<div class="wrap">

		<h2>Настройки Аква Сана</h2>

		<form name="form1" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
			<input type="hidden" name="<?php echo $hidden_field_name; ?>" value="Y">

			<p>Email, на который отправлять заказы: 
			<input type="text" name="<?php echo $data_field_name; ?>" value="<?php echo $opt_val; ?>" size="20">
			</p><hr />

			<p class="submit">
			<input type="submit" name="Submit" value="Сохранить изменения" />
			</p>



		</form>
<?php
	  echo "<pre>";
    echo var_dump($_POST);
    echo "</pre>";

}
