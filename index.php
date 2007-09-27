<?php
// $Header: /cvsroot/bitweaver/_bit_events/index.php,v 1.6 2007/09/27 20:52:04 nickpalmer Exp $
// Copyright (c) 2004 bitweaver Events
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once('../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage('events' );

// Now check permissions to access this page
$gBitSystem->verifyPermission('p_events_view' );

if (isset($_REQUEST['events_id'] ) ) {
	require_once(EVENTS_PKG_PATH.'lookup_events_inc.php' );
	$gContent->invokeServices( 'content_display_function' );
	// Display the template
	$gBitSystem->display('bitpackage:events/event_display.tpl', tra('Event: ') . (!empty($gContent->mInfo['title']) ? $gContent->mInfo['title'] : tra('Untitled') ));
} else {
	include('list_events.php');
}
?>
