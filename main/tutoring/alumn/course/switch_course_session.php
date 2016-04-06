<?php require_once '../../../inc/lib/system/session.class.php';

include_once '../../../inc/global.inc.php';

$cid = is_null($_GET['cid']) ? '' : $_GET['cid'];

if (!empty($cid)) {
    \System\Session::erase('_real_cid');
    \System\Session::erase('_cid');
    \System\Session::erase('_course');
    \System\Session::erase('coursesAlreadyVisited');
    \System\Session::erase('is_allowed_in_course');

    $_course = api_get_course_info_by_id($cid);
    $_cid = $_course['code'];

    \System\Session::write('_real_cid', $cid);
    \System\Session::write('_cid', $_cid);
    \System\Session::write('_course', $_course);
    \System\Session::write('coursesAlreadyVisited', [$_cid => 1]);
    \System\Session::write('is_allowed_in_course', 1);
}

exit;
