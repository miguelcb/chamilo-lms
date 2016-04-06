{% if login_form %}
<div class="panel-sesion">
  <div class="panel-body">
    <h2 class="text-uppercase">ingresa a tu cuenta</h2>
    {{ login_failed }}
    <form method="POST" action="{{ _p.web }}index.php">
      <div class="form-group">
        <input type="text" name="login" id="login" class="form-control" placeholder="{{ 'UserName'|get_lang }}">
      </div>
      <div class="form-group">
        <input type="password" name="password" id="password" class="form-control" placeholder="{{ 'Pass'|get_lang }}">
      </div>
      <div class="col-md-12">
        <div class="input-group">
          <a href="{{ _p.web_ajax }}form.ajax.php?a=get_captcha&amp;var=template.lib" target="_blank" onclick="var cancelClick = false; if (document.images) {  var img = new Image();  var d = new Date();  img.src = this.href + ((this.href.indexOf('?') == -1) ? '?' : '&amp;') + d.getTime();  document.images['QF_CAPTCHA_captcha_question'].src = img.src;  cancelClick = true;} return !cancelClick;">
            <img src="http://localhost:90/chamilo-lms/main/inc/ajax/form.ajax.php?a=get_captcha&amp;var=template.lib" name="QF_CAPTCHA_captcha_question" id="QF_CAPTCHA_captcha_question" height="40" title="Click to view another image">
          </a>
          <span class="help-block" style="color: #fff; font-weight: bold;">Haz clic en la imagen para cambiarla</span>
        </div>
      </div>
      <div class="input-group">
        <input class="form-control" name="captcha" type="text" placeholder="Escribe las letras de la imagen">
      </div>
      <div class="form-group col-md-12">
        <button type="submit" class="btn btn-danger">{{ 'LoginEnter'|get_lang }} <span class="fa fa-arrow-right"></span></button>
        <button type="reset" class="btn btn-default">Limpiar</button>
      </div>
    </form>
  </div>
</div>
{% endif %}
