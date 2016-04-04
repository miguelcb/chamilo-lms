<?php include_once '../../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}
?>

<div class="text-center">
    <h3 style="margin-top: 0;">Lunes, 1 de Enero</h3>
    <h4>John Doe</h4>
    <div class="btn-group" data-toggle="buttons">
      <label class="btn btn-primary active">
        <input type="checkbox" autocomplete="off" checked> Presencial
      </label>
      <label class="btn btn-primary">
        <input type="checkbox" autocomplete="off"> Virtual
      </label>
    </div>
</div>

