<nav class="navbar navbar-default x-navbar" {% if _u.logged == 0 %}style="background-color: #fff; padding: 4px 0; min-height: 64px;"{% endif %}>
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menuone" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="
              {% if _u.logged == 0 %}
              {{ _p.web }}
              {% else %}
                {% if _u.status == 5 %}
                  {{  _p.web_main }}tutoring/alumn/dashboard.php
                {% else %}
                  {{ _p.web }}
                {% endif %}
              {% endif %}" class="navbar-brand">
              {% if _u.logged == 0 %}
                <img src="{{ _p.web_css_theme }}images/logo.png" height="40" alt="">
              {% else %}
              <span>TUTOR</span><br>
              <span>VIRTUAL</span>
              {% endif %}
            </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="menuone">
            <ul class="nav navbar-nav x-navbar__menu">
                {% if _u.status != 5 %}
                {{ menu }}
                {% else %}
                <li class="text-center">
                  <a href="javascript:void(0)" title="Perfil" data-toggle="ajax-modal" data-target="#profile-modal" data-source="{{ _p.web_main }}tutoring/alumn/profile.php">
                    <span class="fa fa-user"></span>Perfil
                  </a>
                </li>
                <li class="text-center">
                  <a href="javascript:void(0)" title="Novedades" data-toggle="ajax-modal" data-target="#news-modal" data-source="{{ _p.web_main }}tutoring/alumn/course/news.php">
                    <span class="badge">{{ count_unread_news }}</span>
                    <span class="fa fa-bell"></span>Novedades
                  </a>
                </li>
                <li class="text-center">
                  <a href="javascript:void(0)" title="Mensajes" data-toggle="ajax-modal" data-target="#messages-modal" data-source="{{ _p.web_main }}tutoring/alumn/message/inbox.php">
                    <span class="badge">{{ count_unread_message }}</span>
                    <span class="fa fa-comment"></span>Mensajes
                  </a>
                </li>
                {% endif %}
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li>
                {% if _u.logged == 0  %}
                  <div style="  font-size: 24px; line-height: 1; padding: 8px 0;">
                    <span>TUTOR</span><br>
                    <span>VIRTUAL</span>
                  </div>
                {% else %}
                  <a style="padding: 8px 0;" href="http://utp.edu.pe">
                    <img src="{{ _p.web }}/web/css/themes/TutorVirtual/images/utp-blanco.png" alt="" height="48">
                  </a>
                {% endif %}
              </li>
            </ul>
           {% if _u.logged == 1 %}
           <ul class="nav navbar-nav navbar-right">
               {% if _u.status != 6 %}
                <li class="dropdown">
                    <a href="javascript:void(0);" title=":)" style="line-height: 34px; color: #fff; font-size: 18px;">
                        {{ _u.complete_name }}
                    </a>
                </li>
               {% if logout_link is not null %}
               <li>
                   <a id="logout_button" title="{{ "Logout"|get_lang }}" href="{{ logout_link }}" >
                        <em class="fa fa-power-off fa-icon-size"></em>
                   </a>
               </li>
               {% endif %}
               {% endif %}
            </ul>
            {% endif %}
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
