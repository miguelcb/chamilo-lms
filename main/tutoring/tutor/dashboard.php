<?php
/* For licensing terms, see /license.txt */
/**
* Shows who is online in a specific session
* @package chamilo.main
*/
include_once '../../inc/global.inc.php';

include 'helpers.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}
?>
<?php Display::display_header('Dashboard'); ?>
<!-- area-preguntar -->
<section class="container-fluid padded row" id="ask" style="background-color: rgb(226,228,231); padding-top: 90px;
	padding-bottom: 90px;">
	<div class="container container-white" style="background-color: rgba(255,255,255,0.7);">
		<?php include_once 'repository_questions.php';?>
	</div>
</section>
<section id="appointment" class="row course-tool last-child">
<header class="text-center course-tool__header">
  <span class="fa fa-calendar fa-rounded fa-icon-size fa-icon-size--medium vlms-bgc--palette-1" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="" data-content="Reservas de citas presenciales o virtuales realizadas por tus alumnos." data-original-title="Citas"></span>
  <div class="text-uppercase">Citas</div>
</header>
	<section class="container">
		<div class="row">
			<div class="col-sm-8">
				<h3 class="text-center">Horarios de tutoría</h3>
				<div class="row">
					<div class="col-sm-6">
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group">
									<label for="sel1">Curso:</label>
									<select class="form-control" id="selCurso">
										<option>Matemática 1</option>
										<option>Física 1</option>
										<option>Química Orgánica</option>
										
									</select>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group">
									<label for="sel1">Tipo:</label>
									<select class="form-control" id="selCitaTipo">
										<option selected>Sesión virtual</option>
										<option>Otros</option>
									</select>
								</div>
							</div>
							
						</div>
						<div class="vlms">
            <div class="vlms-datepicker center-block" aria-hidden="false" style="margin-top: 24px;">
                            <div class="vlms-datepicker__filter">
                              <div class="vlms-datepicker__filter__month">
                                <div class="vlms-datepicker__filter__month__control">
                                  <button class="vlms-datepicker__filter__month__control__button">
                                    <svg aria-hidden="true" class="vlms-datepicker__filter__month__control__button__icon">
                                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#left"></use>
                                    </svg>
                                    <span class="sr-only">Previous Month</span>
                                  </button>
                                </div>
                                <h2 id="month" class="vlms-datepicker__filter__month__title" aria-live="assertive" aria-atomic="true">Noviembre</h2>
                                <div class="vlms-datepicker__filter__month__control">
                                  <button class="vlms-datepicker__filter__month__control__button">
                                    <svg aria-hidden="true" class="vlms-datepicker__filter__month__control__button__icon">
                                      <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#right"></use>
                                    </svg>
                                    <span class="sr-only">Next Month</span>
                                  </button>
                                </div>
                              </div>
                              <div class="vlms-datepicker__filter__year">
                                <div class="vlms-datepicker__filter__year__container">
                                  <select class="vlms-datepicker__filter__year__container__select" disabled="">
                                    <option value="2016" selected="">2016</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <table class="vlms-datepicker__month" role="grid" aria-labelledby="month">
                              <thead>
                                <tr id="weekdays">
                                  <th id="Sunday" scope="col">
                                    <abbr title="Domingo">D</abbr>
                                  </th>
                                  <th id="Monday" scope="col">
                                    <abbr title="Lunes">L</abbr>
                                  </th>
                                  <th id="Tuesday" scope="col">
                                    <abbr title="Martes">M</abbr>
                                  </th>
                                  <th id="Wednesday" scope="col">
                                    <abbr title="Miercoles">X</abbr>
                                  </th>
                                  <th id="Thursday" scope="col">
                                    <abbr title="Jueves">J</abbr>
                                  </th>
                                  <th id="Friday" scope="col">
                                    <abbr title="Viernes">V</abbr>
                                  </th>
                                  <th id="Saturday" scope="col">
                                    <abbr title="Sábado">S</abbr>
                                  </th>
                                </tr>
                              </thead>
                              <tbody>
                                <tr>
                                  <td headers="Sunday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--disabled">31</span>
                                  </td>
                                  <td headers="Monday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">1</span>
                                  </td>
                                  <td headers="Tuesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">2</span>
                                  </td>
                                  <td headers="Wednesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--presential">3</span>
                                  </td>
                                  <td headers="Thursday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--presential">4</span>
                                  </td>
                                  <td headers="Friday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--filled">5</span>
                                  </td>
                                  <td headers="Saturday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">6</span>
                                  </td>
                                </tr>
                                <tr>
                                  <td headers="Sunday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">7</span>
                                  </td>
                                  <td headers="Monday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--presential">8</span>
                                  </td>
                                  <td headers="Tuesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">9</span>
                                  </td>
                                  <td headers="Wednesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--filled">10</span>
                                  </td>
                                  <td headers="Thursday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">11</span>
                                  </td>
                                  <td headers="Friday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--presential">12</span>
                                  </td>
                                  <td headers="Saturday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--presential">13</span>
                                  </td>
                                </tr>
                                <tr>
                                  <td headers="Sunday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">14</span>
                                  </td>
                                  <td headers="Monday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--both">15</span>
                                  </td>
                                  <td headers="Tuesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">16</span>
                                  </td>
                                  <td headers="Wednesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--filled">17</span>
                                  </td>
                                  <td headers="Thursday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--is-today">18</span>
                                  </td>
                                  <td headers="Friday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">19</span>
                                  </td>
                                  <td headers="Saturday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--presential">20</span>
                                  </td>
                                </tr>
                                <tr>
                                  <td headers="Sunday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">21</span>
                                  </td>
                                  <td headers="Monday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">22</span>
                                  </td>
                                  <td headers="Tuesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--virtual">23</span>
                                  </td>
                                  <td headers="Wednesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">24</span>
                                  </td>
                                  <td headers="Thursday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--presential">25</span>
                                  </td>
                                  <td headers="Friday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">26</span>
                                  </td>
                                  <td headers="Saturday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">27</span>
                                  </td>
                                </tr>
                                <tr>
                                  <td headers="Sunday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">28</span>
                                  </td>
                                  <td headers="Monday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--presential">29</span>
                                  </td>
                                  <td headers="Tuesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day">30</span>
                                  </td>
                                  <td headers="Wednesday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--disabled">1</span>
                                  </td>
                                  <td headers="Thursday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--disabled">2</span>
                                  </td>
                                  <td headers="Friday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--disabled">3</span>
                                  </td>
                                  <td headers="Saturday" role="gridcell" aria-disabled="true">
                                    <span class="vlms-datepicker__month__day vlms-datepicker__month__day--disabled">4</span>
                                  </td>
                                </tr>
                              </tbody>
                            </table>
                        </div>						
						</div>
						<div id="fechacita"></div>
					</div>
					<div class="col-sm-6">
						<h3 class="text-center">Horarios</h3>
						<table class="table table-striped table-bordered table-hover">
							<tbody>
								<tr>
									<th>&nbsp;</th>
									<th>Hora</th>
									<th>Estado</th>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkbox"></td>
									<td>10:00 - 10:45</td>
									<td>Disponible</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkbox2"></td>
									<td>10:00 - 10:45</td>
									<td>Disponible</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkbox3"></td>
									<td>10:00 - 10:45</td>
									<td>No disponible</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkbox4"></td>
									<td>10:00 - 10:45</td>
									<td>No disponible</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkbox3"></td>
									<td>10:00 - 10:45</td>
									<td>No disponible</td>
								</tr>
								<tr>
									<td><input type="checkbox" name="checkbox4"></td>
									<td>10:00 - 10:45</td>
									<td>No disponible</td>
								</tr>
							</tbody>
						</table>
						
					</div>
				</div>
			</div>
			<div class="col-sm-4">
				<h3 class="text-center">Alumnos</h3>
				<div class="panel panel-default">
					<div class="panel-heading">
						<div class="form-group">
							<div class="checkbox">
								<label>
									<input type="checkbox">
								Mostrar todos</label>
							</div>
						</div>
					</div>
					<!-- START list group-->
					<div class="list-group">
						<!-- START list group item-->
						<a href="javascript:void(0);" class="list-group-item">
							<div class="row">
								<div class="col-xs-1">
									<input type="checkbox">
								</div>
								<div class="col-xs-2">
									<img src="../image/03.jpg" alt="Image" class="media-object img-circle thumb48">
								</div>
								<div class="col-xs-9">Peter Parker</div>
							</div>
						</a>
						<!-- END list group item-->
						<!-- START list group item-->
						<a href="javascript:void(0);" class="list-group-item">
							<div class="row">
								<div class="col-xs-1">
									<input type="checkbox">
								</div>
								<div class="col-xs-2">
									<img src="../image/03.jpg" alt="Image" class="media-object img-circle thumb48">
								</div>
								<div class="col-xs-9">Peter Parker</div>
							</div>
						</a>
						<!-- END list group item-->
						<!-- START list group item-->
						<a href="javascript:void(0);" class="list-group-item">
							<div class="row">
								<div class="col-xs-1">
									<input type="checkbox">
								</div>
								<div class="col-xs-2">
									<img src="../image/03.jpg" alt="Image" class="media-object img-circle thumb48">
								</div>
								<div class="col-xs-9">Peter Parker</div>
							</div>
						</a>
						<!-- END list group item-->
					</div>
					<!-- END list group-->
					<!-- START panel footer-->
					<div class="panel-footer clearfix">
						<a data-toggle="modal" data-target="#pregunta-dialog" class="pull-left">
							<a class="btn btn-default pull-left"  data-toggle="modal" data-target="#confirmacion-cita-dialog">Registrar asistencia</a>
						</a>
					</div>
					<!-- END panel-footer-->
				</div>
			</div>
		</div>
	</section>
</section>
<a href="#" title="Ir arriba" id="hook-top" class="fa fa-arrow-up"></a>
<?php Display::display_footer(); ?>

<script>
    $.fn.boostrapTooltip = $.fn.tooltip.noConflict();
    $.fn.boostrapPopover = $.fn.popover.noConflict();
    $('[data-toggle=tooltip]').boostrapTooltip();
    $('[data-toggle=popover]').boostrapPopover();
</script>