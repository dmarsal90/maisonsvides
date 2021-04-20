{{-- @extends('layouts.app')

@section('content')
<div class="row category">
	@foreach($categories as $category)
		<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-1 mb-5">
			<div class="category__content">
				<a href="{!! route('estates', [$category['slug'], '']) !!}"></a>
				<span class="category__number">{!! $category['count'] !!}</span>
				<span class="category__name">{!! $category['name'] !!}</span>
			</div>
		</div>
		@if($category['has_child'] === 1)
			@foreach($subCategories as $subCategory)
				<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 col-xl-3 mb-5">
					<div class="category__content">
						<a href="{!! route('estates', [$subCategory['slug_parent'], $subCategory['slug']]) !!}"></a>
						<span class="category__number">{!! $subCategory['count'] !!}</span>
						<span class="category__name">{!! $subCategory['name'] !!}</span>
					</div>
				</div>
			@endforeach
		@endif
	@endforeach
</div>
@endsection --}}

@extends('layouts.app')

@section('content')
<?php setlocale(LC_TIME, "fr_FR"); ?>
<div class="row category">
	@foreach($categories as $category)
		@if($category['count'] != 0)
		<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-xl-2 mb-5">
			<div class="category__content">
				<a href="{!! route('estates', [$category['slug'], '']) !!}"></a>
				<span class="category__number">{!! $category['count'] !!}</span>
				<span class="category__name">{!! $category['name'] !!}</span>
			</div>
		</div>
		@endif
	@endforeach
	@foreach($subCategories as $subCategory)
		@if($subCategory['count'] != 0)
		<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-xl-2 mb-5">
			<div class="category__content">
				<a href="{!! route('estates', [$subCategory['slug_parent'], $subCategory['slug']]) !!}"></a>
				<span class="category__number">{!! $subCategory['count'] !!}</span>
				<span class="category__name">{!! $subCategory['name'] !!}</span>
			</div>
		</div>
		@endif
	@endforeach
	@if(count($remindersTask) != 0)
	<div class="col-xs-12 col-sm-3 col-md-3 col-lg-2 col-xl-2 mb-5">
		<div class="category__content">
			<a href="#content-tasks"></a>
			<span class="category__number">{!! count($remindersTask) !!}</span>
			<span class="category__name">Tâches</span>
		</div>
	</div>
	@endif
	@if(count($auxTickets) != 0)
	<div class="col-xs-12 col-sm-3 col-md-2 col-lg-2 col-xl-2 mb-5">
		<div class="category__content">
			<a href="{!! route('viewTickets') !!}"></a>
			<span class="category__number">{!! count($auxTickets) !!}</span>
			<span class="category__name">Tickets</span>
		</div>
	</div>
	@endif
</div>
<div class="row mb-5">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2 table-font-size" id="content-tasks">
		<h2>Tâches</h2>
		<hr>
		<div>
			<table data-table="touts" class="display responsive nowrap" style="width: 100%;">
				<thead>
					<tr>
						<th>Tâche</th>
						<th>Date de la tâche</th>
						<th>Catégorie</th>
						<th>Status</th>
						<th>Dossier</th>
						<th>Détail</th>
						<th>Nom-Prénom</th>
						<th>Tel</th>
						<th>Mail</th>
					</tr>
				</thead>
				<tbody>
					@foreach($remindersTask as $task)
						<tr>
							<td>{!! $task['name_reminder'] !!}</td>
							<td>{!! strftime('%a', strtotime($task['date'])) !!} {!! date('d-m-Y', strtotime($task['date'])) !!}</td>
							<td>En attente de prise de rendez-vous</td>
							<td>
								{!! $task['details_estate']['category']['name'] !!}
							</td>
							<td>{!! date('ymdh.i', strtotime($task['details_estate']['reference'])) !!}</td>
							<td>
								<a href="{!! route('estate', $task['estate_id']) !!}?modal=true&id={!! $task['id'] !!}" class="btn btn-primary">Détails</a>
							</td>
							<td>
								{!! $task['details_estate']['details_seller']['name'] !!}
							</td>
							<td>
								{!! $task['details_estate']['details_seller']['phone'] !!}
							</td>
							<td>
								{!! $task['details_estate']['details_seller']['email'] !!}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2 table-font-size" id="content-tickets">
		<h2>Tickets</h2>
		<hr>
		<div>
			<div class="mb-10">
				<table data-table="touts" class="display responsive nowrap" style="width: 100%;">
					<thead>
						<tr>
							<?php if (Auth::user()->type == 2): ?>
								<th>Agents</th>
							<?php endif ?>
							<th>Id</th>
							<th>Suject</th>
							<th>Demandeur</th>
							<th>Demandé</th>
							<th>Mis à jour</th>
						</tr>
					</thead>
					<tbody>
						@foreach($auxTickets as $ticket)
							<tr>
								<?php if (Auth::user()->type == 2): ?>
									<td>Users</td>
								<?php endif ?>
								<td>{!! $ticket->id !!}</td>
								<td><a href="{!! route('viewoneticketdash', $ticket->id) !!}">{!! $ticket->title !!}</a></td>
								<td>{!! $ticket->requester->name !!}</td>
								<td>{!! strftime('%a', strtotime($ticket->created_at)) !!} {!! date('d-m-Y H:m', strtotime($ticket->created_at)) !!}</td>
								<td>{!! strftime('%a', strtotime($ticket->updated_at)) !!} {!! date('d-m-Y H:m', strtotime($ticket->updated_at)) !!}</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection