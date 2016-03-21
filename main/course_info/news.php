<?php
/* For licensing terms, see /license.txt */

require_once '../inc/global.inc.php';

//api_protect_course_script(true);
api_block_anonymous_users();

$user_id                    = api_get_user_id();
$table_course_user          = Database::get_main_table(TABLE_MAIN_COURSE_USER);
$table_course_item_property = Database::get_course_table(TABLE_ITEM_PROPERTY);
$table_course_tool          = Database::get_course_table(TABLE_TOOL_LIST);
$table_forum_post           = Database::get_course_table(TABLE_FORUM_POST);
$table_calendar_event       = Database::get_course_table(TABLE_AGENDA);
$table_document             = Database::get_course_table(TABLE_DOCUMENT);
// TEMPORAL SOLUTION WHEN MIGRATE TO NEW TOOLS CHANGES THE TOOLS NAMES
require_once '../tutoring/constants.inc.php';
$TOOL_REVIEW_PRACTICE = TOOL_REVIEW_PRACTICE;
$TOOL_ASK             = TOOL_ASK;
$TOOL_APPOINMENT      = TOOL_APPOINTMENT;
$INTERVAL_DAYS_NEWS   = INTERVAL_DAYS_NEWS;

$course_news = [];
$sql         = "SELECT * FROM $table_course_user WHERE user_id = $user_id";
$courses     = Database::query($sql);

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

while($course = Database::fetch_assoc($courses)) {
    $c_id = $course['c_id'];

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
                  cip.lastedit_date > (SELECT tca.logout_course_date FROM track_e_course_access tca
                                      WHERE tca.c_id = cip.c_id AND user_id = $user_id
                                      ORDER BY logout_course_date DESC
                                      LIMIT 1) AND
                  cip.visibility = 1 AND
                  cip.c_id = $c_id
            GROUP BY cip.tool
            ORDER BY cip.lastedit_date DESC";

    $news = Database::query($sql);

    while($new = Database::fetch_assoc($news)) {
        switch ($new['tool']) {
            case TOOL_REVIEW_PRACTICE:
                $path = $new['ref'];

                $sql = "SELECT UPPER(clc.name) AS tool FROM c_lp_item cli
                        INNER JOIN c_lp cl ON cl.c_id = cli.c_id AND cl.iid = cli.lp_id
                        INNER JOIN c_lp_category clc ON clc.c_id = cl.c_id AND clc.iid = cl.category_id
                        WHERE cli.c_id = 1 AND cli.path = $path";

                if (Database::fetch_assoc(Database::query($sql))['tool'] == 'REPASAR') {
                    $document = "SELECT * FROM $table_document cd WHERE cd.c_id = $c_id AND cd.id = $path";

                    $course_news[$c_id][] = [
                        'date' => $new['lastedit_date'],
                        'tool' => 'review',
                        'icon' => 'fa fa-book',
                        'description' => Database::fetch_assoc(Database::query($document))['title']
                    ];
                } else {
                    $document = "SELECT * FROM $table_document cd WHERE cd.c_id = $c_id AND cd.id = $path";

                    $course_news[$c_id][] = [
                        'date' => $new['lastedit_date'],
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
}
?>
<?php if (count($course_news) > 0): ?>
<ul class="list-group">
    <?php foreach ($course_news as $c_id => $new): ?>
    <li class="list-group-item active"><?php echo api_get_course_info_by_id($c_id)['title']; ?></li>
    <?php foreach($new as $tool): ?>
    <li class="list-group-item">
        <h4 class="list-group-item-heading clearfix">
            <span class="<?php echo $tool['icon']; ?> pull-left"></span>
            <span class="pull-left">Se ha agregado un nuevo material</span>
            <div class="pull-right small" style="padding: 0;"><?php echo api_convert_and_format_date($tool['date'], '%b %d') ?>
        </h4>
        <p class="list-group-item-text"><?php echo $tool['description']; ?></p><?
    </li>
    <?php endforeach; ?>
    <?php endforeach; ?>
</ul>
<?php else: ?>
<div class="alert alert-info">No hay ninguna novedad</div>
<?php endif; ?>
