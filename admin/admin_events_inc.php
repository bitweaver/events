<?php
// $Header: /cvsroot/bitweaver/_bit_events/admin/admin_events_inc.php,v 1.1 2006/01/26 00:54:46 bitweaver Exp $
// Copyright (c) 2005 bitweaver Events
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.

if (isset($_REQUEST["eventsset"]) && isset($_REQUEST["homeEvents"])) {
    $gBitSystem->storePreference("home_events", $_REQUEST["homeEvents"]);
    $gBitSmarty->assign('home_events', $_REQUEST["homeEvents"]);
}

require_once(EVENTS_PKG_PATH.'BitEvents.php' );

$formEventsLists = array(
	"events_list_events_id" => array(
		'label' => 'Id',
		'note' => 'Display the events id.',
	),
	"events_list_title" => array(
		'label' => 'Title',
		'note' => 'Display the title.',
	),
	"events_list_description" => array(
		'label' => 'Description',
		'note' => 'Display the description.',
	),
	"events_list_data" => array(
		'label' => 'Text',
		'note' => 'Display the text.',
	),
);
$gBitSmarty->assign( 'formEventsLists',$formEventsLists );

$processForm = set_tab();

if( $processForm ) {
	$eventsToggles = array_merge( $formEventsLists );
	foreach( $eventsToggles as $item => $data ) {
		simple_set_toggle( $item );
	}

}

$events = new BitEvents();
$events = $events->getList( $_REQUEST );
$gBitSmarty->assign_by_ref('events', $events['data']);
?>
