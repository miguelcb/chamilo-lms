<?php require_once '../../../inc/global.inc.php';

include_once '../../helpers.inc.php';

api_block_anonymous_users();

$user_id   = is_null($_GET['uid']) ? '' : $_GET['uid'];
$course_id = is_null($_GET['cid']) ? '' : $_GET['cid'];
$tutor_id  = is_null($_GET['tid']) ? '' : $_GET['tid'];

$sql = "SELECT pa.*, u.user_id, u.firstname, u.lastname FROM personal_agenda pa
        INNER JOIN user u ON u.user_id = pa.user
        WHERE pa.course = $course_id AND pa.user = $tutor_id AND (SELECT COUNT(*) FROM personal_agenda pax WHERE pax.course = $course_id AND pax.user = $user_id AND pax.parent_event_id = pa.id) < 1
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
                                <button type="button" class="vlms-list__item__action pull-right" data-appointment-id="<?php echo $row->id; ?>">
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
    $('[data-appointment-id]').click(function() {
        if (!window.confirm("¿Desea reservar cita?")) return;
        var $sup = $(this);
        $.ajax({
            url: Course.AJAX_URI + 'course/appointments_register.php',
            data: {
                uid: Course.USER_ID,
                aid: $sup.attr('data-appointment-id')
            }
        })
            .done(function() {
                // update appointments by date
                $.ajax({
                    url: Course.AJAX_URI + 'course/appointments_by_date_availability.php',
                    data: {
                        uid: Course.USER_ID,
                        cid: '<?php echo $course_id; ?>',
                        d: $('#appointments-by-date .vlms-datepicker .vlms-datepicker__month .active').attr('data-date')
                    }
                  })
                    .done(function(view) { $('#appointments-by-date-availability').html(view); });
                // update appointments by tutor
                $.ajax({
                    url: Course.AJAX_URI + 'course/appointments_by_tutor_availability.php',
                    data: {
                        uid: Course.USER_ID,
                        cid: '<?php echo $course_id; ?>',
                        tid: $('#appointment-tutor-picker .carousel-inner .active').attr('data-tutor-id')
                    }
                })
                    .done(function(view) { $('#appointments-by-tutor-availability').html(view); });
                // update appoinments
                $.ajax({
                    url: Course.AJAX_URI + 'course/appointments.php',
                    data: {
                        uid: Course.USER_ID,
                        cid: '<?php echo $course_id; ?>'
                    }
                })
                    .done(function(view) { $('#appointments').html(view); });
            });
    });
</script>
