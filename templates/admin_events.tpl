{strip}
{formfeedback hash=$feedback}
{form}
	{jstabs}

		{jstab title="Display Settings"}
		{legend legend="Display Settings"}
			<input type="hidden" name="page" value="{$page}" />
			{foreach from=$formEventsDisplayOptions key=item item=output}
				<div class="row">
					{formlabel label=`$output.label` for=$item}
					{forminput}
						{if $output.type == 'numeric'}
							{html_options name="$item" values=$numbers output=$numbers selected=$gBitSystem->getConfig($item) labels=false id=$item}
						{elseif $output.type == 'input'}
							<input type='text' name="{$item}" id="{$item}" value="{$gBitSystem->getConfig($item)}" />
						{else}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
						{/if}
						{formhelp note=`$output.note` page=`$output.page`}
					{/forminput}
				</div>
			{/foreach}
		{/legend}
		{/jstab}
{* Not sure where this came from since we don't set any formEventsStripOptions...
		{jstab title="Sanitation Settings"}
		{legend legend="Santitation Settings"}
			<input type="hidden" name="page" value="{$page}" />
			{foreach from=$formEventsStripOptions key=item item=output}
				<div class="row">
					{formlabel label=`$output.label` for=$item}
					{forminput}
						{if $output.type == 'numeric'}
							{html_options name="$item" values=$numbers output=$numbers selected=$gBitSystem->getConfig($item) labels=false id=$item}
						{elseif $output.type == 'input'}
							<input type='text' name="{$item}" id="{$item}" value="{$gBitSystem->getConfig($item)}" />
						{else}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
						{/if}
						{formhelp note=`$output.note` page=`$output.page`}
					{/forminput}
				</div>
			{/foreach}
		{/legend}
		{/jstab}
*}
{*
		{jstab title="Other Settings"}
		{legend legend="Other Settings"}
			<input type="hidden" name="page" value="{$page}" />
			{foreach from=$formEventsOtherOptions key=item item=output}
				<div class="row">
					{formlabel label=`$output.label` for=$item}
					{forminput}
						{if $output.type == 'numeric'}
							{html_options name="$item" values=$numbers output=$numbers selected=$gBitSystem->getConfig($item) labels=false id=$item}
						{elseif $output.type == 'input'}
							<input type='text' name="{$item}" id="{$item}" value="{$gBitSystem->getConfig($item)}" />
						{else}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
						{/if}
						{formhelp note=`$output.note` page=`$output.page`}
					{/forminput}
				</div>
			{/foreach}
		{/legend}
		{/jstab}
*}
	{/jstabs}
	<div class="row submit">
		<input type="submit" name="events_preferences" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
{/strip}
