<?php require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

$user_id                    = api_get_user_id();
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

$course_news = [];
$sql         = "SELECT * FROM $table_course_user WHERE user_id = $user_id";
$courses     = Database::query($sql);

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
                  cip.lastedit_date > (SELECT tla.access_date FROM $table_track_lastaccess tla
                                       WHERE tla.c_id = cip.c_id AND tla.access_user_id = $user_id
                                       ORDER BY tla.access_date DESC
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
<div class="vlms">
    <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
        <?php foreach ($course_news as $c_id => $new): ?>
        <li class="vlms-title-divider"><?php echo api_get_course_info_by_id($c_id)['title']; ?></li>
            <?php foreach($new as $row): ?>
            <li class="vlms-list__item">
                <div class="vlms-media">
                    <div class="vlms-media__figure">
                        <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                        </svg>
                    </div>
                    <div class="vlms-media__body">
                        <div class="vlms-media__body__title clearfix">
                            <a class="pull-left" href="javascript:void(0);">
                                <?php if ($row['tool'] == 'practice' || $row['tool'] == 'review'): ?>
                                    Se ha agregado nuevo material para <?php echo $row['tool'] == 'practice' ? 'practicar' : 'repasar'; ?>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div class="vlms-media__body__detail">
                            <ul class="vlms-list vlms-list--vertical vlms-text--small">
                                <li class="vlms-list__item"><?php echo date_to_str_ago($row['date']); ?></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach; ?>
        <?php endforeach; ?>
    </ul>
</div>
<?php else: ?>
<div class="alert alert-info">No hay ninguna novedad</div>
<?php endif; ?>
