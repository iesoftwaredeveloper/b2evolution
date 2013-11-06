<?php
/**
 * This file implements functions to work with email tools.
 *
 * This file is part of the b2evolution/evocms project - {@link http://b2evolution.net/}.
 * See also {@link http://sourceforge.net/projects/evocms/}.
 *
 * @copyright (c)2003-2013 by Francois Planque - {@link http://fplanque.com/}.
 *
 * @license http://b2evolution.net/about/license.html GNU General Public License (GPL)
 *
 * @package evocore
 *
 * {@internal Below is a list of authors who have contributed to design/coding of this file: }}
 * @author fplanque: Francois PLANQUE.
 *
 * @version $Id$
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );


/**
 * Get array of status titles for email address
 *
 * @return array Status titles
 */
function emblk_get_status_titles()
{
	return array(
			'unknown'     => TS_('Unknown'),
			'warning'     => TS_('Warning'),
			'suspicious1' => TS_('Suspicious 1'),
			'suspicious2' => TS_('Suspicious 2'),
			'suspicious3' => TS_('Suspicious 3'),
			'prmerror'    => TS_('Permanent error'),
			'spammer'     => TS_('Spammer'),
		);
}


/**
 * Get array of status colors for email address
 *
 * @return array Status colors
 */
function emblk_get_status_colors()
{
	return array(
			''            => '808080',
			'unknown'     => '808080',
			'warning'     => 'FFFF00',
			'suspicious1' => 'FFC800',
			'suspicious2' => 'FFA500',
			'suspicious3' => 'FF8C00',
			'prmerror'    => 'FF0000',
			'spammer'     => '990000',
		);
}


/**
 * Get array of status icons for email address
 *
 * @return array Status icons
 */
function emblk_get_status_icons()
{
	return array(
			'unknown'     => get_icon( 'bullet_white', 'imgtag', array( 'title' => emblk_get_status_title( 'unknown' ) ) ),
			'warning'     => get_icon( 'bullet_yellow', 'imgtag', array( 'title' => emblk_get_status_title( 'warning' ) ) ),
			'suspicious1' => get_icon( 'bullet_orange', 'imgtag', array( 'title' => emblk_get_status_title( 'suspicious1' ) ) ),
			'suspicious2' => get_icon( 'bullet_orange', 'imgtag', array( 'title' => emblk_get_status_title( 'suspicious2' ) ) ),
			'suspicious3' => get_icon( 'bullet_orange', 'imgtag', array( 'title' => emblk_get_status_title( 'suspicious3' ) ) ),
			'prmerror'    => get_icon( 'bullet_red', 'imgtag', array( 'title' => emblk_get_status_title( 'prmerror' ) ) ),
			'spammer'     => get_icon( 'bullet_brown', 'imgtag', array( 'title' => emblk_get_status_title( 'spammer' ) ) ),
		);
}


/**
 * Get status levels of email address
 *
 * @return array Status levels
 */
function emblk_get_status_levels()
{
	$levels = array(
			'unknown'     => 1,
			'warning'     => 2,
			'suspicious1' => 3,
			'suspicious2' => 4,
			'suspicious3' => 5,
			'prmerror'    => 6,
			'spammer'     => 7,
		);

	return $levels;
}


/**
 * Get status level of email address by status value
 *
 * @param string Status value
 * @return integer Status level
 */
function emblk_get_status_level( $status )
{
	$levels = emblk_get_status_levels();

	return isset( $levels[ $status ] ) ? $levels[ $status ] : 0;
}


/**
 * Get statuses of email address by status value which have a level less or equal then level of the given status
 *
 * @param string Status value
 * @return array Statuses
 */
function emblk_get_statuses_less_level( $status )
{
	$levels = emblk_get_status_levels();
	$current_level = emblk_get_status_level( $status );

	$statuses = array();
	foreach( $levels as $status => $level )
	{
		if( $level <= $current_level )
		{	// Add this status into array if the level is less or equal then current level
			$statuses[] = $status;
		}
	}

	return $statuses;
}


/**
 * Get status title of email address by status value
 *
 * @param string Status value
 * @return string Status title
 */
function emblk_get_status_title( $status )
{
	$emblk_statuses = emblk_get_status_titles();

	return isset( $emblk_statuses[ $status ] ) ? $emblk_statuses[ $status ] : $status;
}


/**
 * Get status color of email address by status value
 *
 * @param string Status value
 * @return string Color value
 */
function emblk_get_status_color( $status )
{
	if( $status == 'NULL' )
	{
		$status = '';
	}

	$emblk_statuses = emblk_get_status_colors();

	return isset( $emblk_statuses[ $status ] ) ? '#'.$emblk_statuses[ $status ] : 'none';
}


/**
 * Get status icon of email address by status value
 *
 * @param string Status value
 * @return string Icon
 */
function emblk_get_status_icon( $status )
{
	$emblk_statuses = emblk_get_status_icons();

	return isset( $emblk_statuses[ $status ] ) ? $emblk_statuses[ $status ] : '';
}


/**
 * Get result info of email log
 *
 * @param string Result ( 'ok', 'error', 'blocked' )
 * @param boolean Params
 * @return string Result info
 */
function emlog_result_info( $result, $params = array() )
{
	$params = array_merge( array(
			'display_icon'  => true,	// Display icon
			'display_text'  => true,	// Display text
			'link_blocked'  => false, // TRUE - to display 'Blocked' as link to go to page with Blocked email adresses with filter by email
			'email'         => '',    // Email address to filter
		), $params );

	$result_info = '';

	switch( $result )
	{
		case 'ok':
			if( $params['display_icon'] )
			{
				$result_info .= get_icon( 'bullet_green', 'imgtag', array( 'alt' => T_('Ok') ) );
			}
			if( $params['display_text'] )
			{
				$result_info .= ' '.T_('Ok');
			}
			break;

		case 'error':
			if( $params['display_icon'] )
			{
				$result_info .= get_icon( 'bullet_red', 'imgtag', array( 'alt' => T_('Error') ) );
			}
			if( $params['display_text'] )
			{
				$result_info .= ' '.T_('Error');
			}
			break;

		case 'blocked':
			if( $params['display_icon'] )
			{
				$result_info .= get_icon( 'bullet_black', 'imgtag', array( 'alt' => T_('Blocked') ) );
			}
			if( $params['display_text'] )
			{
				if( $params['link_blocked'] && !empty( $params['email'] ) )
				{	// Create a link for email address
					global $admin_url;
					$result_info .= ' <a href="'.$admin_url.'?ctrl=email&amp;tab=blocked&amp;email='.$params['email'].'">'.T_('Blocked').'</a>';
				}
				else
				{	// Display only text
					$result_info .= ' '.T_('Blocked');
				}
			}
			break;
	}

	return $result_info;
}


/**
 * Add a mail log
 *
 * @param integer User ID
 * @param string To (email address)
 * @param string Subject
 * @param string Message
 * @param string Headers
 * @param string Result type ( 'ok', 'error', 'blocked' )
 */
function mail_log( $user_ID, $to, $subject, $message, $headers, $result )
{
	global $DB, $servertimenow;

	if( empty( $user_ID ) )
	{
		$user_ID = NULL;
	}

	$DB->query( 'INSERT DELAYED INTO T_email__log
		( emlog_user_ID, emlog_to, emlog_result, emlog_subject, emlog_message, emlog_headers )
		VALUES
		( '.$DB->quote( $user_ID ).',
		  '.$DB->quote( $to ).',
		  '.$DB->quote( $result ).',
		  '.$DB->quote( $subject ).',
		  '.$DB->quote( $message ).',
		  '.$DB->quote( $headers ).' )' );

	if( $result == 'ok' )
	{	// Save a report about sending of this message in the table T_email__blocked
		// The mail sending is susccess. Update last sent date and increase a counter
		$DB->query( 'INSERT INTO T_email__blocked ( emblk_address, emblk_sent_count, emblk_sent_last_returnerror, emblk_last_sent_ts )
			VALUES( '.$DB->quote( $to ).', 1, 1, '.$DB->quote( date( 'Y-m-d H:i:s', $servertimenow ) ).' )
			ON DUPLICATE KEY UPDATE
			    emblk_sent_count = emblk_sent_count + 1,
			    emblk_sent_last_returnerror = emblk_sent_last_returnerror + 1,
			    emblk_last_sent_ts = '.$DB->quote( date( 'Y-m-d H:i:s', $servertimenow ) ) );
	}
}


/**
 * Load the blocked emails from DB in cache
 *
 * @param array User IDs
 * @param array Blocked statuses to know what emails are blocked to send
 *     'unknown'     - Unknown
 *     'warning'     - Warning
 *     'suspicious1' - Suspicious 1
 *     'suspicious2' - Suspicious 2
 *     'suspicious3' - Suspicious 3
 *     'prmerror'    - Permament error
 *     'spammer'     - Spammer
 */
function load_blocked_emails( $user_IDs, $blocked_statuses = array() )
{
	global $DB, $cache_mail_is_blocked_status;

	if( $user_IDs )
	{ // No users, Exit here
		return;
	}

	if( !isset( $cache_mail_is_blocked_status ) )
	{ // Init array first time
		$cache_mail_is_blocked_status = array();
	}

	$status_filter_name = implode( '_', $blocked_statuses );
	if( !isset( $cache_mail_is_blocked_status[ $status_filter_name ] ) )
	{ // Init subarray for each filter by statuses
		$cache_mail_is_blocked_status[ $status_filter_name ] = array();
	}

	$SQL = new SQL();
	$SQL->SELECT( 'user_email, emblk_ID' );
	$SQL->FROM( 'T_users' );
	$SQL->FROM_add( 'LEFT JOIN T_email__blocked
		 ON user_email = emblk_address
		AND '.get_mail_blocked_condition( true, $blocked_statuses ) );
	$SQL->WHERE( 'user_ID IN ( '.$DB->quote( $user_IDs ).' )' );
	$blocked_emails = $DB->get_assoc( $SQL->get() );

	foreach( $blocked_emails as $email => $email_blocked_ID )
	{ // The blocked email has TRUE value; Trust emails - FALSE
		$cache_mail_is_blocked_status[ $status_filter_name ][ $email ] = (boolean) $email_blocked_ID;
	}
}


/**
 * Check if the email address is blocked
 *
 * @param string Email address
 * @param array Blocked statuses to know what emails are blocked to send
 *     'unknown'     - Unknown
 *     'warning'     - Warning
 *     'suspicious1' - Suspicious 1
 *     'suspicious2' - Suspicious 2
 *     'suspicious3' - Suspicious 3
 *     'prmerror'    - Permament error
 *     'spammer'     - Spammer
 * @return boolean TRUE
 */
function mail_is_blocked( $email, $blocked_statuses = array() )
{
	global $cache_mail_is_blocked_status;

	if( !isset( $cache_mail_is_blocked_status ) )
	{ // Init array first time
		$cache_mail_is_blocked_status = array();
	}

	$status_filter_name = implode( '_', $blocked_statuses );
	if( !isset( $cache_mail_is_blocked_status[ $status_filter_name ] ) )
	{ // Init subarray for each filter by statuses
		$cache_mail_is_blocked_status[ $status_filter_name ] = array();
	}

	if( !isset( $cache_mail_is_blocked_status[ $status_filter_name ][ $email ] ) )
	{ // If we check status of this email first time - get it from DB and store in cache
		global $DB;
		$SQL = new SQL();
		$SQL->SELECT( 'emblk_ID' );
		$SQL->FROM( 'T_email__blocked' );
		$SQL->WHERE( 'emblk_address = '.$DB->quote( $email ) );
		$SQL->WHERE_and( get_mail_blocked_condition( true, $blocked_statuses ) );
		$cache_mail_is_blocked_status[ $status_filter_name ][ $email ] = (boolean) $DB->get_var( $SQL->get() );
	}

	// Get email block status from cache variable
	return $cache_mail_is_blocked_status[ $status_filter_name ][ $email ];
}


/**
 * Get where conditino to check if a mail is blocked or not
 *
 * @param boolean set true for blocked emails and false for not blocked emails
 * @param array Blocked statuses to know what emails are blocked to send
 *     'unknown'     - Unknown
 *     'warning'     - Warning
 *     'suspicious1' - Suspicious 1
 *     'suspicious2' - Suspicious 2
 *     'suspicious3' - Suspicious 3
 *     'prmerror'    - Permament error
 *     'spammer'     - Spammer
 * @return string the where condition
 */
function get_mail_blocked_condition( $is_blocked = true, $blocked_statuses = array() )
{
	global $DB;

	if( empty( $blocked_statuses ) )
	{	// Default the blocked statuses
		$blocked_statuses = array( 'prmerror', 'spammer' );
	}

	$operator = $is_blocked ? 'IN' : 'NOT IN';
	return 'emblk_status '.$operator.' ( '.$DB->quote( $blocked_statuses ).' )';
}


/**
 * Memorize the blocked emails in cache array in order to display the message 
 * @see blocked_emails_display()
 *
 * @param string Email address
 */
function blocked_emails_memorize( $email )
{
	global $current_User, $cache_blocked_emails;

	if( empty( $email ) )
	{ // Empty email, Exit here
		return;
	}

	if( is_logged_in() && $current_User->check_perm( 'users', 'view' ) )
	{ // User has permissions to view other users
		if( mail_is_blocked( $email ) )
		{ // Check if the email address is blocked
			if( isset( $cache_blocked_emails[ $email ] ) )
			{ // Icrease a count of blocked email
				$cache_blocked_emails[ $email ]++;
			}
			else
			{
				$cache_blocked_emails[ $email ] = 1;
			}
		}
	}
}


/**
 * Display the blocked emails from cache array
 */
function blocked_emails_display()
{
	global $Messages, $cache_blocked_emails;

	if( !empty( $cache_blocked_emails ) && is_array( $cache_blocked_emails ) )
	{ // Display the messages about the blocked emails (grouped by email)
		foreach( $cache_blocked_emails as $blocked_email => $blocked_emails_count )
		{
			$Messages->add( sprintf( T_('We could not send %d email to %s because this address is blocked.'), $blocked_emails_count, $blocked_email ) );
		}
	}
}

/*
 * $Log$
 * Revision 1.2  2013/11/06 08:04:54  efy-asimo
 * Update to version 5.0.1-alpha-5
 *
 */
?>