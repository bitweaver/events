{strip}
	{if $gBitUser->hasPermission( 'p_read_events')}
		<ul>
			<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}list_events.php">{biticon ipackage="icons" iname="format-justify-fill" iexplain="List Events" iforce="icon"}{tr}List Events{/tr}</a></li>
			{if $gBitUser->hasPermission( 'p_create_events' ) || $gBitUser->hasPermission( 'p_edit_events' ) }
				<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}edit.php">{biticon ipackage="icons" iname="appointment-new" iexplain="Create Event" iforce="icon"}{tr}Create Event{/tr}</a></li>
			{/if}
			{if $gBitSystem->isPackageActive('calendar')}
				<li><a class="item" href="{$smarty.const.CALENDAR_PKG_URL}index.php">{biticon ipackage="icons" iname="x-office-calendar" iexplain="Events Calendar" iforce="icon"}{tr}Events Calendar{/tr}</a></li>
			{/if}
		</ul>
	{/if}
{/strip}
