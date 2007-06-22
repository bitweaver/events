<?php
/**
 * @version $Header: /cvsroot/bitweaver/_bit_events/BitEvents.php,v 1.21 2007/06/22 11:13:14 lsces Exp $
 * Events class to illustrate best practices when creating a new bitweaver package that
 * builds on core bitweaver functionality, such as the Liberty CMS engine
 *
 * @author spider <spider@steelsun.com>
 * @package events
 */

/**
 * required setup
 */
require_once( LIBERTY_PKG_PATH.'LibertyAttachable.php' );
include_once( KERNEL_PKG_PATH.'BitDate.php' );

/**
 * This is used to uniquely identify the object
 */
define( 'BITEVENTS_CONTENT_TYPE_GUID', 'bitevents' );

/**
 * @package events
 */
class BitEvents extends LibertyAttachable {
	/**
	* Primary key for our mythical Events class object & table
	* @public
	*/
	var $mEventsId;

	/**
	* During initialisation, be sure to call our base constructors
	**/
	function BitEvents( $pEventsId=NULL, $pContentId=NULL ) {
		LibertyAttachable::LibertyAttachable();
		$this->mEventsId = $pEventsId;
		$this->mContentId = $pContentId;
		$this->mContentTypeGuid = BITEVENTS_CONTENT_TYPE_GUID;
		$this->registerContentType( BITEVENTS_CONTENT_TYPE_GUID, array(
			'content_type_guid' => BITEVENTS_CONTENT_TYPE_GUID,
			'content_description' => 'Events',
			'handler_class' => 'BitEvents',
			'handler_package' => 'events',
			'handler_file' => 'BitEvents.php',
			'maintainer_url' => 'http://wired.st-and.ac.uk/~hash9/'
		) );
		$offset = BitDate::get_display_offset();
		$this->mDate = new BitDate($offset);
	}

	/**
	* Load the data from the database
	* @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	**/
	function load() {
		global $gBitSystem;
		if( $this->verifyId( $this->mEventsId ) || $this->verifyId( $this->mContentId ) ) {
			// LibertyContent::load()assumes you have joined already, and will not execute any sql!
			// This is a significant performance optimization
			$lookupColumn = $this->verifyId( $this->mEventsId ) ? 'events_id' : 'content_id';
			$bindVars = array(); $selectSql = ''; $joinSql = ''; $whereSql = '';
			array_push( $bindVars, $lookupId = @BitBase::verifyId( $this->mEventsId )? $this->mEventsId : $this->mContentId );
			$this->getServicesSql( 'content_load_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

			$query = "SELECT e.*, lc.*, " .
				"uue.`login` AS modifier_user, uue.`real_name` AS modifier_real_name, " .
				"uuc.`login` AS creator_user, uuc.`real_name` AS creator_real_name " .
				"$selectSql " .
				"FROM `".BIT_DB_PREFIX."events` e " .
				"INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = e.`content_id` ) $joinSql" .
				"LEFT JOIN `".BIT_DB_PREFIX."users_users` uue ON( uue.`user_id` = lc.`modifier_user_id` )" .
				"LEFT JOIN `".BIT_DB_PREFIX."users_users` uuc ON( uuc.`user_id` = lc.`user_id` )" .
				"WHERE e.`$lookupColumn`=? $whereSql";
			$result = $this->mDb->query( $query, $bindVars );

			if( $result && $result->numRows() ) {
				$this->mInfo = $result->fields;
				$this->mContentId = $result->fields['content_id'];
				$this->mEventsId = $result->fields['events_id'];

				$this->mInfo['creator'] =( isset( $result->fields['creator_real_name'] )? $result->fields['creator_real_name'] : $result->fields['creator_user'] );
				$this->mInfo['editor'] =( isset( $result->fields['modifier_real_name'] )? $result->fields['modifier_real_name'] : $result->fields['modifier_user'] );
				$this->mInfo['display_url'] = $this->getDisplayUrl();
				$this->mInfo['parsed_data'] = $this->parseData( $this->mInfo['data'], $this->mInfo['format_guid'] );

				$prefChecks = array('show_start_time', 'show_end_time');
				foreach ($prefChecks as $key => $var) {
					if ($this->getPreference($var) == 'on') {
						$this->mInfo[$var] = 1;
					}
					else {
						$this->mInfo[$var] = 0;
					}
				}				

				LibertyAttachable::load();
			}
		}
		return( count( $this->mInfo ) );
	}

	function preview( &$pParamHash ) {
		global $gBitSmarty, $gBitSystem;
		$this->verify( $pParamHash );
		// This is stupid! verify does NOT work how it should.
		// verify should call the super class verify at all levels.
		LibertyAttachable::verify($pParamHash);
		LibertyContent::verify($pParamHash);

		$this->mInfo = array_merge($pParamHash['events_store'],$pParamHash['content_store'],$pParamHash['events_prefs_store']);
		$this->mInfo['data'] = $pParamHash['edit'];
		$this->mInfo['parsed'] = $this->parseData($pParamHash['edit'], empty($pParamHash['format_guid']) ? $pParamHash['format_guid'] : $gBitSystem->getConfig('default_format'));

		$this->invokeServices( 'content_preview_function' );    

		$gBitSmarty->assign('preview', true);

	}

	/**
	* Any method named Store inherently implies data will be written to the database
	* @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	* This is the ONLY method that should be called in order to store( create or update )an events!
	* It is very smart and will figure out what to do for you. It should be considered a black box.
	*
	* @param array pParams hash of values that will be used to store the page
	*
	* @return bool TRUE on success, FALSE if store could not occur. If FALSE, $this->mErrors will have reason why
	*
	* @access public
	**/
	function store( &$pParamHash ) {
		if( $this->verify( $pParamHash )&& LibertyAttachable::store( $pParamHash ) ) {
			$table = BIT_DB_PREFIX."events";

			$prefChecks = array('show_start_time', 'show_end_time');
			foreach ($prefChecks as $var) {
				if (isset($pParamHash['events_prefs_store'][$var])) {
					$this->storePreference($var, $pParamHash['events_prefs_store'][$var]);
				}
				else {
					$this->storePreference($var);
				}
			}

			$this->mDb->StartTrans();

			if( $this->mEventsId ) {
				$result = $this->mDb->associateUpdate( $table, $pParamHash['events_store'], array( 'events_id' => $pParamHash['events_id'] ) );
			} else {
				$pParamHash['events_store']['content_id'] = $pParamHash['content_id'];
				if( @$this->verifyId( $pParamHash['events_id'] ) ) {
					// if pParamHash['events_id'] is set, some is requesting a particular events_id. Use with caution!
					$pParamHash['events_store']['events_id'] = $pParamHash['events_id'];
				} else {
					$pParamHash['events_store']['events_id'] = $this->mDb->GenID( 'events_events_id_seq' );
				}
				$this->mEventsId = $pParamHash['events_store']['events_id'];

				$result = $this->mDb->associateInsert( $table, $pParamHash['events_store'] );
			}


			$this->mDb->CompleteTrans();
			$this->load();
		}
		return( count( $this->mErrors )== 0 );
	}

	/**
	* Make sure the data is safe to store
	* @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	* This function is responsible for data integrity and validation before any operations are performed with the $pParamHash
	* NOTE: This is a PRIVATE METHOD!!!! do not call outside this class, under penalty of death!
	*
	* @param array pParams reference to hash of values that will be used to store the page, they will be modified where necessary
	*
	* @return bool TRUE on success, FALSE if verify failed. If FALSE, $this->mErrors will have reason why
	*
	* @access private
	**/
	function verify( &$pParamHash ) {
		global $gBitUser, $gBitSystem;
		// make sure we're all loaded up of we have a mEventsId
		if( $this->verifyId( $this->mEventsId )/* && empty( $this->mInfo )*/ ) {
			$this->load();
		}

		if( @$this->verifyId( $this->mInfo['content_id'] ) ) {
			$pParamHash['content_id'] = $this->mInfo['content_id'];
		}

		// It is possible a derived class set this to something different
		if( @$this->verifyId( $pParamHash['content_type_guid'] ) ) {
			$pParamHash['content_type_guid'] = $this->mContentTypeGuid;
		}

		if( @$this->verifyId( $pParamHash['content_id'] ) ) {
			$pParamHash['events_store']['content_id'] = $pParamHash['content_id'];
		}

		if( !empty( $pParamHash['cost'] ) ) {
		    $pParamHash['events_store']['cost'] = substr( trim($pParamHash['cost']), 0, 160 );
		}

		$prefChecks = array('show_start_time', 'show_end_time');
		foreach ($prefChecks as $var) {
			if (isset($pParamHash[$var])) {
				$pParamHash['events_prefs_store'][$var] = $pParamHash[$var];
			}
		}

		if ( !empty($pParamHash['frequency'] ) ) {
			$pParamHash['events_store']['frequency'] = $pParamHash['frequency'];
		}
		else {
			$pParamHash['events_store']['frequency'] = 0;
		}

		if( !empty( $pParamHash['start_date']) && !empty($pParamHash['start_time']) ) {
			if (isset($pParamHash['start_time']['Meridian'])) {
				$pParamHash['event_time'] = 
					$this->mDate->gmmktime(($pParamHash['start_time']['Meridian'] == 'pm' ?
							      $pParamHash['start_time']['Hour'] + 12 : 
							      $pParamHash['start_time']['Hour']),
							     $pParamHash['start_time']['Minute'],
							     isset($pParamHash['start_time']['Second']) ? 
							     $pParamHash['start_time']['Second'] : 0, 
							     $pParamHash['start_date']['Month'], 
							     $pParamHash['start_date']['Day'],
							     $pParamHash['start_date']['Year']
							     );
			}
			else {
				$pParamHash['event_time'] =
					$this->mDate->gmmktime($pParamHash['start_time']['Hour'],
							     $pParamHash['start_time']['Minute'],
							     isset($pParamHash['start_time']['Second']) ? 
							     $pParamHash['start_time']['Second'] : 0, 
							     $pParamHash['start_date']['Month'], 
							     $pParamHash['start_date']['Day'],
							     $pParamHash['start_date']['Year']
							     );
			}
		}
		
		if( !empty($pParamHash['end_time']) && !empty($pParamHash['event_time']) ) {
			if (empty($pParamHash['start_date'])) {
				$pParamHash['start_date']['Month'] = $this->mDate->strftime("%m", $pParamHash['event_time'], true);
				$pParamHash['start_date']['Day'] = $this->mDate->strftime("%d", $pParamHash['event_time'], true);
				$pParamHash['start_date']['Year'] = $this->mDate->strftime("%Y", $pParamHash['event_time'], true);
			}
			if ((!isset($pParamHash['end_time']['Meridian']) || 
			     ($pParamHash['end_time']['Meridian'] == 'am' ||
			      $pParamHash['end_time']['Meridian'] == 'pm')) &&
			    (isset($pParamHash['end_time']['Hour']) &&
			     is_numeric($pParamHash['end_time']['Hour'])) && 				   
			    (!isset($pParamHash['end_time']['Minute']) || 
			     is_numeric($pParamHash['end_time']['Minute']) && 
			     (!isset($pParamHash['end_time']['Second']) || 
			      is_numeric($pParamHash['end_time']['Second'])))) {
				
				if (isset($pParamHash['end_time']['Meridian'])) {
					$pParamHash['events_store']['end_time'] = 
					  $this->mDate->gmmktime(($pParamHash['end_time']['Meridian'] == 'pm' ?
								      $pParamHash['end_time']['Hour'] + 12 : 
								      $pParamHash['end_time']['Hour']),
								     $pParamHash['end_time']['Minute'],
								     isset($pParamHash['end_time']['Second']) ? 
								     $pParamHash['end_time']['Second'] : 0,
								     $pParamHash['start_date']['Month'], 
								     $pParamHash['start_date']['Day'],
								     $pParamHash['start_date']['Year']
								     );
				}
				else {
					$pParamHash['events_store']['end_time'] =
					  $this->mDate->gmmktime($pParamHash['end_time']['Hour'],
								     $pParamHash['end_time']['Minute'],
								     isset($pParamHash['end_time']['Second']) ? 
								     $pParamHash['end_time']['Second'] : 0,
								     $pParamHash['start_date']['Month'], 
								     $pParamHash['start_date']['Day'],
								     $pParamHash['start_date']['Year']
								     );
				}					       
				$pParamHash['events_store']['end_time'] = $this->mDate->getUTCFromDisplayDate($pParamHash['events_store']['end_time']);
			}
		}

		if( !empty( $pParamHash['event_time'] ) ) {
			$pParamHash['event_time'] = $this->mDate->getUTCFromDisplayDate( $pParamHash['event_time']);
		} else if ( !empty( $this->mInfo['event_time'] ) ) {
			$pParamHash['event_time'] = $this->mDate->getUTCFromDisplayDate( $this->mInfo['event_time']);
		} else {
			$pParamHash['event_time'] = $gBitSystem->getUTCTime();
		}

		// check some lengths, if too long, then truncate
		if( $this->isValid() && !empty( $this->mInfo['description'] ) && empty( $pParamHash['description'] ) ) {
			// someone has deleted the description, we need to null it out
			$pParamHash['events_store']['description'] = '';
		} else if( empty( $pParamHash['description'] ) ) {
			unset( $pParamHash['description'] );
		} else {
			$pParamHash['events_store']['description'] = substr( $pParamHash['description'], 0, 200 );
		}

		if( !empty( $pParamHash['data'] ) ) {
			$pParamHash['edit'] = $pParamHash['data'];
		}

		// check for name issues, first truncate length if too long
		if( !empty( $pParamHash['title'] ) ) {
			if( empty( $this->mEventsId ) ) {
				if( empty( $pParamHash['title'] ) ) {
					$this->mErrors['title'] = 'You must enter a name for this page.';
				} else {
					$pParamHash['content_store']['title'] = substr( $pParamHash['title'], 0, 160 );
				}
			} else {
				$pParamHash['content_store']['title'] =( isset( $pParamHash['title'] ) )? substr( $pParamHash['title'], 0, 160 ): '';
			}
		} else if( empty( $pParamHash['title'] ) ) {
			// no name specified
			$this->mErrors['title'] = 'You must specify a name';
		}

		return( count( $this->mErrors )== 0 );
	}

	/**
	* This function removes a events entry
	**/
	function expunge() {
		$ret = FALSE;
		if( $this->isValid() ) {
			$this->mDb->StartTrans();
			$query = "DELETE FROM `".BIT_DB_PREFIX."events` WHERE `content_id` = ?";
			$result = $this->mDb->query( $query, array( $this->mContentId ) );
			if( LibertyAttachable::expunge() ) {
				$ret = TRUE;
				$this->mDb->CompleteTrans();
			} else {
				$this->mDb->RollbackTrans();
			}
		}
		return $ret;
	}

	/**
	* Make sure events is loaded and valid
	**/
	function isValid() {
		return( $this->verifyId( $this->mEventsId ) );
	}

	/**
	* This function generates a list of records from the liberty_content database for use in a list page
	**/
	function getList( &$pParamHash ) {
		global $gBitSystem, $gBitUser;
		
		if ( empty( $pParamHash['sort_mode'] ) ) {
			if ( empty( $_REQUEST["sort_mode"] ) ) {
				$pParamHash['sort_mode'] = 'event_time_desc';
			} else {
			$pParamHash['sort_mode'] = $_REQUEST['sort_mode'];
			}
		}

		LibertyContent::prepGetList( $pParamHash );

		$selectSql = '';
		$joinSql = '';
		$whereSql = '';
		$bindVars = array();
		array_push( $bindVars, $this->mContentTypeGuid );
		$this->getServicesSql( 'content_list_sql_function', $selectSql, $joinSql, $whereSql, $bindVars );

		// this will set $find, $sort_mode, $max_records and $offset
		extract( $pParamHash );

		if( is_array( $find ) ) {
			// you can use an array of pages
			$whereSql .= " AND lc.`title` IN( ".implode( ',',array_fill( 0,count( $find ),'?' ) )." )";
			$bindVars = array_merge( $bindVars, $find );
		} else if( is_string( $find ) ) {
			// or a string
			$whereSql .= " AND UPPER( lc.`title` )like ? ";
			$bindVars[] = '%' . strtoupper( $find ). '%';
		} else if( @$this->verifyId( $pUserId ) ) {
			// or a string
			$whereSql .= " AND lc.`creator_user_id` = ? ";
			$bindVars[] = array( $pUserId );
		}
		
		if (!empty($event_before)) {
			$whereSql .= "AND lc.`event_time` <= ?";
			$bindVars[] = $event_before;
		}


		if (!empty($event_after)) {
			$whereSql .= "AND lc.`event_time` > ?";
			$bindVars[] = $event_after;
		}


		$query = "SELECT e.*, lc.`content_id`, lc.`title`, lc.`data`, lc.`modifier_user_id` AS `modifier_user_id`, lc.`user_id` AS`creator_user_id`,
			lc.`last_modified` AS `last_modified`, lc.`event_time` AS `event_time`, lc.`format_guid`, lcps.`pref_value` AS `show_start_time`, lcpe.`pref_value` AS `show_end_time`  $selectSql
			$selectSql
			FROM `".BIT_DB_PREFIX."events` e
			INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = e.`content_id` ) 
			LEFT JOIN `".BIT_DB_PREFIX."liberty_content_prefs` lcps ON (lc.`content_id` = lcps.`content_id` AND lcps.`pref_name` = 'show_start_time')
			LEFT JOIN `".BIT_DB_PREFIX."liberty_content_prefs` lcpe ON (lc.`content_id` = lcpe.`content_id` AND lcpe.`pref_name` = 'show_end_time')
			$joinSql
			WHERE lc.`content_type_guid` = ? $whereSql
			ORDER BY ".$this->mDb->convertSortmode( $sort_mode );
		$query_cant = "SELECT COUNT( * )
				FROM `".BIT_DB_PREFIX."events` e
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = e.`content_id` ) $joinSql
				WHERE lc.`content_type_guid` = ? $whereSql";
		$result = $this->mDb->query( $query, $bindVars, $max_records, $offset );
		$ret = array();
		while( $res = $result->fetchRow() ) {
			if (!empty($parse_split)) {
				$res = array_merge($this->parseSplit($res), $res);
			}
			$ret[] = $res;
		}
		$pParamHash["data"] = $ret;

		$pParamHash["cant"] = $this->mDb->getOne( $query_cant, $bindVars );

		LibertyContent::postGetList( $pParamHash );
		return $ret;
	}

	/**
	* Generates the URL to the events page
	* @param pExistsHash the hash that was returned by LibertyContent::pageExists
	* @return the link to display the page.
	*/
	function getDisplayUrl() {
		$ret = NULL;
		if( @$this->verifyId( $this->mEventsId ) ) {
			$ret = EVENTS_PKG_URL."index.php?events_id=".$this->mEventsId;
		}
		return $ret;
	}

	/* Limits content status types for users who can not enter all status */
	function getContentStatus() {
	  global $gBitSystem;
	  if ($gBitSystem->isFeatureActive('events_moderation')) {
		return LibertyContent::getContentStatus(-100,0);
	  }
	  return parent::getContentStatus();
	}

	function getRenderFile() {
		return EVENTS_PKG_PATH."display_event_inc.php";
	}
}
?>
