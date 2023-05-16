<!DOCTYPE html>
<html lang="{!! str_replace('_', '-', app()->getLocale()) !!}">
<head>
	<link rel="shortcut icon" href="{!! asset('img/icon.svg') !!}" />
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{!! csrf_token() !!}">

	<title>{{ config('app.name', 'MaisonsVides') }}</title>
    @section('scripts')
	<!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

	<script src="{!! asset('js/vendor.js') !!}?{!! time() !!}" defer></script>
	<script src="{!! asset('js/app.js') !!}?{!! time() !!}" defer></script>
    <!-- Carga de jQuery y script.js -->
    <script src="{!! asset('js/script.js') !!}?{!! time() !!}" defer></script>

	<!-- Tiny -->
	<script src="https://cdn.tiny.cloud/1/wzwscrdo1x87gkvkliuxlxf88h2gv2ut9gd2s50sh492bugn/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
    @endsection

	<!-- Styles -->
	<link href="{!! asset('css/app.css') !!}?{!! time() !!}" rel="stylesheet">
	<link href="{!! asset('css/vendor.css') !!}?{!! time() !!}" rel="stylesheet">
	<link href="{!! asset('css/style.css') !!}?{!! time() !!}" rel="stylesheet">
   <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">-->


</head>
<body data-spy="scroll" data-target="#menu-scrolling" data-offset="131">
	@php
		$cookies = (isset($_COOKIE['open-menu'])) ? $_COOKIE['open-menu'] : 0;
	@endphp
	<div class="wrapper">
		@if(auth()->check())
			<!-- Start header -->
			<header class="header">
				<!-- Logo -->
				<a href="{!! route('dashboard') !!}" class="header__logo {!! ($cookies == 1) ? 'header__logo--full' : '' !!}">
					<img src="{!! asset('img/logo.svg') !!}">
					<img src="{!! asset('img/logo_minified.svg') !!}">
				</a>
				<!-- Search -->
				<nav class="navbar navbar-dark">
					<button class="navbar-toggler" type="button" data-open-menu="menu" data-close-wrapper="wrapper">
						<span class="navbar-toggler-icon"></span>
					</button>
					@php $s = (request()->get('s') && request()->get('s') !== "") ? request()->get('s') : ""; @endphp
					<form action="{!! route('search') !!}" method="GET" class="d-flex">
						<input class="form-control me-2" type="search" name="s" autocomplete="off" placeholder="Rechercher" aria-label="Search" value="{!! $s !!}" required>
						<button class="btn btn-outline-success" type="submit">Rechercher</button>
					</form>
					<span class="ml-5" style="font-size: 14px;">
						<a class="text-white" href="{!! route('settings') !!}?tab-name=settings-users" data-url-modal="{!! route('settings') !!}" data-my-profile> Mon profil ({!! Auth::user()->name !!})</a> <span class="text-white mr-2 ml-2"> | </span> <a class="text-white {{ Request::is('logout*') ? 'active' : '' }}" href="{!! route('logout') !!}">Déconnexion</a>
					</span>
				</nav>
			</header>
			<!-- End Header -->
			<!-- Start left side menu -->
			<aside class="menu {!! ($cookies == 1) ? 'menu--opened' : '' !!}" data-menu="menu">
				<a href="#" class="menu__button" data-open-menu="menu" data-close-wrapper="wrapper">
					@include('svg.iconchevronup')
				</a>
				<a href="{!! route('dashboard') !!}" class="menu__logo">
					<img src="{!! asset('img/logo.svg') !!}">
				</a>
				<ul class="menu__list">
					<li class="menu__item">
						<a class="{{ Request::is('dashboard*') ? 'active' : '' }}" href="{!! route('dashboard') !!}">@include('svg.iconhouse')<span>Home Page</span></a>
					</li>
					<li class="menu__item">
						<a class="{{ Request::is('estates*') ? 'active' : '' }} {{ Request::is('estate*') ? 'active' : '' }}" href="{!! route('estates', ['general', 'all']) !!}">@include('svg.iconfiles')<span>Dossiers</span></a>
					</li>
					@if(!session()->get('requireGoogleToken') && Auth::user()->google_token != NULL)
						<li class="menu__item">
							<a class="{{ Request::is('calendar*') ? 'active' : '' }}" href="{!! route('calendar') !!}">@include('svg.iconcalendar')<span>Calendrier</span></a>
						</li>
					@endif
					<li class="menu__item">
						<a class="{{ Request::is('visits*') ? 'active' : '' }}" href="{!! route('visits') !!}">@include('svg.iconvisit')<span>Module Visite</span></a>
					</li>
					<li class="menu__item">
						<a class="{{ Request::is('settings*') ? 'active' : '' }}" href="{!! route('settings') !!}">@include('svg.iconconfig')<span>Paramètres</span></a>
					</li>
					<li class="menu__item">
						<a class="{{ Request::is('logout*') ? 'active' : '' }}" href="{!! route('viewTickets') !!}"><i style="font-size: 25px;" class="bi bi-card-checklist"></i><span>Tickets</span></a>
					</li>
					<li class="menu__item">
						<a class="{{ Request::is('logout*') ? 'active' : '' }}" href="{!! route('logout') !!}">@include('svg.iconlogout')<span>Se déconnecter</span></a>
					</li>
				</ul>
			</aside>
		@endif

		<main class="wrapper__{!! (auth()->check()) ? 'container' : 'login' !!} {!! ($cookies == 1) ? 'wrapper__container--small' : '' !!}" data-wrapper="wrapper">
			@if(session()->get('requireGoogleToken') && auth()->check())
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div class="alert alert-danger" role="alert">
							Vous n'êtes pas connecté à Google Agenda ou la session a expiré, <a href="{!! route('connect') !!}">cliquez ici</a> pour démarrer ou renouveler la session.
						</div>
					</div>
				</div>
			@endif
			@yield('content')
			@yield('modals')
            <!-- Script special of Page -->
	        @yield('scripts')
		</main>
	</div>
</body>
</html>
