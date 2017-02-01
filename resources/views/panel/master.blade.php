<!-- (.)(.) -->
<!DOCTYPE html>
<html>
<head lang="ru">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>Административная панель сайта</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="viewport" content="width=1024, maximum-scale=1.0" />
    <meta name="csrf-token" content="{!! csrf_token() !!}" />
    <link rel="stylesheet" type="text/css" href="/panel/bower_components/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/panel/css/panel.css" />
    <link rel="stylesheet" type="text/css" href="/panel/js/jquery-ui-1.11.4.custom/jquery-ui.min.css" />
    <script src="/panel/bower_components/jquery/dist/jquery.min.js"></script>
    <!--[if lt IE 9]>
    <script src="/panel/bower_components/lt-ie-9/lt-ie-9.min.js"></script>
    <![endif]-->
    <link rel="icon" type="image/ico" href="/panel/favicon.ico" />
</head>

<body>

<div class="wait"></div>

@yield('layout')

<script src="/panel/bower_components/jquery-form/jquery.form.js"></script>
<script src="/panel/bower_components/js-cookie/src/js.cookie.js"></script>
<script src="/panel/js/ckeditor/ckeditor.js"></script>
<script src="/panel/js/ckeditor/adapters/jquery.js"></script>
<script src="/panel/js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<script src="/panel/js/ui.datepicker-ru.js"></script>
<script src="/panel/js/panel.js"></script>
<script src="/panel/js/custom.js"></script>
</body>
</html>