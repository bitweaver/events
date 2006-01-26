<?php
global $gBitSystem;
$gBitSystem->registerPackage( 'events', dirname(__FILE__).'/' );

if ($gBitSystem->isPackageActive( 'events' ) ) {
    $gBitSystem->registerAppMenu( EVENTS_PKG_DIR, 'Events', EVENTS_PKG_URL.'index.php', 'bitpackage:events/menu_events.tpl', 'events' );
}

?>
