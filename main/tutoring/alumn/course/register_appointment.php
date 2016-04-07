<?php include_once '../../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

$appointment_type = is_null($_GET['appointmentType']) ? '0' : $_GET['appointmentType'];
?>

<div class="text-center">
    <h3 style="margin-top: 0;">Martes, 23 de Noviembre del 2016 <span class="vlms-badge"><?php echo $appointment_type == '0' ? 'cita' : 'chat'; ?></span></h3>
    <h4>8:00 am - 8:45 am</h4>
    <h4>Quispe Zapata, Juan</h4>
    <button type="button" class="btn btn-success">Reservar</button>
</div>

