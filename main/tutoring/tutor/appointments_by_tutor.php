	<div class="vlms">
		<div class="vlms-title-divider">Rerserva por fecha</div>
		<?php calendar_appointment($dates, 'style="margin-top: 24px;"'); ?>
		<div class="text-center" style="margin-top: 16px;">
			<span class="vlms-badge vlms-bgc--sun-flower">presencial</span>
			<span class="vlms-badge vlms-bgc--peter-river">virtual</span>
			<span class="vlms-badge vlms-bgc--emerald">presencial/virtual</span>
		</div>
	</div>
	<script type="text/javascript">
  $('[data-toggle=tooltip]').boostrapTooltip();
    $('[data-toggle=popover]').boostrapPopover();

</script>