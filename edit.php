<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_events/edit.php,v 1.15 2008/03/15 10:37:29 nickpalmer Exp $
 * Copyright (c) 2004 bitweaver Events
 * @package events
 * @subpackage functions
 */

/**
 * required setup
 */
require_once('../bit_setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage('events' );

require_once(EVENTS_PKG_PATH.'lookup_events_inc.php' );

// Now check permissions to access this page
if ($gContent->isValid()) {
	$gContent->verifyPermission('p_events_edit' );
}
else {
	$gBitUser->verifyPermission('p_events_create');
}

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
	$gContent->preview($_REQUEST);
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
		bit_redirect($gContent->getDisplayUrl() );
		die;
	} else {
		$gBitSmarty->assign_by_ref('errors', $gContent->mErrors );
	}
}

// Remove events
if ( !empty( $_REQUEST['remove'] ) ) {
	if ( $gContent->isValid() && !empty( $gContent->mInfo ) ) {
		if( empty( $_REQUEST['confirm'] ) ) {
			$formHash['remove'] = TRUE;
			$formHash['input'][] = '<input type="hidden" name="events_id" value="'.$_REQUEST['events_id'].'"/>';
			$gBitSystem->confirmDialog( $formHash, array( 'warning' => tra('Are you sure you want to delete the "').htmlentities($gContent->mInfo['title']).tra('" event?'), 'error' => tra('This cannot be undone!') ) );
		} else if ( $_REQUEST['confirm'] ) {
			if ( $gContent->expunge() ) {
				bit_redirect( EVENTS_PKG_URL );
				die;
			} else {
				$gBitSmarty->assign_by_ref('errors', $gContent->mErrors );
			}
		}
	}
	else {
		$gBitSystem->setHttpStatus(404);
		$gBitSystem->fatalError( tra("No such event could be found to be removed.") );
	}
}

if( $gBitSystem->isFeatureActive('events_use_types') ) {
	$eventTypes = $gContent->loadEventTypes(FALSE);
	if( $gBitSystem->isFeatureActive('events_allow_no_type') ) {
		$eventTypes[-1] = '';
		asort($eventTypes);
	}
	$gBitSmarty->assign('eventTypes', $eventTypes);
}

// Display the template
$gBitSystem->display('bitpackage:events/edit_events.tpl', tra('Events') );
?>
