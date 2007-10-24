{* $Header: /cvsroot/bitweaver/_bit_events/modules/mod_upcoming.tpl,v 1.2 2007/10/24 21:23:55 nickpalmer Exp $ *}
{strip}
{if $eventsPackageActive}
	{if count($modUpcomingEvents) > 0}
		{bitmodule title="$moduleTitle" name="upcoming_events"}
			<ul>
				{section name=ix loop=$modUpcomingEvents}
					<li class="{cycle values="even,odd"}">
						<h1><a href="{$modUpcomingEvents[ix].display_url}">{$modUpcomingEvents[ix].title|default:"Events"}</a></h1>
						{include file="bitpackage:events/render_header_inc.tpl" contentHash=$modUpcomingEvents[ix]}
						{if !empty($modUpcomingEvents[ix].parsed_description)}
							<br/>
							{$modUpcomingEvents[ix].parsed_description}
							<br/>
							{if $modUpcomingEvents[ix].has_more}
								<a class="more" href="{$modUpcomingEvents[ix].display_url}">{tr}Read More&hellip;{/tr}</a>
							{/if}
						{/if}
					</li>
				{/section}
			</ul>
		{/bitmodule}
	{/if}
{/if}
{/strip}
