<?php
class SerialScanPlugin extends MantisPlugin {
    function register() {
        $this->name 		= 'Serial Scan';    # Proper name of plugin
        $this->description 	= 'Serial number entry form for list generation and verification, C of C generation and searchable history';    # Short description of the plugin
        $this->page 		= 'config';           # Default plugin page
        $this->version 		= '1.1'; 
		$this->requires    = array('MantisCore' => '1.2.0',);
        $this->author 		= 'Phuc Tran & Khin Tram';         # Author/team name
        $this->contact 		= 'PhucTran@eminc.com / Ktram@eminc.com';        # Author/team e-mail address
        $this->url 			= '';            # Support webpage
    }
	
  function config() {
    return array(
		'ss_format_text' 	=> ON,
		'ss_search_text' 	=> ON,
		'ss_search_threshold' => 10,
		'ss_view_threshold' 	=> 10,
		'ss_format_threshold'   => 55,
		'ss_manage_threshold'	=>ADMINISTRATOR
    );
  }

  function init() {
    plugin_event_hook( 'EVENT_MENU_MAIN', 'mainpage' );
  }

  function formatpage() {
    return array( '<a href="'. plugin_page( 'format.php' ) . '">' . plugin_lang_get( 'format_title' ) . '</a>' );
  }
 // TODO ++ Addition of Tutorial page for training and guidance. 
  function helppage() {
    return array( '<a href="'. plugin_page( 'help.php' ) . '">' . plugin_lang_get( 'help_title' ) . '</a>' );
  }
 //++ 
 
  function mainpage() {
    return array( '<a href="'. plugin_page( 'scan.php' ) . '">' . lang_get( 'menu_serials_link' ) . '</a>' );
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
	  array( 'CreateTableSQL', array( plugin_table( 'main' ), "
        serial_id				I		NOTNULL UNSIGNED ZEROFILL AUTOINCREMENT PRIMARY,
        assembly_id		I		NOTNULL UNSIGNED ZEROFILL ,
        customer_id		I		NOTNULL UNSIGNED ZEROFILL ,
		user_id		I		NOTNULL UNSIGNED ZEROFILL ,
        date_posted		T		NOTNULL,
        serial_scan		C(250)	DEFAULT \" '' \",
        work_order		C(250)	DEFAULT \" '' \",
		session_id		C(250)	NOTNULL
        " )
      ),
    );
  }  
} 