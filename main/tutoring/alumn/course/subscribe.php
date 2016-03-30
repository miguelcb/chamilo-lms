<?php

require_once '../../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

$user_id       = api_get_user_id();
$category_code = 'TUT';

$table_course      = Database::get_main_table(TABLE_MAIN_COURSE);
$table_course_user = Database::get_main_table(TABLE_MAIN_COURSE_USER);

$sql = "SELECT *, (SELECT count(*) FROM $table_course_user cu WHERE cu.c_id = c.id AND cu.user_id = $user_id) subscribed
        FROM $table_course c
        WHERE c.category_code = '$category_code'";

$courses = Database::query($sql);
?>

<ul class="list-group">
    <?php while ($course = Database::fetch_assoc($courses)): ?>
        <li class="list-group-item <?php echo (!!+$course['subscribed'] ? 'list-group-item-info' : '') ?>" role="button" data-course-code="<?php echo $course['code']; ?>">
            <h4 class="list-group-item-heading clearfix" style="position: relative; padding-right: 24px;">
                <div class="pull-left"><?php echo $course['title']; ?> (<?php echo $course['department_name']; ?>)</div>
                <div class="btn-group" style="position: absolute; right: 0;">
                  <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 2px 4px; line-height: 1;">
                    <span class="caret"></span>
                    <span class="sr-only">Toggle Dropdown</span>
                  </button>
                  <ul class="dropdown-menu dropdown-menu-right">
                    <li>
                        <?php if (!!+$course['subscribed']): ?>
                            <a href="javascript:void(0)" data-unsubscribe-course-code="<?php echo $course['code']; ?>"> Cancelar suscripción</a>
                        <?php else: ?>
                            <a href="javascript:void(0)" data-subscribe-course-code="<?php echo $course['code']; ?>"> Suscribirme</a>
                        <?php endif; ?>
                    </li>
                  </ul>
                </div>
            </h4>
            <div class="list-group-item-text"><?php echo $course['description']; ?></div>
        </li>
    <?php endwhile; ?>
</ul>

<script>
    $('[data-subscribe-course-code]').click(function(e) {
        console.log($(this).attr('data-subscribe-course-code'));
        e.preventDefault();
        $.ajax({
            url: '<?php echo api_get_path(WEB_CODE_PATH); ?>auth/courses.php',
            data: {
                action: 'subscribe_course',
                sec_token: '<?php echo Security::get_existing_token(); ?>',
                subscribe_course: $(this).attr('data-subscribe-course-code')
            }
        })
            .done(function(view) {
                $.ajax({ url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/subscribe.php' })
                    .done(function(view) { window.location.reload(); });
            });
    });

    $('[data-unsubscribe-course-code]').click(function(e) {
        e.preventDefault();
        $.ajax({
            url: '<?php echo api_get_path(WEB_CODE_PATH); ?>auth/courses.php',
            data: {
                action: 'unsubscribe',
                sec_token: '<?php echo Security::get_existing_token(); ?>',
                unsubscribe: $(this).attr('data-unsubscribe-course-code')
            }
        })
            .done(function(view) {
                $.ajax({ url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/subscribe.php' })
                    .done(function(view) { window.location.reload(); });
            });
    });
</script>
