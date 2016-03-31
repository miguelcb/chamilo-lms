<?php $cidReset= true; require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

if (api_get_setting('allow_message_tool')!='true') {
	api_not_allowed();
}

$message_id = intval(Database::escape_string($_GET['id']));

$table_message            = Database::get_main_table(TABLE_MESSAGE);
$table_message_attachment = Database::get_main_table(TABLE_MESSAGE_ATTACHMENT);
$message_status_new       = MESSAGE_STATUS_NEW;
$user_id                  = api_get_user_id();

$query = "UPDATE $table_message
          SET msg_status = '$message_status_new'
          WHERE user_receiver_id = $user_id AND id = '$message_id'";

Database::query($query);

$query  = "SELECT * FROM $table_message
          WHERE msg_status <> 4 AND user_receiver_id = $user_id AND id = '$message_id'";

$result      = Database::query($query);
$row         = Database::fetch_array($result, 'ASSOC');
$sender_info = $from_user = api_get_user_info($row['user_sender_id']);

$query  = "SELECT * FROM $table_message_attachment WHERE message_id = $message_id";
$result = Database::query($query);
?>

<div class="panel panel-default">
    <div class="panel-body">
        <h3 style="margin-top: 0;"><?php echo Security::remove_XSS($row['title'], STUDENT, true); ?></h3>
        <?php echo Security::remove_XSS($row['content'], STUDENT, true); ?>
    </div>
    <?php if (Database::num_rows($result) > 0): ?>
    <div class="panel-footer">
        <?php while($row = Database::fetch_assoc($result)): ?>
            <a href="<?php echo api_get_path(WEB_CODE_PATH).'messages/download.php?type=inbox&file='.$row['path']; ?>" style="text-decoration: none;">
                <span class="fa fa-file"></span>
                <?php echo $row['filename']; ?>
            </a>
        <?php endwhile; ?>
    </div>
    <?php endif; ?>
</div>
