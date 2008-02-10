<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_events/admin/admin_events_inc.php,v 1.6 2008/02/10 11:28:51 nickpalmer Exp $
 *
 * @author nickpalmer
 * @package events
 * @subpackage functions
 */

require_once(EVENTS_PKG_PATH.'BitEvents.php');

/**
 * required setup
 */
$formEventsDisplayOptions = array(
  // This should pobably be a generic setting in kernel or something that is
  // site wide for all html_select_times and such.
	"events_use_24" => array(
		'label' => 'Use 24 Hour',
		'note' => 'Time display uses 24 hour format.',
		'type' => 'toggle',
	),
	"events_end_year" => array(
		'label' => 'Events End Year',
		'note' => 'End year in events date set. Can be a specific year or +# (i.e. +1) to allow events a certain number of years in the future.',
		'type' => 'input',
	),
);
$gBitSmarty->assign( 'formEventsDisplayOptions', $formEventsDisplayOptions );

$formEventsFeatureOptions = array(
	"events_moderation" => array(
		'label' => 'Events Moderation',
		'note' => 'Use content status to moderate events. Note, there is no UI for doing the moderation yet.',
		'type' => 'toggle',
	),
	"events_show_primary" => array(
		'label' => 'Auto Show Primary',
		'note' => 'Automatically show the primary attachment.',
		'type' => 'toggle',
	),
	"events_use_types" => array(
		'label' => 'Use Event Types',
		'note' => 'Use the event types features.',
		'type' => 'toggle',
	),
	"events_allow_no_type" => array(
		'label' => 'Allow No Types',
		'note' => 'Include in the event types an empty choice.',
		'type' => 'toggle',
	),
);
$gBitSmarty->assign( 'formEventsFeatureOptions', $formEventsFeatureOptions );

$be = new BitEvents();
$eventTypes = $be->loadEventTypes(true);
$gBitSmarty->assign('eventTypes', $eventTypes);

if( !empty( $_REQUEST['events_preferences'] ) ) {
  	$events = array_merge( $formEventsDisplayOptions, $formEventsFeatureOptions  );
	foreach( $events as $item => $data ) {
		if( $data['type'] == 'numeric' ) {
			simple_set_int( $item, EVENTS_PKG_NAME );
		} elseif( $data['type'] == 'toggle' ) {
			simple_set_toggle( $item, EVENTS_PKG_NAME );
		} elseif( $data['type'] == 'input' ) {
			simple_set_value( $item, EVENTS_PKG_NAME );
		}
	}
	// Update some types?
	foreach( $eventTypes as $id => $data ) {
		if( isset( $_REQUEST['update'][$id] ) ){
			if ($eventTypes[$id]['name'] != $_REQUEST['update'][$id]['name'] or
				$eventTypes[$id]['description'] != $_REQUEST['update'][$id]['desc'] ) {
				$be->storeType($id,
							   $_REQUEST['update'][$id]['name'],
							   $_REQUEST['update'][$id]['desc']);
			}
		}
	}

	// Delete some types ?
	if( !empty($_REQUEST['deleteType']) ) {
		foreach($_REQUEST['deleteType'] as $id => $val) {
			$be->expungeType($id);
		}
	}
	// Store a new type?
	if( !empty($_REQUEST['typeName']) ) {
		$be->storeType(NULL, $_REQUEST['typeName'], empty($_REQUEST['typeDesc']) ? NULL : $_REQUEST['typeDesc']);
	}
}

// Reload these now as we have changed them around
$eventTypes = $be->loadEventTypes(true);
$gBitSmarty->assign('eventTypes', $eventTypes);

?>