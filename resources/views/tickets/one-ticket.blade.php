@extends('layouts.app')

@section('content')
<?php setlocale(LC_TIME, "fr_BE"); ?>
	<div >
		<h3>{!! $dataTicket->title !!}</h3>
		<span class="text-gray">{!! $dataTicket->requester->name !!} < {!! $dataTicket->requester->email !!} ></span>
		<div class="row mb-4">
			<h3></h3>
		</div>
		<form action="{!! route('comment') !!}" method="POST" data-form="form-create-comment">
			@csrf()
			<input type="hidden" name="ticket_id" value="{!! $id !!}">
			<div class="row mb-2">
				<textarea class="form-control" name="comment" rows="4"></textarea>
			</div>
			<div class="row mb-2">
				<span>Résolu</span>
				<div class="col-xs-12 col-sm-1 col-md-1 col-lg-12 col-xl-1">
					<input type="checkbox" name="resolved">
				</div>
			</div>
			<div class="row mb-4">
				<button class="btn btn-success" type="submit" data-submit-form="form-create-comment">Commenté en tant que new</button>
			</div>
		</form>
		<hr>
		@foreach($comments as $comment)
			<div class="row mb-3">
				<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 text-gray">
					<i style="font-size: 25px;" class="bi bi-person-circle"></i> {!! $comment->author->name !!} · {!! strftime('%a', strtotime($comment->created_at)) !!} {!! date('d-m-Y H:m', strtotime($comment->created_at)) !!}
				</div>
			</div>
			<div class="row pl-4">
				{!! $comment->body !!}
			</div>
			<hr>
		@endforeach
	</div>
@endsection