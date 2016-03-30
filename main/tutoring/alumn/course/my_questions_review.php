<?php

require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

$cid     = is_null($_GET['cid']) ? '' : $_GET['cid'];
$user_id = api_get_user_id();

$table_forum_post = Database::get_course_table(TABLE_FORUM_POST);

$sql = "SELECT *, (SELECT COUNT(*) FROM $table_forum_post fpr WHERE fpr.post_parent_id = fp.post_id) answered FROM $table_forum_post fp
        INNER JOIN user u on u.user_id = fp.poster_id
        WHERE fp.c_id = $cid AND fp.poster_id = $user_id AND u.status = 5
        LIMIT 3";

$questions = Database::query($sql);
?>

<div class="panel panel-primary">
    <div class="panel-heading">
        <h4 class="m0">Mis preguntas</h4>
    </div>
    <?php if (Database::num_rows($questions) > 0): ?>
        <ul class="list-group">
            <?php while ($row = Database::fetch_assoc($questions)): ?>
                <li class="list-group-item"><?php echo $row['post_title']; ?></li>
            <?php endwhile; ?>
        </ul>
        <div class="panel-footer clearfix">
            <a href="javascript:void(0);" class="pull-right" data-toggle="ajax-modal" id="my-questions-link" data-target="#my-questions-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/my_questions.php?cid=<?php echo $cid; ?>">Ver todas mis preguntas</a>
        </div>
    <?php else: ?>
        <div class="panel-body">
            <div class="alert alert-info m0">No hay preguntas</div>
        </div>
    <?php endif; ?>
</div>

<script>
    $('[data-toggle=ajax-modal], [data-modal=ajax-modal]').click(function() {
      var $sup = $(this);
      $.ajax({ url: $sup.attr('data-source') })
        .done(function(view) {
          var $modal = $($sup.attr('data-target'));
          $modal.find('.modal-body').html(view);
          $modal.modal('show');
        });
    });
</script>
