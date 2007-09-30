<?php
global $gBitSystem, $gBitUser;
$registerHash = array(
	'package_name' => 'events',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );


if( $gBitSystem->isPackageActive( 'events' ) &&  $gBitUser->hasPermission( 'p_events_view' )) {
	$menuHash = array(
		'package_name'  => EVENTS_PKG_NAME,
		'index_url'     => EVENTS_PKG_URL.'index.php',
		'menu_template' => 'bitpackage:events/menu_events.tpl',
	);
	$gBitSystem->registerAppMenu( $menuHash );
	$gLibertySystem->registerService( 'events', EVENTS_PKG_NAME, array(
		'content_list_sql_function' => 'events_content_list_sql',
		)
	);

}
?>
