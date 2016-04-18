<?php require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

$cid     = is_null($_GET['cid']) ? '' : $_GET['cid'];

$table_forum_post       = Database::get_course_table(TABLE_FORUM_POST);
$table_forum_attachment = Database::get_course_table(TABLE_FORUM_ATTACHMENT);

$all_questions = [];

$sql = "SELECT *, (SELECT COUNT(*) FROM $table_forum_post fpr WHERE fpr.post_parent_id = fp.post_id) answered FROM $table_forum_post fp
        INNER JOIN user u ON u.user_id = fp.poster_id
        WHERE fp.c_id = $cid AND u.status = 5 AND (SELECT COUNT(*) FROM $table_forum_post fpr WHERE fpr.post_parent_id = fp.post_id) > 0";

$questions = Database::query($sql);

while ($question = Database::fetch_assoc($questions)) {
    $post_id = $question['post_id'];

    $all_questions[$question['post_id']] = $question;

    $sql = "SELECT * FROM $table_forum_attachment fa WHERE fa.c_id = $cid AND fa.post_id = $post_id";
    $attachments = Database::query($sql);

    while ($attachment = Database::fetch_assoc($attachments)) {
        $all_questions[$question['post_id']]['attachments'][] = $attachment;
    }
}

?>
<?php if (count($all_questions) > 0): ?>
<div class="row">
    <div class="col-md-4">
        <div class="vlms">
            <div class="vlms-block">
                <div class="vlms-scrollable vlms-scrollable--y">
                    <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                        <li class="vlms-title-divider">Repositorio de preguntas</li>
                        <?php foreach ($all_questions as $question): ?>
                            <li class="vlms-list__item" role="button" data-question-id="<?php echo $question['post_id']; ?>">
                                <div class="vlms-media">
                                    <div class="vlms-media__figure">
                                        <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#share_post"></use>
                                        </svg>
                                    </div>
                                    <div class="vlms-media__body">
                                        <div class="vlms-media__body__title vlms-truncate">
                                            <a href="javascript:void(0);"><?php echo $question['post_title']; ?></a>
                                        </div>
                                        <div class="vlms-media__body__detail">
                                            <ul class="vlms-list vlms-list--horizontal vlms-has-dividers vlms-text--small">
                                                <li class="vlms-list__item"><?php echo api_convert_and_format_date($question['post_date'], '%b %d, %Y'); ?></li>
                                                <li class="vlms-list__item"><?php echo $question['pseudonym']; ?></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 question-tutoring">
        <div class="alert alert-info">Haz click en alguna de las preguntas para ver la pregunta completa</div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-info">No hay preguntas</div>
<?php endif; ?>

<script>
    $('[data-question-id]').click(function() {
        var $sup = $(this);
        $('[data-question-id]').removeClass('active');
        $sup.addClass('active');
        $.ajax({
            url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/view_question.php',
            data: { id: $sup.attr('data-question-id') }
        })
            .done(function(view) {
                $('.question-tutoring').html(view);
            });
    });
</script>
