{* $Header: /cvsroot/bitweaver/_bit_events/templates/edit_events.tpl,v 1.9 2007/06/10 02:55:35 nickpalmer Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

<div class="edit events">
	<div class="header">
		<h1>
			{* this weird dual assign thing is cause smarty wont interpret backticks to object in assign tag - spiderr *}
 			{assign var=conDescr value=$gContent->mType.content_description}
 			{if $gContent->mInfo.events_id}
 				{assign var=editLabel value="{tr}Edit{/tr} $conDescr"}
 				{tr}{tr}Edit{/tr} {$gContent->mInfo.title}{if $gContent->mInfo.page_alias}&nbsp;( {$gContent->mInfo.page_alias} ){/if}{/tr}
 			{else}
 				{assign var=editLabel value="{tr}Create{/tr} $conDescr"}
 				{tr}{$editLabel}{/tr}
 			{/if}
 		</h1>
 	</div>

	{* Check to see if there is an editing conflict *}
 	{if $errors.edit_conflict}
		<script type="text/javascript">/* <![CDATA[ */
 			alert( "{$errors.edit_conflict|strip_tags}" );
 		/* ]]> */</script>
 		{formfeedback warning=`$errors.edit_conflict`}
 	{/if}
	
	{if $preview}
		<h2>Preview {$gContent->mInfo.title}</h2>
		<div class="preview">
			{include file="bitpackage:events/event_display.tpl" page=`$gContent->mInfo.events_id`}
		</div>
	{/if}

{/strip}
	<div class="body">
		{if $translateFrom}
		  	<div class="translate">
				{include file="bitpackage:events/event_display.tpl" pageInfo=$translateFrom->mInfo}
							 
				{if $translateFrom->mInfo.google_guess}
					<hr />
					<h1>{tr}Google's translation attempt{/tr}</h1>
					<code>{$translateFrom->mInfo.google_guess|nl2br}</code>
				{/if}
			</div>
		{/if}
	
		{form enctype="multipart/form-data" id="editeventsform"}
			{jstabs}
				{jstab title="$editLabel"}
					{legend legend="$editLabel"}
						{strip}
						<input type="hidden" name="events_id" value="{$gContent->mInfo.events_id}" />
						<input type="hidden" name="events_date" value="{$gContent->mInfo.events_date}" />

						<div class="row">
							{formlabel label="Title" for="title"}
							{forminput}
								<input type="text" size="60" maxlength="200" name="title" id="title" value="{if $preview}{$gContent->mInfo.title}{else}{$gContent->mInfo.title}{/if}" />
								{formhelp note="The title or name of the event."}
							{/forminput}
						</div>

						<div class="row">
							{formlabel label="Summary" for="description"}
							{forminput}
								<input size="60" type="text" name="description" id="description" value="{$gContent->mInfo.description|escape}" />
								{formhelp note="Brief description of the event."}
							{/forminput}
						</div>

						<div class="row">
							{formlabel label="Cost" for="cost"}
							{forminput}
								<input size="60" type="text" name="cost" id="cost" value="{$gContent->mInfo.cost|escape}" />
								{formhelp note="The cost of the event. (Free, $10, $5 in advance $10 at door, etc)"}
							{/forminput}
						</div>
				
						<!-- value="$event_time|bit_short_datetime}" -->
{if $gBitSystem->isFeatureActive( 'site_use_jscalendar' )}

						{forminput}
							<input type="hidden" id="event_time" name="event_time"/>
							<span class="highlight" style="cursor:pointer;" title="{tr}Date Selector{/tr}" id="datrigger">{$gContent->mInfo.event_time|bit_short_datetime}</span>
							&nbsp;&nbsp;&nbsp;<small>&laquo;&nbsp;{tr}click to change date{/tr}</small>
							{formhelp note="The date the event is on"}
						{/forminput}
{else}
						{formlabel label="Event Date"}
						{forminput}
							{html_select_date time=$gContent->mInfo.event_time field_array="start_date" prefix="" end_year=$gBitSystem->getConfig('events_end_year', "+1")}
							{formhelp note="The date the event is on."}
						{/forminput}
						{formlabel label="Start Time"}
						{forminput}
							<input type="checkbox" name="show_start_time" {if $gContent->getField('show_start_time')}checked{/if} /> 
							{html_select_time time=$gContent->mInfo.event_time minute_interval=$gBitSystem->getConfig('events_minute_interval', 15) field_array="start_time" prefix="" display_seconds=0 use_24_hours=$gBitSystem->isFeatureActive('events_use_24')}
							{formhelp note="The time the event starts. If no start time is specified the event is assumed to be an all day event."}
						{/forminput}
{/if}
						{/strip}
{if $gBitSystem->isFeatureActive( 'site_use_jscalendar' )}
						<script type="text/javascript">//<![CDATA[
							Calendar.setup( {ldelim}
								date			: "{$gContent->mInfo.event_time|bit_short_datetime}",	// initial date
								inputField		: "event_time",				// ID of the input field
								ifFormat		: "%s",					// the date format
								displayArea 	: "datrigger",			// ID of the span where the date is to be shown
								daFormat		: "{"%d/%m/%Y %H:%M"}",	// format of the displayed date
								electric		: false,
								showsTime		: true,
								timeFormat		: {if $gBitSystem->isFeatureActive('events_use_24')}"24"{else}"12"{/if}
							{rdelim} );
						//]]>
						</script>
{/if}
						{strip}

						{formlabel label="End Time"}
						{forminput}
							<input type="checkbox" name="show_end_time" {if $gContent->getField('show_end_time')}checked{/if} />
							{html_select_time time=$gContent->mInfo.end_time minute_interval=$gBitSystem->getConfig('events_minute_interval', 15) field_array="end_time" prefix="" display_seconds=0 use_24_hours=$gBitSystem->isFeatureActive('events_use_24')}
							{formhelp note="The time the event is over."}
						{/forminput}


						{textarea label="Description" help="The long description of the event including any images."}{$gContent->mInfo.data}{/textarea}
			
						{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_mini_tpl}

						<div class="row submit">
							<input type="submit" name="preview" value="{tr}Preview{/tr}" /> 
							<input type="submit" name="save_events" value="{tr}Save{/tr}" />
						</div>
				
						{/strip}
					{/legend}
				{/jstab}

				{jstab title="Attachments"}
					<div class=row>
					{legend legend="Attachments"}
						{include file="bitpackage:liberty/edit_storage.tpl"}

					{/legend}
					</div>
				{/jstab}

				{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_tab_tpl}

			{/jstabs}
		{/form}
	</div><!-- end .body -->
</div><!-- end .events -->


