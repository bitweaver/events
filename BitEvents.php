<?php
/**
* $Header: /cvsroot/bitweaver/_bit_events/BitEvents.php,v 1.7 2006/02/10 13:22:11 lsces Exp $
* $Id: BitEvents.php,v 1.7 2006/02/10 13:22:11 lsces Exp $
*/

/**
* Events class to illustrate best practices when creating a new bitweaver package that
* builds on core bitweaver functionality, such as the Liberty CMS engine
*
* @date created 2004/8/15
* @author spider <spider@steelsun.com>
* @version $Revision: 1.7 $ $Date: 2006/02/10 13:22:11 $ $Author: lsces $
* @class BitEvents
*/

require_once( LIBERTY_PKG_PATH.'LibertyAttachable.php' );

/**
* This is used to uniquely identify the object
*/
define( 'BITEVENTS_CONTENT_TYPE_GUID', 'bitevents' );

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
	}

	/**
	* Load the data from the database
	* @param pParamHash be sure to pass by reference in case we need to make modifcations to the hash
	**/
	function load() {
		if( $this->verifyId( $this->mEventsId ) || $this->verifyId( $this->mContentId ) ) {
			// LibertyContent::load()assumes you have joined already, and will not execute any sql!
			// This is a significant performance optimization
			$lookupColumn = $this->verifyId( $this->mEventsId ) ? 'events_id' : 'content_id';
			$bindVars = array(); $selectSql = ''; $joinSql = ''; $whereSql = '';
			array_push( $bindVars, $lookupId = @BitBase::verifyId( $this->mEventsId )? $this->mEventsId : $this->mContentId );
			$this->getServicesSql( 'content_load_function', $selectSql, $joinSql, $whereSql, $bindVars );

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

				LibertyAttachable::load();
			}
		}
		return( count( $this->mInfo ) );
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
		if( $this->verifyId( $this->mEventsId ) && empty( $this->mInfo ) ) {
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

		$pParamHash['content_store']['event_time'] = !empty( $pParamHash['event_time'] ) ? $pParamHash['event_time'] : $gBitSystem->getUTCTime();
		
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

		$joinSql = '';
		$selectSql = '';
		$bindVars = array();
		array_push( $bindVars, $this->mContentTypeGuid );
		$this->getServicesSql( 'content_list_function', $selectSql, $joinSql, $whereSql, $bindVars );

		// this will set $find, $sort_mode, $max_records and $offset
		extract( $pParamHash );

		if( is_array( $find ) ) {
			// you can use an array of pages
			$whereSql .= " WHERE lc.`title` IN( ".implode( ',',array_fill( 0,count( $find ),'?' ) )." )";
			$bindVars[] = $find;
		} else if( is_string( $find ) ) {
			// or a string
			$whereSql .= " WHERE UPPER( lc.`title` )like ? ";
			$bindVars[] = '%' . strtoupper( $find ). '%';
		} else if( @$this->verifyId( $pUserId ) ) {
			// or a string
			$whereSql .= " WHERE lc.`creator_user_id` = ? ";
			$bindVars[] = array( $pUserId );
		}
		
		$query = "SELECT e.*, lc.`content_id`, lc.`title`, lc.`data`, lc.`modifier_user_id` AS `modifier_user_id`, lc.`user_id` AS`creator_user_id`,
			lc.`last_modified` AS `last_modified`, lc.`event_time` AS `event_time` $selectSql
			$selectSql
			FROM `".BIT_DB_PREFIX."events` e
			INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = e.`content_id` ) $joinSql
			WHERE lc.`content_type_guid` = ? $whereSql
			ORDER BY ".$this->mDb->convert_sortmode( $sort_mode );
		$query_cant = "SELECT COUNT( * )
				FROM `".BIT_DB_PREFIX."events` e
				INNER JOIN `".BIT_DB_PREFIX."liberty_content` lc ON( lc.`content_id` = e.`content_id` ) $joinSql
				WHERE lc.`content_type_guid` = ? $whereSql";
		$result = $this->mDb->query( $query, $bindVars, $max_records, $offset );
		$ret = array();
		while( $res = $result->fetchRow() ) {
			$ret[] = $res;
		}
		$pParamHash["data"] = $ret;

		$pParamHash["cant"] = $this->mDb->getOne( $query_cant, $bindVars );

		LibertyContent::postGetList( $pParamHash );
		return $pParamHash;
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

}
?>
