{strip}
	{if !empty($contentHash.description)}
		<div class="row events desc"><h4>{$contentHash.description}</h4></div>
	{/if}
	{$contentHash.event_time|bit_long_date}
	{if $contentHash.show_start_time}
		&nbsp;{$contentHash.event_time|bit_short_time}
	{/if}
	{if $contentHash.show_end_time}
		{if $contentHash.show_start_time}
			<div class=row>{tr}until{/tr}</div>
		{/if}
		{$contentHash.end_time|bit_long_date}&nbsp;
		{$contentHash.end_time|bit_short_time}
	{/if}
{/strip}
