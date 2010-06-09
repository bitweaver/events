<?php
/**
 * @version $Header$
 * @package events
 * @subpackage functions
 */

global $gContent, $gBitSystem, $gBitSmarty;
$displayHash = array( 'perm_name' => 'p_events_view' );
$gContent->invokeServices( 'content_display_function', $displayHash );

$gContent->addHit();

$gBitSystem->display('bitpackage:events/event_display.tpl', tra('Event:') . (!empty($gContent->mInfo['title']) ? $gContent->mInfo['title'] : tra('Untitled') ), array( 'display_mode' => 'display' ));

?>