<?php require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

$user_id        = is_null($_GET['uid']) ? '' : $_GET['uid'];
$appointment_id = is_null($_GET['aid']) ? '' : $_GET['aid'];

$sql = "SELECT * FROM personal_agenda WHERE id = $appointment_id LIMIT 1";

$availability = Database::fetch_object(Database::query($sql));

$title   = $availability->title;
$text    = $availability->text;
$date    = $availability->date;
$enddate = $availability->enddate;
$course  = $availability->course;

$sql = "SELECT COUNT(*) FROM personal_agenda pa WHERE pa.user = $user_id AND pa.parent_event_id = $appointment_id";

if (Database::num_rows(Database::query($sql))) {
    $sql = "INSERT INTO personal_agenda (user, title, text, date, enddate, course, parent_event_id)
            VALUES ($user_id, '$title', '$text', '$date', '$enddate', '$course', $appointment_id)";
}

Database::query($sql);
exit;
