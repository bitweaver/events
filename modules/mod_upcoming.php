<?php
/**
 * Params: 
 * - title : if is "title", show the title of the post, else show the date of creation
 *
 * @version $Header: /cvsroot/bitweaver/_bit_events/modules/mod_upcoming.php,v 1.3 2007/09/27 20:52:38 nickpalmer Exp $
 * @package blogs
 * @subpackage modules
 */

/**
 * required setup
 */
if( !defined( 'MAX_EVENTS_PREVIEW_LENGTH' ) ) {
	define ('MAX_EVENTS_PREVIEW_LENGTH', 100);
}

include_once( EVENTS_PKG_PATH.'BitEvents.php' );
require_once( USERS_PKG_PATH.'BitUser.php' );

global $gBitSmarty, $gQueryUserId, $gBitSystem, $moduleParams;

$module_rows = $moduleParams['module_rows'];
$module_params = $moduleParams['module_params'];
$module_title = isset($moduleParams['title']) ? $moduleParams['title'] : tra( "Upcoming Events");

$gBitSmarty->assign( 'moduleTitle', $module_title );

$listHash = array( 'max_records' => $module_rows, 'parse_split' => !empty($module_params['preview']) && $module_params['preview'] ? TRUE : FALSE ,
		   'sort_mode' => !empty($module_params['random']) && $module_params['random'] ? 'random' : 'event_time_asc',
		   'event_after' => $gBitSystem->getUTCTime(),
		   'event_before' => $gBitSystem->getUTCTime() + (60 * 60 * 24 * $gBitSystem->getConfig('events_upcoming_limit', 7)));

/* Support for selecting entries only from one or more categories */
if (isset($module_params['pigeonholes'])) {
	$listHash['pigeonholes']['filter'] = split(",", $module_params['pigeonholes']);
}
$events = new BitEvents();
$list = $events->getList( $listHash );
$maxPreviewLength = (!empty($module_params['max_preview_length']) ? $module_params['max_preview_length'] : MAX_EVENTS_PREVIEW_LENGTH);

$gBitSmarty->assign('maxPreviewLength', $maxPreviewLength);
$gBitSmarty->assign('modUpcomingEvents', $list);

$gBitSmarty->assign('eventsPackageActive', $gBitSystem->isPackageActive('events'));

?>
