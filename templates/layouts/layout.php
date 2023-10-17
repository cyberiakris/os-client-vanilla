<!DOCTYPE html>
<html>
<head>
    <title>My Website</title>
    <!-- Include CSS and JS files -->
    <link rel="stylesheet" href="{{ base_url }}/public/css/style.css">
    <!-- script src="./public/js/script.js"></script -->
</head>
<body>
    <div class="banner-area">
        <header>
            <div class="menu">
                <ul>
                    <li><a href="{{ base_url }}/">Home</a></li>
                    <li><a href="{{ base_url }}/about">About</a></li>
                    <li><a href="{{ base_url }}/pages/contact">Contact</a></li>
                </ul>
            </div>

        </header>
        <div class="banner-text">
            <!-- Content section of the specific view file -->
            {% block content %}{% endblock %}
        </div>

        <footer>
            &copy; 2023 My Website. All rights reserved.
        </footer>
    </div>
</body>
</html>
