<?php
/* For licensing terms, see /license.txt */

/**
* This file displays the user's profile,
* optionally it allows users to modify their profile as well.
*
* See inc/conf/profile.conf.php to modify settings
*
* @package chamilo.auth
*/

use Chamilo\UserBundle\Entity\User;
use ChamiloSession as Session;

$cidReset = true;
require_once '../inc/global.inc.php';

if (api_get_setting('allow_social_tool') == 'true') {
    $this_section = SECTION_SOCIAL;
} else {
    $this_section = SECTION_MYPROFILE;
}

//$htmlHeadXtra[] = api_get_password_checker_js('#username', '#password1');

$_SESSION['this_section'] = $this_section;

if (!(isset($_user['user_id']) && $_user['user_id']) || api_is_anonymous($_user['user_id'], true)) {
    api_not_allowed(true);
}

$htmlHeadXtra[] = api_get_password_checker_js('#username', '#password1');
$htmlHeadXtra[] = '<link  href="'. api_get_path(WEB_PATH) .'web/assets/cropper/dist/cropper.min.css" rel="stylesheet">';
$htmlHeadXtra[] = '<script src="'. api_get_path(WEB_PATH) .'web/assets/cropper/dist/cropper.min.js"></script>';
$htmlHeadXtra[] = '<script>
$(document).ready(function() {
    var $image = $("#previewImage");
    var $input = $("[name=\'cropResult\']");
    var $cropButton = $("#cropButton");
    var canvas = "";
    var imageWidth = "";
    var imageHeight = "";

    $("input:file").change(function() {
        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("picture_form").files[0]);

        oFReader.onload = function (oFREvent) {
            $image.attr("src", this.result);
            $("#labelCropImage").html("'.get_lang('Preview').'");
            $("#cropImage").addClass("thumbnail");
            $cropButton.removeClass("hidden");
            // Destroy cropper
            $image.cropper("destroy");

            $image.cropper({
                aspectRatio: 1 / 1,
                responsive : true,
                center : false,
                guides : false,
                movable: false,
                zoomable: false,
                rotatable: false,
                scalable: false,
                crop: function(e) {
                    // Output the result data for cropping image.
                    $input.val(e.x+","+e.y+","+e.width+","+e.height);
                }
            });
        };
    });

    $("#cropButton").on("click", function() {
        var canvas = $image.cropper("getCroppedCanvas");
        var dataUrl = canvas.toDataURL();
        $image.attr("src", dataUrl);
        $image.cropper("destroy");
        $cropButton.addClass("hidden");
        return false;
    });
});

function confirmation(name) {
    if (confirm("'.get_lang('AreYouSureToDeleteJS', '').' " + name + " ?")) {
            document.forms["profile"].submit();
    } else {
        return false;
    }
}
function show_image(image,width,height) {
    width = parseInt(width) + 20;
    height = parseInt(height) + 20;
    window_x = window.open(image,\'windowX\',\'width=\'+ width + \', height=\'+ height + \'\');

}
function generate_open_id_form() {
    $.ajax({
        contentType: "application/x-www-form-urlencoded",
        beforeSend: function(objeto) {
        /*$("#div_api_key").html("Loading...");*/ },
        type: "POST",
        url: "'.api_get_path(WEB_AJAX_PATH).'user_manager.ajax.php?a=generate_api_key",
        data: "num_key_id="+"",
        success: function(datos) {
         $("#div_api_key").html(datos);
        }
    });
}

function hide_icon_edit(element_html)  {
    ident="#edit_image";
    $(ident).hide();
}
function show_icon_edit(element_html) {
    ident="#edit_image";
    $(ident).show();
}
</script>';

$warning_msg = '';
if (!empty($_GET['fe'])) {
    $warning_msg .= get_lang('UplUnableToSaveFileFilteredExtension');
    $_GET['fe'] = null;
}

$jquery_ready_content = '';
if (api_get_setting('allow_message_tool') == 'true') {
    $jquery_ready_content = <<<EOF
    $(".message-content .message-delete").click(function(){
        $(this).parents(".message-content").animate({ opacity: "hide" }, "slow");
        $(".message-view").animate({ opacity: "show" }, "slow");
    });
EOF;
}

$tool_name = is_profile_editable() ? get_lang('ModifProfile') : get_lang('ViewProfile');
$table_user = Database :: get_main_table(TABLE_MAIN_USER);

/*
 * Get initial values for all fields.
 */
$user_data = api_get_user_info(api_get_user_id());
$array_list_key = UserManager::get_api_keys(api_get_user_id());
$id_temp_key = UserManager::get_api_key_id(api_get_user_id(), 'dokeos');
$value_array = $array_list_key[$id_temp_key];
$user_data['api_key_generate'] = $value_array;

if ($user_data !== false) {
    if (api_get_setting('login_is_email') == 'true') {
        $user_data['username'] = $user_data['email'];
    }
    if (is_null($user_data['language'])) {
        $user_data['language'] = api_get_setting('platformLanguage');
    }
}

/*
 * Initialize the form.
 */
$form = new FormValidator(
    'profile',
    'post',
    api_get_self()."?".str_replace('&fe=1', '', Security::remove_XSS($_SERVER['QUERY_STRING'])),
    null
);

// if (api_is_western_name_order()) {
//     //    FIRST NAME and LAST NAME
//     $form->addElement('text', 'firstname', get_lang('FirstName'), array('size' => 40));
//     $form->addElement('text', 'lastname',  get_lang('LastName'),  array('size' => 40));
// } else {
//     //    LAST NAME and FIRST NAME
//     $form->addElement('text', 'lastname',  get_lang('LastName'),  array('size' => 40));
//     $form->addElement('text', 'firstname', get_lang('FirstName'), array('size' => 40));
// }
// if (api_get_setting('profile', 'name') !== 'true') {
//     $form->freeze(array('lastname', 'firstname'));
// }
// $form->applyFilter(array('lastname', 'firstname'), 'stripslashes');
// $form->applyFilter(array('lastname', 'firstname'), 'trim');
// $form->applyFilter(array('lastname', 'firstname'), 'html_filter');
// $form->addRule('lastname' , get_lang('ThisFieldIsRequired'), 'required');
// $form->addRule('firstname', get_lang('ThisFieldIsRequired'), 'required');

//    USERNAME
// $form->addElement(
//     'text',
//     'username',
//     get_lang('UserName'),
//     array(
//         'id' => 'username',
//         'maxlength' => USERNAME_MAX_LENGTH,
//         'size' => USERNAME_MAX_LENGTH,
//     )
// );
// if (api_get_setting('profile', 'login') !== 'true' || api_get_setting('login_is_email') == 'true') {
//     $form->freeze('username');
// }
// $form->applyFilter('username', 'stripslashes');
// $form->applyFilter('username', 'trim');
// $form->addRule('username', get_lang('ThisFieldIsRequired'), 'required');
// $form->addRule('username', get_lang('UsernameWrong'), 'username');
// $form->addRule('username', get_lang('UserTaken'), 'username_available', $user_data['username']);

//    PSEUDONYM
$form->addElement(
    'text',
    'pseudonym',
    'Seudónimo',
    array(
        'id' => 'pseudonym',
        'maxlength' => 8,
        'size' => USERNAME_MAX_LENGTH,
    )
);
$form->addRule('pseudonym', get_lang('ThisFieldIsRequired'), 'required');

//    PHONE
$form->addElement('text', 'phone', get_lang('Phone'), array('size' => 20));
$form->applyFilter('phone', 'stripslashes');
$form->applyFilter('phone', 'trim');
$form->applyFilter('phone', 'html_filter');
$form->addRule('phone', get_lang('ThisFieldIsRequired'), 'required');

//  PICTURE
if (is_profile_editable() && api_get_setting('profile', 'picture') == 'true') {
    $form->addElement(
        'file',
        'picture',
        ($user_data['picture_uri'] != '' ? get_lang('UpdateImage') : get_lang(
            'AddImage'
        )),
        array('id' => 'picture_form', 'class' => 'picture-form')
    );
    $form->addHtml(''
                . '<div class="form-group">'
                    . '<label for="cropImage" id="labelCropImage" class="col-sm-2 control-label"></label>'
                        . '<div class="col-sm-8">'
                            . '<div id="cropImage" class="cropCanvas">'
                                . '<img id="previewImage" src="'.($user_data['picture_uri'] != '' ? UserManager::getUserPicture(api_get_user_id(), USER_IMAGE_SIZE_ORIGINAL) : '').'">'
                            . '</div>'
                            . '<div>'
                                . '<button class="btn btn-primary hidden" name="cropButton" id="cropButton"><em class="fa fa-crop"></em> '.get_lang('CropYourPicture').'</button>'
                            . '</div>'
                        . '</div>'
                . '</div>'
    . '');
    $form->addHidden('cropResult', '');
    $form->add_progress_bar();
    if (!empty($user_data['picture_uri'])) {
        $form->addElement('checkbox', 'remove_picture', null, get_lang('DelImage'));
    }
    $allowed_picture_types = api_get_supported_image_extensions();
    $form->addRule(
        'picture',
        get_lang('OnlyImagesAllowed').' ('.implode(', ', $allowed_picture_types).')',
        'filetype',
        $allowed_picture_types
    );
}

// the $jquery_ready_content variable collects all functions that
// will be load in the $(document).ready javascript function
$htmlHeadXtra[] ='<script>
$(document).ready(function(){
    '.$jquery_ready_content.'
});
</script>';

if (api_get_setting('profile', 'apikeys') == 'true') {
    $form->addElement('html', '<div id="div_api_key">');
    $form->addElement(
        'text',
        'api_key_generate',
        get_lang('MyApiKey'),
        array('size' => 40, 'id' => 'id_api_key_generate')
    );
    $form->addElement('html', '</div>');
    $form->addElement(
        'button',
        'generate_api_key',
        get_lang('GenerateApiKey'),
        array(
            'id' => 'id_generate_api_key',
            'onclick' => 'generate_open_id_form(); return false;',
        )
    ); //generate_open_id_form()
}
//    SUBMIT
if (is_profile_editable()) {
    $form->addButtonUpdate(get_lang('SaveSettings'), 'apply_change');
} else {
    $form->freeze();
}
$form->setDefaults($user_data);

/**
 * Is user auth_source is platform ?
 *
 * @return  boolean if auth_source is platform
 */
function is_platform_authentication() {
    $tab_user_info = api_get_user_info();
    return $tab_user_info['auth_source'] == PLATFORM_AUTH_SOURCE;
}

/**
 * Can a user edit his/her profile?
 *
 * @return    boolean    Editability of the profile
 */
function is_profile_editable() {
    return $GLOBALS['profileIsEditable'];
}

$filtered_extension = false;

if ($form->validate()) {
    $wrong_current_password = false;
    $user_data = $form->getSubmitValues(1);

    $user = UserManager::getRepository()->find(api_get_user_id());

    // set password if a new one was provided
    $validPassword = false;
    $passwordWasChecked = false;
    if ($user &&
        (!empty($user_data['password0']) &&
        !empty($user_data['password1'])) ||
        (!empty($user_data['password0']) &&
        api_get_setting('profile', 'email') == 'true')
    ) {
        $passwordWasChecked = true;
        $validPassword = UserManager::isPasswordValid(
            $user_data['password0'],
            $user
        );

        if ($validPassword) {
            $password = $user_data['password1'];
        } else {
            Display::addFlash(
                Display:: return_message(
                    get_lang('CurrentPasswordEmptyOrIncorrect'),
                    'warning',
                    false
                )
            );
        }
    }

    $allow_users_to_change_email_with_no_password = true;
    if (is_platform_authentication() &&
        api_get_setting('allow_users_to_change_email_with_no_password') == 'false'
    ) {
        $allow_users_to_change_email_with_no_password = false;
    }

    // If user sending the email to be changed (input available and not frozen )
    if (api_get_setting('profile', 'email') == 'true') {
        if ($allow_users_to_change_email_with_no_password) {
            if (!check_user_email($user_data['email'])) {
                $changeemail = $user_data['email'];
            }
        } else {
            // Normal behaviour
            if (!check_user_email($user_data['email']) && $validPassword) {
                $changeemail = $user_data['email'];
            }

            if (!check_user_email($user_data['email']) &&
                empty($user_data['password0'])
            ){
                Display::addFlash(
                    Display:: return_message(
                        get_lang('ToChangeYourEmailMustTypeYourPassword'),
                        'error',
                        false
                    )
                );
            }
        }
    }

    // Upload picture if a new one is provided
    if ($_FILES['picture']['size']) {
        $new_picture = UserManager::update_user_picture(
            api_get_user_id(),
            $_FILES['picture']['name'],
            $_FILES['picture']['tmp_name'],
            $user_data['cropResult']
        );

        if ($new_picture) {
            $user_data['picture_uri'] = $new_picture;

            Display::addFlash(
                Display:: return_message(
                    get_lang('PictureUploaded'),
                    'normal',
                    false
                )
            );
        }
    } elseif (!empty($user_data['remove_picture'])) {
        // remove existing picture if asked
        UserManager::delete_user_picture(api_get_user_id());
        $user_data['picture_uri'] = '';
    }

    // Remove production.
    if (isset($user_data['remove_production']) &&
        is_array($user_data['remove_production'])
    ) {
        foreach (array_keys($user_data['remove_production']) as $production) {
            UserManager::remove_user_production(api_get_user_id(), urldecode($production));
        }
        if ($production_list = UserManager::build_production_list(api_get_user_id(), true, true)) {
            $form->insertElementBefore(
                $form->createElement('static', null, null, $production_list),
                'productions_list'
            );
        }
        $form->removeElement('productions_list');
        Display::addFlash(
            Display:: return_message(get_lang('FileDeleted'), 'normal', false)
        );
    }

    // upload production if a new one is provided
    if (isset($_FILES['production']) && $_FILES['production']['size']) {
        $res = upload_user_production(api_get_user_id());
        if (!$res) {
            //it's a bit excessive to assume the extension is the reason why
            // upload_user_production() returned false, but it's true in most cases
            $filtered_extension = true;
        } else {
            Display::addFlash(
                Display:: return_message(
                    get_lang('ProductionUploaded'),
                    'normal',
                    false
                )
            );
        }
    }

    // remove values that shouldn't go in the database
    unset(
        $user_data['password0'],
        $user_data['password1'],
        $user_data['password2'],
        $user_data['MAX_FILE_SIZE'],
        $user_data['remove_picture'],
        $user_data['apply_change'],
        $user_data['email']
    );

    // Following RFC2396 (http://www.faqs.org/rfcs/rfc2396.html), a URI uses ':' as a reserved character
    // we can thus ensure the URL doesn't contain any scheme name by searching for ':' in the string
    $my_user_openid = isset($user_data['openid']) ? $user_data['openid'] : '';
    if (!preg_match('/^[^:]*:\/\/.*$/', $my_user_openid)) {
        //ensure there is at least a http:// scheme in the URI provided
        $user_data['openid'] = 'http://'.$my_user_openid;
    }
    $extras = array();

    //Checking the user language
    $languages = api_get_languages();
    if (!in_array($user_data['language'], $languages['folder'])) {
        $user_data['language'] = api_get_setting('platformLanguage');
    }
    $_SESSION['_user']['language'] = $user_data['language'];

    //Only update values that are request by the "profile" setting
    $profile_list = api_get_setting('profile');
    //Adding missing variables

    $available_values_to_modify = array();
    foreach ($profile_list as $key => $status) {
        if ($status == 'true') {
            switch($key) {
                case 'login':
                    $available_values_to_modify[] = 'username';
                    break;
                case 'name':
                    $available_values_to_modify[] = 'firstname';
                    $available_values_to_modify[] = 'lastname';
                    break;
                case 'picture':
                    $available_values_to_modify[] = 'picture_uri';
                    break;
                default:
                    $available_values_to_modify[] = $key;
                    break;
            }
        }
    }

    //Fixing missing variables
    $available_values_to_modify = array_merge(
        $available_values_to_modify,
        array('competences', 'diplomas', 'openarea', 'teach', 'openid', 'pseudonym')
    );

    // build SQL query
    $sql = "UPDATE $table_user SET";
    unset($user_data['api_key_generate']);

    foreach ($user_data as $key => $value) {
        if (substr($key, 0, 6) == 'extra_') { //an extra field
           continue;
        } elseif (strpos($key, 'remove_extra_') !== false) {
        } else {
            if (in_array($key, $available_values_to_modify)) {
                $sql .= " $key = '".Database::escape_string($value)."',";
            }
        }
    }

    $changePassword = false;
    // Change email
    if ($allow_users_to_change_email_with_no_password) {
        if (isset($changeemail) && in_array('email', $available_values_to_modify)) {
            $sql .= " email = '".Database::escape_string($changeemail)."' ";
        }
        if (isset($password) && in_array('password', $available_values_to_modify)) {
            $changePassword = true;
            /*$password = api_get_encrypted_password($password);
            $sql .= " password = '".Database::escape_string($password)."'";*/
        } else {
            // remove trailing , from the query we have so far
            //$sql = rtrim($sql, ',');
        }
    } else {
        if (isset($changeemail) && !isset($password) && in_array('email', $available_values_to_modify)) {
            $sql .= " email = '".Database::escape_string($changeemail)."'";
        } else {
            if (isset($password) && in_array('password', $available_values_to_modify)) {
                if (isset($changeemail) && in_array('email', $available_values_to_modify)) {
                    $sql .= " email = '".Database::escape_string($changeemail)."' ";
                }
                $changePassword = true;
                /*$password = api_get_encrypted_password($password);
                $sql .= " password = '".Database::escape_string($password)."'";*/
            } else {
                // remove trailing , from the query we have so far
                //$sql = rtrim($sql, ',');
            }
        }
    }

    $sql = rtrim($sql, ',');

    if ($changePassword && !empty($password)) {
        UserManager::updatePassword(api_get_user_id(), $password);
    }

    if (api_get_setting('profile', 'officialcode') == 'true' &&
        isset($user_data['official_code'])
    ) {
        $sql .= ", official_code = '".Database::escape_string($user_data['official_code'])."'";
    }

    $sql .= " WHERE user_id  = '".api_get_user_id()."'";
    Database::query($sql);

    if ($passwordWasChecked == false) {
        Display::addFlash(
            Display:: return_message(get_lang('ProfileReg'), 'normal', false)
        );
    } else {
        if ($validPassword) {
            Display::addFlash(
                Display:: return_message(get_lang('ProfileReg'), 'normal', false)
            );
        }
    }

    $extraField = new ExtraFieldValue('user');
    $extraField->saveFieldValues($user_data);

    $userInfo = api_get_user_info();
    Session::write('_user', $userInfo);
}

?>
<form action="">
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
    <div class="form-group">
        <label for="phone" class="control-label"><?php echo get_lang('Phone'); ?></label>
        <input type="phone" name="phone" id="phone" class="form-control"  value="<?php echo $user_data['phone']; ?>">
    </div>
    <div class="form-group">
        <label for="pseudonym" class="control-label">Seudónimo</label>
        <input type="pseudonym" name="pseudonym" id="pseudonym" class="form-control"  value="<?php echo $user_data['pseudonym']; ?>">
    </div>
    <div class="alert alert-info">
        asdasda <strong>dasdasd</strong> adas
    </div>
</form>

