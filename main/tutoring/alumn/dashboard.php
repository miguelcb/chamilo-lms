<?php
/* For licensing terms, see /license.txt */
/**
 * Shows who is online in a specific session
 * @package chamilo.main
 */

include_once '../../inc/global.inc.php';
api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

$user_id = api_get_user_id();

$table_course      = Database::get_main_table(TABLE_MAIN_COURSE);
$table_course_user = Database::get_main_table(TABLE_MAIN_COURSE_USER);

$sql = "SELECT c.* FROM $table_course c
        INNER JOIN $table_course_user cu ON cu.c_id = c.id AND cu.user_id = $user_id
        ORDER BY cu.sort ASC";

$courses    = Database::query($sql);
$indicators = Database::query($sql);
?>

<?php Display::display_header('Dashboard'); ?>
<section class="row" id="courses-tutoring-wrapper">
    <div id="courses-tutoring" class="carousel" data-ride="carousel" data-interval="false">
      <ol class="carousel-indicators">
        <?php $counter = 0; ?>
        <?php while($indicator = Database::fetch_assoc($indicators)): ?>
            <li data-target="#courses-tutoring" data-slide-to="<?php echo $counter; ?>" class="<?php echo $counter == 0 ? 'active' : ''; ++$counter; ?>" data-toggle="tooltip" data-container="body" title="<?php echo $indicator['title']; ?>"></li>
        <?php endwhile; ?>
        <li class="fa fa-plus" data-target="#courses-tutoring" data-slide-to="<?php echo $counter; ?>" data-toggle="tooltip" data-container="body" title="Suscribirte a un curso"></li>
      </ol>

      <div class="carousel-inner" role="listbox">
        <?php $counter = 0; ?>
        <?php while($course = Database::fetch_assoc($courses)): ?>
            <div class="course-tutoring item <?php echo $counter == 0 ? 'active' : ''; ++$counter; ?>" data-course-id="<?php echo $course['id']; ?>">
                <div class="container">
                    <h1 class="text-center"><?php echo $course['title']; ?></h1>
                    <p><?php echo $course['description']; ?></p>
                    <ul class="list-unstyled text-center course-tutoring__nav">
                        <li style="display: inline-block;">
                            <a href="javascript:void(0)" class="fa fa-newspaper-o fa-icon-size fa-icon-size--medium fa-rounded fa-rounded--lg bg-violet" data-modal="ajax-modal" data-target="#recent-activities-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/recent_activities.php?cid=<?php echo $course['id']; ?>" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Actividades recientes"></a>
                        </li>
                        <li style="display: inline-block;">
                            <a href="javascript:void(0)" class="fa fa-cog fa-icon-size fa-icon-size--medium fa-rounded fa-rounded--lg bg-orange" data-modal="ajax-modal" data-target="#alert-settings-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/alert_settings.php?cid=<?php echo $course['id']; ?>" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Configuración de alertas"></a>
                        </li>
                        <li style="display: inline-block;">
                            <a href="javascript:Course.unsubscribe('<?php echo $course['code']; ?>');" class="fa fa-sign-out fa-icon-size fa-icon-size--medium fa-rounded fa-rounded--lg" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Cancelar suscripción"></a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
        <div class="course-tutoring item">
            <div class="container" style="position: relative; height: 100%;">
                <h1 class="text-center">Suscribite a una tutoría</h1>
                <p class="text-center">Busca entre las tutorias de cursos disponibles y suscribete al de mayor interés.</p>
                <div style="position: absolute; top: 62.5%; left: 50%; transform: translate3d(-50%, -50%, 0);">
                    <span class="fa fa-plus fa-icon-size" role="button" data-toggle="ajax-modal" data-target="#subscribe-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/subscribe.php"></span>
                </div>
            </div>
        </div>
      </div>

      <a class="left carousel-control" href="#courses-tutoring" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#courses-tutoring" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
    <ul id="nav-tools" class="list-unstyled text-center fa-icon-size fa-icon-size--medium">
        <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Preguntar">
            <a href="#ask" class="fa fa-question fa-rounded"></a>
        </li>
        <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Sacar cita">
            <a href="#appointment" class="fa fa-calendar fa-rounded"></a>
        </li>
        <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Repasar">
            <a href="#review" class="fa fa-book fa-rounded"></a>
        </li>
        <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Practicar">
            <a href="#practice" class="fa fa-edit fa-rounded"></a>
        </li>
    </ul>
</section>
<section id="ask" class="row course-tool">
    <header class="text-center course-tool__header">
        <span class="fa fa-question fa-rounded fa-icon-size fa-icon-size--medium" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Preguntar" data-content="And here's some amazing content. It's very engaging. Right?"></span>
        <div class="text-uppercase">preguntar</div>
    </header>
    <section class="container">
        <div class="row" style="padding: 32px 0;">
            <div class="col-md-6">
                <form id="form-ask">
                    <div class="form-group">
                        <textarea name="question" id="question" rows="10" class="form-control" placeholder="Escribir pregunta"></textarea>
                    </div>
                    <div class="clearfix">
                        <div class="pull-left">
                            <div class="btn-toolbar" role="toolbar" aria-label="..." style="margin: 0;">
                                <div class="btn-group" role="group" aria-label="..." style="margin-left: 0;">
                                    <select name="" id="" class="form-control">
                                        <option value="asda">Sin preferencia (Tutor)</option>
                                        <option value="asda">Jane Doe (Tutor)</option>
                                        <option value="asda">John Doe (Tutor)</option>
                                        <option value="asda">Batman (Tutor)</option>
                                   </select>
                                </div>
                                <div class="btn-group" role="group" data-toggle="buttons">
                                    <label class="btn btn-default active fa fa-eye" title="Público">
                                        <input type="checkbox" autocomplete="off" checked>
                                    </label>
                                </div>
                                <div class="btn-group" role="group">
                                    <label class="btn btn-default fa fa-paperclip" title="Archivos adjuntos">
                                        <input type="file" style="display: none;">
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="pull-right">
                            <button type="button" class="btn btn-success">Preguntar</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6" id="my-questions">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="m0">Mis preguntas</h4>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-info m0">No hay preguntas</div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                Ve al <a href="javascript:void(0);" id="repository-questions-link" data-toggle="ajax-modal" data-target="#repository-questions-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/repository_questions.php?cid=1">repositorio de preguntas</a> para que validez que no haya sido hecha antes, y ya tenga una respuesta
            </div>
        </div>
    </section>
</section>
<section style="height: 756px;" id="appointment" class="row course-tool">
    <header class="text-center course-tool__header">
        <span class="fa fa-calendar fa-rounded fa-icon-size fa-icon-size--medium" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Sacar cita" data-content="And here's some amazing content. It's very engaging. Right?"></span>
        <div class="text-uppercase">sacar cita</div>
    </header>
</section>
<section style="height: 756px;" id="review" class="row course-tool">
    <header class="text-center course-tool__header">
        <span class="fa fa-book fa-rounded fa-icon-size fa-icon-size--medium" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Repasar" data-content="And here's some amazing content. It's very engaging. Right?"></span>
        <div class="text-uppercase">repasar</div>
    </header>
</section>
<section style="height: 756px;" id="practice" class="row course-tool">
    <header class="text-center course-tool__header">
        <span class="fa fa-edit fa-rounded fa-icon-size fa-icon-size--medium" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Practicar" data-content="And here's some amazing content. It's very engaging. Right?"></span>
        <div class="text-uppercase">practicar</div>
    </header>
</section>

<a href="#" title="Ir arriba" id="hook-top" class="fa fa-arrow-up"></a>

<script>
    $.fn.boostrapTooltip = $.fn.tooltip.noConflict();
    $.fn.boostrapPopover = $.fn.popover.noConflict();
    $('[data-toggle=tooltip]').boostrapTooltip();
    $('[data-toggle=popover]').boostrapPopover();

    window.Course = (function($, $w) {
        var _init, _unsubscribe, _switchTo;
        _init = function() {
            Course.switchTo($('.course-tutoring.active').attr('data-course-id') || 0);
            $('#courses-tutoring').on('slid.bs.carousel', function() {
                Course.switchTo($('.course-tutoring.active').attr('data-course-id') || 0);
            });
        };
        _unsubscribe = function(courseCode) {
            if (!$w.confirm("Cancelar suscripción")) return;
            $.ajax({
                url: '<?php echo api_get_path(WEB_CODE_PATH); ?>auth/courses.php',
                data: {
                    action: 'unsubscribe',
                    sec_token: '<?php echo Security::get_existing_token(); ?>',
                    unsubscribe: courseCode
                }
            })
                .done(function(view) {
                    $.ajax({ url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/subscribe.php' })
                        .done(function(view) { $w.location.reload(); });
                });
        };
        _switchTo = function(courseID) {
            // update my questions list
            $.ajax({
                url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/my_questions_review.php',
                data: { cid: courseID }
            })
                .done(function(view) {
                    $('#my-questions').html(view);
                });
            // update link repository questions
            $('#repository-questions-link').attr('data-source', '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/repository_questions.php?cid=' + courseID);
            // update link my questions
            $('#my-questions-link').attr('data-source', '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/my_questions.php?cid=' + courseID);
            // update tutors
        };
        return {
            init: _init,
            unsubscribe: _unsubscribe,
            switchTo: _switchTo
        };
    })(jQuery, window);

    Course.init();
</script>

<?php Display::display_footer(); ?>
