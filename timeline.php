<?php
/*
Plugin Name: Timeline
Plugin URI: 
Description: Make your own timeline calendar with many options!
Version: 1
Author: Omid Korat
Author URI: http://dementor.ir/
*/


//DEFAULTS
define("TABLE_NAME", $wpdb->prefix . "timeline");
require_once('widget.php');
$gmonth = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
$jmonth = array('فروردین', 'اردیبهشت', 'خرداد', 'تیر', 'مرداد', 'شهریور', 'مهر',
            'آبان', 'آذر', 'دی', 'بهمن', 'اسفند');
//WPLANG

function checktimeline(){
    if (get_option('timeline_empty') == '') update_option('timeline_empty', __('Nothing happened today!', 'timeline'));
    if (get_option('timeline_hidempty') == '') update_option('timeline_hidempty', '');
    if (get_option('timeline_formaty') == '') update_option('timeline_formaty', '<div><strong>%day% %month% ('.__('Yesterday', 'timeline').')</strong>: %event%</div>');
    if (get_option('timeline_format') == '') update_option('timeline_format', '<div><strong>%day% %month% ('.__('Today', 'timeline').')</strong>: %event%</div>');
    if (get_option('timeline_formatt') == '') update_option('timeline_formatt', '<div><strong>%day% %month% ('.__('Tomorrow', 'timeline').')</strong>: %event%</div>');
    if (get_option('timeline_days') == '') update_option('timeline_days', '2');
    if (checkjalali() and WPLANG == 'fa_IR') { if (get_option('timeline_jalali') == '') update_option('timeline_jalali', '1'); }
}

function checkjalali($plugin = false){

    if (function_exists('gregorian_to_jalali') and function_exists('jalali_to_gregorian')) {
        if (get_option('timeline_jalali') == '0') {
            if ($plugin) {
                return true;
            } else {
                return false;
            }
        } else {
            return true;
        }
    } else {
        return false;
    }
}

function set_plugin_meta($links, $file) {
	$plugin = plugin_basename(__FILE__);
	// create link
	if ($file == $plugin) {
		return array_merge(
			$links,
			array( sprintf( '<a href="admin.php?page=timeline" alt="%s">%s</a>', $plugin, __('Settings') ) )
		);
	}
	return $links;
}
add_filter( 'plugin_row_meta', 'set_plugin_meta', 10, 2 );


function timeline_menu()
{
    add_menu_page(__('Timeline', 'timeline'), __('Timeline', 'timeline'),
        'manage_options', 'timeline', 'timeline_handle', plugins_url('timeline/icon.png'));
    add_submenu_page('timeline', __('Events', 'timeline'), __('Events', 'timeline'),
        'manage_options', 'events', 'events_page');
    add_submenu_page('timeline', __('Uninstall', 'timeline'), __('Uninstall', 'timeline'),
        'manage_options', 'uninstall', 'uninstall_page');
    

}

function timeline_handle()
{
    global $wpdb;
    checktimeline();
    if (!empty($_POST) && check_admin_referer('timelinecal')) {
        if (isset($_POST['timeline_config'])) {
            update_option('timeline_days', $_POST['days']);
            if ($_POST['empty'] != '') { update_option('timeline_empty', $_POST['empty']); }
            update_option('timeline_hidempty', $_POST['hidempty']);
            update_option('timeline_jalali', $_POST['jalali']);
            if ($_POST['jalali'] == '') update_option('timeline_jalali', '0');
            if ($_POST['formaty'] != '') update_option('timeline_formaty', $_POST['formaty']);
            if ($_POST['format'] != '') update_option('timeline_format', $_POST['format']);
            if ($_POST['formatt'] != '') update_option('timeline_formatt', $_POST['formatt']);
            checktimeline();
            echo '<div id="message" class="updated fade"><p>'.__('Settings saved.', 'timeline').'</p></div>';
        } elseif (isset($_POST['timeline_df'])) {
            update_option('timeline_days', '');
            update_option('timeline_empty', '');
            update_option('timeline_hidempty', '');
            update_option('timeline_formaty', '');
            update_option('timeline_format', '');
            update_option('timeline_formatt', '');
            if (!checkjalali() or WPLANG != 'fa_IR') { update_option('timeline_jalali', ''); } else {
            update_option('timeline_jalali', '1');
            }
            checktimeline();
            echo '<div id="message" class="updated fade"><p>'.__('Settings restored.', 'timeline').'</p></div>';
    
        }
    }
?>
    
<div class='wrap'>
<form name="frmtimeline" method="post" action="">
	<h2><?php echo __('Timeline', 'timeline'); ?></h2>
	
    <p><?php echo __('Which days should be displayed?', 'timeline'); ?></p>
	<p><select name="days">
	<option value="1"<?php if (get_option('timeline_days') == '1') {
        echo ' selected="selected"';
    } ?>><?php echo __('Only today', 'timeline'); ?></option>
	<option value="2"<?php if (get_option('timeline_days') != '1') {
        echo ' selected="selected"';
    } ?>><?php echo __('Yesterday, today and tomorrow', 'timeline'); ?></option>
	</select></p>
    <p><label<?php if (!checkjalali(true)) echo ' style="color: gray;"'; ?>><input<?php if (!checkjalali(true)) { echo ' disabled="disabled"'; } else { if (get_option('timeline_jalali') == '1') { echo ' checked="checked"'; } } ?> name="jalali" type="checkbox" value="1" />&nbsp;<?php echo __('Use Iranian (Solar Hejri) calendar.', 'timeline'); ?></label>&nbsp;<small style="font-style: italic;<?php if (!checkjalali(true)) echo ' color: gray;';?>">(<?php echo __('Your events actual date will not corrupt.', 'timeline'); ?>)</small></p>
    <?php if (!checkjalali(true)) echo '<p><small>'.__('In order to use this feature, you need to install the <a href="http://wordpress.org/extend/plugins/wp-jalali/">wp-jalali</a> plugin.', 'timeline').'</small></p>'; ?>
	<p><?php echo __('Text when no records found:', 'timeline'); ?></p>
	<input name="empty" style="width: 400px" type="text" value="<?php  echo htmlspecialchars(stripslashes(get_option('timeline_empty'))); ?>" />
    <p><label><input <?php if (get_option('timeline_hidempty') == '0') { echo 'checked="checked"'; } ?> name="hidempty" type="checkbox" value="0" />&nbsp;<?php echo __('Hide empty days.', 'timeline'); ?></label></p>
	<p><?php echo __('Yesterday template:', 'timeline'); ?></p>
	<input name="formaty" style="width: 400px" type="text" value="<?php echo htmlspecialchars(stripslashes(get_option('timeline_formaty'))); ?>" dir="ltr" />
    <p><?php echo __('Today template:', 'timeline'); ?></p>
	<input name="format" style="width: 400px" type="text" value="<?php echo htmlspecialchars(stripslashes(get_option('timeline_format'))); ?>" dir="ltr" />
    <p><?php echo __('Tomorrow template:', 'timeline'); ?></p>
	<input name="formatt" style="width: 400px" type="text" value="<?php echo htmlspecialchars(stripslashes(get_option('timeline_formatt'))); ?>" dir="ltr" />
    <input type="hidden" id="timeline_config" name="timeline_config" />
    <p><input name="submit" type="submit" value="<?php echo __('Save', 'timeline'); ?>" class="button-primary" /></p>
<?php wp_nonce_field('timelinecal'); ?>
</form>

<p><?php echo __('Template variables:', 'timeline'); ?></p>
<p><strong>%day%</strong> <?php echo __('Display the day.', 'timeline'); ?></p>
<p><strong>%month%</strong> <?php echo __('Display the full textual representation of the month, such as January or March.', 'timeline'); ?></p>
<p><strong>%event%</strong> <?php echo __('Display the event of the day.', 'timeline'); ?></p>

<form name="frmtimelinedf" method="post" action="" style="margin-top: 50px;">
<input type="submit" name="default" value="<?php echo __('Restore Defaults', 'timeline'); ?>" class="button" onclick="javascript:return confirm('<?php echo __('Whould you like to reset settings?', 'timeline'); ?>')" />
<input type="hidden" id="timeline_df" name="timeline_df" />
<?php wp_nonce_field('timelinecal'); ?>
</form>
</div>
    
<?php }

function events_page()
{
    global $wpdb, $gmonth, $jmonth;
    if ($wpdb->get_var("show tables like '".TABLE_NAME."'") != TABLE_NAME) {
        $sql = "CREATE TABLE " . TABLE_NAME . " (
      id mediumint(9) NOT NULL AUTO_INCREMENT,
	  day int(2) COLLATE utf8_general_ci NOT NULL,
      month int(2) COLLATE utf8_general_ci NOT NULL,
	  event longtext COLLATE utf8_general_ci NOT NULL,
	  UNIQUE KEY id (id)
	);";

        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    if (checkjalali()) {
        list($ey, $em, $ed) = gregorian_to_jalali(date("Y"), date("n"), date("j"));
    } else {
        $ed = date("j");
        $em = date("n");
    }
    $ee = '';
    
    if (!empty($_POST) && check_admin_referer('timelinecal')) {
        if (isset($_POST['timeline_add'])) {
            if ($_POST['event'] != '') { 
            $day = $_POST['date'];
            $month = $_POST['month'];
            if (checkjalali()) {
            list($y, $month, $day) = jalali_to_gregorian(date("Y"), $_POST['month'], $_POST['date']);
            }
            $tcquery = $wpdb->get_var("SELECT * FROM ".TABLE_NAME." WHERE day=$day AND month=$month");
            if (!$tcquery) {
            $eventhtml = esc_attr($_POST['event']);
            $wpdb->insert(TABLE_NAME, array('day' => $day, 'month' => $month, 'event' => $eventhtml));
            echo '<div id="message" class="updated fade"><p>' . stripslashes($_POST['event']) .
                ' '.__('added successfully!', 'timeline').'</p></div>';
            } else {
            echo '<div id="message" class="error fade"><p>'.__('You already have a event for this day and month!', 'timeline').'</p></div>';
            }
             }
        } elseif (isset($_POST['timeline_edit'])) {
            $day = $_POST['date'];
            $month = $_POST['month'];
            if (checkjalali()) {
            list($y, $month, $day) = jalali_to_gregorian(date("Y"), $_POST['month'], $_POST['date']);
            }
            $eevent = esc_attr($_POST['event']);
            $eid = $_POST['timeline_id'];
            $sql = $wpdb->prepare("UPDATE ".TABLE_NAME." SET day = '$day', month = '$month', event = '$eevent' WHERE id = $eid");
            $wpdb->query($sql);
            echo '<div id="message" class="updated fade"><p>'.__('Edited!', 'timeline').'</p></div>';
        }
    }

    if (isset($_GET['edit'])) {
        $eid = $_GET['edit'];
        $load = $wpdb->get_row("SELECT day, month, event FROM ".TABLE_NAME." WHERE id = $eid");
        $ed = $load->day;
        $em = $load->month;
        if (checkjalali()) {
        list($y, $em, $ed) = gregorian_to_jalali(date("Y"), $load->month, $load->day);
        }
        $ee = $load->event;
    } elseif (isset($_GET['delete'])) {
        $eid = $_GET['delete'];
        $sql = $wpdb->prepare("DELETE FROM ".TABLE_NAME." WHERE id = $eid");
        $wpdb->query($sql);
        if (!$wpdb->query) echo '<div id="message" class="updated fade"><p>'.__('Deleted!', 'timeline').'</p></div>';
    } ?>
   
   <div class='wrap'>
   <script type="text/javascript">
    function goConfirm(title, link){
       if(confirm(title) == true){
          window.location.href = link;
          return true;
       } else {
          return false;
       }
    }
    </script>
   <form action="" method="post">
	<h2><?php if ($ee != '') {
        echo __('Edit', 'timeline');
    } else {
        echo __('Add', 'timeline');
    } ?></h2>
	<?php echo __('On:', 'timeline'); ?> <select name="date">
	<?php for ($i = 1; $i < 32; $i++) {
        if ($i == $ed) {
            echo '<option value="' . $i . '" selected="selected">' . $i . '</option>';
        } else {
            echo '<option value="' . $i . '">' . $i . '</option>';
        } } ?>
	</select> <select name="month">
	<?php for ($i = 0; $i < 12; $i++) {
        $i3 = $i + 1;
        if ($i3 == $em) {
            if (checkjalali()) {
            echo '<option selected="selected" value="' . $i3 . '">' . $jmonth[$i] .
                '</option>';  
            } else {
            echo '<option selected="selected" value="' . $i3 . '">' . $gmonth[$i] .
                '</option>';
            }
        } else {
            if (checkjalali()) {
            echo '<option value="' . $i3 . '">' . $jmonth[$i] . '</option>';
            } else {
            echo '<option value="' . $i3 . '">' . $gmonth[$i] . '</option>';
            }
        } } ?>
	</select><br />
	<?php echo __('This event happens:', 'timeline'); ?> <br /><textarea cols="30" rows="4" name="event" id="event"><?php echo stripslashes($ee); ?></textarea>
    <p style="font-style: italic;"><small><?php echo __('You can use HTML codes.', 'timeline'); ?></small></p>
    <?php if ($ee != '') { ?>
    <input type="hidden" id="timeline_edit" name="timeline_edit" />
    <input type="hidden" id="timeline_id" name="timeline_id" value="<?php echo $eid; ?>" />
    <?php } else { ?>
    <input type="hidden" id="timeline_add" name="timeline_add" />    
    <?php } ?>
    <input name="submit" type="submit" value="<?php if ($ee != '') {
        echo __('Edit', 'timeline');
    } else {
        echo __('Add', 'timeline');
    } ?>" class="button-primary" /><?php if ($ee != '') { ?>&nbsp;<input type="button" name="delete" value="<?php echo __('Delete', 'timeline'); ?>" class="button" onclick="goConfirm('<?php echo __('Are you sure?', 'timeline'); ?>','<?php echo get_admin_url().'admin.php?page=events&amp;delete=' . $eid; ?>');" />&nbsp;<input type="button" name="cancel" value="<?php echo __('Cancel', 'timeline'); ?>" class="button" onclick="javascript:history.go(-1)" /><?php } ?>
    <?php wp_nonce_field('timelinecal'); ?>
    </form>
    </div>
    

    <?php $total = $wpdb->get_var("SELECT COUNT(*) FROM ".TABLE_NAME); ?>
        <table class="widefat" style="margin-top: 40px;">
        	<thead><tr>
        		<th>
        		<strong><?php echo __('Date', 'timeline'); ?></strong></th>
        		<th>
        		<strong><?php echo __('Event', 'timeline'); ?></strong></th>
        		<th>
        		<strong><?php echo __('Actions', 'timeline'); ?></strong></th>
        	</tr></thead><tbody>

        <?php $events = $wpdb->get_results("SELECT * FROM ".TABLE_NAME."  ORDER BY id ASC");
        foreach ($events as $event) {
            if (checkjalali()) {
                list($y, $m, $d) = gregorian_to_jalali(date("Y"), $event->month, $event->day);
                $m = $m-1;
            } else {
                $m = $event->month-1;
                $d = $event->day;
            }
            ?>
        
            <tr>
            		<td valign="top"><?php if (checkjalali()) { echo $d.' '.$jmonth[(int)$m]; } else { echo $d.' '.$gmonth[(int)$m]; } ?></td>
            		<td valign="top"><?php echo ($event->event); ?></td>
            		<td valign="top"><a href="<?php echo get_admin_url().'admin.php?page=events&amp;edit=' . $event->id; ?>"><?php echo __('Edit', 'timeline'); ?></a>&nbsp;&nbsp;<a href="<?php echo get_admin_url() .
'admin.php?page=events&amp;delete=' . $event->id; ?>" onclick="javascript:return confirm('<?php echo __('Are you sure?', 'timeline'); ?>')"><?php echo __('Delete', 'timeline'); ?></a></td>
           	</tr>
            
        <?php } if (!$total) { ?>
        <tr><td colspan="3" align="center"><strong><?php echo __('No Events Found', 'timeline'); ?></strong></td></tr>
        <?php } ?>
        </tbody></table>

<?php }

function mytimeline(){
    checktimeline();
    
    if (get_option('timeline_days') == '1') {
        maketimeline(date("j"), date("n"), $format, $empty);
    } else {
        $yd = array(date("j")-1 ,date("n"));
        $td = array(date("j")+1 ,date("n"));
        if (date("j") == 1) {
        $yd[0] = 31;
            if (date("n") == 1) {
            $yd[1] = 12;
            } else {
            $yd[1] = date("n")-1;
                if ($yd[1] == 11 or $yd[1] == 9 or $yd[1] == 6 or $yd[1] == 4) {
                $yd[0] = 30;
                } elseif ($yd[1] == 2) {
                    $yd[0] = 28;
                }
            }
        } elseif (date("j") == 28) {
            if ($td[1] == 2) {
                $td[0] = 1;
                $td[1] = 3;
            }
        } elseif (date("j") == 30) {
            if ($td[1] == 11 or $td[1] == 9 or $td[1] == 6 or $td[1] == 4) {
                $td[0] = 1;
                $td[1] = $td[1]+1;
            }
        } elseif (date("j") == 31) {
            $td[0] = 1;
            if ($td[1] == 12) {
                $td[1] = 1;
            } else {
                $td[1] = $td[1]+1;
            }
        }
        $formaty = stripslashes(get_option('timeline_formaty'));
        $formatt = stripslashes(get_option('timeline_formatt'));
        maketimeline($yd[0], $yd[1], $formaty, $empty);
        maketimeline(date("j"), date("n"));
        maketimeline($td[0], $td[1], $formatt, $empty);
    }
    
}

function maketimeline($day = 'cycle', $month = 'cycle', $format = '', $empty = '') {
    
    global $wpdb, $gmonth, $jmonth;
    checktimeline();
    
    if ($format == '') $format = stripslashes(get_option('timeline_format'));
    if ($empty == '') $empty = stripslashes(get_option('timeline_empty'));
    if ($day == 'cycle' or $month == 'cycle') { mytimeline(); return false; }
    
    $load = $wpdb->get_row("SELECT event
    FROM ".TABLE_NAME."
    WHERE DAY =$day AND MONTH=$month
    ");
    if (checkjalali()) {
    list($y, $month, $day) = gregorian_to_jalali(date("Y"), $month, $day);
    }
    $month =$month-1;
    $format = str_replace("%day%", $day, $format);
    if (checkjalali()) {
    $format = str_replace("%month%", $jmonth[$month], $format);
    } else {
    $format = str_replace("%month%", $gmonth[$month], $format);
    }
    if ($load->event == '') {
        $format = str_replace("%event%", $empty, $format);
        if (get_option('timeline_hidempty') == '' or $empty == 'hide') {
           echo $format;
        }
    } else {
        $format = str_replace("%event%", stripslashes($load->event), $format);
        echo $format;
    }
    
}

function uninstall_page()
{
    require_once('uninstall.php');
}

add_action('widgets_init', create_function('', 'return register_widget("TimelinecalWidget");'));
add_action('admin_menu', 'timeline_menu');
load_plugin_textdomain('timeline', "/wp-content/plugins/timeline/");
?>