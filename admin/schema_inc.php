<?php

/* repeating events schema explained: Designed to capture RFC2455 repeating format excluding secondly repeats.
	If an event repeats then it gets the following:
	frequency: enum(norepeat=0, minutely=1, hourly, daily, weekly, monthly, yearly}
	r_count: If this is < 0 then the event repeats indefinately. If r_count is > 0 this overrides end_date and specifies the total number of repeats.
	end_date: The end date to end repeating on.
	interval: The interval at which the repeat is done. >=1
	bylists: RFC2455 formatted bylists which can be used to expand the event into the events_on table.

	The events_on table is designed to make calendar rendering go faster. There is a cron job that deletes events that are over a certain
	age to keep table size down and expands repeating events some set amount into the future. Then the calendar render can find all
	events which occurs in a certain range quickly.
*/

$tables = array(
	'events_types' => "
		type_id I4 PRIMARY,
		name C(30) NOTNULL,
		description C(160)
	",		
	'events' => "
		events_id I4 PRIMARY,
		end_time I4,
		content_id I4 NOTNULL,
		description C(160),
		cost C(160),
		type_id I4,
		location_id I4,
		frequency I4 NOTNULL,
		event_interval I4,
		r_count I4,
		end_date I4,
		bylists X
		CONSTRAINT '
            , CONSTRAINT `events_type_ref` FOREIGN KEY (`type_id`) REFERENCES `".BIT_DB_PREFIX."events_types`( `type_id` )
            , CONSTRAINT `events_location_ref` FOREIGN KEY (`location_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )
            , CONSTRAINT `events_content_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )
		'
	",
	'events_on' => "
		content_id I4 PRIMARY,
		event_on I4 NOTNULL
		CONSTRAINT '
            , CONSTRAINT `events_on_content_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id` )
		'
	",
	'events_invites' => "
		invites_id I4 PRIMARY,
		content_id I4 NOTNULL,
		event_content_id I4 NOTNULL,
		user_id I4 NOTNULL,
		interest I4 NOTNULL,
		guests I4
		CONSTRAINT '
			, CONSTRAINT `events_users_content_ref` FOREIGN KEY (`content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id`)
			, CONSTRAINT `events_users_event_content_ref` FOREIGN KEY (`event_content_id`) REFERENCES `".BIT_DB_PREFIX."liberty_content`( `content_id`)
		'
	",
);

global $gBitInstaller;

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( EVENTS_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( EVENTS_PKG_NAME, array(
	'description' => "Events package.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes

$indices = array(
	'events_events_location_idx' => array('table' => 'events', 'cols' => 'location_id', 'opts' => NULL ),
	'events_invites_event_idx' => array('table' => 'events_invites', 'cols' => 'event_content_id', 'opts' => NULL),
	'events_invites_user_idx' => array('table' => 'events_invites', 'cols' => 'user_id', 'opts' => NULL),
);
$gBitInstaller->registerSchemaIndexes( EVENTS_PKG_NAME, $indices );

// ### Sequences

$sequences = array (
	'events_events_id_seq' => array( 'start' => 1 ),
	'events_types_id_seq' => array( 'start' => 1 ),
	'events_invites_id_seq' => array( 'start' => 1),
);
$gBitInstaller->registerSchemaSequences( EVENTS_PKG_NAME, $sequences );

$gBitInstaller->registerSchemaDefault( EVENTS_PKG_NAME, array(
	//      "INSERT INTO `".BIT_DB_PREFIX."events_types` (`type`) VALUES ('Events')",
) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( EVENTS_PKG_NAME, array(
	array( 'p_admin_events', 'Can admin events', 'admin', EVENTS_PKG_NAME ),
	array( 'p_create_events', 'Can create a events', 'registered', EVENTS_PKG_NAME ),
	array( 'p_edit_events', 'Can edit any events', 'editors', EVENTS_PKG_NAME ),
	array( 'p_read_events', 'Can read events', 'basic',  EVENTS_PKG_NAME ),
	array( 'p_remove_events', 'Can delete events', 'admin',  EVENTS_PKG_NAME ),
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( EVENTS_PKG_NAME, array(
	array( EVENTS_PKG_NAME, 'events_default_ordering', 'events_id_desc' ),
	array( EVENTS_PKG_NAME, 'events_end_year', '+1'),
	//array( EVENTS_PKG_NAME, 'events_moderation', 'n'),
	//array( EVENTS_PKG_NAME, 'events_use_24', 'n' ),
) );
?>
