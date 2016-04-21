<?php $cidReset = true; require_once '../../../inc/global.inc.php';

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
    <?php if (Database::num_rows($result) > 0): ?>
    <div class="col-md-4">
        <div class="vlms">
            <div class="vlms-block">
                <div class="vlms-scrollable vlms-scrollable--y">
                    <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions messages-tutoring">
                        <li class="vlms-list__item">
                            <button class="btn btn-block btn-danger" id="delete-all-messages"><?php echo get_lang('Borrar todos los mensajes'); ?></button>
                        </li>
                        <?php while($row = Database::fetch_assoc($result)): ?>
                            <li class="vlms-list__item messages-tutoring__item" role="button" data-message-id="<?php echo $row['id']; ?>">
                                <div class="vlms-media">
                                    <div class="vlms-media__body">
                                        <div class="vlms-media__body__title">
                                            <a class="vlms-truncate vlms-pr--medium" href="javascript:void(0);"><?php echo $row['title']; ?></a>
                                            <button class="vlms-list__item__action message-tutoring__item__delete" data-message-delete-id="<?php echo $row['id']; ?>">
                                                <svg aria-hidden="true" class="vlms-list__item__action__icon" data-toggle="tooltip" data-container="body" data-placement="left" title="Eliminar">
                                                    <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#close"></use>
                                                </svg>
                                                <span class="sr-only">Show More</span>
                                            </button>
                                        </div>
                                        <div class="vlms-media__body__detail">
                                            <ul class="vlms-list vlms-list--vertical vlms-text--small">
                                                <li class="vlms-list__item"><?php echo date_to_str_ago($row['send_date']); ?></li>
                                                <li class="vlms-list__item">
                                                    <strong><?php echo $row['user_sender']; ?></strong>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                            </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 message-tutoring">
        <div class="alert alert-info">Haz click en alguno de los mensajes para ver el mensaje completo</div>
    </div>
    <?php else: ?>
    <div class="alert alert-info">No tienes mensajes</div>
    <?php endif; ?>
</div>

<script>
    $('[data-toggle=tooltip]').boostrapTooltip();

    $('.messages-tutoring__item').click(function() {
        var $sup = $(this);
        $('.messages-tutoring__item').removeClass('active');
        $sup.addClass('active');
        $.ajax({
            url: VLMS.URI + 'message/view.php',
            data: { id: $sup.attr('data-message-id') }
        })
            .done(function(view) {
                $('.message-tutoring').html(view);
            });
    });

    $('.message-tutoring__item__delete').click(function (e) {
        $('[data-toggle=tooltip]').boostrapTooltip('hide');
        e.stopPropagation();
        var $sup = $(this);

        $.ajax({
            url: VLMS.URI + 'message/inbox.php',
            data: {
                action: 'deleteone',
                id: $sup.attr('data-message-delete-id')
            }
        })
            .done(function() {
                $sup.closest('[data-message-id]').remove();
                $('.message-tutoring').empty();
            });
    });

    $('#delete-all-messages').click(function() {
        if (!window.confirm('¿Eliminar todos los mensajes?')) return false;

        $.ajax({
            url: VLMS.URI + 'message/inbox.php',
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
