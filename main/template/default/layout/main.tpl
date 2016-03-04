{% block header %}
{% include template ~ "/layout/main_header_tutoring.tpl" %}
{% endblock %}

{% block body %}
	{% if show_sniff == 1 %}
	 	{% include template ~ "/layout/sniff.tpl" %}
	{% endif %}
{% endblock %}

{% block footer %}
    {#  Footer  #}
    {% if show_footer == true %}
        </div> <!-- end of #col" -->
        </div> <!-- end of #row" -->
        </div> <!-- end of #container" -->
    {% endif %}
    {% include template ~ "/layout/main_footer_tutoring.tpl" %}
{% endblock %}
