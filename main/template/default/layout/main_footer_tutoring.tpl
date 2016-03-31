{% if show_footer == true %}
    {% include template ~ "/layout/footer_tutoring.tpl" %}
{% endif %}
    <div class="modal fade" id="profile-modal" tabindex="-1" role="dialog" aria-labelledby="profile-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <form action="{{ _p.web_main }}tutoring/alumn/profile.php" method="POST" data-parsley-validate data-parsley-errors-messages-disabled data-parsley-ajax="true" data-parsley-ajax-success="parsleyAjaxClose">
            <div class="modal-content">
              <div class="modal-header x-modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ 'Close' | get_lang }}">
                  <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="profile-modal-label">Perfil</h4>
              </div>
              <div class="modal-body" id="profile-modal-update"></div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">{{ 'Close' | get_lang }}</button>
                <button type="submit" class="btn btn-success">{{ 'Save' | get_lang }}</button>
              </div>
            </div>
          </form>
        </div>
    </div>

    <div class="modal fade" id="messages-modal" tabindex="-1" role="dialog" aria-labelledby="messages-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header x-modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="{{ 'Close' | get_lang }}">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="messages-modal-label">Mensajes</h4>
            </div>
            <div class="modal-body" id="messages-modal-update"></div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="news-modal" tabindex="-1" role="dialog" aria-labelledby="news-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header x-modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="{{ 'Close' | get_lang }}">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="news-modal-label">Novedades</h4>
            </div>
            <div class="modal-body" id="news-modal-update"></div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="subscribe-modal" tabindex="-1" role="dialog" aria-labelledby="subscribe-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header x-modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="{{ 'Close' | get_lang }}">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="subscribe-modal-label">Suscribirte a una tutoría</h4>
            </div>
            <div class="modal-body" id="subscribe-modal-update"></div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="recent-activities-modal" tabindex="-1" role="dialog" aria-labelledby="recent-activities-modal-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
          <div class="modal-content">
            <div class="modal-header x-modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="{{ 'Close' | get_lang }}">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="recent-activities-modal-label">Actividades recientes</h4>
            </div>
            <div class="modal-body" id="recent-activities-modal-update"></div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="alert-settings-modal" tabindex="-1" role="dialog" aria-labelledby="alert-settings-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header x-modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="{{ 'Close' | get_lang }}">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="alert-settings-modal-label">Configuración de alertas</h4>
            </div>
            <div class="modal-body" id="alert-settings-modal-update"></div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="my-questions-modal" tabindex="-1" role="dialog" aria-labelledby="my-questions-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header x-modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="{{ 'Close' | get_lang }}">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="my-questions-modal-label">Mis preguntas</h4>
            </div>
            <div class="modal-body" id="my-questions-modal-update"></div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="repository-questions-modal" tabindex="-1" role="dialog" aria-labelledby="repository-questions-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
          <div class="modal-content">
            <div class="modal-header x-modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="{{ 'Close' | get_lang }}">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="repository-questions-modal-label">Repositorio de preguntas</h4>
            </div>
            <div class="modal-body" id="repository-questions-modal-update"></div>
          </div>
        </div>
    </div>

    <div class="modal fade" id="appointment-modal" tabindex="-1" role="dialog" aria-labelledby="appointment-modal-label" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
          <div class="modal-content">
            <div class="modal-header x-modal-header">
              <button type="button" class="close" data-dismiss="modal" aria-label="{{ 'Close' | get_lang }}">
                <span aria-hidden="true">&times;</span>
              </button>
              <h4 class="modal-title" id="appointment-modal-label">Sacar cita</h4>
            </div>
            <div class="modal-body" id="appointment-modal-update"></div>
          </div>
        </div>
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
