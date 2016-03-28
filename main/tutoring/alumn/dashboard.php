<?php
/* For licensing terms, see /license.txt */
/**
 * Shows who is online in a specific session
 * @package chamilo.main
 */

include_once '../../inc/global.inc.php';
api_block_anonymous_users();

function pretty_print($v) {
    print('<pre>'.print_r($v, true).'</pre>');
}

$user_id = api_get_user_id();

$table_course      = Database::get_main_table(TABLE_MAIN_COURSE);
$table_course_user = Database::get_main_table(TABLE_MAIN_COURSE_USER);

$sql = "SELECT * FROM $table_course c
        INNER JOIN $table_course_user cu ON cu.c_id = c.id AND cu.user_id = $user_id
        ORDER BY cu.sort ASC";

$courses    = Database::query($sql);
$indicators = Database::query($sql);
?>

<?php Display::display_header('Dashboard'); ?>
<section class="row" id="courses-tutoring-wrapper">
    <div id="courses-tutoring" class="carousel" data-ride="carousel" data-interval="false">
      <ol class="carousel-indicators">
        <?php $counter = 0; ?>
        <?php while($indicator = Database::fetch_assoc($indicators)): ?>
            <li data-target="#courses-tutoring" data-slide-to="<?php echo $counter; ?>" class="<?php echo $counter == 0 ? 'active' : ''; ++$counter; ?>" data-toggle="tooltip" data-container="body" title="<?php echo $indicator['title']; ?>"></li>
        <?php endwhile; ?>
      </ol>

      <div class="carousel-inner" role="listbox">
        <?php $counter = 0; ?>
        <?php while($course = Database::fetch_assoc($courses)): ?>
            <div class="item <?php echo $counter == 0 ? 'active' : ''; ++$counter; ?>">
                <h2 class="text-center"><?php echo $course['title']; ?></h2>
            </div>
        <?php endwhile; ?>
      </div>

      <a class="left carousel-control" href="#courses-tutoring" role="button" data-slide="prev" style="background: none;">
        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
      </a>
      <a class="right carousel-control" href="#courses-tutoring" role="button" data-slide="next" style="background: none;">
        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
      </a>
    </div>
</section>
<section style="height: 756px;" id="ask" class="row course-tool">
    <header class="text-center course-tool__header">
        <span class="fa fa-question fa-rounded" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Preguntar" data-content="And here's some amazing content. It's very engaging. Right?"></span>
        <div class="text-uppercase">preguntar</div>
    </header>
</section>
<section style="height: 756px;" id="appointment" class="row course-tool">
    <header class="text-center course-tool__header">
        <span class="fa fa-calendar fa-rounded" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Sacar cita" data-content="And here's some amazing content. It's very engaging. Right?"></span>
        <div class="text-uppercase">sacar cita</div>
    </header>
</section>
<section style="height: 756px;" id="review" class="row course-tool">
    <header class="text-center course-tool__header">
        <span class="fa fa-book fa-rounded" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Repasar" data-content="And here's some amazing content. It's very engaging. Right?"></span>
        <div class="text-uppercase">repasar</div>
    </header>
</section>
<section style="height: 756px;" id="practice" class="row course-tool">
    <header class="text-center course-tool__header">
        <span class="fa fa-edit fa-rounded" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="Practicar" data-content="And here's some amazing content. It's very engaging. Right?"></span>
        <div class="text-uppercase">practicar</div>
    </header>
</section>

<script>
    $.fn.boostrapTooltip = $.fn.tooltip.noConflict();
    $.fn.boostrapPopover = $.fn.popover.noConflict();
    $('[data-toggle=tooltip]').boostrapTooltip();
    $('[data-toggle=popover]').boostrapPopover();
</script>

<?php Display::display_footer(); ?>
