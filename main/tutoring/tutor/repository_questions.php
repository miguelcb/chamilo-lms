<?php require_once '../../inc/global.inc.php';

require_once '../helpers.inc.php';

api_block_anonymous_users();

// $cid     = is_null($_GET['cid']) ? 1 : $_GET['cid'];

$table_forum_post       = Database::get_course_table(TABLE_FORUM_POST);
$table_forum_attachment = Database::get_course_table(TABLE_FORUM_ATTACHMENT);

$all_questions = [];

$sql = "SELECT *, (SELECT COUNT(*) FROM $table_forum_post fpr WHERE fpr.post_parent_id = fp.post_id) answered 
        FROM $table_forum_post fp
        JOIN user u on u.id = fp.poster_id
        INNER JOIN course c on c.id = fp.c_id and fp.post_parent_id = 0";

$questions = Database::query($sql);

while ($question = Database::fetch_assoc($questions)) {
    $post_id = $question['post_id'];
    $cid = $question['c_id'];

    $all_questions[$question['post_id']] = $question;

    $sql = "SELECT * FROM $table_forum_attachment fa WHERE fa.c_id = $cid AND fa.post_id = $post_id";
    $attachments = Database::query($sql);

    while ($attachment = Database::fetch_assoc($attachments)) {
        $all_questions[$question['post_id']]['attachments'][] = $attachment;
    }
}
?>

<header class="text-center course-tool__header">
    <div class="row">
        <div class="col-sm-4 col-sm-offset-4">
            <span class="fa fa-question fa-rounded fa-icon-size fa-icon-size--medium vlms-bgc--palette-1" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="" data-content="Responde a las preguntas realizadas por tus alumnos." data-original-title="Preguntas"></span>
        </div>
        <div class="col-sm-4">
            <ul class="list-unstyled text-right course-tutoring__nav">
                <li style="display: inline-block;">
                    <a href="javascript:void(0)" class="fa fa-list-alt fa-icon-size fa-icon-size--medium" style="color: #555; margin: 8px;" data-modal="ajax-modal" data-target="#recent-activities-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/tutor/activity_panel.php" data-toggle="tooltip" data-placement="bottom" data-container="body" title="" data-original-title="Actividades recientes"></a>
                </li>                
            </ul>
        </div>
    </div>
    <div class="text-uppercase">Preguntas</div>
</header>

<?php if (count($all_questions) > 0): ?>
<div class="row" style="padding: 32px 0;">
    <div class="col-md-4">
        <ul class="list-group">
            <!-- <li class="list-group-item">
                <div class="input-group">
                    <input type="text" class="form-control" aria-describedby="basic-addon1">
                    <span class="input-group-addon fa fa-search" role="button"></span>
                </div>
            </li> -->
            <?php foreach ($all_questions as $question): ?>              
                <li class="list-group-item" role="button" data-question-id="<?php echo $question['post_id']; ?>">
                    <h4 class="list-group-item-heading clearfix">
                        <div class="pull-left"><?php echo $question['pseudonym']; ?></div>
                        <div class="pull-right small" style="padding: 0;"><?php echo api_convert_and_format_date($question['post_date'], '%d %B %I:%M %p') ?></div>
                    </h4>
                    <div class="list-group-item-text"><?php echo $question['post_title']; ?></div>
                    <div class="mb-sm"> <small><?php echo $question['title']; ?></small> </div>
                    <strong class="text-primary list-group-item-text"> De <?php echo $question['username']; ?></strong>
                </li>               
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-md-8 question-tutoring">
        <div class="alert alert-info">Haz click en alguna de las preguntas para ver la pregunta completa</div>
    </div>
</div>
<?php else: ?>
<div class="alert alert-info">No hay preguntas</div>
<?php endif; ?>


<script>
    $('[data-toggle=tooltip]').boostrapTooltip();
    $('[data-toggle=popover]').boostrapPopover();

    $('[data-question-id]').click(function() {
        var $sup = $(this);
        $('[data-question-id]').removeClass('active');
        $sup.addClass('active');
        $.ajax({
            url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/tutor/view_question.php',
            data: { id: $sup.attr('data-question-id'), course_code: '<?php echo $question['code']; ?>' , course_id:'<?php echo $question['id']; ?>'}
        })
            .done(function(view) {
                $('.question-tutoring').html(view);
            });
    });

</script>

<!-- START list group
<div class="list-group">
  <!-- START list group item
  <a href="javascript:void(0);" class="list-group-item">
    <div class="media">
      <div class="pull-left"> <input type="checkbox"></div>
      <div class="media-body clearfix"> <small class="pull-right">4 Marzo 3:00 pm</small>
        <div class="media-heading text-green m0">¿Cras sit amet nibh libero, in gravida nulla. Nulla...? </div>
        <p class="mb-sm"> <small>¿Cras sit amet nibh libero, in gravida nulla. Nulla...?</small> </p>
      <strong class="media-heading text-primary"> De Peter Parker</strong> </div>
    </div>
  </a>
 
  <!-- END list group item
</div>-->