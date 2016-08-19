<?php
# MantisBT - a php based bugtracking system

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
	 * This include file prints out the bug file upload form
	 * It POSTs to bug_file_add.php
	 * @package MantisBT
	 * @copyright Copyright (C) 2000 - 2002  Kenzaburo Ito - kenito@300baud.org
	 * @copyright Copyright (C) 2002 - 2014  MantisBT Team - mantisbt-dev@lists.sourceforge.net
	 * @link http://www.mantisbt.org
	 */

	require_once( 'file_api.php' );

	# check if we can allow the upload... bail out if we can't
	if ( !file_allow_bug_upload( $f_bug_id ) ) {
		return false;
	}

	$t_max_file_size = (int)min( ini_get_number( 'upload_max_filesize' ), ini_get_number( 'post_max_size' ), config_get( 'max_file_size' ) );
?>
<br />

<?php
	collapse_open( 'upload_form' );
	$t_file_upload_max_num = max( 1, config_get( 'file_upload_max_num' ) );
?>
<form method="post" enctype="multipart/form-data" action="bug_file_add.php">
<?php echo form_security_field( 'bug_file_add' ) ?>

<table class="width100" cellspacing="1">
<tr>
	<td class="form-title" colspan="2">
<?php
		collapse_icon( 'upload_form' );
		echo lang_get( $t_file_upload_max_num == 1 ? 'upload_file' : 'upload_files' );
?>
	</td>
</tr>
<tr class="row-1">
	<td class="category" width="15%">
		<?php echo lang_get( $t_file_upload_max_num == 1 ? 'select_file' : 'select_files' ) ?><br />
		<?php echo '<span class="small">(' . lang_get( 'max_file_size' ) . ': ' . number_format( $t_max_file_size/1000 ) . 'k)</span>'?>
	</td>
	<td width="85%">
		<input type="hidden" name="bug_id" value="<?php echo $f_bug_id ?>" />
		<input type="hidden" name="max_file_size" value="<?php echo $t_max_file_size ?>" />
<?php
	// Display multiple file upload fields
	for( $i = 0; $i < $t_file_upload_max_num; $i++ ) {
?>
<script type="text/javascript" src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
<script>
function updateSize() {
  var nBytes = 0,
      oFiles = document.getElementById("uploadInput").files,
      nFiles = oFiles.length;
  for (var nFileId = 0; nFileId < nFiles; nFileId++) {
    nBytes += oFiles[nFileId].size;
  }
  var sOutput = nBytes + " bytes";
  // optional code for multiples approximation
  for (var aMultiples = ["KiB", "MiB", "GiB", "TiB", "PiB", "EiB", "ZiB", "YiB"], nMultiple = 0, nApprox = nBytes / 1024; nApprox > 1; nApprox /= 1024, nMultiple++) {
    sOutput = nApprox.toFixed(3) + " " + aMultiples[nMultiple] + " (" + nBytes + " bytes)";
  }
  // end of optional code

  if( nBytes > 5000000 )
  {
	  alert("The file you have selected is larger than the allowed 5mb limit.  Please select a file of the appropriate size.");
	  document.getElementById("uploadInput").value = "";
	  document.getElementById("fileSize").innerHTML = "0";  
  }
  else
  {
	  document.getElementById("fileNum").innerHTML = nFiles;
	  document.getElementById("fileSize").innerHTML = sOutput;  
  }
};
</script>
		<input id="uploadInput" type="file" name="ufile[]" onchange="updateSize();" multiple> selected files: <span id="fileNum">0</span>; total size: <span id="fileSize">0</span>

<?php
		if( $t_file_upload_max_num > 1 ) {
			echo '<br />';
		}
	}
?>

		<input type="submit" class="button"
			value="<?php echo lang_get( $t_file_upload_max_num == 1 ? 'upload_file_button' : 'upload_files_button' ) ?>"
		/>
	</td>
</tr>
</table>
</form>
<?php
	collapse_closed( 'upload_form' );
?>
<table class="width100" cellspacing="1">
<tr>
	<td class="form-title" colspan="2">
		<?php
			collapse_icon( 'upload_form' );
			echo lang_get( 'upload_file' ) ?>
	</td>
</tr>
</table>

<?php
	collapse_end( 'upload_form' );
