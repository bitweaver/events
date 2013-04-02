<div class="display events">
{if $liberty_preview || $preview || $gContent->isValid()}
	<div class="floaticon">
		{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='icon' serviceHash=$gContent->mInfo}
		{if $gContent->hasUpdatePermission()}
			<a title="{tr}Edit{/tr}" href="edit.php?events_id={$gContent->mInfo.events_id}">{booticon iname="icon-edit" ipackage="icons" iexplain="edit"}</a>
		{/if}
		{if $gContent->hasUserPermission( 'p_events_remove' )}
			<a title="{tr}Delete{/tr}" href="edit.php?remove=1&events_id={$gContent->mInfo.events_id}">{booticon iname="icon-trash" ipackage="icons" iexplain="delete"}</a>
		{/if}
	</div><!-- end .floaticon -->

	<div class="header">
		<h1>{$gContent->mInfo.title|default:"Events"}</h1>
		{include file="bitpackage:events/render_header_inc.tpl" contentHash=$gContent->mInfo}
		{if !$preview && !$liberty_preview}
		<div class="date">
			{tr}Created by {displayname user=$gContent->mInfo.creator_user user_id=$gContent->mInfo.creator_user_id real_name=$gContent->mInfo.creator_real_name}, Last modification by {displayname user=$gContent->mInfo.modifier_user user_id=$gContent->mInfo.modifier_user_id real_name=$gContent->mInfo.modifier_real_name} on {$gContent->mInfo.last_modified|bit_long_datetime}{/tr}
		</div>
		{/if}
	</div><!-- end .header -->

	<div class="body">
		{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='body' serviceHash=$gContent->mInfo}
		{include file="bitpackage:events/image_display.tpl"}
		<div class="content">
			{$gContent->mInfo.parsed_data}
		</div><!-- end .content -->
	</div><!-- end .body -->
{if !($liberty_preview or $preview)}
{include file="bitpackage:liberty/services_inc.tpl" serviceLocation='view' serviceHash=$gContent->mInfo}
{/if}
{else}
	<div class=error>No such event.</div>
{/if}
</div><!-- end .events -->
