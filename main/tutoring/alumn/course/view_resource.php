<?php require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

if (!empty($_SESSION['refresh']) && $_SESSION['refresh'] == 1) {
    // Check if we should do a refresh of the oLP object (for example after editing the LP).
    // If refresh is set, we regenerate the oLP object from the database (kind of flush).
    Session::erase('refresh');
    $myrefresh = 1;
    if ($debug > 0) error_log('New LP - Refresh asked', 0);
}

$c_id    = is_null($_GET['cid']) ? '' : $_GET['cid'];
$lp_id   = is_null($_GET['lpid']) ? '' : $_GET['lpid'];
$id      = is_null($_GET['id']) ? '' : $_GET['id'];
$user_id = api_get_user_id();
$course  = api_get_course_info_by_id($c_id);
$c_code  = $course['code'];

$lp      = new learnpath($c_code, $lp_id, $user_id);

$sql = "SELECT * FROM c_lp_item lpi WHERE c_id = $c_id AND id = $id LIMIT 1";
$resource = Database::fetch_assoc(Database::query($sql));
?>

<div class="panel panel-default text-left">
  <div class="panel-heading">
    <?php echo $resource['title']; ?>
    <ul id="star_1" class="star-rating pull-right">
        <li class="current-rating" style="width:0px;"></li>
        <li><a href="javascript:void(0);" data-link="http://localhost:90/chamilo-lms/main/inc/ajax/course.ajax.php?a=add_course_vote&amp;course_id=1&amp;star=1" title="0 estrellas de 5" class="one-star">1</a></li>
        <li><a href="javascript:void(0);" data-link="http://localhost:90/chamilo-lms/main/inc/ajax/course.ajax.php?a=add_course_vote&amp;course_id=1&amp;star=2" title="0 estrellas de 5" class="two-stars">2</a></li>
        <li><a href="javascript:void(0);" data-link="http://localhost:90/chamilo-lms/main/inc/ajax/course.ajax.php?a=add_course_vote&amp;course_id=1&amp;star=3" title="0 estrellas de 5" class="three-stars">3</a></li>
        <li><a href="javascript:void(0);" data-link="http://localhost:90/chamilo-lms/main/inc/ajax/course.ajax.php?a=add_course_vote&amp;course_id=1&amp;star=4" title="0 estrellas de 5" class="four-stars">4</a></li>
        <li><a href="javascript:void(0);" data-link="http://localhost:90/chamilo-lms/main/inc/ajax/course.ajax.php?a=add_course_vote&amp;course_id=1&amp;star=5" title="0 estrellas de 5" class="five-stars">5</a></li>
    </ul>
</div>
  <div class="panel-body vlms" style="height: 458px;">
    <p><?php echo $resource['description']; ?></p>
    <iframe id="content_id" name="content_name" src="<?php echo api_get_path(WEB_CODE_PATH).'newscorm/lp_controller.php?action=content&lp_id='.$lp_id.'&item_id='.$id.'&cidReq='.$c_code.'&id_session=0'; ?>" border="0" frameborder="0" allowfullscreen="true" webkitallowfullscreen="true" mozallowfullscreen="true" style="width: 100%; height: 100%;"></iframe>
  </div>
</div>


