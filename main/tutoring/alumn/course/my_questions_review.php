<?php require_once '../../../inc/global.inc.php';

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
        ORDER BY fp.post_date DESC
        LIMIT 5";

$questions = Database::query($sql);
?>

<div class="vlms">
    <div class="vlms-block" style="height: auto;">
        <div class="vlms-scrollable vlms-scrollable--y">
            <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                <li class="vlms-title-divider">Mis preguntas en el curso</li>
                <?php if (Database::num_rows($questions) > 0): ?>
                    <?php while ($row = Database::fetch_assoc($questions)): ?>
                        <li class="vlms-list__item">
                            <div class="vlms-media">
                                <div class="vlms-media__figure">
                                    <svg aria-hidden="true" class="vlms-icon" style="fill: #555;">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#share_post"></use>
                                    </svg>
                                </div>
                                <div class="vlms-media__body">
                                    <div class="vlms-media__body__title">
                                        <a class="vlms-truncate vlms-pr--medium" href="javascript:void(0);"><?php echo $row['post_text']; ?></a>
                                        <span class="vlms-badge vlms-badge--inverse"><?php echo $row['answered'] == '1' ? 'Atendida' : 'Sin atender'; ?></span>
                                    </div>
                                    <div class="vlms-media__body__detail">
                                        <ul class="vlms-list vlms-list--horizontal vlms-has-dividers vlms-text--small">
                                            <li class="vlms-list__item"><?php echo api_convert_and_format_date($row['post_date'], '%b %d, %Y'); ?></li>
                                            <li class="vlms-list__item"><?php echo $row['teach'] == '' ? 'Sin asignar' : 'Quispe Zapata, Juan'; ?></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endwhile; ?>
                    <div class="panel-footer clearfix">
                        <a href="javascript:void(0);" class="pull-right" data-toggle="ajax-modal" id="my-questions-link" data-target="#my-questions-modal" data-source="<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/my_questions.php?cid=<?php echo $cid; ?>">ver más</a>
                    </div>
                <?php else: ?>
                <li class="vlms-list__item">
                    <div class="vlms-media">
                        <div class="vlms-media__body">
                            <div class="vlms-media__body__title">No tienes preguntas en el curso</div>
                        </div>
                    </div>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
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
