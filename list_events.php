<?php
// $Header: /cvsroot/bitweaver/_bit_events/list_events.php,v 1.3 2006/01/26 11:36:22 squareing Exp $
// Copyright (c) 2004 bitweaver Events
// All Rights Reserved. See copyright.txt for details and a complete list of authors.
// Licensed under the GNU LESSER GENERAL PUBLIC LICENSE. See license.txt for details.
// Initialization
require_once('../bit_setup_inc.php' );
require_once(EVENTS_PKG_PATH.'BitEvents.php' );

// Is package installed and enabled
$gBitSystem->verifyPackage('events' );

// Now check permissions to access this page
$gBitSystem->verifyPermission('bit_p_read_events' );

/* mass-remove:
the checkboxes are sent as the array $_REQUEST["checked[]"], values are the wiki-PageNames,
e.g. $_REQUEST["checked"][3]="HomePage"
$_REQUEST["submit_mult"] holds the value of the "with selected do..."-option list
we look if any page's checkbox is on and if remove_events is selected.
then we check permission to delete events.
if so, we call histlib's method remove_all_versions for all the checked events.
*/
if (isset($_REQUEST["submit_mult"]) && isset($_REQUEST["checked"]) && $_REQUEST["submit_mult"] == "remove_events") {

	// Now check permissions to remove the selected events
	$gBitSystem->verifyPermission( 'bit_p_remove_events' );

	if( !empty( $_REQUEST['cancel'] ) ) {
		// user cancelled - just continue on, doing nothing
	} elseif( empty( $_REQUEST['confirm'] ) ) {
		$formHash['delete'] = TRUE;
		$formHash['submit_mult'] = 'remove_events';
		foreach( $_REQUEST["checked"] as $del ) {
			$formHash['input'][] = '<input type="hidden" name="checked[]" value="'.$del.'"/>';
		}
		$gBitSystem->confirmDialog( $formHash, array( 'warning' => 'Are you sure you want to delete '.count($_REQUEST["checked"]).' events?', 'error' => 'This cannot be undone!' ) );
	} else {
		foreach ($_REQUEST["checked"] as $deleteId) {
			$tmpPage = new BitEvents( $deleteId );
			if( !$tmpPage->load() || !$tmpPage->expunge() ) {
				array_merge( $errors, array_values( $tmpPage->mErrors ) );
			}
		}
		if( !empty( $errors ) ) {
			$gBitSmarty->assign_by_ref( 'errors', $errors );
		}
	}
}

$events = new BitEvents();
$listevents = $events->getList( $_REQUEST );

$gBitSmarty->assign_by_ref('listInfo', $_REQUEST["control"]);
$gBitSmarty->assign_by_ref('list', $listevents["data"]);

// Display the template
$gBitSystem->display('bitpackage:events/list_events.tpl', tra('Events') );

?>
