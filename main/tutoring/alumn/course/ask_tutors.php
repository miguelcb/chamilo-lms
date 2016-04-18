<?php require_once '../../../inc/global.inc.php';

$course_id = is_null($_GET['cid']) ? '' : $_GET['cid'];

$sql = "SELECT
            u.user_id,
            u.username,
            u.firstname,
            u.lastname,
            u.email,
            u.phone,
            u.active
        FROM course_rel_user cu
        INNER JOIN user u ON u.user_id = cu.user_id
        WHERE cu.c_id = $course_id AND cu.status = 1 AND u.active = 1 AND (SELECT COUNT(*) FROM admin a WHERE a.user_id = cu.user_id) < 1";

$result = Database::query($sql);
?>

<option value="0">Sin preferencia (Tutor)</option>
<?php while ($row = Database::fetch_object($result)): ?>
<option value="<?php echo $row->user_id; ?>"><?php echo $row->lastname . ', ' . $row->firstname;  ?></option>
<?php endwhile; ?>
