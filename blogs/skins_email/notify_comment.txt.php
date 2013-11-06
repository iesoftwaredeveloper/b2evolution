<?php
/**
 * This is the PLAIN TEXT template of email message for notify comment
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

global $htsrv_url, $baseurl, $admin_url;

// Default params:
$params = array_merge( array(
		'notify_full'    => false,
		'Comment'        => NULL,
		'Blog'           => NULL,
		'Item'           => NULL,
		'author_name'    => '',
		'notify_type'    => '',
		'notify_user_ID' => 0,
		'notify_key'     => '',
	), $params );


$Comment = $params['Comment'];
$Blog = $params['Blog'];
$Item = $params['Item'];

$notify_salutation = T_( 'Hello $login$ !' );

if( $params['notify_full'] )
{
	$notify_message = T_('New comment').': '
		.str_replace('&amp;', '&', $Comment->get_permanent_url())."\n"
		// TODO: fp> We MAY want to force a short URL and avoid it to wrap on a new line in the mail which may prevent people from clicking
		.T_('Blog').': '.$Blog->get('shortname')."\n"
		// Mail bloat: .' ( '.str_replace('&amp;', '&', $Blog->gen_blogurl())." )\n"
		.T_('Post').': '.$Item->get('title')."\n";
		// Mail bloat: .' ( '.str_replace('&amp;', '&', $Item->get_permanent_url())." )\n";
		// TODO: fp> We MAY want to force short URL and avoid it to wrap on a new line in the mail which may prevent people from clicking

	switch( $Comment->type )
	{
		case 'trackback':
			$user_domain = gethostbyaddr($Comment->author_IP);
			$notify_message .= T_('Website').": $Comment->author (IP: $Comment->author_IP, $user_domain)\n";
			$notify_message .= T_('Url').": $Comment->author_url\n";
			break;

		default:
			if( $Comment->get_author_User() )
			{ // Comment from a registered user:
				$notify_message .= T_('Author').': '.$Comment->author_User->get('preferredname').' ('.$Comment->author_User->get('login').")\n";
			}
			else
			{ // Comment from visitor:
				$user_domain = gethostbyaddr($Comment->author_IP);
				$notify_message .= T_('Author').": $Comment->author (IP: $Comment->author_IP, $user_domain)\n";
				$notify_message .= T_('Email').": $Comment->author_email\n";
				$notify_message .= T_('Url').": $Comment->author_url\n";
			}
	}

	if( !empty( $Comment->rating ) )
	{
		$notify_message .= T_('Rating').": $Comment->rating\n";
	}

	if( $params['notify_type'] == 'moderator' )
	{
		$notify_message .= T_('Status').': '.$Comment->get( 't_status' )."\n";
	}

	$notify_message .= $Comment->get('content')."\n";
}
else
{
	$notify_message = sprintf( T_( '%s posted a new comment on "%s" in blog "%s".' ), $params['author_name'], $Item->get('title'), $Blog->get('shortname') )."\n\n";
	$notify_message .= T_( 'To read the full content of the comment click here:' )
					.str_replace('&amp;', '&', $Comment->get_permanent_url())."\n";
					// TODO: fp> We MAY want to force a short URL and avoid it to wrap on a new line in the mail which may prevent people from clicking
	if( $params['notify_type'] == 'moderator' )
	{
		$notify_message .= "\n"
						.T_('Status').': '.$Comment->get( 't_status' )."\n"
						.T_( 'This is a short form moderation message. To make these emails more useful for quick moderation, ask the administrator to send you long form moderation messages instead.' )
						."\n";
	}
}

$notify_message = $notify_salutation."\n\n".$notify_message."\n-- \n";

// add unsubscribe and edit links
$unsubscribe_text = '';
if( $params['notify_type'] == 'moderator' )
{ // moderation email
	if( ( $Blog->get_setting( 'comment_quick_moderation' ) != 'never' ) && ( !empty( $Comment->secret ) ) )
	{ // quick moderation is permitted, and comment secret was set
		$notify_message .= T_('Quick moderation').': '.$htsrv_url.'comment_review.php?cmt_ID='.$Comment->ID.'&secret='.$Comment->secret."\n\n";
	}
	$notify_message .= T_('Edit comment').': '.$admin_url.'?ctrl=comments&action=edit&comment_ID='.$Comment->ID."\n\n";
	$unsubscribe_text = T_( 'You are a moderator in this blog, and you are receiving notifications when a comments may need moderation.' )."\n";
	$unsubscribe_text .= T_( 'If you don\'t want to receive any more notifications about comment moderation, click here' ).': '
						.$htsrv_url.'quick_unsubscribe.php?type=moderator&user_ID='.$params['notify_user_ID'].'&key='.md5( $params['notify_user_ID'].$params['notify_key'] );
}
else if( $params['notify_type'] == 'blog_subscription' )
{ // blog subscription
	$unsubscribe_text = T_( 'You are receiving notifications when anyone comments on any post.' )."\n";
	$unsubscribe_text .= T_( 'If you don\'t want to receive any more notifications on this blog, click here' ).': '
						.$htsrv_url.'quick_unsubscribe.php?type=coll_comment&user_ID='.$params['notify_user_ID'].'&coll_ID='.$Blog->ID.'&key='.md5( $params['notify_user_ID'].$params['notify_key'] );
	// subscribers are not allowed to see comment author email
}
else if( $params['notify_type'] == 'item_subscription' )
{ // item subscription
	$unsubscribe_text = T_( 'You are receiving notifications when anyone comments on this post.' )."\n";
	$unsubscribe_text .= T_( 'If you don\'t want to receive any more notifications on this post, click here' ).': '
						.$htsrv_url.'quick_unsubscribe.php?type=post&user_ID='.$params['notify_user_ID'].'&post_ID='.$Item->ID.'&key='.md5( $params['notify_user_ID'].$params['notify_key'] );
	// subscribers are not allowed to see comment author email
}
else if( $params['notify_type'] == 'creator' )
{ // user is the creator of the post
	$unsubscribe_text = T_( 'This is your post. You are receiving notifications when anyone comments on your posts.' )."\n";
	$unsubscribe_text .= T_( 'If you don\'t want to receive any more notifications on your posts, click here' ).': '
						.$htsrv_url.'quick_unsubscribe.php?type=creator&user_ID='.$params['notify_user_ID'].'&key='.md5( $params['notify_user_ID'].$params['notify_key'] );
}

if( !empty( $unsubscribe_text ) )
{ // add new line before the text if not empty
	$unsubscribe_text = "\n".$unsubscribe_text;
}

echo $notify_message;
echo sprintf( T_( 'This message was automatically generated by b2evolution running on %s.' ), $baseurl );
echo "\n";
echo T_( 'Please do not reply to this email.' );
echo $unsubscribe_text;
echo "\n";
echo T_( 'Your login is: $login$' );
?>