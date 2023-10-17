{% extends "layouts/layout.php" %}

{% block content %}

<h1>{{ post_title }}</h1>
    {{ post_content|raw }}

    {{ out }}
{% endblock %}
