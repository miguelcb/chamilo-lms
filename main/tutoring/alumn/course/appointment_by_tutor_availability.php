<?php require_once '../../../inc/global.inc.php';

include_once '../../helpers.inc.php';

$user_id   = is_null($_GET['uid']) ? '' : $_GET['uid'];
$course_id = is_null($_GET['cid']) ? '' : $_GET['cid'];
$tutor_id  = is_null($_GET['tid']) ? '' : $_GET['tid'];

$sql = "SELECT pa.*, u.user_id, u.firstname, u.lastname FROM personal_agenda pa
        INNER JOIN user u ON u.user_id = pa.user
        WHERE pa.course = $course_id AND pa.user = $tutor_id
        ORDER BY pa.date";

$result = Database::query($sql);
?>

<div class="vlms-scrollable vlms-scrollable--y">
    <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
        <li class="vlms-title-divider">Disponibilidad</li>
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
                                <a class="vlms-truncate vlms-pr--medium" href="javascript:void(0);"><?php echo api_convert_and_format_date($row->date, '%A, %d de %B del %Y'); ?></a>
                                <button class="vlms-list__item__action pull-right" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php?appointmentType=0">
                                    <svg aria-hidden="true" class="vlms-list__item__action__icon" data-toggle="tooltip" data-container="body" data-placement="left" title="Reservar">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#add"></use>
                                    </svg>
                                    <span class="sr-only">Show More</span>
                                </button>
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
        <li class="vlms-list__item">Sin vacantes</li>
        <?php endif; ?>
    </ul>
</div>

<script>
    $('[data-toggle=tooltip]').boostrapTooltip();
</script>
