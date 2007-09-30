<?php

// $Header: /cvsroot/bitweaver/_bit_events/calendar.php,v 1.1 2007/09/30 20:10:51 nickpalmer Exp $

// Copyright( c ) 2002-2003, Luis Argerich, Garland Foster, Eduardo Polidor, et. al.
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
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

	// And display it with a nice title.
	$gCalendar->display(tra('Events Calendar'), FALSE);
}

?>
