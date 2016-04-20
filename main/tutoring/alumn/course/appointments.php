<?php require_once '../../../inc/global.inc.php';

require_once '../../helpers.inc.php';

api_block_anonymous_users();

$user_id   = is_null($_GET['uid']) ? '' : $_GET['uid'];
$course_id = is_null($_GET['cid']) ? '' : $_GET['cid'];

$sql = "SELECT pa.*, u.firstname, u.lastname FROM personal_agenda pa
        INNER JOIN user u ON u.user_id = (SELECT user FROM personal_agenda pax WHERE pax.id = pa.parent_event_id)
        WHERE pa.course = $course_id AND pa.user = $user_id
        ORDER BY pa.date";

$result = Database::query($sql);
?>

<div class="vlms">
    <div class="vlms-block" style="padding: 0;">
        <div class="vlms-scrollable vlms-scrollable--y">
            <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                <li class="vlms-title-divider">Mis citas en el curso</li>
                <?php if (Database::num_rows($result) > 0): ?>
                    <?php while ($row = Database::fetch_object($result)): ?>
                    <li class="vlms-list__item">
                        <div class="vlms-media">
                            <div class="vlms-media__figure">
                                <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#<?php echo appointment_icon($row->text); ?>"></use>
                                </svg>
                            </div>
                            <div class="vlms-media__body">
                                <div class="vlms-media__body__title">
                                    <a href="javascript:void(0);"><?php echo api_convert_and_format_date($row->date, '%A, %d de %B del %Y'); ?></a>
                                </div>
                                <div class="vlms-media__body__detail">
                                    <ul class="vlms-list vlms-list--vertical vlms-text--small">
                                        <li class="vlms-list__item"><?php echo api_strtolower(api_convert_and_format_date($row->date, '%H:%M %p').' - '.api_convert_and_format_date($row->enddate, '%H:%M %p')); ?></li>
                                        <li class="vlms-list__item">
                                            <strong><?php echo $row->lastname.', '.$row->firstname; ?></strong>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <?php endwhile; ?>
                <?php else: ?>
                    <li class="vlms-list__item">No tengo citas</li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>
