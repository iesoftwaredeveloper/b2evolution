<?php
/**
 * This file implements the AJAX concurrent file uploader
 *
 * This file is part of the evoCore framework - {@link http://evocore.net/}
 * See also {@link http://sourceforge.net/projects/evocms/}.
 *
 * @copyright (c)2003-2013 by Francois Planque - {@link http://fplanque.com/}
 *
 * @license http://b2evolution.net/about/license.html GNU General Public License (GPL)
 *
 * @package htsrv
 *
 * {@internal Below is a list of authors who have contributed to design/coding of this file: }}
 * @author efy-asimo: Attila Simo.
 *
 * @version $Id$
 */


/**
 * Do the MAIN initializations:
 */


/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr {
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);

        if ($realSize != $this->getSize()){
            return false;
        }

        $target = fopen($path, "w");
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);

        return true;
    }

	function get_content()
	{
        $input = fopen("php://input", "rb");
        $temp = tmpfile();
		stream_copy_to_stream($input, $temp);
        fclose($input);

		fseek($temp, 0, SEEK_SET);
		$contents = '';

		while (!feof($temp))
		{
			$contents .= fread($temp, 8192);
		}
		fclose($temp);
		return $contents;
	}



    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];
        } else {
            throw new Exception('Getting content length is not supported.');
        }
    }
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm
{
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path)
    {
		if( !move_uploaded_file($_FILES['qqfile']['tmp_name'], $path) )
		{
            return false;
        }
        return true;
    }

    function getName()
    {
        return $_FILES['qqfile']['name'];
    }

    function getSize()
    {
        return $_FILES['qqfile']['size'];
    }

	function get_content()
	{
		$temp = fopen($_FILES['qqfile']['tmp_name'], "rb");
		fseek($temp, 0, SEEK_SET);
		$contents = '';
		while (!feof($temp))
		{
			$contents .= fread($temp, 8192);
		}
		fclose($temp);
		return $contents;
		//return file_get_contents($_FILES['qqfile']['tmp_name']);
	}
}


function out_echo( $message ,$specialchars )
{
	$message['text'] = base64_encode($message['text']);
	if( $specialchars == 1 )
	{
		$message['specialchars'] = 1;
		echo htmlspecialchars(evo_json_encode(array('success'=>$message)));
	}
	else
	{
		$message['specialchars'] = 0;
		echo (evo_json_encode(array('success'=>$message)));
	}

}

$specialchars = 0;
if( isset($_FILES['qqfile']) )
{
	$specialchars = 1;
}

$message = array();

require_once dirname(__FILE__).'/../conf/_config.php';
require_once $inc_path.'_main.inc.php';

// Do not append Debuglog to response!
$debug = false;

// Do not append Debug JSlog to response!
$debug_jslog = false;

global $current_User;

param( 'upload', 'boolean', true );
param( 'root_and_path', 'string', true );

// Check that this action request is not a CSRF hacked request:
$Session->assert_received_crumb( 'file' );

$upload_path = false;
if( strpos( $root_and_path, '::' ) )
{
	list( $root, $path ) = explode( '::', $root_and_path, 2 );
	$FileRootCache = & get_FileRootCache();
	$fm_FileRoot = $FileRootCache->get_by_ID( $root );
	$non_canonical_list_path = $fm_FileRoot->ads_path.$path;
	$upload_path = get_canonical_path( $non_canonical_list_path );
}

if( $upload_path === false )
{
	$message['text'] = '<span class="result_error">Bad request. Unknown upload location!</span>'; // NO TRANS!!
	out_echo($message, $specialchars);
	exit();
}

if( $upload && ( !$current_User->check_perm( 'files', 'add', false, $fm_FileRoot ) ) )
{
	$message['text'] = '<span class="result_error">'.T_( 'You don\'t have permission to upload on this file root.' ).'</span>';
	out_echo($message, $specialchars);
	exit();
}

if( $upload )
{	// Create the object and assign property

	if( isset($_GET['qqfile']) )
	{
		$file = new qqUploadedFileXhr();
	}
	elseif( isset($_FILES['qqfile']) )
	{
		$file = new qqUploadedFileForm();

	}
	else
	{
		$file = false;
	}

	if( $Settings->get( 'upload_maxkb' ) && ( $file->getSize() > $Settings->get( 'upload_maxkb' )*1024 ) )
	{
		$message['text'] = '<span class="result_error">'.
		// fp>vitaliy : call function to make human readable sized in kB MB etc.
		sprintf( T_('The file is too large: %s but the maximum allowed is %s.'), bytesreadable($file->getSize()), bytesreadable($Settings->get( 'upload_maxkb' )*1024) )
		. '</span>';
		out_echo($message, $specialchars);
		exit();
	}

	$newName = $file->getName();
	$oldName = $newName;
	// validate file name
	if( $error_filename = process_filename( $newName ) )
	{	// Not a file name or not an allowed extension
		$message['text'] =  '<span class="result_error"> '.$error_filename.'</span>';
		out_echo($message, $specialchars);
		exit();
	}

	list( $newFile, $oldFile_thumb ) = check_file_exists( $fm_FileRoot, $path, $newName );
	$newName = $newFile->get('name');

	// If everything is ok, save the file somewhere
	if( save_to_file( $file->get_content(), $newFile->get_full_path(), 'wb' ) )
	{
		// Change to default chmod settings
		$newFile->chmod( NULL );

		// Refreshes file properties (type, size, perms...)
		$newFile->load_properties();

		// save file into the db
		$newFile->dbsave();

		$message = '';
		if( ! empty($oldFile_thumb) )
		{
			$image_info = getimagesize( $newFile->get_full_path() );
			if( $image_info )
			{
				$newFile_thumb = $newFile->get_preview_thumb( 'fulltype' );
			}
			else
			{
				$newFile_thumb = $newFile->get_size_formatted();
			}
			$message = '<br />';
			$message .= sprintf( T_('%s was renamed to %s. Would you like to replace %s with the new version instead?'),
								'&laquo;'.$oldName.'&raquo;', '&laquo;'.$newName.'&raquo;', '&laquo;'.$oldName.'&raquo;' );
			$message .= '<div class="invalid" title="'.T_('File name changed.').'">';
			$message .= '<input type="radio" name="Renamed_'.$newFile->ID.'" value="Yes" id="Yes_'.$newFile->ID.'"/>';
			$message .= '<label for="Yes_'.$newFile->ID.'">';
			$message .= sprintf( T_("Replace the old version %s with the new version %s and keep old version as %s."), $oldFile_thumb, $newFile_thumb, $newName ).'</label><br />';
			$message .= '<input type="radio" name="Renamed_'.$newFile->ID.'" value="No" id="No_'.$newFile->ID.'" checked="checked"/>';
			$message .= '<label for="No_'.$newFile->ID.'">';
			$message .= sprintf( T_("Don't touch the old version and keep the new version as %s."), $newName ).'</label><br />';
			$message .= '</div>';
		}

		if( !empty( $message ) )
		{
			$message .= '<input type="hidden" name="renamedFiles['.$newFile->ID.'][newName]" value="'.$newName.'" />' .
			'<input type="hidden" name="renamedFiles['.$newFile->ID.'][oldName]" value="'.$oldName.'" />';
			$message = array('text' => $message, 'status' => 'rename');
			out_echo($message, $specialchars);
			exit();
		}
		else
		{
			$image_info = getimagesize( $newFile->get_full_path() );
			if( $image_info )
			{
				$newFile_thumb = $newFile->get_preview_thumb( 'fulltype' );
			}
			else
			{
				$newFile_thumb = $newFile->get_size_formatted();
			}

			$message['text'] = "<span class=\"result_success\"> ".T_( 'OK' )." </span> $newFile_thumb ";
			out_echo($message, $specialchars);
			exit();
		}

	}

	$message['text'] = '<span class="result_error">'.T_( 'The file could not be saved!' ).'</span>';
	out_echo($message, $specialchars);
	exit();

}

$message['text'] =  '<span class="error">Invalid upload param</span>';
out_echo($message, $specialchars);
exit();

/*
 * $Log$
 * Revision 1.12  2013/11/06 08:03:44  efy-asimo
 * Update to version 5.0.1-alpha-5
 *
 */
?>