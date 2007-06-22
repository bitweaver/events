<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_events/admin/admin_events_inc.php,v 1.4 2007/06/22 07:22:36 lsces Exp $
 *
 * @author nickpalmer
 * @package events
 * @subpackage functions
 */

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
		'note' => 'Use content status to moderate events.',
		'type' => 'toggle',
	),
);
$gBitSmarty->assign( 'formEventsFeatureOptions', $formEventsFeatureOptions );

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
}

?>