<?php include_once '../../../inc/global.inc.php';

include_once '../../helpers.inc.php';

api_block_anonymous_users();

$cid     = is_null($_GET['cid']) ? '' : $_GET['cid'];
$user_id = api_get_user_id();

$resources = [];

$sql = "SELECT * FROM c_lp_item lpi
        WHERE lpi.c_id = $cid AND lpi.lp_id = (SELECT lp.id FROM c_lp lp WHERE lp.category_id = (SELECT iid FROM c_lp_category lc WHERE lc.c_id = $cid AND lc.name = 'Practicar' LIMIT 1) LIMIT 1)
        ORDER BY lpi.display_order";

$result = Database::query($sql);

while ($row = Database::fetch_assoc($result)) {
    $c_id = $row['c_id'];
    $id   = $row['path'];

    $sql = "SELECT *, (SELECT lastedit_date FROM c_item_property WHERE c_id = 1 AND tool = 'document' AND ref = d.id) date FROM c_document d
            WHERE d.c_id = $c_id AND d.id = $id
            LIMIT 1";

    $row['file_info'] = Database::fetch_assoc(Database::query($sql));

    switch ($row['item_type']) {
        case 'document':
            $row['file_info']['type'] = pathinfo($row['file_info']['path'], PATHINFO_EXTENSION);
            break;
        case 'link':
            $row['file_info']['type'] = 'link';
            break;
        case 'quiz':
            $row['file_info']['type'] = 'box_notes';
            break;
        default:
            $row['file_info']['type'] = '';
            break;
    }

    $resources[] = $row;
}
?>

<?php //Display::display_header(); ?>

<?php if (count($resources) > 0): ?>
<div class="row">
    <div class="col-md-4 vlms">
        <div class="vlms-block">
            <div class="vlms-scrollable vlms-scrollable--y">
                <ul class="vlms-list vlms-list--vertical vlms-has-dividers vlms-has-interactions">
                    <?php foreach ($resources as $row): ?>
                        <?php if ($row['path'] == '0'): ?>
                            <li class="vlms-title-divider"><?php echo $row['title']; ?></li>
                        <?php else: ?>
                            <li class="vlms-list__item" data-resource-practice-id="<?php echo $row['id']; ?>" data-resource-practice-lp-id="<?php echo $row['lp_id'] ?>">
                                <div class="vlms-media">
                                    <div class="vlms-media__figure">
                                      <svg aria-hidden="true" class="vlms-icon">
                                        <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#<?php echo extension_icon($row['file_info']['type']); ?>"></use>
                                      </svg>
                                    </div>
                                    <div class="vlms-media__body">
                                      <div class="vlms-media__body__title">
                                        <a href="javascript:void(0); vlms-truncate" title="<?php echo $row['title']; ?>"><?php echo $row['title']; ?></a>
                                      </div>
                                      <ul class="vlms-media__body__detail vlms-list vlms-list--horizontal vlms-has-dividers vlms-text--small">
                                        <li class="vlms-list__item"><?php echo api_convert_and_format_date($row['file_info']['date'], '%b %d, %Y'); ?></li>
                                        <li class="vlms-list__item"><?php echo human_readable_filesize($row['file_info']['size']); ?></li>
                                      </ul>
                                      <ul class="vlms-media__body__detail vlms-list vlms-list--horizontal vlms-has-dividers vlms-text--small">
                                        <li class="vlms-list__item">
                                          <svg aria-hidden="true" class="vlms-icon vlms-icon--x-small" style="fill: #333;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#favorite"></use>
                                          </svg>
                                          <span><?php echo rand(0, 15); ?></span>
                                        </li>
                                        <li class="vlms-list__item">
                                          <svg aria-hidden="true" class="vlms-icon vlms-icon--x-small" style="fill: #333;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#socialshare"></use>
                                          </svg>
                                          <span><?php echo rand(0, 15); ?></span>
                                        </li>
                                        <li class="vlms-list__item">
                                          <svg aria-hidden="true" class="vlms-icon vlms-icon--x-small" style="fill: #333;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#preview"></use>
                                          </svg>
                                          <span><?php echo rand(0, 15); ?></span>
                                        </li>
                                        <li class="vlms-list__item">
                                          <svg aria-hidden="true" class="vlms-icon vlms-icon--x-small" style="fill: #333;">
                                            <use xmlns:xlink="http://www.w3.org/1999/xlink" xlink:href="#download"></use>
                                          </svg>
                                          <span><?php echo rand(0, 15); ?></span>
                                        </li>
                                      </ul>
                                    </div>
                                  </div>
                            </li>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-8">
        <div class="vlms-block">
            <div class="vlms-scrollable vlms-scrollable--y">
                <div id="resource-practice">
                    <div class="alert alert-info">Haz click en alguno de los materiales para ver el material completo</div>
                </div>
            </div>
        </div>
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
