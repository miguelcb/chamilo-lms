<nav class="navbar navbar-default x-navbar">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#menuone" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="{{  _p.web }}" class="navbar-brand">
              <span>TUTOR</span><br>
              <span>VIRTUAL</span>
            </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="menuone">
            <ul class="nav navbar-nav x-navbar__menu">
                {% if _u.status != 5 %}
                {{ menu }}
                {% else %}
                <li class="text-center">
                  <a href="javascript:void(0)" data-toggle="ajax-modal" data-target="#profile-modal" data-source="{{ _p.web_main }}auth/profile_tutoring.php">
                    <span class="fa fa-user"></span>Perfil
                  </a>
                </li>
                <li class="text-center">
                  <a href="javascript:void(0)" data-toggle="ajax-modal" data-target="#news-modal" data-source="{{ _p.web_main }}course_info/news.php">
                    <span class="badge">{{ count_unread_news }}</span>
                    <span class="fa fa-bell"></span>Novedades
                  </a>
                </li>
                <li class="text-center">
                  <a href="javascript:void(0)" data-toggle="ajax-modal" data-target="#messages-modal" data-source="{{ _p.web_main }}messages/inbox_tutoring.php">
                    <span class="badge">{{ count_unread_message }}</span>
                    <span class="fa fa-comment"></span>Mensajes
                  </a>
                </li>
                {% endif %}
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a style="padding: 8px 0;" href="http://utp.edu.pe"><img src="{{ _p.web }}/web/css/themes/TutorVirtual/images/utp-blanco.png" alt="" height="48"></a></li>
            </ul>
           {% if _u.logged == 1 %}
           <ul class="nav navbar-nav navbar-right">
               {% if user_notifications is not null %}
               <li><a href="{{ message_url }}">{{ user_notifications }}</a></li>
               {% endif %}
               {% if _u.status != 6 %}
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                        {{ _u.complete_name }} <span class="caret"></span>
                    </a>
                    <ul class="dropdown-menu" role="menu">
                        <li>
                            {{ profile_link }}
                            {{ message_link }}
                        </li>
                    </ul>
                </li>
               {% if logout_link is not null %}
               <li>
                   <a id="logout_button" title="{{ "Logout"|get_lang }}" href="{{ logout_link }}" >
                       <em class="fa fa-sign-out"></em> {{ "Logout"|get_lang }}
                   </a>
               </li>
               {% endif %}
               {% endif %}
            </ul>
            {% endif %}
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
