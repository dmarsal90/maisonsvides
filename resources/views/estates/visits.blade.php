@extends('layouts.app')

@section('content')

	<table data-table="all" class="display responsive nowrap" style="width: 100%;">
		<thead>
			<tr class="bg">
				<th></th>
				<th></th>
				<th>Dossier</th>
				<th>Nom</th>
				<th>Date</th>
				<th>Heure</th>
				<th>Type</th>
				<th>Estim.</th>
				<th>Valeur Mar.</th>
				<th>du bien</th>
				<th>Nom-Prénom</th>
				<th>Tel</th>
				<th>Mail</th>
				<th>Dde Tel</th>
				<th>Plan RDV</th>
				<th>Offre</th>
				<th>Légaux</th>
				<th>vente</th>
			</tr>
		</thead>
		<tbody>
			@foreach($estates as $estate)
				<tr>
					<td></td>
					<td>
						<a href="{!! route('visit', $estate['id']) !!}" class="btn btn-primary">Visite</a>
					</td>
					<td><?php echo date('ymd.', strtotime($estate['reference'])) ?></td>
					<td>{!! $estate['name'] !!}</td>
					<td>{!! strftime('%a', strtotime($estate['created_at'])) !!} {!! date('d-m-Y', strtotime($estate['created_at'])) !!}</td>
					<td>{!! date('H:i', strtotime($estate['created_at'])) !!}</td>
					<td>-</td>
					<td>{!! $estate['estimate'] !!}</td>
					<td>{!! $estate['market'] !!}</td>
					<td>-</td>
					<td>{!! $estate['seller']['name'] !!}</td>
					<td>
						<a href="tel:{!! $estate['seller']['phone'] !!}">{!! $estate['seller']['phone'] !!}</a>
					</td>
					<td>
						<a href="mailto:{!! $estate['seller']['email'] !!}">{!! $estate['seller']['email'] !!}</a>
					</td>
					<td>0</td>
					<td>0</td>
					<td>0</td>
					<td>OUI</td>
					<td>OUI</td>
				</tr>
			@endforeach
		</tbody>
	</table>
@endsection
