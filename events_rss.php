<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_events/events_rss.php,v 1.3 2006/05/04 18:43:22 squareing Exp $
 * @package wiki
 * @subpackage functions
 */

/**
 * Initialization
 */
require_once( "../bit_setup_inc.php" );
require_once( RSS_PKG_PATH."rss_inc.php" );
require_once( EVENTS_PKG_PATH."BitEvents.php" );

$gBitSystem->verifyPackage( 'events' );
$gBitSystem->verifyPackage( 'rss' );
$gBitSystem->verifyFeature( 'events_rss' );

$rss->title = $gBitSystem->getConfig( 'events_rss_title', $gBitSystem->mPrefs['siteTitle'].' - '.tra( 'Events' ) );
$rss->description = $gBitSystem->getConfig( 'events_rss_description', $gBitSystem->mPrefs['siteTitle'].' - '.tra( 'RSS Feed' ) );

// check if we want to use the cache file
$cacheFile = TEMP_PKG_PATH.RSS_PKG_NAME.'/'.EVENTS_PKG_NAME.'_'.$version.'.xml';
$rss->useCached( $cacheFile ); // use cached version if age < 1 hour

$events = new BitEvents();
$pParamHash = array();
$pParamHash['find'] ='';
//TODO allow proper sort order
//$pParamHash['sort_mode'] = "event_date_desc";
$pParamHash['sort_mode'] = "last_modified_desc";
$max_records = $gBitSystem->getConfig( 'events_rss_max_records', 10 );
$pParamHash['offset'] = 0;
$feeds = $events->getList( $pParamHash );
$feeds = $feeds['data'];

// get all the data ready for the feed creator
foreach( $feeds as $feed ) {
	/*
	echo "<pre>";
	var_dump($feed);
	//*/
	$item = new FeedItem();
	$item->title = date("d M Y H:i",$feed['event_time'])." - ".$feed['title'];
		
	$item->link = BIT_BASE_URI."/?content_id=".$feed['content_id'];
	$item->description =  $feed['description'];
			
	//TODO allow proper sort order
	//$item->date = ( int )$feed['event_date'];
	
	$item->date = ( int )$feed['event_time'];
	$item->source = 'http://'.$_SERVER['HTTP_HOST'].BIT_ROOT_URL;
	$user = new BitUser($feed['modifier_user_id']);
	$user->load();
	
	$item->author = $user->getDisplayName();//$gBitUser->getDisplayName( FALSE, array( 'user_id' => $feed['modifier_user_id'] ) );
	$item->authorEmail = $user->mInfo['email'];

	$item->descriptionTruncSize = $gBitSystem->getConfig( 'rssfeed_truncate', 1000 );
	$item->descriptionHtmlSyndicated = FALSE;
	/*
	var_dump($item);
	echo "</pre>";
	die();
	//*/
	// pass the item on to the rss feed creator
	$rss->addItem( $item );
}

// finally we are ready to serve the data
echo $rss->saveFeed( $rss_version_name, $cacheFile );
?>
