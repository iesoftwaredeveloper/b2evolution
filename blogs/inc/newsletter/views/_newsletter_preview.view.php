<?php
/**
 * This file is part of the evoCore framework - {@link http://evocore.net/}
 * See also {@link http://sourceforge.net/projects/evocms/}.
 *
 * @copyright (c)2009-2013 by Francois PLANQUE - {@link http://fplanque.net/}
 * Parts of this file are copyright (c)2009 by The Evo Factory - {@link http://www.evofactory.com/}.
 *
 * {@internal License choice
 * - If you have received this file as part of a package, please find the license.txt file in
 *   the same folder or the closest folder above for complete license terms.
 * - If you have received this file individually (e-g: from http://evocms.cvs.sourceforge.net/)
 *   then you must choose one of the following licenses before using the file:
 *   - GNU General Public License 2 (GPL) - http://www.opensource.org/licenses/gpl-license.php
 *   - Mozilla Public License 1.1 (MPL) - http://www.opensource.org/licenses/mozilla1.1.php
 * }}
 *
 * {@internal Open Source relicensing agreement:
 * The Evo Factory grants Francois PLANQUE the right to license
 * The Evo Factory's contributions to this file and the b2evolution project
 * under any OSI approved OSS license (http://www.opensource.org/licenses/).
 * }}
 *
 * @package evocore
 *
 * {@internal Below is a list of authors who have contributed to design/coding of this file: }}
 * @author fplanque: Francois PLANQUE.
 *
 * @version $Id$
 */
if( !defined('EVO_MAIN_INIT') ) die( 'Please, do not access this page directly.' );

global $newsletter, $users_numbers, $admin_url;

$Form = new Form( NULL, 'newsletter' );
$Form->begin_form( 'fform' );

$Form->add_crumb( 'newsletter' );
$Form->hidden( 'ctrl', 'newsletter' );
$Form->hidden( 'action', 'send' );

$Form->begin_fieldset( T_('Users info') );

	echo '<b>'.sprintf( T_('The following newsletter email will be sent to %s users.'), $users_numbers['newsletter'] ).'</b>';

$Form->end_fieldset();

$Form->begin_fieldset( T_('HTML format') );

echo T_('Subject').': <b>'.$newsletter['title'].'</b><br /><br />';
echo $newsletter['html'];

$Form->end_fieldset();

$Form->begin_fieldset( T_('Plain text format') );

echo T_('Subject').': <b>'.$newsletter['title'].'</b><br /><br />';
echo '<pre style="font-size:14px;">';
echo $newsletter['text'];
echo '</pre>';

$Form->end_fieldset();

$Form->end_form( array( array( 'submit', 'submit', T_('Send !'), 'SaveButton' ),
												array( 'button', '', T_('Back'), 'ResetButton', 'location.href=\''.$admin_url.'?ctrl=newsletter\'' ) ) );

/*
 * $Log$
 * Revision 1.2  2013/11/06 08:04:35  efy-asimo
 * Update to version 5.0.1-alpha-5
 *
 */
?>