<?php require_once '../../inc/global.inc.php';

include_once '../helpers.inc.php';

api_block_anonymous_users();

$user_id   = api_get_user_id();

$courses = [];

$sql = "SELECT * from course_rel_user cru
inner join course c on c.id = cru.c_id
where user_id = $user_id";

$result = Database::query($sql);

while ($row = Database::fetch_assoc($result)) {    
    $courses[]      = $row;
}
?>

<div class="vlms">
    <div class="vlms-title-divider">Citas por curso</div>
    <?php if (Database::num_rows($result) > 0): ?>
    <div id="appointment-course-picker" class="carousel slide" data-ride="carousel" data-interval="false">
        <span class="carousel-counter vlms-badge vlms-badge--inverse"><?php echo Database::num_rows($result) > 1 ? Database::num_rows($result).' courses' : '1 course'; ?></span>
        <ul class="carousel-indicators">
            <?php for ($i = 0; $i < count($courses); $i++): ?>
            <li class="<?php echo $i == 0 ? 'active' : ''; ?>" data-target="#appointment-course-picker" data-slide-to="<?php echo $i; ?>"></li>
            <?php endfor; ?>
        </ul>
        <div class="carousel-inner" role="listbox">
            <?php for ($i = 0; $i < count($courses); $i++): ?>
            <div class="item <?php echo $i == 0 ? 'active' : ''; ?>" data-user-id="<?php echo $user_id; ?>" data-course-id="<?php echo $courses[$i]['c_id']; ?>">                
                <div class="carousel-caption">
                    <h4 style="font-size: 20px;"><?php echo $courses[$i]['title']; ?></h4>
                </div>
            </div>
            <?php endfor; ?>
        </div>

        <a class="left carousel-control" href="javascript:void(0);" data-target="#appointment-course-picker" role="button" data-slide="prev">
            <svg aria-hidden="true" style="fill: #555; height: 100%;">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chevronleft"></use>
            </svg>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="javascript:void(0);" data-target="#appointment-course-picker" role="button" data-slide="next">
            <svg aria-hidden="true" style="fill: #555; height: 100%;">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chevronright"></use>
            </svg>
            <span class="sr-only">Next</span>
        </a>
    </div>
    <?php else: ?>
        <div id="appointment-course-picker" class="carousel">
            <div class="carousel-inner">
                <div class="item active">
                    <img src="<?php echo UserManager::getUserPicture(0, USER_IMAGE_SIZE_ORIGINAL); ?>" alt="" class="img-circle center-block">
                    <div class="carousel-caption">
                        <h3>No hay cursos</h3>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <hr style="margin-top: 16px; margin-bottom: 11px;">

    <ul class="vlms-list vlms-list--horizontal" style="  justify-content: center;">
        <li class="vlms-list__item text-center" style="padding: 8px;">
            <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#groups"></use>
            </svg>
            <span class="vlms-badge vlms-badge--inverse" style="display: block;">Cita presencial</span>
        </li>
        <li class="vlms-list__item text-center" style="padding: 8px;">
            <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chat"></use>
            </svg>
            <span class="vlms-badge vlms-badge--inverse" style="display: block;">Chat virtual</span>
        </li>
    </ul>

    <div class="vlms-block" style="height: 300px; padding: 0; margin-top: 8px;" id="appointments-by-course-availability">
        <div class="vlms-scrollable vlms-scrollable--y">
            <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                <li class="vlms-title-divider">Disponibilidad</li>
                <li class="vlms-list__item">Sin vacantes</li>
            </ul>
        </div>
    </div>
</div>

<script>
    (function() {
        var $e = $('#appointment-course-picker');
        if (!$e.find('[data-course-id]').length) return;
        $.ajax({
            async: false,
            url: VLMS.MAIN_URI + 'tutoring/tutor/appointments_by_course_availability.php',
            data: {
                cid: $e.find('.item.active').attr('data-course-id'),
                uid: $e.find('.item.active').attr('data-user-id')
            }
        })
            .done(function(view) { $('#appointments-by-course-availability').html(view); });
    })();

    $('#appointment-course-picker').off().on('slid.bs.carousel', function (e) {
        var $e = $(e.relatedTarget);
        $.ajax({
            url: VLMS.MAIN_URI + 'tutoring/tutor/appointments_by_course_availability.php',
            data: {
                cid: $e.attr('data-course-id'),
                uid: $e.attr('data-user-id')
            }
        })
            .done(function(view) { $('#appointments-by-course-availability').html(view); });
    });
</script>
