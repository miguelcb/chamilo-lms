<?php require_once '../../inc/global.inc.php';

require_once '../helpers.inc.php';

api_block_anonymous_users();

$user_id   = api_get_user_id();

$dates = [];

$sql = "SELECT DATE_FORMAT(pa.date, '%Y-%m-%d') date, pa.text FROM personal_agenda pa
        WHERE user = $user_id
        GROUP BY DATE_FORMAT(pa.date, '%Y-%m-%d'), pa.text";

$result = Database::query($sql);

while ($row = Database::fetch_object($result)) {
    if (array_key_exists($row->date, $dates)) {
        $dates[$row->date] = 'vlms-datepicker__month__day--both';
    } else {
        $dates[$row->date] = ($row->text == 'virtual-appointment' ? 'vlms-datepicker__month__day--presential' : 'vlms-datepicker__month__day--virtual');
    }
}
?>

<div class="vlms">
    <div class="vlms-title-divider">Citas por fecha</div>
    <?php calendar_appointment($dates, 'style="margin-top: 24px;"'); ?>

    <div class="text-center" style="margin-top: 16px;">
        <span class="vlms-badge vlms-bgc--sun-flower">presencial</span>
        <span class="vlms-badge vlms-bgc--peter-river">virtual</span>
        <span class="vlms-badge vlms-bgc--emerald">presencial/virtual</span>
    </div>

    <div class="vlms-block" style="height: 300px; padding: 0; margin-top: 16px;" id="appointments-by-date-availability">
        <div class="vlms-scrollable vlms-scrollable--y">
            <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                <li class="vlms-title-divider">Citas</li>
                <li class="vlms-list__item">Sin Citas</li>
            </ul>
        </div>
    </div>
</div>

<script>
 	$('[data-toggle=tooltip]').boostrapTooltip();
    $('[data-toggle=popover]').boostrapPopover();
    $('.vlms-datepicker [data-date]').click(function(e) {
        var $sup = $(this);
        $('#appointments-by-date .vlms-datepicker .active').removeClass('active');
        $sup.addClass('active');
        $.ajax({
            url: VLMS.MAIN_URI + 'tutoring/tutor/appointments_by_date_availability.php',
            data: {
                uid: VLMS.USER_ID,
                d: $sup.attr('data-date')
            }
        })
            .done(function(view) { $('#appointments-by-date-availability').html(view); });
    });
    </script>