{strip}
{formfeedback hash=$feedback}
{form}
	{jstabs}

		{jstab title="Display Settings"}
		{legend legend="Display Settings"}
			<input type="hidden" name="page" value="{$page}" />
			{foreach from=$formEventsDisplayOptions key=item item=output}
				<div class="control-group column-group gutters">
					{formlabel label=$output.label for=$item}
					{forminput}
						{if $output.type == 'numeric'}
							{html_options name="$item" values=$numbers output=$numbers selected=$gBitSystem->getConfig($item) labels=false id=$item}
						{elseif $output.type == 'input'}
							<input type='text' name="{$item}" id="{$item}" value="{$gBitSystem->getConfig($item)}" />
						{else}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
						{/if}
						{formhelp note=$output.note page=$output.page}
					{/forminput}
				</div>
			{/foreach}
		{/legend}
		{legend legend="Features"}
			{foreach from=$formEventsFeatureOptions key=item item=output}
				<div class="control-group column-group gutters">
					{formlabel label=$output.label for=$item}
					{forminput}
						{if $output.type == 'numeric'}
							{html_options name="$item" values=$numbers output=$numbers selected=$gBitSystem->getConfig($item) labels=false id=$item}
						{elseif $output.type == 'input'}
							<input type='text' name="{$item}" id="{$item}" value="{$gBitSystem->getConfig($item)}" />
						{else}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
						{/if}
						{formhelp note=$output.note page=$output.page}
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
				<div class="control-group column-group gutters">
					{formlabel label=$output.label for=$item}
					{forminput}
						{if $output.type == 'numeric'}
							{html_options name="$item" values=$numbers output=$numbers selected=$gBitSystem->getConfig($item) labels=false id=$item}
						{elseif $output.type == 'input'}
							<input type='text' name="{$item}" id="{$item}" value="{$gBitSystem->getConfig($item)}" />
						{else}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
						{/if}
						{formhelp note=$output.note page=$output.page}
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
				<div class="control-group column-group gutters">
					{formlabel label=$output.label for=$item}
					{forminput}
						{if $output.type == 'numeric'}
							{html_options name="$item" values=$numbers output=$numbers selected=$gBitSystem->getConfig($item) labels=false id=$item}
						{elseif $output.type == 'input'}
							<input type='text' name="{$item}" id="{$item}" value="{$gBitSystem->getConfig($item)}" />
						{else}
							{html_checkboxes name="$item" values="y" checked=$gBitSystem->getConfig($item) labels=false id=$item}
						{/if}
						{formhelp note=$output.note page=$output.page}
					{/forminput}
				</div>
			{/foreach}
		{/legend}
		{/jstab}
*}
		{jstab title="Event Types"}
			{legend legend="Event Types"}
				<table>
					<tr><th>{tr}Type Name{/tr}</th><th>{tr}Description{/tr}<th>{tr}Delete{/tr}</th></tr>
					{foreach from=$eventTypes key=id item=type}
						<tr>
							<td><p style="text-align:center"><input maxlength=30 name="update[{$id}][name]" value="{$type.name}" /></p></td>
							<td><p style="text-align:center"><input maxlength=160 name="update[{$id}][desc]" value="{$type.description}" /></p></td>
							<td><p style="text-align:center"><input type="checkbox" value="y" name="deleteType[{$id}]"/>
						</tr>
					{foreachelse}
						<tr><td colspan=3><p style="text-align:center">{tr}There are no types yet{/tr}</p></td></tr>
					{/foreach}
				</table>				
			{/legend}
			{legend legend="Add Event Type"}
				<div class="control-group column-group gutters"
					{formlabel label="Type Name" for="typeName"}
					{forminput}
						<input maxlength=30 name="typeName" />
						{formhelp note="The name of this type"}
					{/forminput}
				</div>
				<div class="control-group column-group gutters">
					{formlabel label="Type Description" for="typeDesc"}
					{forminput}
						<input maxlength=160 name="typeDesc" />
						{formhelp note="The description of this type"}
					{/forminput}
				</div>
			{/legend}
		{/jstab}
	{/jstabs}
	<div class="control-group submit">
		<input type="submit" class="ink-button" name="events_preferences" value="{tr}Change preferences{/tr}" />
	</div>
{/form}
{/strip}
