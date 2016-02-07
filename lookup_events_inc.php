<?php
/**
 * @version $Header$
 * Copyright (c) 2004 bitweaver Events
 * @package events
 * @subpackage functions
 */

/**
 * required setup
 */
global $gContent;
require_once( EVENTS_PKG_PATH.'BitEvents.php');
require_once( LIBERTY_PKG_PATH.'lookup_content_inc.php' );

	// if we already have a gContent, we assume someone else created it for us, and has properly loaded everything up.
	if( empty( $gContent ) || !is_object( $gContent ) || !$gContent->isValid() ) {
		// if events_id supplied, use that
		if (!empty($_REQUEST['events_id']) && is_numeric($_REQUEST['events_id'])) {
			$gContent = new BitEvents( $_REQUEST['events_id'] );

		// if content_id supplied, use that
		} elseif (!empty($_REQUEST['content_id']) && is_numeric($_REQUEST['content_id'])) {
			$gContent = new BitEvents( NULL, $_REQUEST['content_id'] );

		// otherwise create new object
		} else {
			$gContent = new BitEvents();
		}

		//handle legacy forms that use plain 'events' form variable name
		// TODO not sure what this does - wolff_borg
		if( empty( $gContent->mEventsId ) && empty( $gContent->mContentId )  ) {
		}
		$gContent->load();
		$gBitSmarty->assignByRef( "gContent", $gContent );
	}
?>
