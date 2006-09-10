<?php
global $gBitSystem;
$registerHash = array(
	'package_name' => 'events',
	'package_path' => dirname( __FILE__ ).'/',
	'homeable' => TRUE,
);
$gBitSystem->registerPackage( $registerHash );

if ($gBitSystem->isPackageActive( 'events' ) ) {
	$gBitSystem->registerAppMenu( EVENTS_PKG_NAME, ucfirst( EVENTS_PKG_DIR ), EVENTS_PKG_URL.'index.php', 'bitpackage:events/menu_events.tpl', EVENTS_PKG_NAME );
}

?>
