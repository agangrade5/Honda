<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Xenon Boostrap Admin Panel" />
	<meta name="author" content="" />
	
	<title>Login</title>

	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Arimo:400,700,400italic">
	<link rel="stylesheet" href="{{ asset('assets/css/fonts/linecons/css/linecons.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/fonts/fontawesome/css/font-awesome.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/bootstrap.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/xenon-core.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/xenon-forms.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/xenon-components.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/xenon-skins.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

	<script src="{{ asset('assets/js/jquery-1.11.1.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-cookie/jquery.cookie.js') }}"></script>
	<script type="text/javascript">	 
		window.onbeforeunload = function(e) {
			$(".page-loading-overlay").removeClass('loaded'); 	
		}
	</script>

	<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="page-body login-page">

	<div class="login-container">
		<div class="row">
			<div class="col-sm-6">
				<script type="text/javascript">
					jQuery(document).ready(function($)
					{
                        $.cookie('authorized','no');

						// Reveal Login form
						setTimeout(function(){ $(".fade-in-effect").addClass('in'); }, 1);
						
						// Validation and Ajax action
						$("form#login").validate({
							rules: {
								username: {
									required: true
								},
								passwd: {
									required: true
								}
							},
							
							messages: {
								username: {
									required: 'Please enter your username.'
								},
								passwd: {
									required: 'Please enter your password.'
								}
							},
							
							submitHandler: function(form)
							{
								form.submit();								
							}
						});
						
						// Set Form focus
						$("form#login .form-group:has(.form-control):first .form-control").focus();
					});
				</script>
				
				<!-- Errors container -->
				<div class="errors-container">
					@if ($errors->any())
						<div class="alert alert-danger">
							<ul>
								@foreach ($errors->all() as $error)
									<li>{{ $error }}</li>
								@endforeach
							</ul>
						</div>
					@endif
				</div>
				
				<!-- Add class "fade-in-effect" for login form effect -->
				<form method="post" role="form" action="{{ route('login.post') }}" id="login" class="login-form fade-in-effect">
					@csrf
					
					<div class="login-header">
						<a href="{{ route('login') }}" class="logo">
							<img src="{{ asset('assets/images/logo@2x.png') }}" alt="" width="200" />
							<span>log in</span>
						</a>
					</div>
	
					<div class="form-group">
						<label class="control-label" for="username">Username</label>
						<input type="text" class="form-control input-dark" name="username" id="username" autocomplete="off" value="{{ old('username') }}" />
					</div>
					
					<div class="form-group">
						<label class="control-label" for="passwd">Password</label>
						<input type="password" class="form-control input-dark" name="passwd" id="passwd" autocomplete="off" />
					</div>

					<div class="form-group">
						<button type="submit" class="btn btn-dark btn-block text-left">
							<i class="fa-lock"></i>
							Log In
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="page-loading-overlay">
		<div class="loader-2"></div>
	</div>

	<!-- Bottom Scripts -->
	<script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('assets/js/TweenMax.min.js') }}"></script>
	<script src="{{ asset('assets/js/resizeable.js') }}"></script>
	<script src="{{ asset('assets/js/joinable.js') }}"></script>
	<script src="{{ asset('assets/js/xenon-api.js') }}"></script>
	<script src="{{ asset('assets/js/xenon-toggles.js') }}"></script>
	<script src="{{ asset('assets/js/jquery-validate/jquery.validate.min.js') }}"></script>
	<script src="{{ asset('assets/js/toastr/toastr.min.js') }}"></script>

	<!-- JavaScripts initializations and stuff -->
	<script src="{{ asset('assets/js/xenon-custom.js') }}"></script>

	@if(session('msg') === 'Error')
	<div id="toast-container" class="toast-top-full-width" aria-live="polite" role="alert">
		<div class="toast toast-error" style="">
			<button class="toast-close-button" role="button" onclick="jQuery('#toast-container').remove();">×</button>
			<div class="toast-title">Invalid Login!</div>
			<div class="toast-message">You have entered wrong user credentials, please try again.</div>
		</div>
	</div>
	@endif

</body>
</html>
