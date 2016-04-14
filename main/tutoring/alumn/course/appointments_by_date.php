<?php require_once '../../../inc/global.inc.php';

include_once '../../helpers.inc.php';

$user_id   = is_null($_GET['uid']) ? '' : $_GET['uid'];
$course_id = is_null($_GET['cid']) ? '' : $_GET['cid'];

$sql = "SELECT pa.*, u.user_id, u.firstname, u.lastname FROM personal_agenda pa
        INNER JOIN user u ON u.user_id = pa.user
        WHERE pa.course = $course_id ORDER BY pa.date";

$result = Database::query($sql);
?>

<div class="vlms">
    <div class="vlms-title-divider">Rerserva por fecha</div>
    <div class="vlms-datepicker center-block" aria-hidden="false" style="margin-top: 24px;">
        <div class="vlms-datepicker__filter">
          <div class="vlms-datepicker__filter__month">
            <div class="vlms-datepicker__filter__month__control">
              <button class="vlms-datepicker__filter__month__control__button">
                <svg aria-hidden="true" class="vlms-datepicker__filter__month__control__button__icon">
                  <use xlink:href="#left"></use>
                </svg>
                <span class="sr-only">Previous Month</span>
              </button>
            </div>
            <h2 id="month" class="vlms-datepicker__filter__month__title" aria-live="assertive" aria-atomic="true">Noviembre</h2>
            <div class="vlms-datepicker__filter__month__control">
              <button class="vlms-datepicker__filter__month__control__button">
                <svg aria-hidden="true" class="vlms-datepicker__filter__month__control__button__icon">
                  <use xlink:href="#right"></use>
                </svg>
                <span class="sr-only">Next Month</span>
              </button>
            </div>
          </div>
          <div class="vlms-datepicker__filter__year">
            <div class="vlms-datepicker__filter__year__container">
              <select class="vlms-datepicker__filter__year__container__select" disabled>
                <option value="2016" selected>2016</option>
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
                <span class="vlms-datepicker__month__day">5</span>
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
                <span class="vlms-datepicker__month__day">10</span>
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
                <span class="vlms-datepicker__month__day">17</span>
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

    <div class="text-center" style="margin-top: 16px;">
        <span class="vlms-badge vlms-bgc--sun-flower">presencial</span>
        <span class="vlms-badge vlms-bgc--peter-river">virtual</span>
        <span class="vlms-badge vlms-bgc--emerald">presencial/virtual</span>
    </div>

    <div class="vlms-block" style="height: 300px; padding: 0; margin-top: 16px;">
        <div class="vlms-scrollable vlms-scrollable--y">
            <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                <li class="vlms-title-divider">Disponibilidad</li>
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
    </div>
</div>

<script>
    $('[data-toggle=tooltip]').boostrapTooltip();
</script>
