<?php
require_once("plugins/Serials/core/format_check_api.php");
access_ensure_project_level( plugin_config_get('format_threshold'));
html_page_top1( plugin_lang_get( 'plugin_format_title' ) );
html_page_top2();
?>
<script src="plugins/UTILS_plugin/bower_components/mantis_extended_kernel/client/js/buildscript.js" type="text/javascript"></script>
<script>
	/*global ENV_MODE, UTILS_BOWER_URL, MANTIS_EXTENDED_KERNEL, PLUGIN_URL_SERIALS*/
	/*global loadScript */
	loadScript({
		path: UTILS_BOWER_URL+"/jquery-typeahead-2.1.3/dist/",
		ref: "jquery.typeahead.min.css"
	}, {
		path: MANTIS_EXTENDED_KERNEL +"/client/css/",
		ref: "custom.css"
	});
</script>
<br>
<p align="center">Configuration page to set up Serial Numbering format per Assembly.</p>
</br>
<div align="center">
<form method="post" action="<?php echo $g_format_add_page ?>" autocomplete='off'>
<table class="width75" cellspacing="1">
	<tr <?php echo helper_alternate_class() ?> valign="top">
		<td class="left pad1 positive" colspan="20"><var id="result-container" class="result-container"></var></td>
	</tr>
	<tr <?php echo helper_alternate_class() ?> valign="top">
		<td class="category">
			<?php echo plugin_lang_get( 'customer_name' ) ?>
		</td>
		<td id="typeahead-field1">
			<div class="typeahead-container">
		        <div class="typeahead-field">
		            <span class="typeahead-query">
		        		<input id="field1" type="text" size="100" name="customer_name" required/>
		        	</span>
		        	<span class="typeahead-button">
		                <button type="submit">
		                    <i class="typeahead-search-icon"></i>
		                </button>
		            </span>
		        </div>
		    </div>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?> valign="top">
		<td class="category">
			<?php echo plugin_lang_get( 'assembly_number' ) ?>
		</td>
		<td id="typeahead-field2">
			<div class="typeahead-container">
		        <div class="typeahead-field">
					<span class="typeahead-query">
					<input id="field2" type="text" size="100" name="assembly_number" required/>
					</span>
					<!--<span class="typeahead-button">
		                <button type="submit">
		                    <i class="typeahead-search-icon"></i>
		                </button>
		            </span>-->
		        </div>
		    </div>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?> valign="top">
		<td class="category">
			<?php echo plugin_lang_get( 'revision' ) ?>
		</td>
		<td id="typeahead-field3">
			<div class="typeahead-container">
		        <div class="typeahead-field">
					<span class="typeahead-query">
					<input id="field3" type="text" size="30" name="revision" required/>
					</span>
					<!--<span class="typeahead-button">
		                <button type="submit">
		                    <i class="typeahead-search-icon"></i>
		                </button>
		            </span>-->
				</div>
		    </div>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?> valign="top">
		<td class="category">
			<?php echo plugin_lang_get( 'format_helper' ) ?>
		</td>
		<td id="typeahead-field4">
			<div class="typeahead-container">
		        <div class="typeahead-field">
					<span class="typeahead-query">
						<input id="field4" type="text" size="100" name="format_helper"/>
					</span>
					<!--<span class="typeahead-button">
		                <button type="submit">
		                    <i class="typeahead-search-icon"></i>
		                </button>
		            </span>-->
				</div>
		    </div>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?> valign="top">
		<td class="category">
			<?php echo plugin_lang_get( 'format_example' ) ?>
		</td>
		<td id="typeahead-field5">
			<div class="typeahead-container">
        		<div class="typeahead-field">
					<span class="typeahead-query">
					<input id="field5" type="text" size="100" name="format_example" required/>
					</span>
					<!--<span class="typeahead-button">
		                <button type="submit">
		                    <i class="typeahead-search-icon"></i>
		                </button>
		            </span>-->
				</div>
    		</div>
		</td>
	</tr>
	<tr <?php echo helper_alternate_class() ?> valign="top">
		<td class="category">
			<?php echo plugin_lang_get( 'format' ) ?>
		</td>
		<td>
			<div class="typeahead-container">
				<div class="typeahead-field">
					<span id="f_prepend" class=" exp-decorator">/</span>
						<input id="field6" type="text" size="100" name="format" required/>
					<span id="f_append" class=" exp-decorator">/i</span>
				</div>
			</div>
		</td>
	</tr>
</table>
	<div>
	<input type="submit" value="Submit">
	</div>
</form>
</div>

<script>
	/*global loadScript */
	loadScript({
		path: UTILS_BOWER_URL+"/jquery/",
		ref: "jquery-1.11.3.min.js"
	},{
		path: UTILS_BOWER_URL+"/handlebars/",
		ref: "handlebars-v4.0.4.js"
	},{
		path: UTILS_BOWER_URL+"/jquery-typeahead-2.1.3/dist/",
		ref: "jquery.typeahead.min.js"
	},{
		path: MANTIS_EXTENDED_KERNEL+"/client/js/",
		ref: "ajax_typeahead_api.js"
	},{
		path: PLUGIN_URL_SERIALS+"/js/",
		ref: "format_proc.js"
	});
</script>
<?php
html_page_bottom1( __FILE__ );