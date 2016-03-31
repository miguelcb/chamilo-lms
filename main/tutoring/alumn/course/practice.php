<?php include_once '../../../inc/global.inc.php';

api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824)
    {
        $bytes = number_format($bytes / 1073741824, 2) . ' GB';
    }
    elseif ($bytes >= 1048576)
    {
        $bytes = number_format($bytes / 1048576, 2) . ' MB';
    }
    elseif ($bytes >= 1024)
    {
        $bytes = number_format($bytes / 1024, 2) . ' KB';
    }
    elseif ($bytes > 1)
    {
        $bytes = $bytes . ' bytes';
    }
    elseif ($bytes == 1)
    {
        $bytes = $bytes . ' byte';
    }
    else
    {
        $bytes = '0 bytes';
    }

    return $bytes;
}

$cid     = is_null($_GET['cid']) ? '' : $_GET['cid'];
$user_id = api_get_user_id();

$resources = [];

$sql = "SELECT * FROM c_lp_item lpi
        WHERE lpi.c_id = $cid AND lpi.lp_id = (SELECT lp.id FROM c_lp lp WHERE lp.category_id = (SELECT iid FROM c_lp_category WHERE name = 'Practicar' LIMIT 1) LIMIT 1)
        ORDER BY lpi.display_order";

$result = Database::query($sql);

while ($row = Database::fetch_assoc($result)) {
    $c_id = $row['c_id'];
    $id   = $row['path'];

    $sql = "SELECT *, (SELECT lastedit_date FROM c_item_property WHERE c_id = 1 AND tool = 'document' AND ref = d.id) date FROM c_document d
            WHERE d.c_id = $c_id AND d.id = $id
            LIMIT 1";

    $row['document_info'] = Database::fetch_assoc(Database::query($sql));
    $resources[] = $row;
}
?>

<?php //Display::display_header(); ?>

<?php if (count($resources) > 0): ?>
<div class="row">
    <div class="col-md-4">
        <ul class="list-group">
            <?php foreach ($resources as $row): ?>
                <li role="button" class="list-group-item <?php echo ($row['path'] == '0' ? 'active' : ''); ?>" data-resource-practice-id="<?php echo $row['id']; ?>" data-resource-practice-lp-id="<?php echo $row['lp_id'] ?>">
                    <?php if ($row['path'] != '0'): ?>
                    <div class="media">
                        <div class="media-left">
                            <span class="fa fa-file fa-icon-size--small"></span>
                        </div>
                        <div class="media-body">
                            <h5 class="media-heading"><?php echo $row['title']; ?></h5>
                            <div><?php echo api_convert_and_format_date($row['document_info']['date'], '%b %d'); ?>, <?php echo formatSizeUnits($row['document_info']['size']); ?></div>
                            <div>
                                <span class="fa fa-star"></span> 1
                                <span class="fa fa-share-alt"></span> 4
                                <span class="fa fa-eye"></span> 15
                                <span class="fa fa-download"></span> 3
                            </div>
                        </div>
                    </div>
                    <?php else: ?>
                        <?php echo $row['title']; ?>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="col-md-8" id="resource-practice">
        <div class="alert alert-info">Haz click en alguno de los materiales para ver el material completo</div>
    </div>
</div>
<?php else: ?>
<div class="row" style="padding: 32px 0;">
    <div class="col-md-12">
        <div class="alert alert-info">No hay materiales</div>
    </div>
</div>
<?php endif; ?>

<script>
    $('[data-resource-practice-id]').click(function() {
        $.ajax({
            url: '<?php echo api_get_path(WEB_CODE_PATH); ?>tutoring/alumn/course/view_resource.php',
            data: {
                cid: '<?php echo $cid; ?>',
                lpid: $(this).attr('data-resource-practice-lp-id'),
                id: $(this).attr('data-resource-practice-id')
            }
        })
            .done(function(view) {
                $('#resource-practice').html(view);
            });
    });
</script>

<?php //Display::display_footer(); ?>
