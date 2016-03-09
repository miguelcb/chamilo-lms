<script>
  $("form").on("click", ' .advanced_parameters', function() {
    /*var id = $(this).attr('id') + '_options';
     console.log(id);
     $("#"+id).toggleClass('active');
     var button = $(this);
     $("#"+id).toggle(function() {
     $("#"+id).toggleClass('active');
     });*/
  });

  /* Makes row highlighting possible */
  $(document).ready( function() {
    // Date time settings.
    moment.locale('{{ locale }}');
    $.datepicker.setDefaults($.datepicker.regional["{{ locale }}"]);
    $.datepicker.regional["local"] = $.datepicker.regional["{{ locale }}"];

    // Chosen select
    $(".chzn-select").chosen({
      disable_search_threshold: 10,
      no_results_text: '{{ 'SearchNoResultsFound' | get_lang }}',
      placeholder_text_multiple: '{{ 'SelectSomeOptions' | get_lang }}',
      placeholder_text_single: '{{ 'SelectAnOption' | get_lang }}',
      width: "100%"
    });

    // Bootstrap tabs.
    $('.tab-wrapper a').click(function (e) {
      e.preventDefault();
      $(this).tab('show');

      //$('#tabs a:first').tab('show') // Select first tab
    });

    // Fixes bug when loading links inside a tab.
    $('.tab-wrapper .tab-pane a').unbind();

    /**
     * Advanced options
     * Usage
     * <a id="link" href="url">Advanced</a>
     * <div id="link_options">
     *     hidden content :)
     * </div>
     * */
    $(".advanced_options").on("click", function (event) {
      event.preventDefault();
      var id = $(this).attr('id') + '_options';
      var button = $(this);
      button.toggleClass('active');
      $("#" + id).toggle();
    });

    /**
     * <a class="advanced_options_open" href="http://" rel="div_id">Open</a>
     * <a class="advanced_options_close" href="http://" rel="div_id">Close</a>
     * <div id="div_id">Div content</div>
     * */
    $(".advanced_options_open").on("click", function (event) {
      event.preventDefault();
      var id = $(this).attr('rel');
      $("#" + id).show();
    });

    $(".advanced_options_close").on("click", function (event) {
      event.preventDefault();
      var id = $(this).attr('rel');
      $("#" + id).hide();
    });

    // Adv multi-select search input.
    $('.select_class_filter').on('focus', function () {
      var inputId = $(this).attr('id');
      inputId = inputId.replace('-filter', '');
      $("#" + inputId).filterByText($("#" + inputId + "-filter"));
    });

    $(".jp-jplayer audio").addClass('skip');

    // Mediaelement
    if ( {{ show_media_element }} == 1) {
      jQuery('video:not(.skip), audio:not(.skip)').mediaelementplayer(/* Options */);
    }

    // Table highlight.
    $("form .data_table input:checkbox").click(function () {
      $(this).parentsUntil("tr").parent()[$(this).is(":checked") ? 'addClass' : 'removeClass']('row_selected');
    });

    /* For non HTML5 browsers */
    if ($("#formLogin".length > 1)) {
      $("input[name=login]").focus();
    }

    /* For IOS users */
    $('.autocapitalize_off').attr('autocapitalize', 'off');

    // Tool tip (in exercises)
    $('.boot-tooltip').tooltip({ placement: 'right' });

    $('[data-toggle=ajax-modal]').click(function() {
      var $sup = $(this);
      $.ajax({ url: $sup.attr('data-source') })
        .done(function(view) {
          var $modal = $($sup.attr('data-target'));
          $modal.find('.modal-body').html(view);
          $modal.modal('show');
        });
    });

    $('[data-parsley-ajax=true]')
      .parsley()
      .on('field:error', function() {
        this.$element.parent()
          .removeClass('has-success')
          .addClass('has-error');

        this.$element.next()
          .removeClass('glyphicon-ok')
          .addClass('glyphicon-remove');

        this.$element.next().next()
          .html('<span class="text-danger">' + this.options.errorMessage + '</span>');
      })
      .on('field:success', function() {
        this.$element.parent()
          .removeClass('has-error')
          .addClass('has-success');

        this.$element.next()
          .removeClass('glyphicon-remove')
          .addClass('glyphicon-ok');

        this.$element.next().next()
          .empty();
      })
      .on('form:submit', function(e) {
        var $sup = this.$element;
        console.log($sup.serialize());
        $.ajax({
          url: $sup.attr('action'),
          method: $sup.attr('method'),
          data: $sup.serialize()
        })
          .done(function() { window[$sup.attr('data-parsley-ajax-success')]($sup); });
        return false;
      });

      window.parsleyAjaxClose = function(f) { f.parent().modal('hide'); };
  });
</script>
