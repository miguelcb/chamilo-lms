<?php require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

$cid = is_null($_GET['cid']) ? '' : $_GET['cid'];
?>

<div class="alert alert-info">Si deseas dejar de recibir notificaciones, solo tienes que quitar el check</div>

<form action="" class="alert-settings">
    <div class="checkbox" class="alert-setting__setting">
        <label>
          <input type="checkbox" checked> Preguntar
        </label>
    </div>
    <div class="checkbox" class="alert-setting__setting">
        <label>
          <input type="checkbox" checked> Sacar cita
        </label>
    </div>
    <div class="checkbox" class="alert-setting__setting">
        <label>
          <input type="checkbox" checked> Repasar
        </label>
    </div>
    <div class="checkbox" class="alert-setting__setting">
        <label>
          <input type="checkbox" checked> Practicar
        </label>
    </div>
</form>

<script>
    $('.alert-setting__setting input').change(function() {
        $.ajax({
            url: '',
            data: {
                id: '<?php echo $cid; ?>',
                active: $(this).is(':checked')
            }
        });
    });
</script>
