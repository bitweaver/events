<div class="display events">
{if $gContent->isValid()}
	<div class="floaticon">
		{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='icon' serviceHash=$gContent->mInfo}
		{if $gBitUser->hasPermission( 'bit_p_edit_events' )}
			<a title="{tr}Edit{/tr}" href="edit.php?events_id={$gContent->mInfo.events_id}">{biticon ipackage="icons" iname="accessories-text-editor" iexplain="edit"}</a>
		{/if}
	</div><!-- end .floaticon -->

	<div class="header">
		<h1>{$gContent->mInfo.title|default:"Events"}</h1>
		{if !empty($gContent->mInfo.description)}
			<h2>{$gContent->mInfo.description}</h2>
		{/if}
		{$gContent->mInfo.event_time|bit_long_date}
		{if $gContent->getField('show_start_time')}
			{$gContent->mInfo.event_time|bit_short_time}
		{/if}
		{if $gContent->getField('show_end_time')}
			{if $gContent->getField('show_start_time')}
				<div class=row>{tr}until{/tr}</div>
			{/if}
			{$gContent->mInfo.end_time|bit_long_date}
			{$gContent->mInfo.end_time|bit_short_time}
		{/if}
		<div class="date">
			{tr}Created by {displayname user=$gContent->mInfo.creator_user user_id=$gContent->mInfo.creator_user_id real_name=$gContent->mInfo.creator_real_name}, Last modification by {displayname user=$gContent->mInfo.modifier_user user_id=$gContent->mInfo.modifier_user_id real_name=$gContent->mInfo.modifier_real_name} on {$gContent->mInfo.last_modified|bit_long_datetime}{/tr}
		</div>
	</div><!-- end .header -->

	<div class="body">
		{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='body' serviceHash=$gContent->mInfo}
		<div class="content">
			{include file="bitpackage:events/render_template.tpl" content=$gContent->mInfo}
		</div><!-- end .content -->
	</div><!-- end .body -->
{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='view' serviceHash=$gContent->mInfo}
{else}
	<div class=error>No such event.</div>
{/if}
</div><!-- end .events -->
