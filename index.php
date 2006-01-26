<?php
// $Header: /cvsroot/bitweaver/_bit_events/index.php,v 1.1 2006/01/26 00:54:45 bitweaver Exp $
// Copyright (c) 2004 bitweaver Events
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once('../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage('events' );

// Now check permissions to access this page
$gBitSystem->verifyPermission('bit_p_read_events' );

if (isset($_REQUEST['events_id'] ) ) {
    require_once(EVENTS_PKG_PATH.'lookup_events_inc.php' );
	
    // Display the template
	$gBitSystem->display('bitpackage:events/show_events.tpl', tra('Events') );
} else {
	include('list_events.php');
}
?>
