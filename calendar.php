<?php
/**
 * Access calendar package to display event calendar
 *
 * @package  events
 * @version  $Header$
 * @author   nickpalmer
 */

/**
 * required setup
 */
require_once( '../kernel/setup_inc.php' );
require_once(EVENTS_PKG_PATH.'BitEvents.php' );

if ( $gBitSystem->isPackageActive( 'calendar' ) ) {

	include_once( CALENDAR_PKG_PATH.'Calendar.php' );

	$gBitSystem->verifyPermission( 'p_calendar_view' );

	// now, lets get the ball rolling!
	$gCalendar = new Calendar();

	$gCalendar->processRequestHash($_REQUEST, $_SESSION['calendar']);

	// Setup which content types we want to view.
	$listHash['content_type_guid'] = array('bitevents');

	$events = new BitEvents();
	if( empty( $_REQUEST['event_after'] ) ) {
		$_REQUEST['event_after'] = $gBitSystem->getUTCTime();
	}
	$listevents = $events->getList( $_REQUEST );

	// Build the calendar
	$gCalendar->buildCalendar($listHash, $_SESSION['calendar']);

	// And display it with a nice title.
	$gCalendar->display(tra('Events Calendar'), FALSE, EVENTS_PKG_URL.'calendar.php');
}

?>
