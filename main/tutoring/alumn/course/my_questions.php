<?php require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

$cid     = is_null($_GET['cid']) ? '' : $_GET['cid'];
$user_id = api_get_user_id();

$table_forum_post       = Database::get_course_table(TABLE_FORUM_POST);
$table_forum_attachment = Database::get_course_table(TABLE_FORUM_ATTACHMENT);

$my_questions = [];

$sql = "SELECT *, (SELECT COUNT(*) FROM $table_forum_post fpr WHERE fpr.post_parent_id = fp.post_id) answered FROM $table_forum_post fp
        INNER JOIN user u on u.user_id = fp.poster_id
        WHERE fp.c_id = $cid AND fp.poster_id = $user_id AND u.status = 5";

$questions = Database::query($sql);

while ($question = Database::fetch_assoc($questions)) {
    $post_id = $question['post_id'];

    $my_questions[$question['post_id']] = $question;

    $sql = "SELECT * FROM $table_forum_attachment fa WHERE fa.c_id = $cid AND fa.post_id = $post_id";
    $attachments = Database::query($sql);

    while ($attachment = Database::fetch_assoc($attachments)) {
        $my_questions[$question['post_id']]['attachments'][] = $attachment;
    }
}
?>

<?php if (count($my_questions) > 0): ?>
<div class="row">
    <div class="col-md-4">
        <div class="vlms">
            <div class="vlms-block">
                <div class="vlms-scrollable vlms-scrollable--y">
                    <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                        <li class="vlms-title-divider">Mis preguntas en el curso</li>
                        <?php foreach ($my_questions as $question): ?>
                            <li class="vlms-list__item" role="button" data-question-id="<?php echo $question['post_id']; ?>">
                                <div class="vlms-media">
                                    <div class="vlms-media__figure">
                                        <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#share_post"></use>
                                        </svg>
                                    </div>
                                    <div class="vlms-media__body">
                                        <div class="vlms-media__body__title clearfix">
                                            <a class="pull-left" href="javascript:void(0);"><?php echo $question['post_title']; ?></a>
                                        </div>
                                        <div class="vlms-media__body__detail">
                                            <ul class="vlms-list vlms-list--horizontal vlms-has-dividers vlms-text--small">
                                                <li class="vlms-list__item"><?php echo api_convert_and_format_date($question['post_date'], '%b %d, %Y'); ?></li>
                                                <li class="vlms-list__item"><strong><?php echo $question['answered'] == '1' ? 'Atendida' : 'Sin atender'; ?></strong></li>
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
        <ul class="list-group">
            <li class="list-group-item">
                <div class="input-group">
                    <input type="text" class="form-control" aria-describedby="basic-addon1">
                    <span class="input-group-addon fa fa-search" role="button"></span>
                </div>
            </li>
            <?php foreach ($my_questions as $question): ?>
                <li class="list-group-item" role="button" data-question-id="<?php echo $question['post_id']; ?>">
                    <h4 class="list-group-item-heading clearfix">
                        <div class="pull-left"><?php echo $question['pseudonym']; ?></div>
                        <div class="pull-right small" style="padding: 0;"><?php echo api_convert_and_format_date($question['post_date'], '%b %d') ?></div>
                    </h4>
                    <div class="list-group-item-text"><?php echo $question['post_title']; ?></div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-md-8 question-tutoring">
        <div class="alert alert-info">Haz click en alguna de las preguntas para ver la pregunta completa</div>
    </div>
</div>
<?php else: ?>
    <div class="vlms">
        <div class="vlms-block">
            <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                <li class="vlms-title-divider">Mis preguntas</li>
                <li class="vlms-list__item">
                    <div class="vlms-media__body">
                        <div class="vlms-media__body__title">No tienes preguntas en el curso</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
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
