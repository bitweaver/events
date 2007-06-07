<?php
// $Header: /cvsroot/bitweaver/_bit_events/edit.php,v 1.5 2007/06/07 23:34:14 nickpalmer Exp $
// Copyright (c) 2004 bitweaver Events
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

// Initialization
require_once('../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage('events' );

// Now check permissions to access this page
$gBitSystem->verifyPermission('p_edit_events' );

require_once(EVENTS_PKG_PATH.'lookup_events_inc.php' );

if (isset($_REQUEST["title"])) {
    $gContent->mInfo["title"] = $_REQUEST["title"];
}

if (isset($_REQUEST["description"])) {
    $gContent->mInfo["description"] = $_REQUEST["description"];
}

if (isset($_REQUEST["event_time"])) {
    $gContent->mInfo["event_time"] = $_REQUEST["event_time"];
}
	    

if (isset($_REQUEST["data"])) {
    $gContent->mInfo["data"] = $_REQUEST["data"];
}

// If we are in preview mode then preview it!
if (isset($_REQUEST["preview"])) {
    $gBitSmarty->assign('preview', 'y');
    $gContent->invokeServices( 'content_preview_function' );
} else {
    $gContent->invokeServices( 'content_edit_function' );
}

// Pro
// Check if the page has changed
if (!empty($_REQUEST["save_events"])) {
    
    // Check if all Request values are delivered, and if not, set them
    // to avoid error messages. This can happen if some features are
    // disabled
    if ($gContent->store( $_REQUEST ) ) {
        header("Location: ".$gContent->getDisplayUrl() );
        die;
    } else {
        $gBitSmarty->assign_by_ref('errors', $gContent->mErrors );
    }
}

$gBitSmarty->assign( 'loadAjax', true);

// Display the template
$gBitSystem->display('bitpackage:events/edit_events.tpl', tra('Events') );
?>
