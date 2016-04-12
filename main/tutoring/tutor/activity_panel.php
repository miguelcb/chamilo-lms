<?php
/* For licensing terms, see /license.txt */
/**
* Shows who is online in a specific session
* @package chamilo.main
*/
include_once '../../inc/global.inc.php';
$user_id = api_get_user_id();
$table_course      = Database::get_main_table(TABLE_MAIN_COURSE);
$table_course_user = Database::get_main_table(TABLE_MAIN_COURSE_USER);
$sql = "SELECT * FROM $table_course c
INNER JOIN $table_course_user cu ON cu.c_id = c.id AND cu.user_id = $user_id
ORDER BY cu.sort ASC";
$courses    = Database::query($sql);
?>

<?php if (count($recent_activities) > 0): ?>
<ul class="list-group">
    <?php foreach($recent_activities as $recent_activity): ?>
    <li class="list-group-item">
        <h4 class="list-group-item-heading clearfix">
            <span class="<?php echo $recent_activity['icon']; ?> pull-left"></span>
            <?php if($recent_activity['tool'] == 'review'): ?>
                <span class="pull-left">He repasado</span>
            <?php endif; ?>
            <?php if($recent_activity['tool'] == 'practice'): ?>
                <span class="pull-left">He practicado</span>
            <?php endif; ?>
            <div class="pull-right small" style="padding: 0;"><?php echo api_convert_and_format_date($recent_activity['date'], '%b %d'); ?>
        </h4>
        <p class="list-group-item-text"><?php echo $recent_activity['description']; ?></p>
    </li>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<div class="alert alert-info">No hay ninguna novedad</div>
<?php endif; ?>