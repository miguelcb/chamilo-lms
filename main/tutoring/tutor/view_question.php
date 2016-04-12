<?php require_once '../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

$post_id = is_null($_GET['id']) ? 1 : $_GET['id'];

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
?>
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