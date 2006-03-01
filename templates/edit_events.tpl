{* $Header: /cvsroot/bitweaver/_bit_events/templates/edit_events.tpl,v 1.5 2006/03/01 20:16:07 spiderr Exp $ *}
{strip}
<div class="floaticon">{bithelp}</div>

{* Check to see if there is an editing conflict *}
{if $editpageconflict == 'y'}
	<script language="javascript" type="text/javascript">
		<!-- Hide Script
			alert("{tr}This page is being edited by {$semUser}{/tr}. {tr}Proceed at your own peril{/tr}.")
		//End Hide Script-->
	</script>
{/if}

<div class="admin events">
	{if $preview}
		<h2>Preview {$gContent->mInfo.title}</h2>
		<div class="preview">
			{include file="bitpackage:events/events_display.tpl" page=`$gContent->mInfo.events_id`}
		</div>
	{/if}

	<div class="header">
		<h1>
			{if $gContent->mInfo.events_id}
				{tr}{tr}Edit{/tr} {$gContent->mInfo.title}{if $gContent->mInfo.page_alias}&nbsp;( {$gContent->mInfo.page_alias} ){/if}{/tr}
			{else}
				{tr}Create New Record{/tr}
			{/if}
		</h1>
	</div>
{/strip}
	<div class="body">
		{form enctype="multipart/form-data" id="editeventsform"}
			{legend legend="Edit/Create Events Record"}
				{strip}
				<input type="hidden" name="events_id" value="{$gContent->mInfo.events_id}" />
				<input type="hidden" name="events_date" value="{$gContent->mInfo.events_date}" />

				<div class="row">
					{formlabel label="Title" for="title"}
					{forminput}
						<input type="text" size="60" maxlength="200" name="title" id="title" value="{if $preview}{$gContent->mInfo.title}{else}{$gContent->mInfo.title}{/if}" />
					{/forminput}
				</div>

				{if $gBitSystem->isFeatureActive( 'wiki_description' )}
					<div class="row">
						{formlabel label="Description" for="description"}
						{forminput}
							<input size="60" type="text" name="description" id="description" value="{$gContent->mInfo.description|escape}" />
							{formhelp note="Brief description of the page."}
						{/forminput}
					</div>
				{/if}
				
				
				<!-- value="$event_date|cal_date_format:"%B %e, %Y %H:%M %Z"}" -->
				{forminput}
				<input type="hidden" id="event_time" name="event_time"/>
				<span class="highlight" style="cursor:pointer;" title="{tr}Date Selector{/tr}" id="datrigger">{$gContent->mInfo.event_time|bit_date_format:"%m/%d/%Y %H:%M"}</span>
						&nbsp;&nbsp;&nbsp;<small>&laquo;&nbsp;{tr}click to change date{/tr}</small>
						{formhelp note="The date the event is on"}
				{/forminput}
				{/strip}
				<script type="text/javascript">//<![CDATA[
					Calendar.setup( {ldelim}
						date			: "{$gContent->mInfo.event_time|bit_date_format:"%m/%d/%Y %H:%M"}",	// initial date
						inputField		: "event_time",				// ID of the input field
						ifFormat		: "%s",					// the date format
						displayArea 	: "datrigger",			// ID of the span where the date is to be shown
						daFormat		: "{"%d/%m/%Y %H:%M"}",	// format of the displayed date
						electric		: false,
						showsTime		: true,
						timeFormat		: "12"
					{rdelim} );
				//]]>
				</script>
				{strip}
				{include file="bitpackage:liberty/edit_format.tpl"}

				{if $gBitSystem->isFeatureActive('package_smileys')}
					{include file="bitpackage:smileys/smileys_full.tpl"}
				{/if}

				{if $gBitSystem->isFeatureActive('package_quicktags')}
					{include file="bitpackage:quicktags/quicktags_full.tpl"}
				{/if}

				<div class="row">
					{forminput}
						<textarea id="{$textarea_id}" name="edit" rows="{$smarty.cookies.rows|default:20}" cols="50">{$gContent->mInfo.data|escape:html}</textarea>
					{/forminput}
				</div>

				<div class="row submit">
					<input type="submit" name="preview" value="{tr}preview{/tr}" /> 
					<input type="submit" name="save_events" value="{tr}Save{/tr}" />
				</div>
				{/strip}
			{/legend}
		{/form}
	</div><!-- end .body -->
</div><!-- end .events -->


