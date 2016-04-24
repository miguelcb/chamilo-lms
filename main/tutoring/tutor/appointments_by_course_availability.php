<?php require_once '../../inc/global.inc.php';

require_once '../helpers.inc.php';

api_block_anonymous_users();

$user_id   = is_null($_GET['uid']) ? '' : $_GET['uid'];
$course_id      = is_null($_GET['cid']) ? '' : $_GET['cid'];

$sql = "SELECT * from personal_agenda pa
       inner join user u on u.id = pa.user
       inner join course c on c.id = pa.course
       where parent_event_id in (select id from personal_agenda
        where user =  $user_id)
        and date_format(now(), '%Y-%m')
        and course = $course_id
        order by pa.date";
        echo $sql;

$result = Database::query($sql);
?>
<div class="vlms-scrollable vlms-scrollable--y">
    <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
        <li class="vlms-title-divider">Citas</li>
        <?php if (Database::num_rows($result) > 0): ?>
            <?php while ($row = Database::fetch_object($result)): ?>
            <li class="vlms-list__item" data-schedule-id="<?php echo $row->id; ?>" data-user-id="<?php echo $user_id; ?>">
                <div class="vlms-media">
                    <div class="vlms-media__figure">
                        <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#<?php echo appointment_icon($row->text); ?>"></use>
                        </svg>
                    </div>
                    <div class="vlms-media__body">
                        <div class="vlms-media__body__title">
                            <a class="vlms-truncate vlms-pr--medium" href="javascript:void(0);" title="<?php echo api_convert_and_format_date($row->date, '%A, %d de %B del %Y'); ?>"><?php echo api_convert_and_format_date($row->date, '%A, %d de %B del %Y'); ?></a>
                        </div>
                        <div class="vlms-media__body__detail">
                            <ul class="vlms-list vlms-list--vertical vlms-text--small">
                                <li class="vlms-list__item"><?php echo api_strtolower(api_convert_and_format_date($row->date, '%H:%M %p').' - '.api_convert_and_format_date($row->enddate, '%H:%M %p')); ?></li>
                                <li class="vmls-list__item"><?php echo $row->title ?></li>
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
            <li class="vlms-list__item">Sin Citas</li>
        <?php endif; ?>
    </ul>
</div>

<script>
    
    $('#appointments-by-date-availability [data-appointment-id]').off().click(function(e) {
        //$('[data-toggle=tooltip]').boostrapTooltip('hide');
        if (!window.confirm("¿Desea reservar cita?")) return;
        var $sup = $(this);
        $.ajax({
            url: VLMS.AJAX_URI + 'course/appointments_register.php',
            data: {
                uid: VLMS.USER_ID,
                aid: $sup.attr('data-appointment-id')
            }
        })
            .done(function() {
                // update appointments by date
                $.ajax({
                    url: VLMS.AJAX_URI + 'course/appointments_by_date_availability.php',
                    data: {
                        uid: VLMS.USER_ID,
                        cid: VLMS.current.id,
                        d: $('#appointments-by-date .vlms-datepicker .vlms-datepicker__month .active').attr('data-date')
                    }
                  })
                    .done(function(view) { $('#appointments-by-date-availability').html(view); });
                // update appointments by tutor
                $.ajax({
                    url: VLMS.AJAX_URI + 'course/appointments_by_tutor_availability.php',
                    data: {
                        uid: VLMS.USER_ID,
                        cid: VLMS.current.id,
                        tid: $('#appointment-tutor-picker .carousel-inner .active').attr('data-tutor-id')
                    }
                })
                    .done(function(view) { $('#appointments-by-tutor-availability').html(view); });
                // update appoinments
                $.ajax({
                    url: VLMS.AJAX_URI + 'course/appointments.php',
                    data: {
                        uid: VLMS.USER_ID,
                        cid: VLMS.current.id
                    }
                })
                    .done(function(view) { $('#appointments').html(view); });
            });
    });
</script>
