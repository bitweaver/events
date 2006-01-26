{strip}
	<ul>
		{if $gBitUser->hasPermission( 'bit_p_read_events')}
			<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}index.php">{tr}Events Home{/tr}</a></li>
		{/if}
		{if $gBitUser->hasPermission( 'bit_p_read_events')  || $gBitUser->hasPermission( 'bit_p_remove_events' ) }
			<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}list_events.php">{tr}List Events{/tr}</a></li>
		{/if}
		{if $gBitUser->hasPermission( 'bit_p_create_events' ) || $gBitUser->hasPermission( 'bit_p_edit_events' ) }
			<li><a class="item" href="{$smarty.const.EVENTS_PKG_URL}edit.php">{tr}Create Events{/tr}</a></li>
		{/if}
	</ul>
{/strip}
