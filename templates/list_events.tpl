{* $Header: /cvsroot/bitweaver/_bit_events/templates/list_events.tpl,v 1.10 2007/06/10 09:34:43 squareing Exp $ *}

{strip}

<div class="floaticon">{bithelp}</div>

<div class="listing events">

	<div class="header">
		<h1>{tr}Events{/tr} ({$list|@count})</h1>
	</div>

	<div class="body">
		{minifind sort_mode=$sort_mode}
		{form id="checkform"}

			<input type="hidden" name="offset" value="{$control.offset|escape}" />
			<input type="hidden" name="sort_mode" value="{$control.sort_mode|escape}" />

			<table class="data">
				<caption>{tr}List of Events{/tr}</caption>
				<thead>
					<tr>
						<th>{smartlink ititle="Event Date" isort=event_time idefault=1 iorder=desc offset=$control.offset}</th>
						<th>{smartlink ititle="Title" isort=title offset=$control.offset}</th>
						<th>{smartlink ititle="Description" isort=description offset=$control.offset}</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="3" class="actionicon">
							{literal}
								<script type="text/javascript">//<![CDATA[
									// check / uncheck all.
									document.write("<input name='switcher' id='switcher' type='checkbox' onclick=\"switchCheckboxes(this.form.id,'events_id[]','switcher')\" />");
									document.write("&nbsp;");
									document.write("<label for='switcher'>{tr}Select All{/tr}</label> ");
								//]]></script>
							{/literal}
							<br />
							<select name="submit_mult">
								<option value="" selected="selected">{tr}with checked{/tr}:&nbsp;</option>
									{if $gBitUser->hasPermission( 'p_remove_events' )}
										<option value="remove_events">{tr}remove{/tr}</option>
									{/if}
								<option value="open_events">{tr}display{/tr}</option>
							</select>
						</td>
					</tr>
				</tfoot>
				<tbody>
					{section name=changes loop=$list}
						<tr class="{cycle values="even,odd"}" title="{$list[changes].title|escape}">
							<td class="date">
								{* $list[changes].event_time|bit_short_date *}
								{* ^ ugly / v nice ---- it depends on how you set up your date format. we should really use bit_short_date that all date entries are uniform - xing *}
								{$list[changes].event_time|date_format:"%d.&nbsp;%b&nbsp;%Y"}
								<br />
								{* $list[changes].event_time|bit_short_time *}
								{$list[changes].event_time|date_format:"%H:%M"}
							</td>
							<td>
								<a href="{$smarty.const.EVENTS_PKG_URL}index.php?events_id={$list[changes].events_id|escape:"url"}" title="{$list[changes].title}">
									{$list[changes].title}
								</a>
							</td>
							<td>
								<span class="actionicon">
									{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='list' serviceHash=$listpages[changes]}
									{if $gBitUser->hasPermission( 'p_edit_events' )}
										{smartlink ititle="Edit" ifile="edit.php" ibiticon="icons/accessories-text-editor" events_id=$list[changes].events_id}
									{/if}
									<input type="checkbox" name="events_id[]" id="ev_{$list[changes].events_id}" value="{$list[changes].events_id|escape}" />
								</span>
								<label for="ev_{$list[changes].events_id}">	
									{$list[changes].description}
								</label>
							</td>
						</tr>
					{sectionelse}
						<tr class="norecords">
							<td colspan="3">
								{tr}No records found{/tr}
							</td>
						</tr>
					{/section}
				</tbody>
			</table>

			<div class="row submit">
				<input type="submit" value="{tr}Apply{/tr}" />
			</div>
		{/form}
	</div><!-- end .body -->

	{pagination}
	
</div><!-- end .events -->

{/strip}
