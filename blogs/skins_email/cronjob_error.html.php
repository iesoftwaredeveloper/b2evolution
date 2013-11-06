<?php
/**
 * This is the PLAIN TEXT template of email message for cron job error
 *
 * This file is not meant to be called directly.
 * It is meant to be called by an include in the main.page.php template.
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/license.html}
 * @copyright (c)2003-2013 by Francois Planque - {@link http://fplanque.com/}
 *
 * @package evoskins
 *
 * {@internal Below is a list of authors who have contributed to design/coding of this file: }}
 * @author fplanque: Francois PLANQUE.
 *
 * @version $Id$
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

global $htsrv_url, $admin_url, $baseurl;

// Default params:
$params = array_merge( array(
		'tasks' => array(),
	), $params );

echo T_('Hello $login$ !').'<br /><br />';

echo T_('The following scheduled tasks have ended with error:').'<br />';
if( is_array( $params['tasks'] ) && count( $params['tasks'] ) )
{
	echo '<ul>';
	foreach( $params['tasks'] as $task )
	{
		echo '<li>'.$task['name'].': '.$task['message'].'</li>';
	}
	echo '</ul>';
}
echo '<br />';

$tasks_url = $admin_url.'?ctrl=crontab&ctst_timeout=1&ctst_error=1';
echo sprintf( T_('To see more information about these tasks click here: %s'), get_link_tag( $tasks_url ) ).'<br />';

// Footer:
echo '<br />-- <br />';
echo sprintf( T_( 'This message was automatically generated by b2evolution running on %s.' ), get_link_tag( $baseurl ) ).'<br />';
echo T_( 'Please do not reply to this email.' ).'<br />';
echo T_( 'You are a scheduled task admin, and you are receiving notifications when a scheduled tasks ends with error or timeout.' ).'<br />';
echo T_( 'If you don\'t want to receive any more notifications about scheduled task errors click here' ).': '
		.get_link_tag( $htsrv_url.'quick_unsubscribe.php?type=cronjob_error&user_ID=$user_ID$&key=$unsubscribe_key$' ).'<br />';
echo T_( 'Your login is: $login$' );
?>