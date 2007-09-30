{strip}
<ul>
	<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}list_events.php">{biticon iname="format-justify-fill" iexplain="List Events" ilocation=menu}</a></li>
	{if $gBitUser->hasPermission( 'p_create_events' ) || $gBitUser->hasPermission( 'p_edit_events' ) }
		<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}edit.php">{biticon iname="appointment-new" iexplain="Create Event" ilocation=menu}</a></li>
	{/if}
	{if $gBitSystem->isPackageActive('calendar')}
		<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}calendar.php">{biticon iname="x-office-calendar" iexplain="Events Calendar" ilocation=menu}</a></li>
	{/if}
</ul>
{/strip}
