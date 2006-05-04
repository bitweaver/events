{* $Header: /cvsroot/bitweaver/_bit_events/templates/header_inc.tpl,v 1.1 2006/05/04 19:04:58 squareing Exp $ *}
{strip}
{if $gBitSystem->isPackageActive( 'rss' ) and $gBitSystem->isFeatureActive( 'events_rss' ) and $smarty.const.ACTIVE_PACKAGE eq 'events'}
	<link rel="alternate" type="application/rss+xml" title="{tr}Events{/tr} RSS" href="{$smarty.const.BLOGS_PKG_URL}events_rss.php?version=rss20" />
	<link rel="alternate" type="application/rss+xml" title="{tr}Events{/tr} ATOM" href="{$smarty.const.BLOGS_PKG_URL}events_rss.php?version=atom" />
{/if}
{/strip}
