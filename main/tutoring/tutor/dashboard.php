<?php
/* For licensing terms, see /license.txt */
/**
* Shows who is online in a specific session
* @package chamilo.main
*/
require_once '../../inc/global.inc.php';
require_once '../helpers.inc.php';
api_block_anonymous_users();
?>
<?php Display::display_header('Dashboard'); ?>
<!-- area-preguntar -->
<section class="course-tools">
  <section class="container-fluid padded row" id="ask_T" style="background-color: rgb(226,228,231); padding-top: 90px;
    padding-bottom: 90px;">
    <div class="container container-white" style="background-color: rgba(255,255,255,0.7);">
      <?php include_once 'repository_questions.php';?>
    </div>
  </section>
  <section id="appointment" class="row course-tool last-child">
    <header class="text-center course-tool__header">
      <span class="fa fa-calendar fa-rounded fa-icon-size fa-icon-size--medium vlms-bgc--palette-1" role="button" data-toggle="popover" data-trigger="hover" data-container="body" title="" data-content="Reservas de citas presenciales o virtuales realizadas por tus alumnos." data-original-title="Citas"></span>
      <div class="text-uppercase">Citas</div>
    </header>
    <section class="container">
      <div class="row" style="padding: 32px 0;">
        <div class="col-sm-4">
          <?php include_once 'appointments_by_tutor.php';?>
        </div>
        <div class="col-sm-4">
          <?php include_once 'appointments_by_date.php';?>
        </div>
        <!-- <div class="col-sm-4">
          
        </div> -->
        
      </div>
    </section>
  </section>
</section>
<a href="#" title="Ir arriba" id="hook-top" class="fa fa-arrow-up"></a>
<?php Display::display_footer(); ?>