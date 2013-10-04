{* $Header$ *}
{strip}
{if $gBitSystem->isPackageActive( 'rss' ) and $gBitSystem->isFeatureActive( 'events_rss' ) and $smarty.const.ACTIVE_PACKAGE eq 'events'}
	<link rel="alternate" type="application/rss+xml" title="{tr}Events{/tr} RSS" href="{$smarty.const.EVENTS_PKG_URL}events_rss.php?version=rss20" />
	<link rel="alternate" type="application/rss+xml" title="{tr}Events{/tr} ATOM" href="{$smarty.const.EVENTS_PKG_URL}events_rss.php?version=atom" />
{/if}
{if $smarty.const.ACTIVE_PACKAGE == 'events'}
	<link rel="stylesheet" title="{$style}" type="text/css" href="{$smarty.const.CALENDAR_PKG_URL}styles/plain.css" media="all" />
{/if}
{/strip}
