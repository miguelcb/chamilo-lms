<?php require_once '../../inc/global.inc.php';

require_once '../helpers.inc.php';

api_block_anonymous_users();

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
            <div class="course-tutoring item <?php echo $counter == 0 ? 'active' : ''; ++$counter; ?>" data-course-id="<?php echo $course['id']; ?>">
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
                    <form id="form-ask">
                        <div class="form-group">
                            <span class="help-block">Te recomendamos que revises el <a href="javascript:void(0);" id="repository-questions-link" data-toggle="ajax-modal" data-target="#repository-questions-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/repository_questions.php?cid=1">repositorio de preguntas</a> para validar que tu consulta no se haya realizado antes.</span>
                            <textarea name="question" id="question" rows="10" class="form-control" placeholder="Escribe tu pregunta aquí"></textarea>
                        </div>
                        <div class="clearfix">
                            <div class="pull-left">
                                <div class="form-group" style="margin-top: 24px;">
                                    <span class="help-block">¿Quieres que tu pregunta sea respondida por un tutor en especial?</span>
                                    <div class="btn-toolbar" role="toolbar" aria-label="..." style="margin: 0;">
                                        <div class="btn-group" role="group" aria-label="..." style="margin-left: 0;">
                                            <select name="" id="" class="form-control">
                                                <option value="asda">Sin preferencia (Tutor)</option>
                                                <option value="asda">Mendoza Neudstald, Lorei</option>
                                                <option value="asda">Quispe Zapata, Juan</option>
                                           </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <span class="help-block">¿Quieres que tu pregunta sea compartida en el repositorio de preguntas?</span>
                                    <input type="radio" name="public" id="public1" checked> Sí
                                    <input type="radio" name="public" id="public2"> No
                                </div>
                            </div>
                            <div class="pull-right">
                                <label class="btn btn-default fa fa-paperclip" title="Archivos adjuntos">
                                    <input type="file" style="display: none;">
                                </label>
                            </div>
                            <div class="text-center">
                                <button type="button" class="btn btn-success" id="btn-ask">Preguntar</button>
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
                    <div class="vlms-title-divider">Rerserva por fecha</div>
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
                        <div class="vlms-title-divider">Rerserva por tutor</div>

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
                                    <li class="vlms-list__item">
                                        <div class="vlms-media">
                                            <div class="vlms-media__figure">
                                                <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                                                </svg>
                                            </div>
                                            <div class="vlms-media__body">
                                                <div class="vlms-media__body__title">
                                                    <a href="javascript:void(0);">Lunes, 1 de Abril del 2016</a>
                                                </div>
                                                <div class="vlms-media__body__detail">
                                                    <ul class="vlms-list vlms-list--vertical vlms-text--small">
                                                        <li class="vlms-list__item">8:00 am - 8:45 am</li>
                                                        <li class="vlms-list__item">
                                                            <strong>Quispe Zapata, Juan</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="vlms-list__item">
                                        <div class="vlms-media">
                                            <div class="vlms-media__figure">
                                                <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                                                </svg>
                                            </div>
                                            <div class="vlms-media__body">
                                                <div class="vlms-media__body__title">
                                                    <a href="javascript:void(0);">Martes, 23 de Noviembre del 2016</a>
                                                </div>
                                                <div class="vlms-media__body__detail">
                                                    <ul class="vlms-list vlms-list--vertical vlms-text--small">
                                                        <li class="vlms-list__item">8:00 am - 8:45 am</li>
                                                        <li class="vlms-list__item">
                                                            <strong>Quispe Zapata, Juan</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="vlms-list__item">
                                        <div class="vlms-media">
                                            <div class="vlms-media__figure">
                                                <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chat"></use>
                                                </svg>
                                            </div>
                                            <div class="vlms-media__body">
                                                <div class="vlms-media__body__title">
                                                    <a href="javascript:void(0);">Martes, 3 de Abril del 2016</a>
                                                </div>
                                                <div class="vlms-media__body__detail">
                                                    <ul class="vlms-list vlms-list--vertical vlms-text--small">
                                                        <li class="vlms-list__item">3:45 pm - 4:15 pm</li>
                                                        <li class="vlms-list__item">
                                                            <strong>Mendoza Neudstald, Lorei</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="vlms-list__item">
                                        <div class="vlms-media">
                                            <div class="vlms-media__figure">
                                                <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                                                </svg>
                                            </div>
                                            <div class="vlms-media__body">
                                                <div class="vlms-media__body__title">
                                                    <a href="javascript:void(0);">Jueves, 5 de Abril del 2015</a>
                                                </div>
                                                <div class="vlms-media__body__detail">
                                                    <ul class="vlms-list vlms-list--vertical vlms-text--small">
                                                        <li class="vlms-list__item">3:45 pm - 4:15 pm</li>
                                                        <li class="vlms-list__item">
                                                            <strong>Quispe Zapata, Juan</strong>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
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
    window.Course = (function($, $w) {
        var _MAIN_AJAX_URI = '<?php echo api_get_path(WEB_CODE_PATH); ?>',
            _AJAX_URI = '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/',
            _USER_ID = +'<?php echo api_get_user_id(); ?>',
            _toolsAllowed = ['ask', 'appointment', 'review', 'practice'],
            _init,
            _unsubscribe,
            _switchTo,
            _tools,
            _hideTools,
            _showTool;
        _init = function() {
            Course.switchTo($('.course-tutoring.active').attr('data-course-id') || 0);
            $('[data-toggle=tooltip]').boostrapTooltip();
            $('[data-toggle=popover]').boostrapPopover();

            $('#courses-tutoring').on('slid.bs.carousel', function() {
                Course.switchTo($('.course-tutoring.active').attr('data-course-id') || 0);
                $('[data-toggle=tooltip]').boostrapTooltip();
                $('[data-toggle=popover]').boostrapPopover();
            });
        };
        _toolsByCourse = function(courseID) {
            var t = {
                1: ['ask', 'appointment', 'review', 'practice'],
                3: ['ask', 'appointment'],
                4: ['ask', 'appointment']
            };

            return t[courseID];
        };
        _hideTools = function(courseID) {
            var toolsAvailable = this.toolsByCourse(courseID);
            var hide = [];
            var i = 0, l = this.toolsAllowed.length;
            for (; i < l; i++) {
                $('#' + this.toolsAllowed[i])[
                   toolsAvailable.indexOf(this.toolsAllowed[i]) == -1 ? 'addClass' : 'removeClass'
                ]('hidden');
                $('#nav-tools').find('[href=#' + this.toolsAllowed[i] + ']').parent()[
                    toolsAvailable.indexOf(this.toolsAllowed[i]) == -1 ? 'hide' : 'show'
                ]();
            }
            $('.course-tool').removeClass('last-child');
            $('.course-tool:not(.hidden)').last().addClass('last-child');
        };
        _showTool = function(tool, courseID) {
            if (this.toolsByCourse(courseID).indexOf(tool) == -1) return;
            this[tool + 'Tool'](courseID);
        };
        _askTool = function(courseID) {};
        _appointmentTool = function(courseID) {
          // update appointments by date
          $.ajax({
            url: this.AJAX_URI + 'course/appointments_by_date.php',
            data: { uid: this.USER_ID, cid: courseID }
          })
            .done(function(view) { $('#appointments-by-date').html(view); });
          // update appointments by tutor
          $.ajax({
            url: this.AJAX_URI + 'course/appointments_by_tutor.php',
            data: { uid: this.USER_ID, cid: courseID }
          })
            .done(function(view) { $('#appointments-by-tutor').html(view); });
          // update appoinments
          $.ajax({
            url: this.AJAX_URI + 'course/appointments.php',
            data: { uid: this.USER_ID, cid: courseID }
          })
            .done(function(view) { $('#appointments').html(view); });
        };
        _reviewTool = function(courseID) {};
        _practiceTool = function(courseID) {};
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
            // refresh course session
            $.ajax({
                async: false,
                url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/switch_course_session.php',
                data: { cid: courseID }
            });
            // hide tools
            this.hideTools(courseID);
            // update tools
            $.each(this.toolsAllowed, function(i, tool) {
              this.showTool(tool, courseID);
            }.bind(this));
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
            // update review
            $.ajax({
                url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/review.php',
                data: { cid: courseID }
            })
                .done(function(view) {
                    $('#review .course-tool__body').html(view);
                });
            // update practice
            $.ajax({
                url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/practice.php',
                data: { cid: courseID }
            })
                .done(function(view) {
                    $('#practice .course-tool__body').html(view);
                });
        };
        return {
            MAIN_AJAX_URI: _MAIN_AJAX_URI,
            AJAX_URI: _AJAX_URI,
            USER_ID: _USER_ID,
            init: _init,
            toolsAllowed: _toolsAllowed,
            showTool: _showTool,
            askTool: _askTool,
            appointmentTool: _appointmentTool,
            reviewTool: _reviewTool,
            practiceTool: _practiceTool,
            toolsByCourse: _toolsByCourse,
            hideTools: _hideTools,
            unsubscribe: _unsubscribe,
            switchTo: _switchTo
        };
    })(jQuery, window);

    Course.init();
</script>

<?php Display::display_footer(); ?>
