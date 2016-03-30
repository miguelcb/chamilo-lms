<?php

require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

$cid = is_null($_GET['cid']) ? '' : $_GET['cid'];
?>

<form action="" class="alert-settings">
    <div class="checkbox" class="alert-setting__setting">
        <label>
          <input type="checkbox"> Preguntar
        </label>
    </div>
    <div class="checkbox" class="alert-setting__setting">
        <label>
          <input type="checkbox"> Sacar cita
        </label>
    </div>
    <div class="checkbox" class="alert-setting__setting">
        <label>
          <input type="checkbox"> Repasar
        </label>
    </div>
    <div class="checkbox" class="alert-setting__setting">
        <label>
          <input type="checkbox"> Practicar
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
