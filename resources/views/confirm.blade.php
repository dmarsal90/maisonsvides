@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<img class="mb-5" src="{!! asset('img/logo.svg') !!}">
			@if(!empty($events))
			<h1 class="text-center">Bienvenue, veuillez confirmer votre visite</h1><br>
				@foreach($events as $event)
				<div style="border: 1px solid #707070; padding: 20px;">
					<div class="text-center">
						<span>{!! strftime('%a', strtotime($event->start->dateTime)) !!}, {!! date('d-m-Y', strtotime($event->start->dateTime)) !!} </span> : <span>{!! date('H:i', strtotime($event->start->dateTime)) !!}</span> - <span>{!! date('H:i', strtotime($event->end->dateTime)) !!}</span>
						<a style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: #28a745; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 0.9rem; line-height: 1.6; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; border-color: #38c172; margin-left: 15px; text-decoration: none; cursor: pointer;" href="{!! route('thanks', [$event->id, md5($estateid)]) !!}">Confirmer l&rsquo;horaire</a>
					</div>
				</div>
				@endforeach
			@else
			<h1 class="text-center">Désolé, les heures de visite ne sont plus disponibles, contactez-nous pour reporter de nouvelles heures.</h1><br>
			@endif
		</div>
	</div>
</div>
@endsection
