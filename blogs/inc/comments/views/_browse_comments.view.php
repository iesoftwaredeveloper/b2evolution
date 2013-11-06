<?php
/**
 * This file implements the comment browsing
 *
 * This file is part of the b2evolution/evocms project - {@link http://b2evolution.net/}.
 * See also {@link http://sourceforge.net/projects/evocms/}.
 *
 * @copyright (c)2003-2013 by Francois Planque - {@link http://fplanque.com/}.
 * Parts of this file are copyright (c)2005 by Daniel HAHLER - {@link http://thequod.de/contact}.
 *
 * @license http://b2evolution.net/about/license.html GNU General Public License (GPL)
 *
 * @package admin
 *
 * {@internal Below is a list of authors who have contributed to design/coding of this file: }}
 * @author fplanque: Francois PLANQUE.
 *
 * @version $Id$
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

/**
 * @var Comment
 */
global $Comment;
/**
 * @var Blog
 */
global $Blog;
/**
 * @var CommentList
 */
global $CommentList, $show_statuses;

global $dispatcher;

global $current_User, $admin_url;

/*
 * Display comments:
 */

$CommentList->query();

// Dispay a form to mass delete the comments:
display_comment_mass_delete( $CommentList );

$block_item_Widget = new Widget( 'block_item' );

if( check_comment_mass_delete( $CommentList ) )
{	// A form for mass deleting is availabl, Display link
	$block_item_Widget->global_icon( T_('Delete all comments!'), 'delete', regenerate_url( 'action', 'action=mass_delete' ), T_('Mass delete...'), 3, 3 );
}

if( $CommentList->is_filtered() )
{	// List is filtered, offer option to reset filters:
	$block_item_Widget->global_icon( T_('Reset all filters!'), 'reset_filters', '?ctrl=comments&amp;blog='.$Blog->ID.'&amp;filter=reset', T_('Reset filters'), 3, 3 );
}
$emptytrash_link = '';
// Display recycle bin placeholder, because users may have rights to recycle particular comments
$opentrash_link = '<span id="recycle_bin" class="floatright"></span>';
if( $current_User->check_perm( 'blogs', 'editall' ) )
{
	if( $CommentList->is_trashfilter() )
	{
		$emptytrash_link = '<span class="floatright">'.action_icon( T_('Empty recycle bin'), 'recycle_empty', $admin_url.'?ctrl=comments&amp;action=emptytrash', T_('Empty recycle bin...'), 5, 3 ).'</span> ';
	}
	else
	{
		$opentrash_link = get_opentrash_link( false );
	}
}
$block_item_Widget->title = $opentrash_link.$emptytrash_link.T_('Feedback (Comments, Trackbacks...)');
$block_item_Widget->disp_template_replaced( 'block_start' );

// Display filters title
echo $CommentList->get_filter_title( '<h3>', '</h3>', '<br />', NULL, 'htmlbody' );

$display_params = array(
				'header_start' => '<div class="NavBar center">',
					'header_text' => '<strong>'.T_('Pages').'</strong>: $prev$ $first$ $list_prev$ $list$ $list_next$ $last$ $next$',
					'header_text_single' => T_('1 page'),
				'header_end' => '</div>',
				'footer_start' => '',
					'footer_text' => '<div class="NavBar center"><strong>'.T_('Pages').'</strong>: $prev$ $first$ $list_prev$ $list$ $list_next$ $last$ $next$<br />$page_size$</div>',
					'footer_text_single' => '<div class="NavBar center">$page_size$</div>',
						'prev_text' => T_('Previous'),
						'next_text' => T_('Next'),
						'list_prev_text' => T_('...'),
						'list_next_text' => T_('...'),
						'list_span' => 11,
						'scroll_list_range' => 5,
				'footer_end' => ''
			);

$CommentList->display_if_empty();

$CommentList->display_init( $display_params );

// Display navigation:
$CommentList->display_nav( 'header' );

load_funcs( 'comments/model/_comment_js.funcs.php' );

// Display list of comments:
// comments_container value is -1, because in this case we have to show all comments in current blog (Not just one item comments)
echo '<div id="comments_container" value="-1">';
require dirname(__FILE__).'/_comment_list.inc.php';
echo '</div>';

// Display navigation:
$CommentList->display_nav( 'footer' );

$block_item_Widget->disp_template_replaced( 'block_end' );


/*
 * $Log$
 * Revision 1.16  2013/11/06 08:04:07  efy-asimo
 * Update to version 5.0.1-alpha-5
 *
 */
?>