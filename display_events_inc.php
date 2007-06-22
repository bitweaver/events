<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_events/display_events_inc.php,v 1.2 2007/06/22 07:22:36 lsces Exp $
 * @package events
 * @subpackage functions
 */

global $gContent, $gBitSystem, $gBitSmarty;
$displayHash = array( 'perm_name' => 'bit_p_read_events' );
$gContent->invokeServices( 'content_display_function', $displayHash );

$gContent->addHit();

$gBitSystem->display('bitpackage:events/event_display.tpl', tra('Event:') . (!empty($gContent->mInfo['title']) ? $gContent->mInfo['title'] : tra('Untitled') ));

?>