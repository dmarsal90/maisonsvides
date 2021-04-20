@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<img src="{!! asset('img/logo.svg') !!}">
			<br><br><br><br><br>
			<h1 class="text-center">Merci d'avoir confirmé votre visite</h1><br>
			<h3 class="text-center">La visite est <span>{!! strftime('%a', strtotime($eventConfirmed->start->dateTime)) !!}, {!! date('d-m-Y', strtotime($eventConfirmed->start->dateTime)) !!} </span> : <span>{!! date('H:i', strtotime($eventConfirmed->start->dateTime)) !!}</span> - <span>{!! date('H:i', strtotime($eventConfirmed->end->dateTime)) !!}</span></h3>
		</div>
	</div>
</div>
@endsection
