<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_events/display_events_inc.php,v 1.4 2008/06/25 22:21:09 spiderr Exp $
 * @package events
 * @subpackage functions
 */

global $gContent, $gBitSystem, $gBitSmarty;
$displayHash = array( 'perm_name' => 'p_events_view' );
$gContent->invokeServices( 'content_display_function', $displayHash );

$gContent->addHit();

$gBitSystem->display('bitpackage:events/event_display.tpl', tra('Event:') . (!empty($gContent->mInfo['title']) ? $gContent->mInfo['title'] : tra('Untitled') ), array( 'display_mode' => 'display' ));

?>