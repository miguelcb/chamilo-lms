<?php

use Chamilo\UserBundle\Entity\User;
use ChamiloSession as Session;

$cidReset = true;

require_once '../../inc/global.inc.php';

$_SESSION['this_section'] = $this_section;

if (!(isset($_user['user_id']) && $_user['user_id']) || api_is_anonymous($_user['user_id'], true)) {
    api_not_allowed(true);
}

$user_data = api_get_user_info(api_get_user_id());
$token = Security::get_token();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['sec_token'])) {
    $POST = array_intersect_key($_POST, array('phone' => 0, 'pseudonym' => 0, 'picture_uri' => 0));
    $sql = sprintf(
        "UPDATE %s SET %s WHERE user_id = '%s'",
        Database::get_main_table(TABLE_MAIN_USER),
        implode(', ', array_map(function($k, $v) {
            return sprintf("%s = '%s'", $k, Database::escape_string($v));
        }, array_keys($POST), array_values($POST))),
        api_get_user_id()
    );
    Database::query($sql);

    $userInfo = api_get_user_info();
    Session::write('_user', $userInfo);
    exit;
}
?>

<div class="text-center" style="padding: 16px 0;">
    <!--<img src="<?php echo UserManager::getUserPicture(api_get_user_id(), USER_IMAGE_SIZE_ORIGINAL); ?>" alt="" width="100" height="100" class="img-circle">-->
    <img width="100" height="100" class="img-circle" role="button" src="<?php echo UserManager::getUserPicture(api_get_user_id(), USER_IMAGE_SIZE_ORIGINAL); ?>" data-avatar-path="<?php echo api_get_path(WEB_CODE_PATH).'img/avatars/'; ?>" data-avatar-id="<?php echo (empty($user_data['picture_uri']) ? '0' : str_replace('.png', '', $user_data['picture_uri'])) ?>" alt="" onclick="(function(e) {
        var n = (+$(e).attr('data-avatar-id') >= 12 ? 1 : +$(e).attr('data-avatar-id') + 1),
            filename = n + '.png';
        $(e)
            .attr('data-avatar-id', n)
            .attr('src', $(e).attr('data-avatar-path') + filename);
        $(e).next().val(filename);
    })(this)">
    <input type="hidden" name="picture_uri" value="<?php echo $user_data['picture_uri']; ?>">
    <span class="help-block">Haz click en la imagen para cambiar de avatar</span>
</div>
<div class="form-group">
    <label for="lastname" class="control-label"><?php echo get_lang('LastName'); ?></label>
    <input type="text" name="lastname" id="lastname" class="form-control"  value="<?php echo $user_data['lastname']; ?>" readonly>
</div>
<div class="form-group">
    <label for="firstname" class="control-label"><?php echo get_lang('FirstName'); ?></label>
    <input type="text" name="firstname" id="firstname" class="form-control"  value="<?php echo $user_data['firstname']; ?>" readonly>
</div>
<div class="form-group">
    <label for="email" class="control-label"><?php echo get_lang('Email'); ?></label>
    <input type="email" name="email" id="email" class="form-control"  value="<?php echo $user_data['email']; ?>" readonly>
</div>
<div class="form-group has-feedback">
    <label for="phone" class="control-label"><?php echo get_lang('Phone'); ?></label>
    <input type="phone" name="phone" id="phone" class="form-control"  value="<?php echo $user_data['phone']; ?>" required data-parsley-type="number" data-parsley-error-message="Este campo es requerido y númerico">
    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
    <div class="help-block"></div>
</div>
<div class="form-group has-feedback">
    <label for="pseudonym" class="control-label">Seudónimo</label>
    <input type="pseudonym" name="pseudonym" id="pseudonym" class="form-control"  value="<?php echo $user_data['pseudonym']; ?>" required maxlength="8" data-parsley-error-message="Este campo es requerido">
    <span class="glyphicon form-control-feedback" aria-hidden="true"></span>
    <div class="help-block"></div>
</div>
<input type="hidden" name="sec_token" value="<?php echo $token ?>">
<div class="alert alert-info">
    asdasda <strong>dasdasd</strong> adas
</div>
