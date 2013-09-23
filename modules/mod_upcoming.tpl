{* $Header$ *}
{strip}
{if $eventsPackageActive}
	{if count($modUpcomingEvents) > 0}
		{bitmodule title="$moduleTitle" name="upcoming_events"}
			<ul>
				{section name=ix loop=$modUpcomingEvents}
					<li class="{cycle values="even,odd"} eventrow">
						<a href="{$modUpcomingEvents[ix].display_url}">
							<div class="event icon"><p class="event image">
								{if !empty($modUpcomingEvents[ix].primary_attachment)}
									<img src="{$modUpcomingEvents[ix].primary_attachment.thumbnail_url.avatar}" alt="{$modUpcomingEvents[ix].title}">
								{/if}
							</p></div>
							<h4>{$modUpcomingEvents[ix].title|default:"Events"}</h4>
						</a>
						<div class="event info">
							{include file="bitpackage:events/render_header_inc.tpl" contentHash=$modUpcomingEvents[ix]}
							{if !empty($modUpcomingEvents[ix].parsed_description)}
								{$modUpcomingEvents[ix].parsed_description|truncate:$maxPreviewLength}
								{if $modUpcomingEvents[ix].has_more}
									<a class="more" href="$modUpcomingEvents[ix].display_url}">{tr}Read More&hellip;{/tr}</a>
								{/if}
							{/if}
						</div>
					</li>
				{/section}
			</ul>
		{/bitmodule}
	{/if}
{/if}
{/strip}
