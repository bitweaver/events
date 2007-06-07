{strip}
	{if $gBitUser->hasPermission( 'p_read_events')}
		<ul>
			<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}list_events.php">{tr}List Events{/tr}</a></li>
			{if $gBitUser->hasPermission( 'p_create_events' ) || $gBitUser->hasPermission( 'p_edit_events' ) }
				<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}edit.php">{tr}Create Events{/tr}</a></li>
			{/if}
			{if $gBitSystem->isPackageActive('calendar')}
				<li><a class="item" href="{$smarty.const.CALENDAR_PKG_URL}index.php">{tr}Events Calendar{/tr}</a></li>
			{/if}
		</ul>
	{/if}
{/strip}
