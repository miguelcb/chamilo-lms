<?php require_once '../../inc/global.inc.php';

require_once '../helpers.inc.php';

api_block_anonymous_users();

$user_id = api_get_user_id();

$table_course      = Database::get_main_table(TABLE_MAIN_COURSE);
$table_course_user = Database::get_main_table(TABLE_MAIN_COURSE_USER);

$sql = "SELECT c.*, (SELECT COALESCE(ff.forum_id, 0) FROM c_forum_forum ff WHERE ff.c_id = c.id LIMIT 1) forum_id FROM $table_course c
        INNER JOIN $table_course_user cu ON cu.c_id = c.id AND cu.user_id = $user_id
        ORDER BY cu.sort ASC";

$courses    = Database::query($sql);
$indicators = Database::query($sql);
?>

<?php Display::display_header('Dashboard'); ?>
<section class="row" id="courses-tutoring-wrapper">
    <div id="courses-tutoring" class="container carousel" data-ride="carousel" data-interval="false">
      <ol class="carousel-indicators">
        <?php $counter = 0; ?>
        <?php while($indicator = Database::fetch_assoc($indicators)): ?>
            <li data-target="#courses-tutoring" data-slide-to="<?php echo $counter; ?>" class="<?php echo $counter == 0 ? 'active' : ''; ++$counter; ?>" data-toggle="tooltip" data-container="body" title="<?php echo $indicator['title']; ?>"></li>
        <?php endwhile; ?>
        <li class="fa fa-plus" data-target="#courses-tutoring" data-slide-to="<?php echo $counter; ?>" data-toggle="tooltip" data-container="body" title="Suscríbete a tutoría"></li>
      </ol>

      <div class="carousel-inner" role="listbox">
        <?php $counter = 0; ?>
        <?php while($course = Database::fetch_assoc($courses)): ?>
            <div class="course-tutoring item <?php echo $counter == 0 ? 'active' : ''; ++$counter; ?>" data-course-id="<?php echo $course['id']; ?>" data-course-code="<?php echo $course['code']; ?>" data-course-forum-id="<?php echo $course['forum_id']; ?>">
                <div class="container-fluid" style="padding: 0 64px;">
                    <h1 class="text-center" style="margin-bottom: 32px;"><?php echo $course['title']; ?></h1>
                    <p><?php echo $course['description']; ?></p>
                    <ul class="list-unstyled text-right course-tutoring__nav">
                        <li style="display: inline-block;">
                            <a href="javascript:void(0)" class="fa fa-newspaper-o fa-icon-size fa-icon-size--medium" style="color: #555; margin: 8px;" data-modal="ajax-modal" data-target="#recent-activities-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/recent_activities.php?cid=<?php echo $course['id']; ?>" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Actividades recientes"></a>
                        </li>
                        <li style="display: inline-block;">
                            <a href="javascript:void(0)" class="fa fa-cog fa-icon-size fa-icon-size--medium" style="color: #555; margin: 8px;" data-modal="ajax-modal" data-target="#alert-settings-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/alert_settings.php?cid=<?php echo $course['id']; ?>" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Alertas del curso"></a>
                        </li>
                        <li style="display: inline-block;">
                            <a href="javascript:Course.unsubscribe('<?php echo $course['code']; ?>');" class="fa fa-times fa-icon-size fa-icon-size--medium" style="color: #555; margin: 8px;" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Cancelar suscripción"></a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
        <div class="course-tutoring item">
            <div class="container" style="position: relative; height: 100%;">
                <h1 class="text-center" style="margin-bottom: 32px;">Suscríbete a tutoría</h1>
                <p class="text-center">Busca entre las tutorias de cursos disponibles y suscribete al de mayor interés.</p>
                <div style="position: absolute; top: 62.5%; left: 50%; transform: translate3d(-50%, -50%, 0);">
                    <span class="fa fa-plus fa-icon-size" role="button" data-toggle="ajax-modal" data-target="#subscribe-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/subscribe.php"></span>
                </div>
            </div>
        </div>
      </div>

      <a class="left carousel-control" href="javascript:void(0);" data-target="#courses-tutoring" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Curso anterior"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="javascript:void(0);" data-target="#courses-tutoring" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Siguiente curso"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
    <div>
        <h3 class="text-center hidden">Servicios del tutor</h3>
        <ul id="nav-tools" class="list-unstyled text-center fa-icon-size">
            <li style="font-size: 16px; font-weight: bold; margin-right: 16px;">Servicios del Tutor</li>
            <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Preguntar">
                <a href="#ask" class="fa fa-question fa-rounded fa-rounded--lg vlms-bgc--palette-1"></a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Reunirte con tu tutor">
                <a href="#appointment" class="fa fa-calendar fa-rounded fa-rounded--lg vlms-bgc--palette-1"></a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Repasar">
                <a href="#review" class="fa fa-book fa-rounded fa-rounded--lg vlms-bgc--palette-1"></a>
            </li>
            <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Practicar">
                <a href="#practice" class="fa fa-edit fa-rounded fa-rounded--lg vlms-bgc--palette-1"></a>
            </li>
        </ul>
    </div>
</section>
<section class="course-tools">
    <!-- ask -->
    <section id="ask" class="row course-tool">
        <header class="text-center course-tool__header">
            <span class="fa fa-question fa-rounded fa-icon-size fa-icon-size--medium vlms-bgc--palette-1" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Preguntar" data-content="Resuelve tus dudas realizando preguntas o visitando el repositorio de preguntas."></span>
            <div class="text-uppercase">preguntar</div>
        </header>
        <section class="container">
            <div class="row" style="padding: 32px 0;">
                <div class="col-md-6">
                    <form class="vlms" id="form-ask" action="">
                        <input type="hidden" name="post_title" value="Pregunta para responder">
                        <input type="hidden" name="_qf__thread">
                        <input type="hidden" name="forum_id" value="0">
                        <input type="hidden" name="thread_id" value="0">
                        <input type="hidden" name="gradebook" value="0">
                        <input type="hidden" name="MAX_FILE_SIZE" value="268435456">
                        <input type="hidden" name="sec_token" value="<?php echo Security::get_token(); ?>">
                        <input type="hidden" name="post_notification" value="1">
                        <input type="hidden" name="calification_notebook_title" value="Índice de utilidad">
                        <input type="hidden" name="numeric_calification" value="5">
                        <div class="form-group">
                            <span class="help-block">Te recomendamos que revises el <a href="javascript:void(0);" id="repository-questions-link" data-toggle="ajax-modal" data-target="#repository-questions-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/repository_questions.php?cid=1">repositorio de preguntas</a> para validar que tu consulta no se haya realizado antes.</span>
                            <textarea name="post_text" rows="10" class="form-control" placeholder="Escribe tu pregunta aquí"></textarea>
                        </div>
                        <div id="wrapper-files"></div>
                        <div class="clearfix">
                            <div class="pull-left">
                                <div class="form-group" style="margin-top: 24px;">
                                    <span class="help-block">¿Quieres que tu pregunta sea respondida por un tutor en especial?</span>
                                    <div class="btn-toolbar" role="toolbar" aria-label="..." style="margin: 0;">
                                        <div class="btn-group" role="group" aria-label="..." style="margin-left: 0;">
                                            <select name="tutor_id" id="ask-tutors" class="form-control">
                                                <option value="0">Sin preferencia (Tutor)</option>
                                           </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="help-block">¿Quieres que tu pregunta sea compartida en el repositorio de preguntas?</span>
                                    <input type="radio" name="public" id="public1" checked value="1"> Sí
                                    <input type="radio" name="public" id="public2" value="0"> No
                                </div>
                            </div>
                            <div class="pull-right">
                                <label class="btn btn-default fa fa-paperclip" title="Archivos adjuntos">
                                    <input name="user_upload" type="file" style="display: none;">
                                </label>
                            </div>
                            <div class="text-center">
                                <input type="hidden" name="SubmitPost" value="Preguntar">
                                <button type="button" class="btn btn-success">Preguntar</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="col-md-6" id="my-questions">
                    <div class="vlms">
                        <div class="vlms-block">
                            <div class="vlms-scrollable vlms-scrollable--y">
                                <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                                    <li class="vlms-title-divider">Mis preguntas en el curso</li>
                                    <li class="vlms-list__item">
                                        <div class="vlms-media__body">
                                            <div class="vlms-media__body__title">No tienes preguntas en el curso</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <!-- appointment -->
    <section id="appointment" class="row course-tool">
        <header class="text-center course-tool__header">
            <span class="fa fa-calendar fa-rounded fa-icon-size fa-icon-size--medium vlms-bgc--palette-1" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Reunirte con tu tutor" data-content="Si deseas un asesoría mas personalizada, reserva una cita presencial o virtual con tu tutor."></span>
            <div class="text-uppercase">Reunirte con tu tutor</div>
        </header>
        <section class="container">
            <div class="row" style="padding: 32px 0;">
                <!-- reserva por fechas -->
                <div class="col-md-4" id="appointments-by-date">
                  <div class="vlms">
                    <div class="vlms-title-divider">Reserva por fecha</div>
                    <?php calendar_appointment([], 'style="margin-top: 24px;"'); ?>

                    <div class="text-center" style="margin-top: 16px;">
                        <span class="vlms-badge vlms-bgc--sun-flower">presencial</span>
                        <span class="vlms-badge vlms-bgc--peter-river">virtual</span>
                        <span class="vlms-badge vlms-bgc--emerald">presencial/virtual</span>
                    </div>

                    <div class="vlms-block" style="height: 300px; padding: 0; margin-top: 16px;" id="appointments-by-date-availability">
                        <div class="vlms-scrollable vlms-scrollable--y">
                            <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                                <li class="vlms-title-divider">Disponibilidad</li>
                                <li class="vlms-list__item">Sin vacantes</li>
                            </ul>
                        </div>
                    </div>
                  </div>
                </div>
                <!-- reserva por tutor -->
                <div class="col-md-4" id="appointments-by-tutor">
                    <div class="vlms">
                        <div class="vlms-title-divider">Reserva por tutor</div>

                        <div id="appointment-tutor-picker" class="carousel">
                            <div class="carousel-inner">
                                <div class="item active">
                                    <img src="<?php echo UserManager::getUserPicture(0, USER_IMAGE_SIZE_ORIGINAL); ?>" alt="" class="img-circle center-block">
                                    <div class="carousel-caption">
                                        <h3>No hay tutores</h3>
                                    </div>
                                </div>
                            </div>
                        </div>

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

                        <div class="vlms-block" style="height: 300px; padding: 0; margin-top: 8px;" id="appointments-by-tutor-availability">
                            <div class="vlms-scrollable vlms-scrollable--y">
                                <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                                    <li class="vlms-title-divider">Disponibilidad</li>
                                    <li class="vlms-list__item">Sin vacantes</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- mis citas -->
                <div class="col-md-4" id="appointments">
                    <div class="vlms">
                        <div class="vlms-block" style="height: 695px; padding: 0;">
                            <div class="vlms-scrollable vlms-scrollable--y">
                                <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                                    <li class="vlms-title-divider">Mis citas en el curso</li>
                                    <li class="vlms-list__item">No tengo citas</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </section>
    <!-- review -->
    <section id="review" class="row course-tool">
        <header class="text-center course-tool__header">
            <span class="fa fa-book fa-rounded fa-icon-size fa-icon-size--medium vlms-bgc--palette-1" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Repasar" data-content="Mejora tus habilidades en el curso revisando nuevos materiales para repasar."></span>
            <div class="text-uppercase">repasar</div>
        </header>
        <section class="container">
            <div class="row" style="padding: 32px 0;">
                <div class="col-md-12 course-tool__body">
                    <div class="alert alert-info">No hay materiales</div>
                </div>
            </div>
        </section>
    </section>
    <!-- practice -->
    <section id="practice" class="row course-tool last-child">
        <header class="text-center course-tool__header">
            <span class="fa fa-edit fa-rounded fa-icon-size fa-icon-size--medium vlms-bgc--palette-1" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Practicar" data-content="Pon a prueba tus conocimientos y tu avance, con estos materiales para practicar."></span>
            <div class="text-uppercase">practicar</div>
        </header>
        <section class="container">
            <div class="row" style="padding: 32px 0;">
                <div class="col-md-12 course-tool__body">
                    <div class="alert alert-info">No hay materiales</div>
                </div>
            </div>
        </section>
    </section>
</section>

<a href="#" title="Ir arriba" id="hook-top" class="fa fa-arrow-up"></a>

<script>
    $('[data-toggle=tooltip]').boostrapTooltip();
    $('[data-toggle=popover]').boostrapPopover();

    window.Course = (function($) {
        var _init,
            _unsubscribe,
            _switchTo,
            _tools,
            _hideTools,
            _showTool;
        _init = function() {
            // GET TOOLS BY COURSE
            $.ajax({ async: false, url: VLMS.URI + 'course/tools.php' })
                .done(function(tools) {
                    VLMS.current.toolsCourse = JSON.parse(tools);
                });
            // SWITCH TO CURRENT COURSE
            Course.switchTo($('.course-tutoring.active').attr('data-course-id') || 0);

            $('#courses-tutoring').on('slid.bs.carousel', function(e) {
                Course.switchTo($(e.relatedTarget).attr('data-course-id') || 0);
                $('[data-toggle=tooltip]').boostrapTooltip();
                $('[data-toggle=popover]').boostrapPopover();
            });
        };
        _getTools = function(courseID) {
            return VLMS.current.toolsCourse[courseID];
        };
        _hideTools = function(courseID) {
            var toolsAvailable = this.getTools(courseID);
            var hide = [];
            var i = 0, l = VLMS.TOOLS.length;
            for (; i < l; i++) {
                $('#' + VLMS.TOOLS[i])[
                   toolsAvailable.indexOf(VLMS.TOOLS[i]) == -1 ? 'addClass' : 'removeClass'
                ]('hidden');
                $('#nav-tools').find('[href=#' + VLMS.TOOLS[i] + ']').parent()[
                    toolsAvailable.indexOf(VLMS.TOOLS[i]) == -1 ? 'hide' : 'show'
                ]();
            }
            $('.course-tool').removeClass('last-child');
            $('.course-tool:not(.hidden)').last().addClass('last-child');
        };
        _showTool = function(tool, courseID) {
            if (this.toolsByCourse(courseID).indexOf(tool) == -1) return;
            this[tool + 'Tool'](courseID);
        };
        _askTool = function(courseID) {
            // UPDATE ACTION
            $('#form-ask').attr('action', VLMS.MAIN_URI + 'forum/newthread.php?forum=' + VLMS.current.forumID + '&gradebook=0&thread=0&post=0&cidReq=' + VLMS.current.code + '&id_session=0&gidReq=0&origin=');
            // UPDATE FORUM ID
            $('#form-ask [name=forum_id]').val(VLMS.current.forumID);
            // UPDATE TUTORS
            $.ajax({
                url: VLMS.URI + 'course/ask_tutors.php',
                data: { cid: courseID }
            })
                .done(function(view) { $('#ask-tutors').html(view); });
            // UPDATE MY QUESTIONS LIST
            $.ajax({
                url: VLMS.URI + 'course/my_questions_review.php',
                data: { cid: courseID }
            })
                .done(function(view) { $('#my-questions').html(view); });
            // UPDATE LINK REPOSITORY QUESTIONS
            $('#repository-questions-link').attr('data-source', VLMS.URI + 'course/repository_questions.php?cid=' + courseID);
            // UPDATE LINK MY QUESTIONS
            $('#my-questions-link').attr('data-source', VLMS.URI + 'course/my_questions.php?cid=' + courseID);
            // FORM ASK
            $('#form-ask button').off().click(function(e) {
                $.ajax({
                    url: $('#form-ask').attr('action'),
                    data: new FormData($('#form-ask')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST'
                })
                    .done(function() {
                        alert("Se ha realizado su pregunta satisfactoriamente.");
                        $.ajax({
                            url: VLMS.URI + 'course/my_questions_review.php',
                            data: { cid: VLMS.current.id }
                        })
                            .done(function(view) { $('#my-questions').html(view); });
                    });
            });
            // SHOW FILE SELECTED
            $('#form-ask [name=user_upload]').change(function(e) {
                if (!window.File || !window.FileReader || !window.FileList || !window.Blob) {
                  alert('El File APIs no esta soportado por el navegador.');
                  return;
                }

                if (!e.target.files.length) return;

                var $self = $(this);
                var t = '<div class="alert alert-info alert-dismissible" role="alert" style="margin-bottom: 8px;">' +
                        '<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' +
                        '<div class="vlms-media"><div class="vlms-media__figure">' +
                        '<svg aria-hidden="true" class="vlms-icon vlms-icon--small" style="fill: #555;" data-toggle="tooltip" data-container="body" data-placement="bottom" title="Elegir un tutor">' +
                        '<use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#attachment"></use></svg></div>' +
                        '<div class="vlms-media__body"><a href="javascript:void(0)">' + e.target.files[0].name + '</a></div></div></div>';

                $('#wrapper-files').html(t);
                $('#wrapper-files .close').click(function() { $self.val(''); });
            });
        };
        _appointmentTool = function(courseID) {
          // UPDATE APPOINTMENTS BY DATE
          $.ajax({
            url: VLMS.URI + 'course/appointments_by_date.php',
            data: { uid: VLMS.USER_ID, cid: courseID }
          })
            .done(function(view) { $('#appointments-by-date').html(view); });
          // UPDATE APPOINTMENTS BY TUTOR
          $.ajax({
            url: VLMS.URI + 'course/appointments_by_tutor.php',
            data: { uid: VLMS.USER_ID, cid: courseID }
          })
            .done(function(view) { $('#appointments-by-tutor').html(view); });
          // UPDATE APPOINMENTS
          $.ajax({
            url: VLMS.URI + 'course/appointments.php',
            data: { uid: VLMS.USER_ID, cid: courseID }
          })
            .done(function(view) { $('#appointments').html(view); });
        };
        _reviewTool = function(courseID) {
            // UPDATE REVIEW
            $.ajax({
                url: VLMS.URI + '/course/review.php',
                data: { cid: courseID }
            })
                .done(function(view) { $('#review .course-tool__body').html(view); });
        };
        _practiceTool = function(courseID) {
            // UPDATE PRACTICE
            $.ajax({
                url: VLMS.URI + 'course/practice.php',
                data: { cid: courseID }
            })
                .done(function(view) { $('#practice .course-tool__body').html(view); });
        };
        _unsubscribe = function(courseCode) {
            if (!$w.confirm("Cancelar suscripción")) return;
            $.ajax({
                url: VLMS.MAIN_URI + 'auth/courses.php',
                data: {
                    action: 'unsubscribe',
                    sec_token: '<?php echo Security::get_existing_token(); ?>',
                    unsubscribe: courseCode
                }
            })
                .done(function(view) {
                    $.ajax({ url: VLMS.URI + 'course/subscribe.php' })
                        .done(function(view) { $w.location.reload(); });
                });
        };
        _switchTo = function(courseID) {
            var $current = $('.course-tutoring.active');
            VLMS.current.code = $current.attr('data-course-code') || '';
            VLMS.current.id = $current.attr('data-course-id') || 0;
            VLMS.current.forumID = $current.attr('data-course-forum-id') || 0;
            // REFRESH COURSE SESSION
            $.ajax({
                async: false,
                url: VLMS.URI + 'course/switch_course_session.php',
                data: { cid: courseID }
            });
            // HIDE TOOLS
            this.hideTools(courseID);
            // UPDATE TOOLS
            this.askTool(courseID);
            this.appointmentTool(courseID);
            this.reviewTool(courseID);
            this.practiceTool(courseID);
        };
        return {
            init: _init,
            // TOOLS
            getTools: _getTools,
            showTool: _showTool,
            hideTools: _hideTools,
            askTool: _askTool,
            appointmentTool: _appointmentTool,
            reviewTool: _reviewTool,
            practiceTool: _practiceTool,
            // COURSE
            unsubscribe: _unsubscribe,
            switchTo: _switchTo
        };
    })(jQuery);

    Course.init();
</script>

<?php Display::display_footer(); ?>
