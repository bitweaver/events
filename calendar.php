<?php
/**
 * Access calendar package to display event calendar
 *
 * @package  liberty
 * @version  $Header: /cvsroot/bitweaver/_bit_events/calendar.php,v 1.3 2008/02/09 22:52:02 nickpalmer Exp $
 * @author   nickpalmer
 */

/**
 * required setup
 */
require_once( '../bit_setup_inc.php' );

if ( $gBitSystem->isPackageActive( 'calendar' ) ) {

	include_once( CALENDAR_PKG_PATH.'Calendar.php' );

	$gBitSystem->verifyPermission( 'p_calendar_view' );

	// now, lets get the ball rolling!
	$gCalendar = new Calendar();

	$gCalendar->processRequestHash($_REQUEST, $_SESSION['calendar']);

	// Setup which content types we want to view.
	$listHash['content_type_guid'] = array('bitevents');
	$listHash['time_limit_table'] = 'eo.';
	$listHash['order_table'] = 'eo.';
	$listHash['sort_mode'] = 'event_on_asc';

	// Build the calendar
	$gCalendar->buildCalendar($listHash, $_SESSION['calendar']);

	$gBitThemes->loadAjax('mochikit');

	// And display it with a nice title.
	$gCalendar->display(tra('Events Calendar'), FALSE);
}

?>
