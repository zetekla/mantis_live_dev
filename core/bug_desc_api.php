<?php
# MantisBT - A PHP based bugtracking system

# MantisBT is free software: you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation, either version 2 of the License, or
# (at your option) any later version.
#
# MantisBT is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License
# along with MantisBT.  If not, see <http://www.gnu.org/licenses/>.
/**
* @package CoreAPI
 * @subpackage BUG_DESC_API
 * @copyright Copyright 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
 * @copyright Copyright 2002  MantisBT Team - mantisbt-dev@lists.sourceforge.net
 * @copyright Copyright 2016 - ZeTek
 * @link github.com/zenithtekla
 * @uses core.php
 */

// require_once( 'core.php' );
$g_mantis_bug_text_table = 'mantis_bug_text_table ';
$g_emailreporting_table = 'mantis_plugin_emailreporting_msgids_table';

function get_issue_id ($s)
{
	GLOBAL $g_emailreporting_table;
	$query = 'SELECT issue_id
			FROM ' . $g_emailreporting_table . '
			WHERE issue_id ='. $s;
	$t_result = db_query_bound( $query );
	return string_display(db_fetch_array( $t_result ));
}

function ERP_bug_from_email( $p_bug_id ) {
	return $p_bug_id == get_issue_id ($p_bug_id);
}

function get_description($p_bug_id){
	GLOBAL $g_mantis_bug_text_table;
	try {
 		$query = 'SELECT description FROM ' . $g_mantis_bug_text_table . ' WHERE id='. $p_bug_id;
		$res = db_query_bound( $query );
		if (count($res)<1) throw new Exception('Query result NOT found');
		$result = db_fetch_array($res);
	} catch (Exception $e) {
		$result['description'] = $e->getMessage();
	}
	finally {
		return $result;
	}
}

// new methods
/**
 * Prints the list of visible attachments belonging to a given bug.
 * @param integer $p_bug_id ID of the bug to print attachments list for.
 * @return array
 */
function bug_inline_get_attachments( $p_bug_id ) {
	$t_attachments = file_get_visible_attachments( $p_bug_id );
	$count = 0;
	foreach ( $t_attachments as $t_attachment ) {
		if( $t_attachment['type'] === 'image' ){
			$t_images[] = $t_attachment;
			$count++;
		}
	}
	return ($count>0) ? $result['response'] = bug_attachment_preview_image_array( $t_images ) : null;
}

/**
 * Prints the preview of an image file attachment.
 * @param array An attachment array of image files
 * @return array
 */
function bug_attachment_preview_image_array( array $attachments ) {
	foreach ($attachments as $p_attachment) {
		$file['download_url'] = string_attribute( $p_attachment['download_url'] );
		$file['image_url'] = string_attribute( $p_attachment['download_url'] );
		// $file['src'] = '<img src="' . string_attribute( $p_attachment['download_url'] ). string_attribute( $t_image_url ) . '" alt="' . string_attribute( $t_title ) . '" style="' . string_attribute( $t_preview_style ) . '" />';
		$file['cid'] = string_attribute( $p_attachment['id'] );
		// comment out file_name (line below) for security
		$file['file_name'] = string_attribute( $p_attachment['display_name'] );
		$result[] = $file;
 	}
	return $result;
}

function bug_get_image_url($response){
	foreach ($response as $value) {
		$result[md5($value['file_name'])] = $value['image_url'];
	}
	return $result;
}

function bug_get_image_url_simplified($response){
	foreach ($response as $value) {
		$result[]['image_url'] = $value['image_url'];
	}
	return $result;
}

/**
 * Prints the preview of an image file attachment.
 * @param language string for 'description', bug_id and customize settings
 * @return array
 */
function show_description($p_bug_id, $p_inline = null){
	$response = get_description($p_bug_id);
	$t_description = $response['description'];
	if (!empty($p_inline)){
		preg_match_all('/src=[("|\')]([^"&&^\']+)[("|\')]/', $t_description, $src_array);
		$t_img_urls = bug_get_image_url($p_inline);
		// $result['img_urls'] = $t_img_urls;
		$t_cid_array = $src_array[1];
		// $result['cid_array'] = $t_cid_array;
		foreach ($t_cid_array as $cid){
			// echo '<br/> *my cid: '. $cid;
			/*for($i;$i<count($p_inline);$i++){
				$p_inline[$i]['cid'] = $cid ;
				$pre_str = $p_inline[$i]['file_name'];
				if (preg_match("/$pre_str/", $cid, $match))
				$t_description = str_replace($p_inline[$i]['cid'], $p_inline[$i]['image_url'] , $t_description);
			}*/
			foreach ($p_inline as $value) {
				$pre_str = $value['file_name'];
				// echo '<br/> *my inline: '. $pre_str;
				if (preg_match("/$pre_str/", $cid, $match)){
					$t_img_url = $t_img_urls[md5($pre_str)];
					// echo ';<br/> *method str_replace(: '. $cid. ', ' . $t_img_url;
					$t_description = str_replace($cid, $t_img_url, $t_description);
				}
			}
		}
		$result['description'] = $t_description;
	} 
	// added to resolve no result value when inline was null or empty.
	else {
		$result['description'] = $t_description . '<br/> Email HTML Generated Without Images';
	}
	return (object)$result;
}

function print_p ($a){
	echo '<pre>';
	print_r($a);
	echo '</pre>';
}