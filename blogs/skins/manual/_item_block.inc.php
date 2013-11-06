<?php
/**
 * This is the template that displays the item block
 *
 * This file is not meant to be called directly.
 * It is meant to be called by an include in the main.page.php template (or other templates)
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/license.html}
 * @copyright (c)2003-2013 by Francois Planque - {@link http://fplanque.com/}
 *
 * @package evoskins
 * @subpackage manual
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

global $Item, $cat;
global $posttypes_specialtypes;

// Default params:
$params = array_merge( array(
		'feature_block'     => false,
		'content_mode'      => 'auto',		// 'auto' will auto select depending on $disp-detail
		'item_class'        => 'bPost',
		'image_size'        => 'fit-640x480',
		'disp_comment_form' => true,
		'item_link_type'    => 'permalink',
	), $params );

echo '<div id="styled_content_block">'; // Beginning of post display
if( ( $disp == 'single' ) && empty( $cat ) )
{ // Display breadcrumb, but only if it was not displayed yet. When category is set then breadcrumbs is already displayed.
	$Skin->display_breadcrumbs( $Item->main_cat_ID );
}
?>

<div id="<?php $Item->anchor_id() ?>" class="<?php $Item->div_classes( $params ) ?>" lang="<?php $Item->lang() ?>">

	<?php
		$Item->locale_temp_switch(); // Temporarily switch to post locale (useful for multilingual blogs)
	?>

	<?php
		// ------------------- PREV/NEXT POST LINKS (SINGLE POST MODE) -------------------
		item_prevnext_links( array(
				'block_start' => '<div class="posts_navigation">',
				'separator'   => ' :: ',
				'block_end'   => '</div>',
				'target_blog' => $Blog->ID,	// this forces to stay in the same blog, should the post be cross posted in multiple blogs
				'post_navigation' => 'same_category', // force to stay in the same category in this skin
			) );
		// ------------------------- END OF PREV/NEXT POST LINKS -------------------------

	$item_edit_link = $Item->get_edit_link( array( // Link to backoffice for editing
			'before' => '',
			'after'  => '',
			'class'  => 'roundbutton roundbutton_text',
		) );
	if( $Item->status != 'published' )
	{
		$Item->status( array( 'format' => 'styled' ) );
	}
	$Item->title( array(
			'link_type'  => $params['item_link_type'],
			'before'     => '<div class="bTitle linked"><h1>',
			'after'      => '</h1>'.$item_edit_link.'<div class="clear"></div></div>',
			'nav_target' => false,
		) );

		// ---------------------- POST CONTENT INCLUDED HERE ----------------------
		skin_include( '_item_content.inc.php', $params );
		// Note: You can customize the default item feedback by copying the generic
		// /skins/_item_content.inc.php file into the current skin folder.
		// -------------------------- END OF POST CONTENT -------------------------
	?>

	<?php
		// List all tags attached to this post:
		$Item->tags( array(
				'before' =>         '<div class="bSmallPrint">'.T_('Tags').': ',
				'after' =>          '</div>',
				'separator' =>      ', ',
			) );

		echo '<p class="notes">';
		$Item->author( array(
				'before' => T_('Created by '),
				'after'  => ' &bull; ',
			) );
		$Item->lastedit_user( array(
				'before' => T_('Last edit by '),
				'after'  => T_(' on ').$Item->get_mod_date( 'F jS, Y' ),
			) );
		'</p>';
		if( is_logged_in() && $current_User->check_perm( 'item_post!CURSTATUS', 'edit', false, $Item ) )
		{	// Check permission to view histories of this item
			global $admin_url;
			echo '  &bull; ';
			echo '<a href="'.$admin_url.'?ctrl=items&amp;action=history&amp;p='.$Item->ID.'">'.T_('View history').'</a>';
		}

		// ------------------ FEEDBACK (COMMENTS/TRACKBACKS) INCLUDED HERE ------------------
		skin_include( '_item_feedback.inc.php', array_merge( $params, array(
				'before_section_title' => '<h2 class="comments_list_title">',
				'after_section_title'  => '</h2>',
				'form_title_start'     => '<h3 class="comments_form_title">',
				'form_title_end'       => '</h3>',
			) ) );
		// Note: You can customize the default item feedback by copying the generic
		// /skins/_item_feedback.inc.php file into the current skin folder.
		// ---------------------- END OF FEEDBACK (COMMENTS/TRACKBACKS) ---------------------
	?>

	<?php
		locale_restore_previous();	// Restore previous locale (Blog locale)
	?>
</div>
<?php 
echo '</div>'; // End of post display
?>