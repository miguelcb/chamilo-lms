<?php
/* For licensing terms, see /license.txt */

require_once '../../../inc/global.inc.php';

//api_protect_course_script(true);
api_block_anonymous_users();

$user_id                    = api_get_user_id();
$c_id                       = is_null($_GET['cid']) ? '' : $_GET['cid'];
$table_course_user          = Database::get_main_table(TABLE_MAIN_COURSE_USER);
$table_course_item_property = Database::get_course_table(TABLE_ITEM_PROPERTY);
$table_course_tool          = Database::get_course_table(TABLE_TOOL_LIST);
$table_forum_post           = Database::get_course_table(TABLE_FORUM_POST);
$table_calendar_event       = Database::get_course_table(TABLE_AGENDA);
$table_document             = Database::get_course_table(TABLE_DOCUMENT);
$table_track_lastaccess     = Database::get_main_table(TABLE_STATISTIC_TRACK_E_LASTACCESS);
// TEMPORAL SOLUTION WHEN MIGRATE TO NEW TOOLS CHANGES THE TOOLS NAMES
require_once '../../constants.inc.php';
$TOOL_REVIEW_PRACTICE = TOOL_REVIEW_PRACTICE;
$TOOL_ASK             = TOOL_ASK;
$TOOL_APPOINMENT      = TOOL_APPOINTMENT;

$recent_activities = [];

$sql = "SELECT
            cip.c_id,
            cip.insert_user_id,
            cip.tool,
            cip.insert_date,
            cip.lastedit_date,
            cip.ref,
            cip.visibility
        FROM $table_course_item_property cip
        INNER JOIN $table_course_tool ct ON ct.c_id = cip.c_id AND ct.visibility = 1
        WHERE cip.tool IN ('$TOOL_REVIEW_PRACTICE', '$TOOL_ASK', '$TOOL_APPOINMENT') AND
              cip.lastedit_date > (SELECT tla.access_date FROM $table_track_lastaccess tla
                                   WHERE tla.c_id = cip.c_id AND tla.access_user_id = $user_id
                                   ORDER BY tla.access_date DESC
                                   LIMIT 1) AND
              cip.visibility = 1 AND
              cip.c_id = $c_id AND
              cip.insert_user_id = $user_id
        GROUP BY cip.tool
        ORDER BY cip.lastedit_date DESC";

$activities = Database::query($sql);

while($activity = Database::fetch_assoc($activities)) {
    switch ($activity['tool']) {
        case TOOL_REVIEW_PRACTICE:
            $path = $activity['ref'];

            $sql = "SELECT UPPER(clc.name) AS tool FROM c_lp_item cli
                    INNER JOIN c_lp cl ON cl.c_id = cli.c_id AND cl.iid = cli.lp_id
                    INNER JOIN c_lp_category clc ON clc.c_id = cl.c_id AND clc.iid = cl.category_id
                    WHERE cli.c_id = 1 AND cli.path = $path";

            if (Database::fetch_assoc(Database::query($sql))['tool'] == 'REPASAR') {
                $document = "SELECT * FROM $table_document cd WHERE cd.c_id = $c_id AND cd.id = $path";

                $recent_activities[] = [
                    'date' => $activity['lastedit_date'],
                    'tool' => 'review',
                    'icon' => 'fa fa-book',
                    'description' => Database::fetch_assoc(Database::query($document))['title']
                ];
            } else {
                $document = "SELECT * FROM $table_document cd WHERE cd.c_id = $c_id AND cd.id = $path";

                $recent_activities[] = [
                    'date' => $activity['lastedit_date'],
                    'tool' => 'practice',
                    'icon' => 'fa fa-edit',
                    'description' => Database::fetch_assoc(Database::query($document))['title']
                ];
            }
            break;
        case TOOL_ASK:
            // $insert_user_id = $new['insert_user_id'];
            // $post_id        = $new['ref'];

            // $sql = "SELECT * FROM $table_forum_post cfp
            //         WHERE cfp.c_id = $c_id AND cfp.post_id = $post_id AND cfp.poster_id = $insert_user_id";

            // $course_news[$c_id][] = [
            //     'date' => $new['lastedit_date'],
            //     'tool' => 'ask',
            //     'icon' => 'fa fa-question',
            //     'description' => Database::fetch_assoc(Database::query($sql))['post_text']
            // ];
            // break;
        case TOOL_APPOINTMENT:
            // $event_id = $new['ref'];

            // $sql = "SELECT * FROM $table_calendar_event cce WHERE cce.c_id = $c_id AND cce.id = $event_id";

            // $course_news[$c_id][] = [
            //     'date' => $new['lastedit_date'],
            //     'tool' => 'appointment',
            //     'icon' => 'fa fa-calendar',
            //     'description' => Database::fetch_assoc(Database::query($sql))['title']
            // ];
            break;
    }
}
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
