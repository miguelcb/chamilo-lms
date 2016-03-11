<!DOCTYPE html>
<!--[if lt IE 7]> <html lang="{{ document_language }}" class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>    <html lang="{{ document_language }}" class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>    <html lang="{{ document_language }}" class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--><html lang="{{ document_language }}" class="no-js"> <!--<![endif]-->
<head>
{% block head %}
{% include template ~ "/layout/head.tpl" %}
{% endblock %}
</head>
<body dir="{{ text_direction }}" class="{{ section_name }} {{ login_class }}">
<noscript>{{ "NoJavascript"|get_lang }}</noscript>

<!-- Display the Chamilo Uses Cookies Warning Validation if needed -->
{% if displayCookieUsageWarning == true %}
    <!-- If toolbar is displayed, we have to display this block bellow it -->
    {% if toolBarDisplayed == true %}
        <div class="displayUnderToolbar" >&nbsp;</div>
    {% endif %}
    <form onSubmit="$(this).toggle('slow')" action="" method="post">
        <input value=1 type="hidden" name="acceptCookies"/>
        <div class="cookieUsageValidation">
            {{ "YouAcceptCookies" | get_lang }}
            <span style="margin-left:20px;" onclick="$(this).next().toggle('slow'); $(this).toggle('slow')">
                ({{"More" | get_lang }})
            </span>
            <div style="display:none; margin:20px 0;">
                {{ "HelpCookieUsageValidation" | get_lang}}
            </div>
            <span style="margin-left:20px;" onclick="$(this).parent().parent().submit()">
                ({{"Accept" | get_lang }})
            </span>
        </div>
    </form>
{% endif %}


{% if show_header == true %}

<div id="page-wrap" class="{% if _u.logged  == 0 %}bg-login{% endif %}"><!-- page section -->
    {# Bug and help notifications #}
    {% block help_notifications %}
    <ul id="navigation" class="notification-panel">
        {{ help_content }}
        {{ bug_notification_link }}
    </ul>
    {% endblock %}

    {# topbar #}
    {% block topbar %}
        {% include template ~ "/layout/topbar.tpl" %}
        {% if show_toolbar == 1 %}
            <div class="clear-header"></div>
        {% endif %}
    {% endblock %}
        <header class="x-header">
            <section id="menu-bar">
                {# menu #}
                {% block menu %}
                {% include template ~ "/layout/menu_tutoring.tpl" %}
                {% endblock %}
            </section>
        </header>
    <div id="top_main_content" class="container">
    <div class="row">
    {# course navigation links/shortcuts need to be activated by the admin #}
    {% include template ~ "/layout/course_navigation.tpl" %}
{% endif %}
