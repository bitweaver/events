<?php

$tables = array(
	'events' => "
		events_id I4 AUTO PRIMARY,
		content_id I4 NOTNULL,
		description C(160)
	",
);

global $gBitInstaller;

$gBitInstaller->makePackageHomeable( EVENTS_PKG_NAME );

foreach( array_keys( $tables ) AS $tableName ) {
	$gBitInstaller->registerSchemaTable( EVENTS_PKG_NAME, $tableName, $tables[$tableName] );
}

$gBitInstaller->registerPackageInfo( EVENTS_PKG_NAME, array(
	'description' => "Events package.",
	'license' => '<a href="http://www.gnu.org/licenses/licenses.html#LGPL">LGPL</a>',
) );

// ### Indexes
$indices = array(
	'events_events_id_idx' => array('table' => 'events', 'cols' => 'events_id', 'opts' => NULL ),
);
$gBitInstaller->registerSchemaIndexes( EVENTS_PKG_NAME, $indices );

// ### Sequences
$sequences = array (
	'events_events_id_seq' => array( 'start' => 1 )
);
$gBitInstaller->registerSchemaSequences( EVENTS_PKG_NAME, $sequences );



$gBitInstaller->registerSchemaDefault( EVENTS_PKG_NAME, array(
	//      "INSERT INTO `".BIT_DB_PREFIX."events_types` (`type`) VALUES ('Events')",
) );

// ### Default UserPermissions
$gBitInstaller->registerUserPermissions( EVENTS_PKG_NAME, array(
	array( 'bit_p_admin_events', 'Can admin events', 'admin', EVENTS_PKG_NAME ),
	array( 'bit_p_create_events', 'Can create a events', 'registered', EVENTS_PKG_NAME ),
	array( 'bit_p_edit_events', 'Can edit any events', 'editors', EVENTS_PKG_NAME ),
	array( 'bit_p_read_events', 'Can read events', 'basic',  EVENTS_PKG_NAME ),
	array( 'bit_p_remove_events', 'Can delete events', 'admin',  EVENTS_PKG_NAME ),
) );

// ### Default Preferences
$gBitInstaller->registerPreferences( EVENTS_PKG_NAME, array(
	array( EVENTS_PKG_NAME, 'events_default_ordering', 'events_id_desc' ),
	array( EVENTS_PKG_NAME, 'events_list_events_id', 'y' ),
	array( EVENTS_PKG_NAME, 'events_list_title', 'y' ),
	array( EVENTS_PKG_NAME, 'events_list_description', 'y' ),
	array( EVENTS_PKG_NAME, 'feature_list_events', 'y' ),
) );
?>
