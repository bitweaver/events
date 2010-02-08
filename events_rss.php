<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_events/events_rss.php,v 1.7 2010/02/08 21:27:22 wjames5 Exp $
 * @package events
 * @subpackage functions
 */

/**
 * Initialization
 */
require_once( "../kernel/setup_inc.php" );

$gBitSystem->verifyPackage( 'rss' );
$gBitSystem->verifyPackage( 'events' );
$gBitSystem->verifyFeature( 'events_rss' );

require_once( EVENTS_PKG_PATH.'BitEvents.php' );
require_once( RSS_PKG_PATH."rss_inc.php" );

// default feed info
$rss->title = $gBitSystem->getConfig( 'events_rss_title', $gBitSystem->getConfig( 'site_title' ).' - '.tra( 'Events' ) );
$rss->description = $gBitSystem->getConfig( 'events_rss_description', $gBitSystem->getConfig( 'site_title' ).' - '.tra( 'RSS Feed' ) );

// check permission to view wiki pages
if( !$gBitUser->hasPermission( 'p_events_view' ) ) {
	require_once( RSS_PKG_PATH."rss_error.php" );
} else {
	// check if we want to use the cache file
	$cacheFile = TEMP_PKG_PATH.RSS_PKG_NAME.'/'.EVENTS_PKG_NAME.'/'.( !empty( $_REQUEST['user_id'] ) ? "_".$_REQUEST['user_id'] : "" ).( !empty( $_REQUEST['event_id'] ) ? "_".$_REQUEST['event_id'] : "" ).$cacheFileTail;
	$rss->useCached( $rss_version_name, $cacheFile, $gBitSystem->getConfig( 'rssfeed_cache_time' ));

	$event = new BitEvents();
	$listHash['sort_mode'] = 'last_modified_desc';
	$listHash['max_records'] = $gBitSystem->getConfig( 'events_rss_max_records', 10 );
	$listHash['parse_data'] = TRUE;
	$listHash['full_data'] = TRUE;
	if( !empty( $_REQUEST['user_id'] ) ) {
		require_once( USERS_PKG_PATH.'BitUser.php' );
		$eventUser = new BitUser();
		$userData = $eventUser->getUserInfo( array('user_id' => $_REQUEST['user_id']) );
		// dont try and fool me
		if (!empty($userData)){
			$userName = $userData['real_name']?$userData['real_name']:$userData['login'];
			$rss->title = $userName." at ".$gBitSystem->getConfig( 'site_title' );
			$listHash['user_id'] = $_REQUEST['user_id'];
		}
	}

	if( !empty( $_REQUEST['event_id'] ) ) {
		$listHash['event_id'] = $_REQUEST['event_id'];
		$gEvent = new BitEvents( $_REQUEST['event_id'] );
		$gEvent->load();
		if( isset($gEvent->mContentId) ) {
			// adjust feed title to event title
			$rss->title = $gEvent->getTitle()." at ".$gBitSystem->getConfig( 'site_title' );
			if (isset($userName)){
				$rss->title = $userName."'s Events in ".$rss->title;
			}
			$rss->description = $gEvent->parseData();
		}
	}
	$feeds = $event->getList( $listHash );

	// set the rss link
	$rss->link = 'http://'.$_SERVER['HTTP_HOST'].EVENTS_PKG_URL.( !empty( $_REQUEST['event_id'] ) ? "?event_id=".$_REQUEST['event_id'] : "" );
	// get all the data ready for the feed creator
	foreach( $feeds as $feed ) {
		$item = new FeedItem();
		$item->title = $event->getTitle( $feed );
		$item->link = BIT_BASE_URI.$event->getDisplayUrl( $feed['content_id'] );
		$item->description = $feed['parsed'];

		$item->date = ( int )$feed['last_modified'];
		$item->source = 'http://'.$_SERVER['HTTP_HOST'].BIT_ROOT_URL;
		$item->author = $gBitUser->getDisplayName( FALSE, $feed );

		$item->descriptionTruncSize = $gBitSystem->getConfig( 'rssfeed_truncate', 50000 );
		$item->descriptionHtmlSyndicated = TRUE;

		// pass the item on to the rss feed creator
		$rss->addItem( $item );
	}

	// finally we are ready to serve the data
	echo $rss->saveFeed( $rss_version_name, $cacheFile );
}
?>
