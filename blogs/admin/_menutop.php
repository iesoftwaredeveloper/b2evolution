<?php
/**
 * Displays first part of the page menu (before the page title) 
 *
 * b2evolution - {@link http://b2evolution.net/}
 * Released under GNU GPL License - {@link http://b2evolution.net/about/license.html}
 * @copyright (c)2003-2004 by Francois PLANQUE - {@link http://fplanque.net/}
 *
 * @package admin
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xml:lang="<?php locale_lang() ?>" lang="<?php locale_lang() ?>">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=<?php locale_charset() ?>" />
	<title>b2evo :: <?php echo $admin_pagetitle; ?></title>
	<link href="admin.css" rel="stylesheet" type="text/css" />
	<?php if( $mode == 'sidebar' )
	{ ?>
	<link href="sidebar.css" rel="stylesheet" type="text/css" />
	<?php } ?>
</head>
<body>

<?php
param( 'blog', 'integer', 0, true );	// We need this for the urls

if( empty($mode) )
{	// We're not running in an special mode (bookmarklet, sidebar...)
?>

<div id="header">
	<a href="http://b2evolution.net/" title="<?php echo T_("visit b2evolution's website") ?>"><img id="evologo" src="../img/b2evolution_minilogo2.png" alt="b2evolution"  title="<?php echo T_("visit b2evolution's website") ?>" width="185" height="40" /></a>

	<div id="headfunctions">
		<a href="<?php echo $htsrv_url ?>/login.php?action=logout"><?php echo T_('Logout').' ['.$current_User->get('login').']' ?></a>
		&middot;
		<a href="<?php echo $baseurl ?>"><?php echo T_('Exit to blogs') ?></a><br />
	</div>

	<?php	
	if( !$obhandler_debug )
	{ // don't display changing time when we want to test obhandler
	?>
	<div id="headinfo">
		b2evo v <strong><?php echo $b2_version ?></strong>
		&middot; <?php echo T_('Blog time:') ?> <strong><?php echo date_i18n( locale_timefmt(), $localtimenow ) ?></strong>
		&middot; <?php echo T_('GMT:') ?> <strong><?php echo gmdate( locale_timefmt(), $servertimenow); ?></strong>
		&middot; <?php echo T_('Logged in as:'), ' <strong>', $user_login; ?></strong>
	</div>
	<?php } ?>
	
	<ul class="tabs">
	<?php
		if( $admin_tab == 'new' )
			echo '<li class="current">';
		else
			echo '<li>';
		echo '<a href="b2edit.php?blog=', $blog, '" style="font-weight: bold;">', T_('New Post'), '</a></li>';

		if( $admin_tab == 'edit'  )
			echo '<li class="current">';
		else
			echo '<li>';
		echo '<a href="b2browse.php?blog=', $blog, '" style="font-weight: bold;">', T_('Browse/Edit'), '</a></li>';

		if( $admin_tab == 'cats' )
			echo '<li class="current">';
		else
			echo '<li>';
		echo '<a href="b2categories.php?blog=', $blog, '" >', T_('Cats'), '</a></li>';

		if( $admin_tab == 'blogs' )
			echo '<li class="current">';
		else
			echo '<li>';
		echo '<a href="b2blogs.php" >', T_('Blogs'), '</a></li>';

		if( $current_User->check_perm( 'stats', 'view' ) )
		{
			if( $admin_tab == 'stats' )
				echo '<li class="current">';
			else
				echo '<li>';
			echo '<a href="b2stats.php" >', T_('Stats'), '</a></li>';
		}

		if( $current_User->check_perm( 'spamblacklist', 'view' ) )
		{
			if( $admin_tab == 'antispam' )
				echo '<li class="current">';
			else
				echo '<li>';
			echo '<a href="b2antispam.php" >', T_('Antispam'), '</a></li>';
		}

		if( $current_User->check_perm( 'templates', 'any' ) )
		{
			if( $admin_tab == 'templates' )
				echo '<li class="current">';
			else
				echo '<li>';
			echo '<a href="b2template.php">', T_('Templates'), '</a></li>';
		}

		if( $current_User->check_perm( 'users', 'view' ) )
		{
			if( $admin_tab == 'users' )
				echo '<li class="current">';
			else
				echo '<li>';
			echo '<a href="b2users.php" >', T_('Users'), '</a></li>';
		}

		if( $current_User->check_perm( 'options', 'view' ) )
		{
			if( $admin_tab == 'options' )
				echo '<li class="current">';
			else
				echo '<li>';
			echo '<a href="b2options.php" >', T_('Settings'), '</a></li>';
		}

		if( $admin_tab == 'profile' )
			echo '<li class="current">';
		else
			echo '<li>';
		echo '<a href="b2profile.php" >', T_('My Profile'), '</a></li>';

	?>

	</ul>
</div>

<h1><strong>:: <?php echo $admin_pagetitle; ?></strong>

<?php
}	// not in special mode
?>


