<?php
auth_reauthenticate();
access_ensure_global_level( config_get( 'manage_plugin_threshold' ) );
$f_format_text = gpc_get_int( 'format_text', ON );
$f_search_text = gpc_get_int( 'search_text', ON );
$f_search_threshold = gpc_get_string( 'search_threshold', REPORTER);
$f_serials_view_threshold = gpc_get_string( 'serials_view_threshold', REPORTER );
$f_format_threshold = gpc_get_string( 'format_threshold', DEVELOPER );
$f_manage_threshold = gpc_get_string( 'manage_threshold', ADMINISTRATOR );

plugin_config_set( 'format_text', $f_format_text );
plugin_config_set( 'search_text', $f_search_text );
plugin_config_set( 'search_threshold', $f_search_threshold );
plugin_config_set( 'serials_view_threshold', $f_serials_view_threshold );
plugin_config_set( 'format_threshold', $f_format_threshold );
plugin_config_set( 'manage_threshold', $f_manage_threshold );

print_successful_redirect( plugin_page( 'config',TRUE ) );
