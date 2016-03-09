{% if show_footer == true %}
    {% include template ~ "/layout/footer_tutoring.tpl" %}
{% endif %}
    <div class="modal fade" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="profile-modal-label">
      <form action="{{ _p.web_main }}auth/profile_tutoring.php" method="POST" data-parsley-validate data-parsley-errors-messages-disabled data-parsley-ajax="true" data-parsley-ajax-success="parsleyAjaxClose">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header x-modal-header">
              <h4 class="modal-title" id="profile-modal-label">Perfil</h4>
            </div>
            <div class="modal-body" id="profile-modal-update"></div>
            <div class="modal-footer">
              <button type="button" class="btn btn-danger" data-dismiss="modal">{{ 'Close' | get_lang }}</button>
              <button type="submit" class="btn btn-success">{{ 'Save' | get_lang }}</button>
            </div>
          </div>
        </div>
      </form>
    </div>

    {# Global modal, load content by AJAX call to href attribute on anchor tag with 'ajax' class #}
    <div class="modal fade" id="global-modal" tabindex="-1" role="dialog" aria-labelledby="global-modal-title" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ "Close" | get_lang }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="global-modal-title">&nbsp;</h4>
                </div>
                <div class="modal-body">
                </div>
            </div>
        </div>
    </div>
</body>
</html>
