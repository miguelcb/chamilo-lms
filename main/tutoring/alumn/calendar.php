<?php include '../helpers.inc.php';

function abc($dates = ['2016-04-12' => 'vlms-datepicker__month__day--presential'], $style = '', $attributes = '') {
    $calendar      = array_fill(0, 35, null);
    $days_of_month = +date('t');
    $day_of_week   = +date('N', mktime(0, 0, 0, date('m'), 1, date('Y')));
    $days_short    = array('L', 'M', 'X', 'J', 'V', 'S', 'D');
    $days          = array('Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo');

    for ($d = 0; $d < $days_of_month; $d++) {
        $calendar[$d + $day_of_week - 1] = date('Y-m-' . ($d + 1));
    }

    // pretty_print($calendar);
    echo '<div class="vlms-datepicker center-block" aria-hidden="false" style="' . $style .'" ' . $attributes . '>';
    // datepicker filter
    echo '<div class="vlms-datepicker__filter">';
    // datepicker filter month
    echo '<div class="vlms-datepicker__filter__month">';
    // datepicker filter month control
    echo '<div class="vlms-datepicker__filter__month__control">';
    echo '<button class="vlms-datepicker__filter__month__control__button">';
    echo '<svg aria-hidden="true" class="vlms-datepicker__filter__month__control__button__icon"><use xlink:href="#left"></use></svg>';
    echo '<span class="sr-only">Mes anterior</span>';
    echo '</button>';
    echo '</div>';
    // datepicker filter month title
    echo '<h2 id="month" class="vlms-datepicker__filter__month__title" aria-live="assertive" aria-atomic="true">Noviembre</h2>';
    // datepicker filter month control
    echo '<div class="vlms-datepicker__filter__month__control">';
    echo '<button class="vlms-datepicker__filter__month__control__button">';
    echo '<svg aria-hidden="true" class="vlms-datepicker__filter__month__control__button__icon"><use xlink:href="#right"></use></svg>';
    echo '<span class="sr-only">Mes siguiente</span>';
    echo '</button>';
    echo '</div>';
    echo '</div>';
    // datepicker filter year
    echo '<div class="vlms-datepicker__filter__year">';
    echo '<div class="vlms-datepicker__filter__year__container">';
    echo '<select class="vlms-datepicker__filter__year__container__select" disabled><option value="' . date('Y') . '" selected>' . date('Y') . '</option></select>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    // datepicker month
    echo '<table class="vlms-datepicker__month" role="grid" aria-labelledby="month">';
    // week days
    echo '<thead>';
    echo '<tr>';
    for ($i = 0; $i < 7; $i++) {
        echo '<th scope="col"><abbr title="' . $days[$i] . '">' . $days_short[$i] . '</abbr></th>';
    }
    echo '</tr>';
    echo '</thead>';
    // days
    for ($w = 0; $w < 5; $w++) {
        echo '<tr>';
        for ($d = 0; $d < 7; $d++) {
            echo '<td role="gridcell" aria-disabled="true">';
            $key = $d + ($w * 7);
            $class = is_null($calendar[$key]) ? '' : array_key_exists($calendar[$key], $dates) ? $dates[$calendar[$key]] : '';
            echo '<span class="vlms-datepicker__month__day '. (is_null($calendar[$key]) ? 'vlms-datepicker__month__day--disabled' : $class) .'">' . (is_null($calendar[$key]) ? '' : date('j', strtotime($calendar[$key]))) . '</span>';
            echo '</td>';
        }
        echo '</tr>';
    }
    echo '</table>';
    echo '</div>';
}

abc();
?>
