<?php
/**
 * Access calendar package to display event calendar
 *
 * @package  events
 * @version  $Header: /cvsroot/bitweaver/_bit_events/index.php,v 1.7 2007/11/18 12:00:19 lsces Exp $
 * @author   nickpalmer
 */

/**
 * required setup
 */
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
