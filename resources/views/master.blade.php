<!-- (.)(.) -->
<!DOCTYPE html>
<html>

<head lang="ru">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="x-ua-compatible" content="IE=9">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title or 404 }}{{ request()->is('/') ? '' : $settings->title }}</title>
    <meta name="description" content="" />
    <meta name="keywords" content="" />
    <meta name="csrf-token" content="{!! csrf_token() !!}" />
    <link rel="stylesheet" type="text/css" href="/bower_components/font-awesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="/bower_components/magnific-popup/dist/magnific-popup.css" />
    <link rel="stylesheet" type="text/css" href="/panel/js/jquery-ui-1.11.4.custom/jquery-ui.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/{{ $css or 'error' }}.css" />
    <script src="/bower_components/jquery/dist/jquery.min.js"></script>
    <!--[if lt IE 9]>
    <script src="/bower_components/lt-ie-9/lt-ie-9.min.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="shortcut.ico" />
</head>

<body>

<div class="body-wait"></div>
<div class="body-fade"></div>

@include('common.header')

@yield('content')

@include('common.footer')

<script src="/bower_components/jquery-form/jquery.form.js"></script>
<script src="/bower_components/cookie/cookie.min.js"></script>
<script src="/bower_components/magnific-popup/dist/jquery.magnific-popup.min.js"></script>
<script src="/panel/js/jquery-ui-1.11.4.custom/jquery-ui.min.js"></script>
<script src="/panel/js/ui.datepicker-ru.js"></script>
<script src="/js/js.js"></script>

<!-- Yandex.Metrika counter --> <script type="text/javascript"> (function (d, w, c) { (w[c] = w[c] || []).push(function() { try { w.yaCounter41541519 = new Ya.Metrika({ id:41541519, clickmap:true, trackLinks:true, accurateTrackBounce:true, webvisor:true }); } catch(e) { } }); var n = d.getElementsByTagName("script")[0], s = d.createElement("script"), f = function () { n.parentNode.insertBefore(s, n); }; s.type = "text/javascript"; s.async = true; s.src = "https://mc.yandex.ru/metrika/watch.js"; if (w.opera == "[object Opera]") { d.addEventListener("DOMContentLoaded", f, false); } else { f(); } })(document, window, "yandex_metrika_callbacks"); </script> <noscript><div><img src="https://mc.yandex.ru/watch/41541519" style="position:absolute; left:-9999px;" alt="" /></div></noscript> <!-- /Yandex.Metrika counter -->
</body>
</html>