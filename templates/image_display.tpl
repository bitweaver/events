{strip}
{if $gBitSystem->isFeatureActive('events_show_primary')}
	{foreach from=$gContent->mStorage item=image}
		{if $image.is_primary and $image.thumbnail_url.medium}
			<div class="image">
				<img src="{$image.thumbnail_url.medium}" alt="{$gContent->mInfo.title}">
			</div>
		{/if}
	{/foreach}
{/if}
{/strip}
