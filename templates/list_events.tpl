{* $Header: /cvsroot/bitweaver/_bit_events/templates/list_events.tpl,v 1.6 2007/03/21 23:42:51 phoenixandy Exp $ *}
<div class="floaticon">{bithelp}</div>

<div class="listing events display">
	<div class="header">
		<h1>{tr}Events Records{/tr}</h1>
	</div>

	<div class="body">
		{minifind sort_mode=$sort_mode}
		{form id="checkform"}
{strip}
			<input type="hidden" name="offset" value="{$control.offset|escape}" />
			<input type="hidden" name="sort_mode" value="{$control.sort_mode|escape}" />

			<table class="data">
				<caption>{tr}List of Events{/tr}</caption>
				<tr>
					<th>{smartlink ititle="Event Date" isort=event_time idefault=1 iorder=desc offset=$control.offset}</th>
					<th>{smartlink ititle="Title" isort=title offset=$control.offset}</th>

					<th>{smartlink ititle="Description" isort=description offset=$control.offset}</th>

					{if $gBitUser->hasPermission( 'bit_p_remove_events' )}
						<th>{tr}Actions{/tr}</th>
					{/if}
				</tr>

				{section name=changes loop=$list}
					<tr class="{cycle values="even,odd"}">
						<td>{$list[changes].event_time|bit_short_datetime}</td>

						<td><a href="{$smarty.const.EVENTS_PKG_URL}index.php?events_id={$list[changes].events_id|escape:"url"}" title="{$list[changes].events_id}">{$list[changes].title}</a></td>

						<td>{$list[changes].description}</td>

						{if $gBitUser->hasPermission( 'bit_p_remove_events' )}
							<td class="actionicon">
								{smartlink ititle="Edit" ifile="edit.php" ibiticon="icons/accessories-text-editor" events_id=$list[changes].events_id}
								<input type="checkbox" name="checked[]" title="{$list[changes].title}" value="{$list[changes].events_id|escape}" />
							</td>
							<td style="text-align:right; vertical-align:top;">
	                                                        {include file="bitpackage:liberty/services_inc.tpl" serviceLocation='list' serviceHash=$listpages[changes]}
	                                                </td>
						{/if}
					</tr>
				{sectionelse}
					<tr class="norecords"><td colspan="16">
						{tr}No records found{/tr}
					</td></tr>
				{/section}
			</table>
{/strip}

			{if $gBitUser->hasPermission( 'bit_p_remove_events' )}
				<div style="text-align:right;">
					<script type="text/javascript">//<![CDATA[
						// check / uncheck all.
						document.write("<label for=\"switcher\">{tr}Select All{/tr}</label> ");
						document.write("<input name=\"switcher\" id=\"switcher\" type=\"checkbox\" onclick=\"switchCheckboxes(this.form.id,'checked[]','switcher')\" /><br />");
					//]]></script>

					<select name="submit_mult" onchange="this.form.submit();">
						<option value="" selected="selected">{tr}with checked{/tr}:</option>
						{if $gBitUser->hasPermission( 'bit_p_remove_events' )}
							<option value="remove_events">{tr}remove{/tr}</option>
						{/if}
					</select>

					<script type="text/javascript">//<![CDATA[
					// Fake js to allow the use of the <noscript> tag (so non-js-users kenn still submit)
					//]]></script>

					<noscript>
						<div><input type="submit" value="{tr}Submit{/tr}" /></div>
					</noscript>
				</div>
			{/if}
		{/form}
	</div><!-- end .body -->

	{pagination}
</div><!-- end .admin -->
