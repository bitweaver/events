<?php
/**
 * Access calendar package to display event calendar
 *
 * @package  events
 * @version  $Header: /cvsroot/bitweaver/_bit_events/index.php,v 1.10 2010/02/08 21:27:22 wjames5 Exp $
 * @author   nickpalmer
 */

/**
 * required setup
 */
require_once('../kernel/setup_inc.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage('events' );

// Now check permissions to access this page
$gBitSystem->verifyPermission('p_events_view' );

if (isset($_REQUEST['events_id'] ) ) {
	require_once(EVENTS_PKG_PATH.'lookup_events_inc.php' );
	$gContent->invokeServices( 'content_display_function' );
	// Display the template
	if ( $gContent->isValid() && !empty( $gContent->mInfo ) ) {
		$gBitSystem->display('bitpackage:events/event_display.tpl', tra('Event: ') . (!empty($gContent->mInfo['title']) ? $gContent->mInfo['title'] : tra('Untitled') ), array( 'display_mode' => 'display' ));
	}
	else {
		$gBitSystem->setHttpStatus(404);
		$gBitSystem->fatalError( tra("No such event could be found.") );
	}
} else {
	include('list_events.php');
}
?>
