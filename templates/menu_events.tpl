{strip}
{if $packageMenuTitle}<a class="dropdown-toggle" data-toggle="dropdown" href="#"> {tr}{$packageMenuTitle}{/tr} <b class="caret"></b></a>{/if}
<ul class="{$packageMenuClass}">
	<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}list_events.php">{booticon iname="icon-list" iexplain="List Events" ilocation=menu}</a></li>
	{if $gBitUser->hasPermission( 'p_events_create' )}
		<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}edit.php">{booticon iname="icon-date-add" iexplain="Create Event" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive('calendar')}
		<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}calendar.php">{booticon iname="icon-date" iexplain="Events Calendar" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
