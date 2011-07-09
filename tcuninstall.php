<?php global $wpdb;
$tc_settings = array('timeline_empty', 'timeline_hidempty', 'timeline_formaty', 'timeline_format', 'timeline_formatt', 'timeline_days', 'timeline_jalali', 'timeline_break', 'timeline_excerpt', 'timeline_excerptch');
?>
<div class='wrap'>
<form name="frmtimelineun" method="post" action="">
<h2><?php echo __('Uninstall Timeline', 'timeline'); ?></h2>
<p><?php echo __('Deactivating does not remove data that created by this plugin so you have to uninstall it first in this page! Please pay attention that this process cannot be undone.', 'timeline'); ?></p>
<input type="submit" name="default" value="<?php echo __('Uninstall Timeline', 'timeline'); ?>" class="button-primary" onclick="javascript:return confirm('<?php echo __('Do you really want to uninstall?', 'timeline'); ?>')" />
<input type="hidden" id="timeline_un" name="timeline_un" />
<?php wp_nonce_field('timelinecal_uninstall'); ?>
</form>

<?php if (!empty($_POST) && check_admin_referer('timelinecal_uninstall')) {

			if (isset($_POST['timeline_un'])) {
				$wpdb->query("DROP TABLE " . TABLE_NAME);
                foreach($tc_settings as $setting) {
					$delete_setting = delete_option($setting);
				}
                echo '<div id="message" class="updated fade">';
				echo '<font style="color: green;">' . __('All data has been deleted.', 'timeline') . '</font><br />';
                echo '<p><strong>' . __('Now you have to deactivate the plugin, otherwise table and settings will be create again upon entering Timeline pages.', 'timeline') . '</strong></p>';
				echo '</div>'; 
			}
}
?>
</div>