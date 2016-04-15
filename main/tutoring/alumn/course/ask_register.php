<?php require_once '../../../forum/forumfunction.inc.php';

$values = show_add_post_form(
    $current_forum,
    $forum_setting,
    'newthread',
    '',
    isset($_SESSION['formelements']) ? $_SESSION['formelements'] : null
);

if (!empty($values) && isset($values['SubmitPost'])) {
    // Add new thread in table forum_thread.
    store_thread($current_forum, $values);
}
