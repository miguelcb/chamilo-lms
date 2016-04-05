<?php include_once '../../inc/global.inc.php';

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
    <div id="courses-tutoring" class="container carousel" data-ride="carousel" data-interval="false">
      <ol class="carousel-indicators">
        <?php $counter = 0; ?>
        <?php while($indicator = Database::fetch_assoc($indicators)): ?>
            <li data-target="#courses-tutoring" data-slide-to="<?php echo $counter; ?>" class="<?php echo $counter == 0 ? 'active' : ''; ++$counter; ?>" data-toggle="tooltip" data-container="body" title="<?php echo $indicator['title']; ?>"></li>
        <?php endwhile; ?>
        <li class="fa fa-plus" data-target="#courses-tutoring" data-slide-to="<?php echo $counter; ?>" data-toggle="tooltip" data-container="body" title="Suscribirte a tutoría"></li>
      </ol>

      <div class="carousel-inner" role="listbox">
        <?php $counter = 0; ?>
        <?php while($course = Database::fetch_assoc($courses)): ?>
            <div class="course-tutoring item <?php echo $counter == 0 ? 'active' : ''; ++$counter; ?>" data-course-id="<?php echo $course['id']; ?>">
                <div class="container-fluid" style="padding: 0 64px;">
                    <h1 class="text-center"><?php echo $course['title']; ?></h1>
                    <p><?php echo $course['description']; ?></p>
                    <ul class="list-unstyled text-center course-tutoring__nav">
                        <li style="display: inline-block;">
                            <a href="javascript:void(0)" class="fa fa-newspaper-o fa-icon-size fa-icon-size--medium fa-rounded bg-violet" data-modal="ajax-modal" data-target="#recent-activities-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/recent_activities.php?cid=<?php echo $course['id']; ?>" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Actividades recientes"></a>
                        </li>
                        <li style="display: inline-block;">
                            <a href="javascript:void(0)" class="fa fa-cog fa-icon-size fa-icon-size--medium fa-rounded bg-orange" data-modal="ajax-modal" data-target="#alert-settings-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/alert_settings.php?cid=<?php echo $course['id']; ?>" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Configuración de alertas"></a>
                        </li>
                        <li style="display: inline-block;">
                            <a href="javascript:Course.unsubscribe('<?php echo $course['code']; ?>');" class="fa fa-sign-out fa-icon-size fa-icon-size--medium fa-rounded" data-toggle="tooltip" data-placement="bottom" data-container="body" title="Cancelar suscripción"></a>
                        </li>
                    </ul>
                </div>
            </div>
        <?php endwhile; ?>
        <div class="course-tutoring item">
            <div class="container" style="position: relative; height: 100%;">
                <h1 class="text-center">Suscribirte a tutoría</h1>
                <p class="text-center">Busca entre las tutorias de cursos disponibles y suscribete al de mayor interés.</p>
                <div style="position: absolute; top: 62.5%; left: 50%; transform: translate3d(-50%, -50%, 0);">
                    <span class="fa fa-plus fa-icon-size" role="button" data-toggle="ajax-modal" data-target="#subscribe-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/subscribe.php"></span>
                </div>
            </div>
        </div>
      </div>

      <a class="left carousel-control" href="javascript:void(0);" data-target="#courses-tutoring" role="button" data-slide="prev">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="javascript:void(0);" data-target="#courses-tutoring" role="button" data-slide="next">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
    <ul id="nav-tools" class="list-unstyled text-center fa-icon-size">
        <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Preguntar">
            <a href="#ask" class="fa fa-question fa-rounded fa-rounded--lg"></a>
        </li>
        <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Reunirte con tu tutor">
            <a href="#appointment" class="fa fa-calendar fa-rounded fa-rounded--lg"></a>
        </li>
        <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Repasar">
            <a href="#review" class="fa fa-book fa-rounded fa-rounded--lg"></a>
        </li>
        <li data-toggle="tooltip" data-placement="bottom" data-container="body" title="Practicar">
            <a href="#practice" class="fa fa-edit fa-rounded fa-rounded--lg"></a>
        </li>
    </ul>
</section>
<section class="course-tools">
    <!-- ask -->
    <section id="ask" class="row course-tool">
        <header class="text-center course-tool__header">
            <span class="fa fa-question fa-rounded fa-icon-size fa-icon-size--medium" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Preguntar" data-content="Resuelve tus dudas realizando preguntas o visitando el repositorio de preguntas."></span>
            <div class="text-uppercase">preguntar</div>
        </header>
        <section class="container">
            <div class="row" style="padding: 32px 0;">
                <div class="col-md-6">
                    <form id="form-ask">
                        <div class="form-group">
                            <span class="help-block">Ve al <a href="javascript:void(0);" id="repository-questions-link" data-toggle="ajax-modal" data-target="#repository-questions-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/repository_questions.php?cid=1">repositorio de preguntas</a> para que validez que no haya sido hecha antes y ya tenga una respuesta, sino elabora una nueva pregunta.</span>
                            <textarea name="question" id="question" rows="10" class="form-control" placeholder="Escribir pregunta"></textarea>
                        </div>
                        <div class="clearfix">
                            <div class="pull-left">
                                <div class="btn-toolbar" role="toolbar" aria-label="..." style="margin: 0;">
                                    <div class="btn-group" role="group" aria-label="..." style="margin-left: 0;">
                                        <select name="" id="" class="form-control">
                                            <option value="asda">Sin preferencia (Tutor)</option>
                                            <option value="asda">Mendoza Neudstald, Lorei(Tutor)</option>
                                            <option value="asda">Quispe Zapata, Juan (Tutor)</option>
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
                                <button type="button" class="btn btn-success" id="btn-ask">Preguntar</button>
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

                </div>
            </div>
        </section>
    </section>
    <!-- appointment -->
    <section id="appointment" class="row course-tool">
        <header class="text-center course-tool__header">
            <span class="fa fa-calendar fa-rounded fa-icon-size fa-icon-size--medium" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Sacar cita" data-content="Si deseas un asesoría mas personalizada, reserva una cita presencial o virtual con tu tutor."></span>
            <div class="text-uppercase">Reunirte con tu tutor</div>
        </header>
        <section class="container">
            <div class="row" style="padding: 32px 0;">
                <div class="col-md-4">
                    <div class="vlms">
                        <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                            <li class="vlms-title-divider">Mis citas</li>
                            <li class="vlms-list__item">
                                <div class="vlms-media">
                                    <div class="vlms-media__figure">
                                        <svg aria-hidden="true" class="vlms-icon" style="fill: #333;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                                        </svg>
                                    </div>
                                    <div class="vlms-media__body">
                                        <div class="vlms-media__body__title clearfix">
                                            <a class="pull-left" href="javascript:void(0);">Lunes, 1 de Abril del 2015</a>
                                            <span class="pull-right badge vlms-text--small">Cita</span>
                                        </div>
                                        <div class="vlms-media__body__detail">
                                            <ul class="vlms-list vlms-list--horizontal vlms-has-dividers">
                                                <li class="vlms-list__item">3:45 pm</li>
                                                <li class="vlms-list__item">Quispe Zapata, Juan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="vlms-list__item">
                                <div class="vlms-media">
                                    <div class="vlms-media__figure">
                                        <svg aria-hidden="true" class="vlms-icon" style="fill: #333;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                                        </svg>
                                    </div>
                                    <div class="vlms-media__body">
                                        <div class="vlms-media__body__title clearfix">
                                            <a class="pull-left" href="javascript:void(0);">Lunes, 1 de Abril del 2015</a>
                                            <span class="pull-right badge vlms-text--small">Cita</span>
                                        </div>
                                        <div class="vlms-media__body__detail">
                                            <ul class="vlms-list vlms-list--horizontal vlms-has-dividers">
                                                <li class="vlms-list__item">3:45 pm</li>
                                                <li class="vlms-list__item">Quispe Zapata, Juan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="vlms-list__item">
                                <div class="vlms-media">
                                    <div class="vlms-media__figure">
                                        <svg aria-hidden="true" class="vlms-icon" style="fill: #333;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#chat"></use>
                                        </svg>
                                    </div>
                                    <div class="vlms-media__body">
                                        <div class="vlms-media__body__title clearfix">
                                            <a class="pull-left" href="javascript:void(0);">Martes, 3 de Abril del 2015</a>
                                            <span class="pull-right badge vlms-text--small">Chat</span>
                                        </div>
                                        <div class="vlms-media__body__detail">
                                            <ul class="vlms-list vlms-list--horizontal vlms-has-dividers">
                                                <li class="vlms-list__item">3:45 pm</li>
                                                <li class="vlms-list__item">Mendoza Neudstald, Lorei</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <li class="vlms-list__item">
                                <div class="vlms-media">
                                    <div class="vlms-media__figure">
                                        <svg aria-hidden="true" class="vlms-icon" style="fill: #333;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                                        </svg>
                                    </div>
                                    <div class="vlms-media__body">
                                        <div class="vlms-media__body__title clearfix">
                                            <a class="pull-left" href="javascript:void(0);">Jueves, 5 de Abril del 2015</a>
                                            <span class="pull-right badge vlms-text--small">Cita</span>
                                        </div>
                                        <div class="vlms-media__body__detail">
                                            <ul class="vlms-list vlms-list--horizontal vlms-has-dividers">
                                                <li class="vlms-list__item">3:45 pm</li>
                                                <li class="vlms-list__item">Quispe Zapata, Juan</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-8">
                    <ul class="tutors-appointments list-unstyled text-center" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tutor1" aria-controls="tutor1" role="tab" data-toggle="tab">
                                <img src="https://placeholdit.imgix.net/~text?txtsize=14&txt=72%C3%9772&w=72&h=72" alt="" class="img-circle" data-toggle="tooltip" data-container="body" title="Quispe Zapata, Juan">
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tutor2" aria-controls="tutor2" role="tab" data-toggle="tab">
                                <img src="https://placeholdit.imgix.net/~text?txtsize=14&txt=72%C3%9772&w=72&h=72" alt="" class="img-circle" data-toggle="tooltip" data-container="body" title="Mendoza Neudstald, Lorei">
                            </a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade active in" id="tutor1">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <div class="datepicker-week">
                                            <div class="datepicker-week__header clearfix">
                                                <div class="pull-left" role="button">
                                                    <span class="fa fa-caret-left"></span>
                                                </div>
                                                <div class="datepicker-week__header__title text-uppercase">abril</div>
                                                <div class="pull-right" role="button">
                                                    <span class="fa fa-caret-right"></span>
                                                </div>
                                            </div>
                                            <table class="datepicker-week__body" role="grid" aria-labelledby="month">
                                                <thead>
                                                    <tr id="weekdays">
                                                        <th id="Sunday" class="text-center" scope="col">
                                                            <abbr title="Domingo">D</abbr>
                                                        </th>
                                                        <th id="Monday" class="text-center" scope="col">
                                                            <abbr title="Lunes">L</abbr>
                                                        </th>
                                                        <th id="Tuesday" class="text-center" scope="col">
                                                            <abbr title="Martes">M</abbr>
                                                        </th>
                                                        <th id="Wednesday" class="text-center" scope="col">
                                                            <abbr title="Miercoles">X</abbr>
                                                        </th>
                                                        <th id="Thursday" class="text-center" scope="col">
                                                            <abbr title="Jueves">J</abbr>
                                                        </th>
                                                        <th id="Friday" class="text-center" scope="col">
                                                            <abbr title="Viernes">V</abbr>
                                                        </th>
                                                        <th id="Saturday" class="text-center" scope="col">
                                                            <abbr title="Sabado">S</abbr>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-mute" headers="Sunday" role="gridcell" aria-disabled="true">
                                                            <span class="slds-day"></span>
                                                        </td>
                                                        <td headers="Monday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">1</span>
                                                        </td>
                                                        <td headers="Tuesday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">2</span>
                                                        </td>
                                                        <td headers="Wednesday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">3</span>
                                                        </td>
                                                        <td headers="Thursday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">4</span>
                                                        </td>
                                                        <td headers="Friday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">5</span>
                                                        </td>
                                                        <td headers="Saturday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">6</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7"><hr></td>
                                                    </tr>
                                                    <tr>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td class="active" role="button">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>13:45</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td class="active" role="button">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>14:30</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>15:15</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td class="active" role="button">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button" data-toggle="ajax-modal" data-target="#appointment-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/register_appointment.php">
                                                            <span>16:00</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="vlms">
                                        <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                                            <li class="vlms-title-divider">Citas con: <strong>Quispe Zapata, Juan</strong></li>
                                            <li class="vlms-list__item">
                                                <div class="vlms-media">
                                                    <div class="vlms-media__figure">
                                                        <svg aria-hidden="true" class="vlms-icon" style="fill: #333;">
                                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                                                        </svg>
                                                    </div>
                                                    <div class="vlms-media__body">
                                                        <div class="vlms-media__body__title clearfix">
                                                            <a class="pull-left" href="javascript:void(0);">Lunes, 1 de Abril del 2015</a>
                                                            <span class="pull-right badge vlms-text--small">Cita</span>
                                                        </div>
                                                        <div class="vlms-media__body__detail">
                                                            <ul class="vlms-list vlms-list--horizontal vlms-has-dividers">
                                                                <li class="vlms-list__item">3:45 pm</li>
                                                                <li class="vlms-list__item">Quispe Zapata, Juan</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="vlms-list__item">
                                                <div class="vlms-media">
                                                    <div class="vlms-media__figure">
                                                        <svg aria-hidden="true" class="vlms-icon" style="fill: #333;">
                                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                                                        </svg>
                                                    </div>
                                                    <div class="vlms-media__body">
                                                        <div class="vlms-media__body__title clearfix">
                                                            <a class="pull-left" href="javascript:void(0);">Lunes, 1 de Abril del 2015</a>
                                                            <span class="pull-right badge vlms-text--small">Cita</span>
                                                        </div>
                                                        <div class="vlms-media__body__detail">
                                                            <ul class="vlms-list vlms-list--horizontal vlms-has-dividers">
                                                                <li class="vlms-list__item">3:45 pm</li>
                                                                <li class="vlms-list__item">Quispe Zapata, Juan</li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="vlms-list__item">
                                                <div class="vlms-media">
                                                    <div class="vlms-media__figure">
                                                        <svg aria-hidden="true" class="vlms-icon" style="fill: #333;">
                                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#event"></use>
                                                        </svg>
                                                    </div>
                                                    <div class="vlms-media__body">
                                                        <div class="vlms-media__body__title clearfix">
                                                            <a class="pull-left" href="javascript:void(0);">Jueves, 4 de Abril del 2015</a>
                                                            <span class="pull-right badge vlms-text--small">Cita</span>
                                                        </div>
                                                        <div class="vlms-media__body__detail">
                                                            <ul class="vlms-list vlms-list--horizontal vlms-has-dividers">
                                                                <li class="vlms-list__item">3:45 pm</li>
                                                                <li class="vlms-list__item">Quispe Zapata, Juan</li>
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
                        <div role="tabpanel" class="tab-pane fade" id="tutor2">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="text-center">
                                        <div class="datepicker-week">
                                            <div class="datepicker-week__header clearfix">
                                                <div class="pull-left" role="button">
                                                    <span class="fa fa-caret-left"></span>
                                                </div>
                                                <div class="datepicker-week__header__title text-uppercase">abril</div>
                                                <div class="pull-right" role="button">
                                                    <span class="fa fa-caret-right"></span>
                                                </div>
                                            </div>
                                            <table class="datepicker-week__body" role="grid" aria-labelledby="month">
                                                <thead>
                                                    <tr id="weekdays">
                                                        <th id="Sunday" class="text-center" scope="col">
                                                            <abbr title="Domingo">D</abbr>
                                                        </th>
                                                        <th id="Monday" class="text-center" scope="col">
                                                            <abbr title="Lunes">L</abbr>
                                                        </th>
                                                        <th id="Tuesday" class="text-center" scope="col">
                                                            <abbr title="Martes">M</abbr>
                                                        </th>
                                                        <th id="Wednesday" class="text-center" scope="col">
                                                            <abbr title="Miercoles">X</abbr>
                                                        </th>
                                                        <th id="Thursday" class="text-center" scope="col">
                                                            <abbr title="Jueves">J</abbr>
                                                        </th>
                                                        <th id="Friday" class="text-center" scope="col">
                                                            <abbr title="Viernes">V</abbr>
                                                        </th>
                                                        <th id="Saturday" class="text-center" scope="col">
                                                            <abbr title="Sabado">S</abbr>
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-mute" headers="Sunday" role="gridcell" aria-disabled="true">
                                                            <span class="slds-day"></span>
                                                        </td>
                                                        <td headers="Monday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">1</span>
                                                        </td>
                                                        <td headers="Tuesday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">2</span>
                                                        </td>
                                                        <td headers="Wednesday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">3</span>
                                                        </td>
                                                        <td headers="Thursday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">4</span>
                                                        </td>
                                                        <td headers="Friday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">5</span>
                                                        </td>
                                                        <td headers="Saturday" role="gridcell" aria-selected="false">
                                                            <span class="slds-day">6</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="7"><hr></td>
                                                    </tr>
                                                    <tr>
                                                        <td role="button">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>13:45</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>13:45</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td role="button">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>14:30</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>14:30</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td role="button">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>15:15</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>15:15</span>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td role="button">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>16:00</span>
                                                        </td>
                                                        <td role="button">
                                                            <span>16:00</span>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="vlms">
                                        <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                                            <li class="vlms-title-divider">Citas con: <strong>Mendoza Neudstald, Lorei</strong></li>
                                            <li class="vlms-list__item">
                                                <strong>No tienes citas</strong>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
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
            <span class="fa fa-book fa-rounded fa-icon-size fa-icon-size--medium" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Repasar" data-content="Mejora tus habilidades en el curso revisando nuevos materiales para repasar."></span>
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
            <span class="fa fa-edit fa-rounded fa-icon-size fa-icon-size--medium" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Practicar" data-content="Pon a prueba tus conocimientos y tu avance, con estos materiales para practicar."></span>
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
    $.fn.boostrapTooltip = $.fn.tooltip.noConflict();
    $.fn.boostrapPopover = $.fn.popover.noConflict();

    window.Course = (function($, $w) {
        var _init,
            _unsubscribe,
            _switchTo,
            _tools,
            _toolsAllowed = ['ask', 'appointment', 'review', 'practice'],
            _hideTools;
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
        _tools = function(courseID) {
            var t = {
                1: ['ask', 'appointment', 'review', 'practice'],
                3: ['ask', 'appointment'],
                4: ['ask', 'appointment']
            };

            return t[courseID];
        };
        _hideTools = function(courseID) {
            var toolsAvailable = this.tools(courseID);
            var hide = [];
            var i = 0, l = this.toolsAllowed.length;
            for (; i < l; i++) {
                $('#' + this.toolsAllowed[i])[
                   toolsAvailable.indexOf(this.toolsAllowed[i]) == -1 ? 'addClass' : 'removeClass'
                ]('hidden');
                console.log($('#nav-tools').find('[href=' + this.toolsAllowed[i] + ']'));
                $('#nav-tools').find('[href=#' + this.toolsAllowed[i] + ']').parent()[
                    toolsAvailable.indexOf(this.toolsAllowed[i]) == -1 ? 'hide' : 'show'
                ]();
            }
            $('.course-tool').removeClass('last-child');
            $('.course-tool:not(.hidden)').last().addClass('last-child');
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
            // update tools
            this.hideTools(courseID);
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
            init: _init,
            toolsAllowed: _toolsAllowed,
            tools: _tools,
            hideTools: _hideTools,
            unsubscribe: _unsubscribe,
            switchTo: _switchTo
        };
    })(jQuery, window);

    Course.init();
</script>

<?php Display::display_footer(); ?>
