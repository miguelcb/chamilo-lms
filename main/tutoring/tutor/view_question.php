<?php require_once '../../inc/lib/system/session.class.php';
require_once '../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

$post_id = is_null($_GET['id']) ? "" : $_GET['id'];
$course_code = is_null($_GET['course_code']) ? "" : $_GET['course_code'];
$course_id = is_null($_GET['course_id']) ? "" : $_GET['course_id'];

$table_forum_post       = Database::get_course_table(TABLE_FORUM_POST);
$table_forum_attachment = Database::get_course_table(TABLE_FORUM_ATTACHMENT);

$sql = "SELECT * FROM $table_forum_post fp WHERE fp.post_id = $post_id LIMIT 1";

$result   = Database::query($sql);
$question = Database::fetch_assoc($result);

$sql = "SELECT * FROM $table_forum_post fp WHERE fp.post_parent_id = $post_id LIMIT 1";

$question['answer'] = Database::fetch_assoc(Database::query($sql));

$c_id = $question['c_id'];

$sql = "SELECT * FROM $table_forum_attachment fa WHERE fa.c_id = $c_id AND fa.post_id = $post_id";
$question_attachments = Database::query($sql);

$post_answer_id = isset($question['answer']['post_id']) ? $question['answer']['post_id'] : 0;

$sql = "SELECT * FROM $table_forum_attachment fa WHERE fa.c_id = $c_id AND fa.post_id = $post_answer_id";

$answer_attachments = Database::query($sql);

if (!empty($course_id)) {
    \System\Session::erase('_real_cid');
    \System\Session::erase('_cid');
    \System\Session::erase('_course');
    \System\Session::erase('coursesAlreadyVisited');
    \System\Session::erase('is_allowed_in_course');

    $_course = api_get_course_info_by_id($course_id);
    $_cid = $_course['code'];

    \System\Session::write('_real_cid', $course_id);
    \System\Session::write('_cid', $_cid);
    \System\Session::write('_course', $_course);
    \System\Session::write('coursesAlreadyVisited', [$_cid => 1]);
    \System\Session::write('is_allowed_in_course', 1);

    $_forum_notification = $_SESSION['forum_notification'];
    $_forum_notification['forum'][] = $question['answer']['forum_id'];

    \System\Session::write('forum_notification', $_forum_notification);
}


?>



<?php if ($question['answer'] != null): ?>
<div class="panel panel-default">
    <div class="panel-body">
        <div class="media">
            <div class="media-left">
                <img src="<?php echo UserManager::getUserPicture(api_get_user_id(), USER_IMAGE_SIZE_ORIGINAL); ?>" alt="" style="width: 64px; height: 64px;">
            </div>
            <div class="media-body">
                <h4 class="media-heading"><?php echo $question['post_title']; ?></h4>
                <?php echo $question['post_text']; ?>
                <?php if (Database::num_rows($question_attachments) > 0): ?>
                    <div class="panel panel-default">
                        <div class="panel-body">
                        <?php while($row = Database::fetch_assoc($question_attachments)): ?>
                            <a href="<?php echo api_get_path(WEB_CODE_PATH).'forum/download.php?file='.$row['path']; ?>" style="text-decoration: none;">
                                <span class="fa fa-file"></span>
                                <?php echo $row['filename']; ?>
                            </a>
                        <?php endwhile; ?>
                        </div>
                    </div>
                <?php endif; ?>
                <?php if (!empty($question['answer'])):?>
                    <div class="media">
                        <div class="media-left">
                            <img src="<?php echo UserManager::getUserPicture($question['answer']['poster_id'], USER_IMAGE_SIZE_ORIGINAL); ?>" alt="" style="width: 64px; height: 64px;">
                        </div>
                        <div class="media-body">
                            <h4 class="media-heading"><?php echo $question['answer']['post_title']; ?></h4>
                            <?php echo $question['answer']['post_text']; ?>
                            <?php if (Database::num_rows($answer_attachments) > 0): ?>
                                <div class="panel panel-default">
                                    <div class="panel-body">
                                    <?php while($row = Database::fetch_assoc($answer_attachments)): ?>
                                        <a href="<?php echo api_get_path(WEB_CODE_PATH).'forum/download.php?file='.$row['path']; ?>" style="text-decoration: none;">
                                            <span class="fa fa-file"></span>
                                            <?php echo $row['filename']; ?>
                                        </a>
                                    <?php endwhile; ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php else: ?>

<form id="form-ask" action="">
    <input type="hidden" name="post_title" value="Pregunta para responder">
    <input type="hidden" name="_qf__thread">
    <input type="hidden" name="forum_id" value="0">
    <input type="hidden" name="thread_id" value="0">
    <input type="hidden" name="gradebook" value="0">
    <input type="hidden" name="MAX_FILE_SIZE" value="268435456">
    <input type="hidden" name="sec_token" value="<?php echo Security::get_token(); ?>">
    <input type="hidden" name="post_notification" value="1">
    <input type="hidden" name="calification_notebook_title" value="Índice de utilidad">
    <input type="hidden" name="numeric_calification" value="5">
    <div class="form-group" data-course-id="<?php echo $course_id ?>" data-course-code="<?php echo $course_code; ?>" data-course-forum-id="<?php echo $question['forum_id']; ?>" data-course-thread="<?php echo $question['thread_id']; ?>" data-course-post="<?php echo $question['post_id']; ?>">
        <h4 class="media-heading"><?php echo $question['post_title']; ?></h4>
        <p><?php echo $question['post_text']; ?></p>
        <textarea name="post_text" id="question" rows="10" class="form-control" placeholder="Escribe tu respuesta aquí"></textarea>
    </div>
    <div class="clearfix">
        <ul class="list-unstyled text-right course-tutoring__nav">
            <li style="display: inline-block;">
                <label class="btn btn-default fa fa-paperclip" title="Archivos adjuntos">
                    <input type="file" name="user_upload" style="display: none;">
                </label>
            </li>
            <li style="display: inline-block;">
                <input type="hidden" name="SubmitPost" value="Responder">
                <button type="button" class="btn btn-success">Responder</button>
            </li>
        </ul>
    </div>
</form>

<?php endif; ?>
<script type="text/javascript">

    


    (function() {
        var $current = $('.form-group');
            VLMS.current.code = $current.attr('data-course-code') || '';
            VLMS.current.id = $current.attr('data-course-id') || 0;
            VLMS.current.forumID = $current.attr('data-course-forum-id') || 0;
            VLMS.current.threadID = $current.attr('data-course-thread') || 0;
            VLMS.current.postID = $current.attr('data-course-post') || 0

            $('#form-ask').attr('action', VLMS.MAIN_URI + 'forum/reply.php?forum=' + VLMS.current.forumID + '&gradebook=0&thread='+VLMS.current.threadID+'&post='+VLMS.current.postID+'&action=replymessage&cidReq=' + VLMS.current.code + '&id_session=0&gidReq=0&origin=');

            // UPDATE FORUM ID
            // $('#form-ask [name=forum_id]').val(VLMS.current.forumID);
            // // UPDATE TUTORS
            // $.ajax({
            //     url: VLMS.URI + 'course/ask_tutors.php',
            //     data: { cid: courseID }
            // })
            //     .done(function(view) { $('#ask-tutors').html(view); });
            // // UPDATE MY QUESTIONS LIST
            // $.ajax({
            //     url: VLMS.URI + 'course/my_questions_review.php',
            //     data: { cid: courseID }
            // })
            //     .done(function(view) { $('#my-questions').html(view); });
            // // UPDATE LINK REPOSITORY QUESTIONS
            // $('#repository-questions-link').attr('data-source', VLMS.URI + 'course/repository_questions.php?cid=' + courseID);
            // // UPDATE LINK MY QUESTIONS
            // $('#my-questions-link').attr('data-source', VLMS.URI + 'course/my_questions.php?cid=' + courseID);
            // // FORM ASK
            $('#form-ask button').off().click(function(e) {
                $.ajax({
                    url: $('#form-ask').attr('action'),
                    data: new FormData($('#form-ask')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    type: 'POST'
                })
                    .done(function() {
                        $.ajax({
                            url: VLMS.MAIN_URI + 'tutoring/tutor/view_question.php',
                            data: { id: '<?php echo $post_id ?>', course_code: '<?php echo $course_code ?>' , course_id:'<?php echo $course_id ?>' }
                        })
                            .done(function(view) { $('.question-tutoring').html(view); });
                    });
            });
        
    })();
</script>


<!--<h3>Tema de la pregunta</h3>
<p>Pellentesque libero nisi, lobortis vitae aliquam at, euismod vitae orci. Nunc commodo mi nec sagittis lacinia. Sed in justo est. Integer eget nisl sapien. Etiam ullamcorper justo nisi, et sagittis arcu sollicitudin id.Pellentesque libero nisi, lobortis vitae aliquam at, euismod vitae orci. Nunc commodo mi nec sagittis lacinia. Sed in justo est. Integer eget nisl sapien. Etiam ullamcorper justo nisi, et sagittis arcu sollicitudin id.</p>
<ul class="list-group enlinea">
  <li class="list-group-item"><a href="#"><i class="fa fa-picture-o"></i> Archivo1.ppt</a></li>
  <li class="list-group-item"><a href="#"><i class="fa fa-picture-o"></i> Archivo2.ppt</a></li>
</ul>
<div class="respuesta">
  <div class="form-group">
    <label for="selPreguntaTutor">Respuesta de:</label>
    <select class="form-control" id="selPreguntaTutor">
      <option selected>Bruno Díaz</option>
      <option>Ricardo Tapia</option>
      <option>Natasha Romanova</option>
      <option>Peter Parker</option>
    </select>
  </div>
  <p>Pellentesque libero nisi, lobortis vitae aliquam at, euismod vitae orci. Nunc commodo mi nec sagittis lacinia. Sed in justo est. Integer eget nisl sapien. Etiam ullamcorper justo nisi, et sagittis arcu sollicitudin id. Curabitur sed pretium enim. Nulla ac pharetra tellus. Praesent pharetra ante quis rhoncus euismod.</p>
  <ul class="list-group enlinea">
    <li class="list-group-item"><a href="#"><i class="fa fa-picture-o"></i> Archivo1.ppt</a></li>
    <li class="list-group-item"><a href="#"><i class="fa fa-picture-o"></i> Archivo2.ppt</a></li>
  </ul>
  <div>Ha sido útil <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star"></i> <i class="fa fa-star-o"></i> Recomendar<i class="fa fa-share-alt"></i> </div>
  </div><!-- respuesta -->