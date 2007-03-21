{* $Header: /cvsroot/bitweaver/_bit_events/templates/edit_events.tpl,v 1.7 2007/03/21 23:42:51 phoenixandy Exp $ *}
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
				{jstab title="$editLabel Body"}
					{legend legend="$editLabel Body"}
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
									{formhelp note="Brief description of the page. This is visible when you hover over a link to this page and just below the title of the wiki page."}
								{/forminput}
							</div>
						{/if}
				
						<!-- value="$event_time|bit_short_datetime}" -->
						{forminput}
							<input type="hidden" id="event_time" name="event_time"/>
							<span class="highlight" style="cursor:pointer;" title="{tr}Date Selector{/tr}" id="datrigger">{$gContent->mInfo.event_time|bit_short_datetime}</span>
							&nbsp;&nbsp;&nbsp;<small>&laquo;&nbsp;{tr}click to change date{/tr}</small>
							{formhelp note="The date the event is on"}
						{/forminput}
						{/strip}
						<script type="text/javascript">//<![CDATA[
							Calendar.setup( {ldelim}
								date			: "{$gContent->mInfo.event_time|bit_short_datetime}",	// initial date
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

						{include file="bitpackage:liberty/edit_content_status_inc.tpl"}
				
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
			
						{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_mini_tpl}

						<div class="row submit">
							<input type="submit" name="preview" value="{tr}preview{/tr}" /> 
							<input type="submit" name="save_events" value="{tr}Save{/tr}" />
						</div>
				
						{/strip}
					{/legend}
				{/jstab}

				{include file="bitpackage:liberty/edit_services_inc.tpl serviceFile=content_edit_tab_tpl}

			{/jstabs}
		{/form}
	</div><!-- end .body -->
</div><!-- end .events -->


