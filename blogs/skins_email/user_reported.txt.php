<?php
/**
 * This is the PLAIN TEXT template of email message for reported user account notification
 *
 * This file is not meant to be called directly.
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/license.html}
 * @copyright (c)2003-2013 by Francois Planque - {@link http://fplanque.com/}
 *
 * @package evoskins
 *
 * {@internal Below is a list of authors who have contributed to design/coding of this file: }}
 * @author attila: Attila Simo.
 *
 * @version $Id$
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

global $admin_url, $htsrv_url;

// Default params:
$params = array_merge( array(
		'login'          => '',
		'email'          => '',
		'report_status'  => '',
		'report_info'  => '',
		'user_ID'        => '',
		'reported_by'    => '', // Login of user who has reported this user account
	), $params );

echo sprintf( T_('A user account was reported by %s'), $params['reported_by'] );

echo "\n\n";

echo T_('Login').": ".$params['login']."\n";
echo T_('Email').": ".$params['email']."\n";
echo T_('Reported as').": ".$params['report_status']."\n";
echo T_('Extra info').": ".$params['report_info'];
echo "\n\n";

echo T_('Edit user').': '.$admin_url.'?ctrl=user&user_tab=admin&user_ID='.$params['user_ID']."\n";
echo "\n";
echo T_( 'If you don\'t want to receive any more notification when an account was reported, click here' ).': '
		.$htsrv_url.'quick_unsubscribe.php?type=account_reported&user_ID=$user_ID$&key=$unsubscribe_key$'."\n";
?>