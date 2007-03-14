<div class="display events">
	<div class="floaticon">
		{if $gBitUser->hasPermission( 'bit_p_edit_events' )}
			<a href="edit.php?events_id={$gContent->mInfo.events_id}">{biticon ipackage="icons" iname="accessories-text-editor" iexplain="edit"}</a>
		{/if}
	</div><!-- end .floaticon -->

	<div class="header">
		<h1>{$gContent->mInfo.title|default:"Events"}</h1>
		<h2>{$gContent->mInfo.description} ({$gContent->mInfo.event_time|bit_short_datetime})</h2>
		<div class="date">
			{tr}Created by {displayname user=$gContent->mInfo.creator_user user_id=$gContent->mInfo.creator_user_id real_name=$gContent->mInfo.creator_real_name}, Last modification by {displayname user=$gContent->mInfo.modifier_user user_id=$gContent->mInfo.modifier_user_id real_name=$gContent->mInfo.modifier_real_name} on {$gContent->mInfo.last_modified|bit_long_datetime}{/tr}
		</div>
	</div><!-- end .header -->

	<div class="body">
		<div class="content">
			{$gContent->mInfo.parsed_data}
		</div><!-- end .content -->
	</div><!-- end .body -->
</div><!-- end .events -->
