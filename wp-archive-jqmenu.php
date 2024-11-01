<?php
/*
Plugin Name: WP jqtransform archive
Description: Replace the basic Wordpress select archive with the jQuery/jqtransform plugin select. 
Version: 1.0
Author: Fayçal Tirich
*/

$wp_archive_jqmenu_plugin_basename = plugin_basename(dirname(__FILE__));
$wp_archive_jqmenu_plugin_url_path = WP_PLUGIN_URL.'/'.$wp_archive_jqmenu_plugin_basename; 
$wp_archive_jqmenu_plugin_css_path = $wp_archive_jqmenu_plugin_url_path. '/jqtransformplugin/jqtransform.min.css';
$wp_archive_jqmenu_plugin_js_path = $wp_archive_jqmenu_plugin_url_path. '/jqtransformplugin/jquery.jqtransform.min.js';
$wp_archive_jqmenu_plugin_img_path = $wp_archive_jqmenu_plugin_url_path. '/jqtransformplugin/img/';

function archive_jqmenu_widget($args) {
	?>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery("form.jqtransform").jqTransform({imgPath:'<?php echo $wp_archive_jqmenu_plugin_img_path; ?>'});
	});
	</script>
	<?php 
	global $wp_archive_jqmenu_plugin_img_path;
	extract($args);
	$options = get_option('archive_jqmenu_widget');
	$title = empty($options['title']) ? __('Archives') : $options['title'];
	echo $before_widget;
	if ( $title )
		echo $before_title . $title . $after_title;
	?>
	<div>
		
		<form class='jqtransform' action=''>
			<div class='rowElem'>
				<select name="archive-dropdown" onchange='document.location.href=this.options[this.selectedIndex].value;'> 
					<option value=""><?php echo esc_attr(__('Select Month')); ?></option> 
					<?php wp_get_archives(apply_filters('widget_archives_dropdown_args', array('type' => 'monthly', 'format' => 'option', 'show_post_count' => $c))); ?> 
				</select>
			</div>
		</form>
	</div>
	<?php
	echo $after_widget;
}

function archive_jqmenu_widget_control() {
		$options = $newoptions = get_option('archive_jqmenu_widget');
		if ( $_POST['ajw_submit'] ) {
			$newoptions['title'] = strip_tags(stripslashes($_POST['ajw_title']));
		}
		if ( $options != $newoptions ) {
			$options = $newoptions;
			update_option('archive_jqmenu_widget', $options);
		}
		$ajw_title = attribute_escape($options['title']);
		?>
		<p>
			<label for="ajw_title"><? echo __('Title') ?></label> 
			<input style="width: 200px;" id="ajw_title" name="ajw_title" type="text" value="<?php echo $ajw_title; ?>" />
		</p>
		<input type="hidden" id="ajw_submit" name="ajw_submit" value="1" />
<?php
    }

function init_archive_jqmenu(){
    register_sidebar_widget("Archive jqtransform menu", "archive_jqmenu_widget");  
	register_widget_control("Archive jqtransform menu", "archive_jqmenu_widget_control");
}

function add_jqtransformplugin_js() {
	if (!is_admin()) {
		wp_enqueue_script('jquery'); 
		global $wp_archive_jqmenu_plugin_js_path;
		wp_enqueue_script('jquery.jqtransform.min', $wp_archive_jqmenu_plugin_js_path);
	}
}

function add_jqtransformplugin_css() {
	global $wp_archive_jqmenu_plugin_css_path;
	?>
	<link rel="stylesheet" type="text/css" href="<?php echo $wp_archive_jqmenu_plugin_css_path; ?>" />
	<?php
}


add_action('init', 'add_jqtransformplugin_js');

add_action('wp_print_styles', 'add_jqtransformplugin_css');
//add_action('wp_head', 'add_jqtransformplugin_js');
add_action('plugins_loaded', "init_archive_jqmenu");
?>