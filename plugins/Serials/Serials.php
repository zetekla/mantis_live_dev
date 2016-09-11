<?php
class SerialsPlugin extends MantisPlugin {
    function register() {
        $this->name 		= 'Serials';    # Proper name of plugin
        $this->description 	= 'Serial number entry form for list generation and verification.';    # Short description of the plugin
        $this->page 		= 'config';           # Default plugin page
        $this->version 		= '1.3';
		    $this->requires    = array('MantisCore' => '1.2.0');
        $this->author 		= 'Phuc Tran & Khin Tram';         # Author/team name
        $this->contact 		= 'PhucTran@eminc.com / Ktram@eminc.com';        # Author/team e-mail address
        $this->url 			= '';            # Support webpage
    }

  function config() {
    return array(
		'format_text' => ON,
		'search_text' => ON,
		'search_threshold' => 10,
		'serials_view_threshold' => 10,
		'format_threshold'       => 55,
		'manage_threshold'	=>ADMINISTRATOR
    );
  }

  function init() {
    plugin_event_hook( 'EVENT_MENU_MAIN', 'mainpage' );
  }

  // EVENT_LAYOUT_RESOURCES

  function formatpage() {
    return array( '<a href="'. plugin_page( 'format.php' ) . '">' . plugin_lang_get( 'format_title' ) . '</a>' );
  }


  function mainpage() {
    return array( '<a href="'. plugin_page( 'serials_main_page.php' ) . '">' . lang_get( 'menu_serials_link' ) . '</a>' );
  }

  function schema() {
    return array(
 	  array( 'CreateTableSQL', array( plugin_table( 'format' ), "
        format_id				I		NOTNULL UNSIGNED ZEROFILL AUTOINCREMENT PRIMARY,
		    assembly_id		I		NOTNULL UNSIGNED ZEROFILL ,
        format		C(250)	DEFAULT \" '' \",
        format_example		C(250)	DEFAULT \" '' \"
        " )
      ),
	  array( 'CreateTableSQL', array( plugin_table( 'serial' ), "
        serial_id				I		NOTNULL UNSIGNED ZEROFILL AUTOINCREMENT PRIMARY,
        assembly_id		I		NOTNULL UNSIGNED ZEROFILL ,
        customer_id		I		NOTNULL UNSIGNED ZEROFILL ,
		    user_id		I		NOTNULL UNSIGNED ZEROFILL ,
        date_posted		T		NOTNULL,
        serial_scan		C(250)	DEFAULT \" '' \",
        sales_order		C(250)	DEFAULT \" '' \"
        " )
      ),
	  array( 'AddColumnSQL', array( plugin_table( 'serial' ), "
		unique_key		C(250)
		")
	  ),
    );
  }
}