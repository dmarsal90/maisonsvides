@extends('layouts.app')

@section('content')
	<div class="row mb-5">
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
			<span class="text-title-calendar">Mes agendas</span><br>
			<div id="colors-users-calendar" class="mt-5"></div>
		</div>
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
			<div id="calendar" data-url="{!! route('events') !!}"></div>
		</div>
	</div>
@endsection

@section('modals')
<div class="modal fade" id="calendarModal" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title ffhnb" id="calendarModalLabel" data-event-title>Modal title</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="estateinfo">
					<div class="estateinfo__card">
						<span class="ffhnm">Localisation : </span>
						<span data-address></span>
					</div>
					<div class="estateinfo__card">
						<span class="ffhnm">Contact : </span>
						<span data-contact></span>
					</div>
					<div class="estateinfo__card">
						<span class="ffhnm">Tel : </span>
						<span data-phone></span>
					</div>
					<div class="estateinfo__card">
						<span class="ffhnm">Mail : </span>
						<span data-email></span>
					</div>
					<div class="estateinfo__card">
						<span class="ffhnm">Type : </span>
						<span data-type></span>
					</div>
					<div class="estateinfo__card">
						<span class="ffhnm">Descriptif du bien :</span>
						<div data-description></div>
					</div>
					<div class="estateinfo__location">
						<iframe data-coordinates frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="create" tabindex="-1" aria-labelledby="createEventLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title ffhnb" id="createEventLabel" data-event-title>Créer un évènement</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

			</div>
			<div class="modal-footer">
                <button type="button" class="btn btn-danger">Supprimer</button>
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="createEvent" tabindex="-1" aria-labelledby="calendarModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title ffhnb" id="calendarModalLabel" data-event-title>Créer un événement</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="estateinfo">
					<div class="estateinfo__card">
						<span class="ffhnm">Contact : </span>
						<input type="text" id="contact" name="contact" required>
					</div>
					<div class="estateinfo__card">
						<span class="ffhnm">Tel : </span>
						<input type="phone" id="phone" name="phone" required>
					</div>
					<div class="estateinfo__card">
						<span class="ffhnm">Mail : </span>
						<input type="email" id="mail" name="mail" required>
					</div>
					<div class="estateinfo__card">
						<span class="ffhnm">Type : </span>
						<input type="text" id="type" name="type" required>
					</div>
					<div class="estateinfo__card">
						<span class="ffhnm">Descriptif du bien :</span>
						<input type="text" id="descriptif" name="descriptif" required>
					</div>
                    <div class="estateinfo__card">
						<span class="ffhnm">Localisation : </span>
						<input type="text" id="localisation" name="localisation" placeholder="Entrez votre emplacement" required>
                        <button type="button" class="btn btn-primary mt-2" id="search-location">Chercher</button>
					</div>
					<div class="estateinfo__location">
                        {{-- <div id="map" style="height: 400px;"></div> --}}
						<iframe id="map" data-coordinates frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0"></iframe>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                <button type="button" class="btn btn-success" data-dismiss="modal">Créer</button>
			</div>
		</div>
	</div>
</div>
@endsection
