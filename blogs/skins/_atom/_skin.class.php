<?php
/**
 * This file implements a class derived of the generic Skin class in order to provide custom code for
 * the skin in this folder.
 *
 * This file is part of the b2evolution project - {@link http://b2evolution.net/}
 *
 * @package skins
 * @subpackage custom
 *
 * @version $Id$
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

/**
 * Specific code for this skin.
 *
 * ATTENTION: if you make a new skin you have to change the class name below accordingly
 */
class _atom_Skin extends Skin
{
  /**
	 * Get default name for the skin.
	 * Note: the admin can customize it.
	 */
	function get_default_name()
	{
		return 'Atom';
	}


  /**
	 * Get default type for the skin.
	 */
	function get_default_type()
	{
		return 'feed';
	}
}

/*
 * $Log$
 * Revision 1.2  2013/11/06 08:05:42  efy-asimo
 * Update to version 5.0.1-alpha-5
 *
 */
?>