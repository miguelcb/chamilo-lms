<?php
/* For licensing terms, see /license.txt */
/**
 *  @package chamilo.messages
 */
$cidReset = true;

require_once '../inc/global.inc.php';

api_block_anonymous_users();

$table_message   = Database::get_main_table(TABLE_MESSAGE);
$api_get_user_id = api_get_user_id();
$sql = "SELECT m.id, m.user_sender_id, CONCAT(u.username) user_sender,  m.title, m.send_date, m.msg_status
        FROM $table_message m
        INNER JOIN user u ON u.id = m.user_sender_id
        WHERE m.user_receiver_id = $api_get_user_id AND m.msg_status IN (0, 1)
        ORDER BY m.send_date DESC";

$result = Database::query($sql);

?>
<div class="row" id="inbox">
    <?php if (Database::num_rows($result)): ?>
    <div class="col-md-4">
        <ul class="list-group messages-tutoring">
            <li class="list-group-item">
                <button class="btn btn-block btn-danger" id="delete-all-messages"><?php echo get_lang('Borrar todos los mensajes'); ?></button>
            </li>
        <?php while($row = Database::fetch_assoc($result)): ?>
            <li class="list-group-item messages-tutoring__item" role="button" data-message-id="<?php echo $row['id']; ?>">
                <h4 class="list-group-item-heading clearfix" style="position: relative; padding-right: 24px;">
                    <div class="pull-left"><?php echo $row['user_sender']; ?></div>
                    <div class="pull-right small" style="padding: 0;"><?php echo api_convert_and_format_date($row['sender_date'], '%b %d') ?></div>
                    <div class="btn-group" style="position: absolute; right: 0;">
                      <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="padding: 2px 4px; line-height: 1;">
                        <span class="caret"></span>
                        <span class="sr-only">Toggle Dropdown</span>
                      </button>
                      <ul class="dropdown-menu dropdown-menu-right">
                        <li>
                            <!--<a class="message-tutoring__item__delete" onclick="javascript:if(!confirm('<?php echo addslashes(api_htmlentities(get_lang('ConfirmDeleteMessage'))); ?>')) return false;" href="<?php echo api_get_path(WEB_CODE_PATH); ?>messages/inbox.php?action=deleteone&id=<?php echo $row['id'] ?>"><?php echo get_lang('DeleteMessage'); ?></a>-->
                            <a class="message-tutoring__item__delete" href="#" data-message-delete-id="<?php echo $row['id']; ?>"><?php echo get_lang('DeleteMessage'); ?></a>
                        </li>
                      </ul>
                    </div>
                </h4>
                <div class="list-group-item-text"><?php echo $row['title']; ?></div>
            </li>
        <?php endwhile; ?>
        </ul>
    </div>
    <div class="col-md-8 message-tutoring">
        <div class="alert alert-info">Haz click en alguno de los mensajes para ver el mensaje completo</div>
    </div>
    <?php else: ?>
    <div class="alert alert-info">No tienes mensajes</div>
    <?php endif; ?>
</div>

<script>
    $('.messages-tutoring__item').click(function() {
        var $sup = $(this);
        $('.messages-tutoring__item').removeClass('active');
        $sup.addClass('active');
        $.ajax({
            url: '<?php echo api_get_path(WEB_CODE_PATH).'messages/view_message_tutoring.php'; ?>',
            data: { id: $sup.attr('data-message-id') }
        })
            .done(function(view) {
                $('.message-tutoring').html(view);
            });
    });

    $('.message-tutoring__item__delete').click(function (e) {
        e.stopPropagation();
        var $sup = $(this);

        $.ajax({
            url: '<?php echo api_get_path(WEB_CODE_PATH); ?>messages/inbox.php',
            data: { action: 'deleteone', id: $sup.attr('data-message-delete-id') }
        })
            .done(function() {
                $sup.closest('[data-message-id]').remove();
                $('.message-tutoring').empty();
            });
    });

    $('#delete-all-messages').click(function() {
        if (!window.confirm('¿Eliminar todos los mensajes?')) return false;

        $.ajax({
            url: '<?php echo api_get_path(WEB_CODE_PATH); ?>messages/inbox.php',
            data: {
                action: 'delete',
                id: $.map($('[data-message-delete-id]'), function(m) {
                    return +$(m).attr('data-message-delete-id');
                })
            }
        })
            .done(function() {
                $('#inbox').html('<div class="alert alert-info">No tienes mensajes</div>');
            });
    });
</script>
