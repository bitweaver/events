{strip}
	{if !empty($contentHash.description)}
		<div class="eventrow desc">{$contentHash.description}</div>
	{/if}
	{if $gBitSystem->isPackageActive('calendar')}
		<a href="{$smarty.const.CALENDAR_PKG_URL}index.php?view_mode=month&todate={math x=$contentHash.event_time y=$gBitSystem->get_display_offset() equation="x + y"}">
	{/if}
	{$contentHash.event_time|bit_long_date}
	{if $gBitSystem->isPackageActive('calendar')}
		</a>
	{/if}
	{if !empty($contentHash.cost)}
		<div class="eventrow cost"><h4>{tr}Cost:{/tr} {$contentHash.cost}</h4></div>
	{/if}
	{if $gBitSystem->isFeatureActive('events_use_types') && !empty($contentHash.type_name)}
		<div class="eventrow type">{tr}Type:{/tr} {$contentHash.type_name}</div>
	{/if}
	{if $contentHash.show_start_time}
		&nbsp;{$contentHash.event_time|bit_short_time}
	{/if}
	{if $contentHash.show_end_time}
		{if $contentHash.show_start_time}
			<div class=row>{tr}until{/tr}</div>
		{else}
			{tr}Ending{/tr}:&nbsp; 
		{/if}
		{$contentHash.end_time|bit_long_date}&nbsp;
		{$contentHash.end_time|bit_short_time}
	{/if}
{/strip}
