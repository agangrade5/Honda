<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="description" content="{{config('app.name')}} Admin Panel" />
<meta name="author" content="NCompassTrac" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title> {{config('app.name')}} | @yield('title') </title>

<link rel="icon" href="{{ asset("assets/images/favicon.ico") }}" type="image/x-icon">
<link rel="shortcut icon" href="{{ asset("assets/images/favicon.ico") }}" type="image/x-icon">

<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo:400,700,400italic">
<link rel="stylesheet" href={{ asset("assets/css/fonts/linecons/css/linecons.css")}}>
<link rel="stylesheet" href={{ asset("assets/css/fonts/fontawesome/css/font-awesome.min.css")}}>
<link rel="stylesheet" href={{ asset("assets/css/bootstrap.css")}}>
<link rel="stylesheet" href={{ asset("assets/css/xenon-core.css")}}>
<link rel="stylesheet" href={{ asset("assets/css/xenon-forms.css")}}>
<link rel="stylesheet" href={{ asset("assets/css/xenon-components.css")}}>
<link rel="stylesheet" href={{ asset("assets/css/xenon-skins.css")}}>
<link rel="stylesheet" href={{ asset("assets/css/custom.css")}}>

{!! returnScriptWithNonce(asset("assets/js/jquery-1.11.1.min.js")) !!}
{!! returnScriptWithNonce(asset("assets/js/jquery-cookie/jquery.cookie.js")) !!}
<script nonce="{{ csp_nonce('script') }}" type="text/javascript">
window.onbeforeunload = function(e) {
    $(".page-loading-overlay").removeClass('loaded');
}
window.addEventListener("pageshow", function (event) {
    $(".page-loading-overlay").addClass("loaded");
});
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>
<style nonce="{{ csp_nonce('style') }}">.datepicker{z-index:1200 !important;}</style>

<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lt IE 9]>
	{!! returnScriptWithNonce("https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js") !!}
	{!! returnScriptWithNonce("https://oss.maxcdn.com/respond/1.4.2/respond.min.js") !!}
<![endif]-->
