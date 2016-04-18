<?php require_once '../../../inc/global.inc.php';

require_once '../../helpers.inc.php';

$user_id = api_get_user_id();

$sql = "SELECT t.c_id, t.name FROM c_tool t
        WHERE t.c_id IN (SELECT cu.c_id FROM course_rel_user cu where cu.user_id = $user_id) AND t.name IN ('ask', 'appointment', 'review', 'practice') AND visibility = 1";

$result = Database::query($sql);

$tools = [];

while ($row = Database::fetch_object($result)) {
    $tools[$row->c_id][] = $row->name;
}

echo json_encode($tools);
