{strip}
{form}
	{jstabs}
		{jstab title="Home Events"}
			{legend legend="Home Events"}
				<input type="hidden" name="page" value="{$page}" />
				<div class="row">
					{formlabel label="Home Events (main events)" for="homeEvents"}
					{forminput}
						<select name="homeEvents" id="homeEvents">
							{section name=ix loop=$events}
								<option value="{$events[ix].events_id|escape}" {if $events[ix].events_id eq $home_events}selected="selected"{/if}>{$events[ix].title|truncate:20:"...":true}</option>
							{sectionelse}
								<option>{tr}No records found{/tr}</option>
							{/section}
						</select>
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" name="homeTabSubmit" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}
		{/jstab}

		{jstab title="List Settings"}
			{legend legend="List Settings"}
				<input type="hidden" name="page" value="{$page}" />
				{foreach from=$formEventsLists key=item item=output}
					<div class="row">
						{formlabel label=`$output.label` for=$item}
						{forminput}
							{html_checkboxes name="$item" values="y" checked=`$gBitSystemPrefs.$item` labels=false id=$item}
							{formhelp note=`$output.note` page=`$output.page`}
						{/forminput}
					</div>
				{/foreach}

				<div class="row submit">
					<input type="submit" name="listTabSubmit" value="{tr}Change preferences{/tr}" />
				</div>
			{/legend}
		{/jstab}
	{/jstabs}
{/form}
{/strip}
