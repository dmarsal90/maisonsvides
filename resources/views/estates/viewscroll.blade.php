	<?php setlocale(LC_TIME, "fr_BE"); ?>
	<ul class="nav nav-tabs wrapper__anchors" id="menu-scrolling">
		<li class="nav-item">
			<a class="nav-link" href="#estate-information" data-href="#estate-information">Infos</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#estate-photos-docs" data-href="#estate-photos-docs">Pics & docs</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#estate-ads" data-href="#estate-ads">Annonces</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#estate-rdv" data-href="#estate-rdv">RDV</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#estate-visit-remarks" data-href="#estate-visit-remarks">Remarques visite</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#estate-reminders" data-href="#estate-reminders">Rappels</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#estate-comments" data-href="#estate-comments">Commentaires internes</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#estate-problems" data-href="#estate-problems">Problèmes</a>
		</li>
		@if(Auth::user()->type != 3)
		<li class="nav-item">
			<a class="nav-link" href="#estate-offer" data-href="#estate-offer">Offre</a>
		</li>
		@endif
		<li class="nav-item">
			<a class="nav-link" href="#estate-tickets" data-href="#estate-tickets">Tickets <sup class="text-danger">{!! ($countTicketsNoAnswer != 0) ? '('.$countTicketsNoAnswer.')' : '' !!}</sup></a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#estate-status" data-href="#estate-status">Status</a>
		</li>
		<li class="nav-item">
			<a class="nav-link" href="#estate-log" data-href="#estate-log">Log</a>
		</li>
	</ul>

	<div id="estate-content-info" class="wrapper__estatecontent">
		<div id="estate-information" class="mb-5">
			<form action="{!! route('editinformations') !!}" method="POST" data-form="form-estate-info">
				@csrf()
				<span id="token" style="display: none;">{!! csrf_token() !!}</span>
				<input type="hidden" name="estate_id" value="{!! $id !!}">
				<input type="hidden" name="seller_id" value="{!! $seller['id'] !!}">
				<input type="hidden" name="type_estate" value="{!! $details['specific_data']['estate_type'] !!}">
				<div class="card font-body-content">
					<div class="card-header">
						<div class="text-left">
							Informations & commentaires
						</div>
						<div class="text-right">
							<button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
							<button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-hide="true" data-submit-form="form-estate-info">Sauvegarder</button>
						</div>
					</div>
					<div class="card-body">
						<div class="row mb-2">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-1">
								<div class="wrapper__content">
									<div class="block-16-9">
										<div>
											<img src="{!! asset('mainImages/'.$estate['main_photo']) !!}" data-img-view="main">
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 mb-2">
											<div class="wrapper__content">
												<div class="wrapper__files text-center ffhnl align-middle">
													Changer l'image principale
													<input data-image="main" data-upload="{!! route('uploadphoto', 'mainImages') !!}" data-estate-id="{!! $id !!}" type="file" name="estate_photos" accept=".jpg, .jpeg, .png">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 mb-1">
								<span class="ffhnm">STATUTS</span>
								<div class="wrapper__content mb-5">
									<div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_technic_layout" id="estate_include_status" disabled>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5"></div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Statut en cours du dossier :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<select name="estate__category" class="form-control" data-change-select>
												@foreach($categories as $category)
													<option value="{!! $category['id'] !!}" <?php echo ($estate['category']['id'] == $category['id']) ? 'selected' : ''; ?>>
														{!! $category['name'] !!}
													</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Statut en cours du dossier :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											NON
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">A assigner à :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<select data-change-select name="estate__agent" id="estate__agent" class="form-control" data-save>
												@foreach($agents as $agent)
												<option value="{!! $agent['id'] !!}" <?php echo ($estate['agent'] == $agent['id']) ? 'selected' : ''; ?> >{!! $agent['name'] !!}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">RDV Fixé le :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											{!! $estate['visit_date_at'] !!}
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mb-1">
								<span class="ffhnm">LOCALISATION</span>
								<div class="wrapper__content">
									<div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_localisation" id="estate_include_localisation">
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-3 col-lg-3 col-xl-3"></div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-3 col-lg-3 col-xl-3">Rue :</div>
										<div class="col-xs-12 col-md-9 col-lg-9 col-xl-9 mb-2">
											<input data-change-input type="text" name="estate__street" class="form-control" value="{!! $estate['street'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-3 col-lg-3 col-xl-3">N° :</div>
										<div class="col-xs-12 col-md-9 col-lg-9 col-xl-9 mb-2">
											<input data-change-input type="number" name="estate__number" class="form-control" value="{!! $estate['number'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-3 col-lg-3 col-xl-3">Bt :</div>
										<div class="col-xs-12 col-md-9 col-lg-9 col-xl-9 mb-2">
											<input data-change-input type="number" name="estate__box" class="form-control" value="{!! $estate['box'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-3 col-lg-3 col-xl-3">CP :</div>
										<div class="col-xs-12 col-md-9 col-lg-9 col-xl-9 mb-2">
											<input data-change-input type="number" name="estate__code_postal" class="form-control" value="{!! $estate['code_postal'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2 d-none" >
										<div class="col-xs-12 col-md-3 col-lg-3 col-xl-3">Ville : </div>
										<div class="col-xs-12 col-md-9 col-lg-9 col-xl-9 mb-2">
											<input data-change-input type="text" name="estate__city" class="form-control" value="{!! $estate['city'] !!}" data-save>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1">
								<span class="ffhnm">CLIENT</span>
								<div class="wrapper__content mb-5">
									<div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_technic_layout" id="estate_include_client" disabled>
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5"></div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Contact :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input  type="text" name="seller_name" class="form-control" value="{!! $seller['name'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Tel :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input  type="number" name="seller_phone" class="form-control" value="{!! $seller['phone'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Mail :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<input data-change-input  type="text" name="seller_email" class="form-control" value="{!! $seller['email'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Type :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<input  data-change-input type="text" name="seller_type" class="form-control" value="{!! $seller['type'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Raison vente :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<input data-change-input type="text" name="seller_reason_sale" class="form-control" value="{!! $seller['reason_sale'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Quand veut-il vendre ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<input data-change-input type="text" name="estate__dateSell" class="form-control" value="{!! $estate['when_want_sell'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Veut-il rester locataire après la vente ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<input data-change-input type="text" name="estate__want_tenant_after_sell" class="form-control" value="{!! $estate['want_tenant_after_sell'] !!}" data-save>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Cherche-t-il un autre bien ? : :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<select data-change-select name="estate__want_buy_wesold" class="form-control">
												<option  {!! ($estate['want_buy_wesold'] == 1) ? 'selected' : '' !!} value="1">Oui</option>
												<option  {!! ($estate['want_buy_wesold'] == 0) ? 'selected' : '' !!} value="0">Non</option>
											</select>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Commentaire général du client :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<textarea data-change-input name="estate__information_additional" class="form-control" rows="4">{!! $estate['information_additional'] !!}</textarea>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1 mt-4">
								<div class="wrapper__content">
									<br>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5"></div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-11 col-lg-11 col-xl-11 mb-2">
											<select data-change-select class="form-control" name="sale__type_of_sale" data-sale>
												<option value="">Choisis une option</option>
												<option {!! ($estate['type_of_sale'] == 'Par agence') ? 'selected' : '' !!} value="Par agence">Par agence</option>
												<option {!! ($estate['type_of_sale'] == 'Par lui même') ? 'selected' : '' !!} value="Par lui même">Par lui même</option>
												<option {!! ($estate['type_of_sale'] == 'Le deux') ? 'selected' : '' !!} value="Le deux">Le deux</option>
												<option {!! ($estate['type_of_sale'] == 'Non') ? 'selected' : '' !!} value="Non">Non</option>
											</select>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-both>
										<div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
											<strong><label class="ffhnm">Par agence</label></strong>
										</div>
									</div>
									<div class="row mb-5 ml-2" data-by-agence>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Nom de l'agence :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" name="sale__agency_name" class="form-control" value="{!! $estate['agency_name'] !!}">
										</div>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Prix publié :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="number" name="sale__price_published_agence" class="form-control" value="{!! $estate['price_published_agence'] !!}">
										</div>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Date du début de la vente :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="date" name="sale__date_of_sale_agence" class="form-control" value="{!! $estate['date_of_sale_agence'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2" data-both>
										<div class="col-xs-12 col-md-12 col-lg-12 col-xl-12">
											<strong><label class="ffhnm">Par lui même</label></strong>
										</div>
									</div>
									<div class="row mb-5 ml-2" data-by-the-same>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Prix publié :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="number" name="sale__price_published_himself" class="form-control" value="{!! $estate['price_published_himself'] !!}">
										</div>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Date du début de la vente :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="date" name="sale__date_of_sale_himself" class="form-control" value="{!! $estate['date_of_sale_himself'] !!}">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row mb-1">
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 mb-1">
								<span class="ffhnm">DONNEES SPECIFIQUES AU TYPE DE BIEN</span>
								<div class="wrapper__content mb-5">
									<div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_data-specific" id="estate_include_data-specific">
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4"></div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">Type de bien:</div>
										<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
											<select data-change-select name="specific__estate_type" id="type_de_bien" class="form-control" data-save>
												<option {!! ($details['specific_data']['estate_type'] == 'Maison') ? 'selected' : '' !!} value="Maison">Maison</option>
												<option {!! ($details['specific_data']['estate_type'] == 'Appartement') ? 'selected' : '' !!} value="Appartement">Appartement</option>
												<option {!! ($details['specific_data']['estate_type'] == 'Immeuble de rapport') ? 'selected' : '' !!} value="Immeuble de rapport">Immeuble de rapport</option>
												<option {!! ($details['specific_data']['estate_type'] == 'Autre') ? 'selected' : '' !!} value="Autre">Autre</option>
											</select>
										</div>
									</div>
									<div id="type-bien-maison">
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">Combien d'étages possède la maison ?:</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<input data-change-input data-disabled-maison type="text" class="form-control" name="specific__estate_house_levels_nbr"  value="@isset( $details['specific_data']['estate_house_levels_nbr']) {!! $details['specific_data']['estate_house_levels_nbr'] !!} @endisset">
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">A quel étage se trouvent les chambres ?</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7">
												<input data-change-input data-disabled-maison type="text" class="form-control" name="specific__estate_house_rooms_floor" 	value="@isset($details['specific_data']['estate_house_rooms_floor']) {!! $details['specific_data']['estate_house_rooms_floor'] !!} @endisset">
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">A quel étage se trouvent les salles de bains ?</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<input data-change-input data-disabled-maison type="text" class="form-control" name="specific__estate_house_bathrooms_floor" 	value="@isset($details['specific_data']['estate_house_bathrooms_floor']) {!! $details['specific_data']['estate_house_bathrooms_floor'] !!} @endisset">
											</div>
										</div>
									</div>
									<div id="type-bien-appartement">
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">À quel étage se situe l'appartement ?:</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<input data-change-input data-disabled-appartment  type="text" class="form-control" name="specific__estate_apartment_level"  value="@isset($details['specific_data']['estate_apartment_level']) {!! $details['specific_data']['estate_apartment_level'] !!} @endisset">
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">Quel est le nombre d'étages de l'immeuble ?</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7">
												<input data-change-input data-disabled-appartment  type="text" class="form-control" name="specific__estate_apartment_maxlevel" 	value="@isset($details['specific_data']['estate_apartment_maxlevel']) {!! $details['specific_data']['estate_apartment_maxlevel'] !!} @endisset">
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">En quelle année l'immeuble a-t-il été construit ?</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<select data-change-select data-disabled-appartment  class="form-control" name="specific__estate_apartment_construction_date">
													<option></option>
													<option @isset($details['specific_data']['estate_apartment_construction_date']) {!!  ($details['specific_data']['estate_apartment_construction_date'] == 'Avant 1960') ? 'selected' : '' !!} @endisset value="Avant 1960">Avant 1960</option>
													<option @isset($details['specific_data']['estate_apartment_construction_date']) {!!  ($details['specific_data']['estate_apartment_construction_date'] == '') ? 'selected' : 'Entre 1960 et 1870' !!} @endisset value="Entre 1960 et 1870">Entre 1960 et 1870</option>
													<option @isset($details['specific_data']['estate_apartment_construction_date']) {!!  ($details['specific_data']['estate_apartment_construction_date'] == '') ? 'selected' : 'Entre 1970 et 1980' !!} @endisset value="Entre 1970 et 1980">Entre 1970 et 1980</option>
													<option  @isset($details['specific_data']['estate_apartment_construction_date']) {!! ($details['specific_data']['estate_apartment_construction_date'] == 'Entre 1981 et 1991') ? 'selected' : '' !!} @endisset value="Entre 1981 et 1991">Entre 1981 et 1991</option>
													<option  @isset($details['specific_data']['estate_apartment_construction_date']) {!! ($details['specific_data']['estate_apartment_construction_date'] == 'Entre 1992 et 2000') ? 'selected' : '' !!} @endisset value="Entre 1992 et 2000">Entre 1992 et 2000</option>
													<option  @isset($details['specific_data']['estate_apartment_construction_date']) {!! ($details['specific_data']['estate_apartment_construction_date'] == 'Entre 2001 et 2010') ? 'selected' : '' !!} @endisset value="Entre 2001 et 2010">Entre 2001 et 2010</option>
													<option  @isset($details['specific_data']['estate_apartment_construction_date']) {!! ($details['specific_data']['estate_apartment_construction_date'] == 'Après 2011') ? 'selected' : '' !!} @endisset value="près 2011">Après 2011</option>
												</select>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">L'appartement sera-t-il loué à la date de la vente ?</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<select data-change-select data-disabled-appartment  name="specific__estate_apartment_rented_when_sold" class="form-control" disabled>
													<option></option>
													<option  @isset($details['specific_data']['estate_apartment_rented_when_sold']) {!! ($details['specific_data']['estate_apartment_rented_when_sold'] == 'Oui') ? 'selected' : '' !!} @endisset value="Oui">Oui</option>
													<option  @isset($details['specific_data']['estate_apartment_rented_when_sold']) {!! ($details['specific_data']['estate_apartment_rented_when_sold'] == 'Non') ? 'selected' : '' !!} @endisset value="Non">Non</option>
													<option  @isset($details['specific_data']['estate_apartment_rented_when_sold']) {!!  ($details['specific_data']['estate_apartment_rented_when_sold'] == 'Peut-être') ? 'selected' : '' !!} @endisset value="Peut-être">Peut-être</option>
												</select>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">L'immeuble est-il pourvu d'un ascenseur ?</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<select data-change-select data-disabled-appartment  name="specific__estate_apartment_lift" class="form-control" disabled>
													<option></option>
													<option @isset($details['specific_data']['estate_apartment_lift']) {!! ($details['specific_data']['estate_apartment_lift'] == 'Oui') ? 'selected' : '' !!} @endisset value="Oui">Oui</option>
													<option @isset($details['specific_data']['estate_apartment_lift']) {!! ($details['specific_data']['estate_apartment_lift'] == 'Non') ? 'selected' : '' !!} @endisset value="Non">Non</option>
												</select>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">Il y a-t-il des cave(s) vendue(s) avec le bien ?</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<select data-change-select data-disabled-appartment name="specific__estate_apartment_cellar" class="form-control" disabled>
													<option></option>
													<option @isset($details['specific_data']['estate_apartment_cellar']) {!! ($details['specific_data']['estate_apartment_cellar'] == 'Oui') ? 'selected' : '' !!} @endisset value="Oui">Oui</option>
													<option @isset($details['specific_data']['estate_apartment_cellar']) {!! ($details['specific_data']['estate_apartment_cellar'] == 'Non') ? 'selected' : '' !!} @endisset value="Non">Non</option>
												</select>
											</div>
										</div>
									</div>
									<div id="type-bien-immeuble">
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">De combien d'unités (appartement, rez commercial, etc) est-il constitué ? :</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<input data-change-input data-disabled-immeuble type="text" class="form-control" name="specific__estate_investmentproperty_units_nbr"  value="@isset($details['specific_data']['estate_investmentproperty_units_nbr']) {!! $details['specific_data']['estate_investmentproperty_units_nbr'] !!} @endisset">
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">Le rez-de-chaussée du bien est de type :</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7">
												<select data-change-select data-disabled-immeuble name="specific__estate_investmentproperty_groundfloor_type" class="form-control" disabled>
													<option></option>
													<option @isset($details['specific_data']['estate_investmentproperty_groundfloor_type']) {!! ($details['specific_data']['estate_investmentproperty_groundfloor_type'] == 'Commercial') ? 'selected' : '' !!} @endisset value="Commercial">Commercial</option>
													<option @isset($details['specific_data']['estate_investmentproperty_groundfloor_type']) {!! ($details['specific_data']['estate_investmentproperty_groundfloor_type'] == 'Bureau') ? 'selected' : '' !!} @endisset value="Bureau">Bureau</option>
													<option  @isset($details['specific_data']['estate_investmentproperty_groundfloor_type']) {!! ($details['specific_data']['estate_investmentproperty_groundfloor_type'] == 'Résidentiel') ? 'selected' : '' !!} @endisset value="Résidentiel">Résidentiel</option>
												</select>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">Nombre de compteurs gaz : </div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<input data-change-input data-disabled-immeuble type="text" class="form-control" name="specific__estate_investmentproperty_gasmeter_nbr"  value="@isset($details['specific_data']['estate_investmentproperty_gasmeter_nbr']) {!! $details['specific_data']['estate_investmentproperty_gasmeter_nbr'] !!} @endisset">
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">Nombre de compteurs d'électricités : </div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<input data-change-input data-disabled-immeuble type="text" class="form-control" name="specific__estate_investmentproperty_electricitymeter_nbr"  value="@isset($details['specific_data']['estate_investmentproperty_electricitymeter_nbr']) {!! $details['specific_data']['estate_investmentproperty_electricitymeter_nbr'] !!} @endisset">
											</div>
										</div>
									</div>
									<div id="type-bien-autre">
										<div class="row mb-2">
											<div class="col-xs-12 col-md-4 col-lg-4 col-xl-4">Si autre : précisez :</div>
											<div class="col-xs-12 col-md-7 col-lg-7 col-xl-7 mb-2">
												<textarea data-change-input data-disabled-autre name="specific__estate_type_other" class="form-control" rows="7">@isset($details['specific_data']['estate_type_other']) {!! $details['specific_data']['estate_type_other'] !!} @endisset</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7 mb-1">
								<span class="ffhnm">DONNEES DE BASE</span>
								<div class="wrapper__content mb-5">
									<div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_data_base" id="estate_include_data_base">
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5"></div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">De quand date la construction du bien ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="basic_data__estate_construction_date"  value="@isset($details['basic_data']['estate_construction_date']) {!! $details['basic_data']['estate_construction_date'] !!} @endisset">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Quelle est la surface habitable du bien ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="basic_data__estate_living_space_area"  value="@isset($details['basic_data']['estate_living_space_area']) {!! $details['basic_data']['estate_living_space_area'] !!} @endisset">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Voulez-vous ajouter quelque chose concernant votre bien ?</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<input data-change-input type="text" class="form-control" name="basic_data__estate_other_infos_text" 	value="@isset($details['basic_data']['estate_other_infos_text']) {!! $details['basic_data']['estate_other_infos_text'] !!} @endisset">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">A quel étage se trouvent les salles de bains ?</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="basic_data__estate_house_rooms_floor" 	value="@isset($details['basic_data']['estate_house_rooms_floor']) {!! $details['basic_data']['estate_house_rooms_floor'] !!} @endisset">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Est-ce que votre bien fait l'objet d'un problème légal ou urbanistique ? : </div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<select data-change-select name="basic_data__estate_judicial_problem" class="form-control" data-problem-bien>
												<option></option>
												<option value="Oui" @isset($details['basic_data']['estate_judicial_problem']) {!! ($details['basic_data']['estate_judicial_problem'] == 'Oui') ? 'selected' : '' !!} @endisset>Oui</option>
												<option value="Non" @isset($details['basic_data']['estate_judicial_problem']) {!! ($details['basic_data']['estate_judicial_problem'] == 'Non') ? 'selected' : '' !!} @endisset>Non</option>
											</select>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-textarea-problem>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Expliquez-nous le problème: : </div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<textarea data-change-input name="basic_data__estate_judicial_problem_text" class="form-control" rows="5" >@isset($details['basic_data']['estate_judicial_problem_text']) {!! $details['basic_data']['estate_judicial_problem_text'] !!} @endisset</textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row mb-5">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1">
								<span class="ffhnm">AMENAGEMENT INTERIEUR</span>
								<div class="wrapper__content">
									<div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_layout_interior" id="estate_include_layout_interior">
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5"></div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Estimation de l'état général de l'extérieur :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<label class="mr-3"><input data-change-radio type="radio" class="radio-middle" name="interior_layout__estate_general_condition_interior" {!! ($details['interior_layout']['estate_general_condition_interior'] == 'À rafraichir') ? 'checked' : '' !!} value="À rafraichir"> À rafraichir</label>
											<label class="mr-3"><input data-change-radio type="radio" class="radio-middle" name="interior_layout__estate_general_condition_interior" {!! ($details['interior_layout']['estate_general_condition_interior'] == 'Bon état') ? 'checked' : '' !!} value="Bon état"> Bon état</label>
											<label class="mr-3"><input data-change-radio type="radio" class="radio-middle" name="interior_layout__estate_general_condition_interior" {!! ($details['interior_layout']['estate_general_condition_interior'] == 'Comme neuf') ? 'checked' : '' !!} value="Comme neuf"> Comme neuf</label>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Comment qualifieriez-vous la décoration intérieure ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="interior_layout__estate_decoration_type"  value="{!! $details['interior_layout']['estate_decoration_type'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Type de sol dans les pièces de vie ?:</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_livingrooms_ground_type[]" value="Carrelage"  @isset($details['interior_layout']['estate_livingrooms_ground_type']) {!! (in_array('Carrelage',$details['interior_layout']['estate_livingrooms_ground_type'])) ? 'checked' : ''; !!} @endisset> Carrelage</label>
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_livingrooms_ground_type[]" value="Parquet"  @isset($details['interior_layout']['estate_livingrooms_ground_type']) {!! (in_array('Parquet', $details['interior_layout']['estate_livingrooms_ground_type'])) ? 'checked' : ''; !!} @endisset> Parquet</label>
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_livingrooms_ground_type[]" value="Vinyle"  @isset($details['interior_layout']['estate_livingrooms_ground_type']) {!! (in_array('Vinyle', $details['interior_layout']['estate_livingrooms_ground_type'])) ? 'checked' : ''; !!} @endisset> Vinyle</label>
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_livingrooms_ground_type[]" value="Béton"  @isset($details['interior_layout']['estate_livingrooms_ground_type']) {!! (in_array('Béton', $details['interior_layout']['estate_livingrooms_ground_type'])) ? 'checked' : ''; !!} @endisset> Béton</label>
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_livingrooms_ground_type[]" value="Autre"  @isset($details['interior_layout']['estate_livingrooms_ground_type']) {!! (in_array('Autre', $details['interior_layout']['estate_livingrooms_ground_type'])) ? 'checked' : ''; !!} @endisset> Autre</label>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Combien y a-t-il de salles de bains ? : </div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="interior_layout__estate_bathroom_nbr" 	value="{!! $details['interior_layout']['estate_bathroom_nbr'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2" data-textarea-problem>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Combien y a-t-il de chambres ? : </div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="interior_layout__estate_bedroom_nbr" 	value="{!! $details['interior_layout']['estate_bedroom_nbr'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Type de sol dans les chambres? : </div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_rooms_ground_type_copie[]" value="Carrelage"  {!! (in_array('Carrelage', $details['interior_layout']['estate_rooms_ground_type_copie'])) ? 'checked' : ''; !!}> Carrelage</label>
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_rooms_ground_type_copie[]" value="Parquet"  {!! (in_array('Parquet', $details['interior_layout']['estate_rooms_ground_type_copie'])) ? 'checked' : ''; !!}> Parquet</label>
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_rooms_ground_type_copie[]" value="Vinyle"  {!! (in_array('Vinyle', $details['interior_layout']['estate_rooms_ground_type_copie'])) ? 'checked' : ''; !!}> Vinyle</label>
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_rooms_ground_type_copie[]" value="Béton"  {!! (in_array('Béton', $details['interior_layout']['estate_rooms_ground_type_copie'])) ? 'checked' : ''; !!}> Béton</label>
											<label class="mr-1"><input data-change-radio type="checkbox" name="interior_layout__estate_rooms_ground_type_copie[]" value="Autre"  {!! (in_array('Autre', $details['interior_layout']['estate_rooms_ground_type_copie'])) ? 'checked' : ''; !!}> Autre</label>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Quel est le type de votre cuisine ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<label class="mr-3"><input data-change-radio type="radio" class="radio-middle" name="interior_layout__estate_kitchen_equipment" {!! ($details['interior_layout']['estate_kitchen_equipment'] == 'Non équipée') ? 'checked' : '' !!} value="Non équipée"> Non équipée</label>
											<label class="mr-3"><input data-change-radio type="radio" class="radio-middle" name="interior_layout__estate_kitchen_equipment" {!! ($details['interior_layout']['estate_kitchen_equipment'] == 'Équipée') ? 'checked' : '' !!} value="Équipée"> Équipée</label>
											<label class="mr-3"><input data-change-radio type="radio" class="radio-middle" name="interior_layout__estate_kitchen_equipment" value="Super-équipée" {!! ($details['interior_layout']['estate_kitchen_equipment'] == 'Super-équipée') ? 'checked' : '' !!} > Super-équipée</label>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Etat de la cuisine :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<label><input data-change-radio type="radio" class="radio-middle" name="interior_layout__estate_kitchen_condition" value="Nécessite un rafraîchissement" {!! ($details['interior_layout']['estate_kitchen_condition'] == 'Nécessite un rafraîchissement') ? 'checked' : '' !!}> Nécessite un rafraîchissement</label><br>
											<label><input data-change-radio type="radio" class="radio-middle" name="interior_layout__estate_kitchen_condition" value="En bon état" {!! ($details['interior_layout']['estate_kitchen_condition'] == 'En bon état') ? 'checked' : '' !!}> En bon état</label><br>
											<label><input data-change-radio type="radio" class="radio-middle" name="interior_layout__estate_kitchen_condition" value="Comme neuf" {!! ($details['interior_layout']['estate_kitchen_condition'] == 'Comme neuf') ? 'checked' : '' !!}> Comme neuf</label>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-textarea-problem>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Matériau du plan de travail : </div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="interior_layout__estate_kitchen_workplan_meterial" 	value="{!! $details['interior_layout']['estate_kitchen_workplan_meterial'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2" data-textarea-problem>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Age de la cuisine : </div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="interior_layout__estate_kitchen_year" 	value="{!! $details['interior_layout']['estate_kitchen_year'] !!}">
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1">
								<span class="ffhnm">AMENAGEMENT EXTERIEUR</span>
								<div class="wrapper__content mb-5">
									<div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_exterior_layout" id="estate_include_exterior_layout">
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5"></div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Estimation de l'état général de l'extérieur :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<label><input data-change-radio type="radio" class="radio-middle" name="exterior_layout__estate_general_condition_exterior" value="A rafraîchir" {!! ($details['exterior_layout']['estate_general_condition_exterior'] == 'A rafraîchi') ? 'checked' : '' !!}> A rafraîchir</label>
											<label><input data-change-radio type="radio" class="radio-middle" name="exterior_layout__estate_general_condition_exterior" value="Bon état" {!! ($details['exterior_layout']['estate_general_condition_exterior'] == 'Bon état') ? 'checked' : '' !!}> Bon état</label>
											<label><input data-change-radio type="radio" class="radio-middle" name="exterior_layout__estate_general_condition_exterior" value="Comme Neuf" {!! ($details['exterior_layout']['estate_general_condition_exterior'] == 'Comme Neuf') ? 'checked' : '' !!}> Comme Neuf</label>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Le bien possède-t-il une terrasse ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input type="text" data-change-input class="form-control" name="exterior_layout__estate_terrace" value="{!! $details['exterior_layout']['estate_terrace'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Le bien possède-t-il un balcon ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input type="text" data-change-input class="form-control" name="exterior_layout__estate_balcony" value="{!! $details['exterior_layout']['estate_balcony'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Le bien possède-t-il une véranda ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input type="text" data-change-input class="form-control" name="exterior_layout__estate_veranda" value="{!! $details['exterior_layout']['estate_veranda'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Le bien bénéficie-t-il d'un ou plusieurs emplacements de parking ? : </div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<select data-change-select name="exterior_layout__estate_parking" class="form-control" data-parking>
												<option></option>
												<option {!! ($details['exterior_layout']['estate_parking'] == 'Oui') ? 'selected' : '' !!} value="Oui">Oui</option>
												<option {!! ($details['exterior_layout']['estate_parking'] == 'Non') ? 'selected' : '' !!} value="Non">Non</option>
											</select>
										</div>
									</div>
									<div data-oui-parking>
										<div class="row mb-2 ml-2">
											<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Type d'emplacement de parking ? : </div>
											<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
												<label><input data-change-radio type="checkbox" name="exterior_layout__estate_parking_type[]" value="Emplacement(s) extérieur(s)" {!! (in_array('Emplacement(s) extérieur(s)', $details['exterior_layout']['estate_parking_type'])) ? 'checked' : '' !!}> Emplacement(s) extérieur(s)</label>
												<label><input data-change-radio type="checkbox" name="exterior_layout__estate_parking_type[]" value="Emplacement(s) intérieur(s)" {!! (in_array('Emplacement(s) intérieur(s)', $details['exterior_layout']['estate_parking_type'])) ? 'checked' : '' !!}> Emplacement(s) intérieur(s)</label>
												<label><input data-change-radio type="checkbox" name="exterior_layout__estate_parking_type[]" value="Garage simple" {!! (in_array('Garage simple', $details['exterior_layout']['estate_parking_type'])) ? 'checked' : '' !!}> Garage simple</label>
												<label><input data-change-radio type="checkbox" name="exterior_layout__estate_parking_type[]" value="Garage double" {!! (in_array('Garage double', $details['exterior_layout']['estate_parking_type'])) ? 'checked' : '' !!}> Garage double</label>
												<label><input data-change-radio type="checkbox" name="exterior_layout__estate_parking_type[]" value="Autre" {!! (in_array('Autre', $details['exterior_layout']['estate_parking_type'])) ? 'checked' : '' !!}> Autre</label>
											</div>
										</div>
										<div class="row mb-2 ml-2">
											<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Combien de voitures peut-on garer au total ? :</div>
											<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
												<input data-change-input type="text" class="form-control" name="exterior_layout__estate_parking_nbr" value="{!! $details['exterior_layout']['estate_parking_nbr'] !!}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="row mb-2">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1">
								<span class="ffhnm">AMENAGEMENT TECHNIQUE</span>
								<div class="wrapper__content mb-5">
									<div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_technic_layout" id="estate_include_technic_layout">
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5"></div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Quel est le type de châssis des fenêtres du bien? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<label><input data-change-radio type="checkbox" name="technical_layout__estate_window_frame_type" value="Simple vitrage" {!! ($details['technical_layout']['estate_window_frame_type'] == 'Simple vitrage') ? 'checked' : '' !!}> Simple vitrage</label>
											<label><input data-change-radio type="checkbox" name="technical_layout__estate_window_frame_type" value="Double vitrage" {!! ($details['technical_layout']['estate_window_frame_type'] == 'Double vitrage') ? 'checked' : '' !!}> Double vitrage</label>
											<label><input data-change-radio type="checkbox" name="technical_layout__estate_window_frame_type" value="Triple vitrage" {!! ($details['technical_layout']['estate_window_frame_type'] == 'Triple vitrage') ? 'checked' : '' !!}> Triple vitrage</label>
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Chauffage central ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<select data-change-select name="technical_layout__estate_central_heating" class="form-control" data-chuaffage>
												<option></option>
												<option {!! ($details['technical_layout']['estate_central_heating'] == 'Oui') ? 'selected' : '' !!} value="Oui">Oui</option>
												<option {!! ($details['technical_layout']['estate_central_heating'] == 'Non') ? 'selected' : '' !!} value="Non">Non</option>
											</select>
										</div>
									</div>
									<div class="row mb-2 ml-2" data-oui-chauffage>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Si oui: de quel type ?:</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
											<select data-change-select class="form-control" name="technical_layout__estate_central_heating_type">
												<option></option>
												<option value="Pompe à chaleur" {!! ($details['technical_layout']['estate_central_heating_type'] == 'Pompe à chaleur') ? 'selected' : '' !!}>Pompe à chaleur</option>
												<option value="À mazout" {!! ($details['technical_layout']['estate_central_heating_type'] == 'À mazout') ? 'selected' : '' !!}>À mazout</option>
												<option value="Au gaz" {!! ($details['technical_layout']['estate_central_heating_type'] == 'Au gaz') ? 'selected' : '' !!}>Au gaz</option>
												<option value="Électrique" {!! ($details['technical_layout']['estate_central_heating_type'] == 'Électrique') ? 'selected' : '' !!}>Électrique</option>
												<option value="À pellet" {!! ($details['technical_layout']['estate_central_heating_type'] == 'À pellet') ? 'selected' : '' !!}>À pellet</option>
												<option value="À accumulation" {!! ($details['technical_layout']['estate_central_heating_type'] == 'À accumulation') ? 'selected' : '' !!}>À accumulation</option>
											</select>
										</div>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-1">
								<span class="ffhnm">INFORMATIONS VENTE & VENDEUR</span>
								<div class="wrapper__content">
									<div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_seller" id="estate_include_seller">
										</div>
									</div>
									<div class="row mb-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5"></div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Quel est le prix que vous souhaiteriez pour votre bien ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="seller_data__estate_price" value="{!! $details['seller_data']['estate_price'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Le bien est-il déjà en vente ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="seller_data__estate_already_on_sale" value="{!! $details['seller_data']['estate_already_on_sale'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">A quelle date auriez-vous besoin de votre argent ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input {!! ($details['seller_data']['estate_availability'] == 'Préciser') ? 'type="text"' : 'type="date"'!!} class="form-control" name="seller_data__estate_availability" value="{!! $details['seller_data']['estate_availability'] !!}">
										</div>
									</div>
									<div class="row mb-2 ml-2">
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Quand pourrions-nous procéder à l'achat ? :</div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<input data-change-input type="text" class="form-control" name="seller_data__estate_availability_text" value="{!! $details['seller_data']['estate_availability_text'] !!}" data-purchase>
										</div>
									</div>
									<div class="row mb-2 ml-2 d-none" data-preciser>
										<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Si préciser : </div>
										<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
											<textarea data-change-input name="seller_data__estate_availability_text" class="form-control">{!! $details['seller_data']['estate_availability_text'] !!}</textarea>
										</div>
									</div>
									<div data-oui-parking>
										<div class="row mb-2 ml-2">
											<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Pour quelle raison vendez-vous ce bien ? : </div>
											<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
												<input data-change-input type="text" class="form-control" name="seller_data__estate_sell_reason" value="{!! $details['seller_data']['estate_sell_reason'] !!}">
											</div>
										</div>
										<div class="row mb-2 ml-2">
											<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Voulez-vous rester locataire du bien vendu ? : </div>
											<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
												<input data-change-input type="text" class="form-control" name="seller_data__estate_sell_but_rent" value="{!! $details['seller_data']['estate_sell_but_rent'] !!}">
											</div>
										</div>
										<div class="row mb-2 ml-2">
											<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Etes-vous à la recherche d'un nouveau bien ?: : </div>
											<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
												<input data-change-input type="text" class="form-control" name="seller_data__estate_seller_search_new_estate" value="{!! $details['seller_data']['estate_seller_search_new_estate'] !!}">
											</div>
										</div>
										<div class="row mb-2 ml-2">
											<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Qui est le vendeur? : </div>
											<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
												<input data-change-input type="text" data-who-is-seller class="form-control" name="seller_data__estate_seller_type" value="{!! $details['seller_data']['estate_seller_type'] !!}">
											</div>
										</div>
										<div class="row mb-2 ml-2" data-seller-autre>
											<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Si 'autre' : explications : </div>
											<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
												<textarea data-change-input class="form-control" name="seller_data__estate_seller_type_text">{!! $details['seller_data']['estate_seller_type_text'] !!}</textarea>
											</div>
										</div>
										<div class="row mb-2 ml-2" data-seller-autre>
											<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Numéro de téléphone du vendeur : </div>
											<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
												<input data-change-input type="text" class="form-control" name="seller_data__estate_seller_phone" value="{!! $details['seller_data']['estate_seller_phone'] !!}">
											</div>
										</div>
										<div class="row mb-2 ml-2" data-seller-autre>
											<div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Par quel moyen préférez-vous être contacté ?</div>
											<div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
												<input data-change-input type="text" class="form-control" name="seller_data__estate_seller_preferred_communication" value="{!! $details['seller_data']['estate_seller_preferred_communication'] !!}">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="text-right">
							<button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
							<button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-hide="true" data-submit-form="form-estate-info">Sauvegarder</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="estate-photos-docs" class="mb-5">
			<form action="" method="POST" data-form="form-estate-documents-photos">
				<div class="card font-body-content">
					<div class="card-header">
						Photographies du bien et documents
						<div class="row">
							<div class="col-12 text-right">
								<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_documents_photos" id="estate_include_documents_photos">
							</div>
						</div>
					</div>
					<div class="card-body">
						@php
							$photos = array();
							foreach($medias as $media) {
								if($media['type'] === "photos") {
									$photos[] = $media;
								}
							}
						@endphp
						<div id="carouselEstatePhotos" class="carousel slide autosize-m mb-4" data-ride="carousel">
							@php
								$classHide = (count($photos) <= 1) ? ' d-none' : '';
							@endphp
							<ol class="carousel-indicators{!! $classHide !!}">
								@foreach ($photos as $key => $photo)
									@php
										$classActive = ($key === 0) ? "active" : "";
									@endphp
									<li data-target="#carouselEstatePhotos" data-slide-to="{!! $key !!}" class="{!! $classActive !!}"></li>
								@endforeach
							</ol>
							<div class="carousel-inner">
								@php $cont = 0; @endphp
								@foreach($photos as $photo)
									@if($photo['type'] === 'photos')
										<div class="carousel-item <?php echo ($cont == 0) ? 'active' : ''; ?>">
											<div class="block-16-9">
												<div>
													<img src="{!! asset('photos/'.$photo['name']) !!}">
												</div>
											</div>
										</div>
										@php $cont++; @endphp
									@endif
								@endforeach
							</div>
							@php
								$classHide = (count($photos) <= 1) ? ' d-none' : '';
							@endphp
							<a class="carousel-control-prev{!! $classHide !!}" href="#carouselEstatePhotos" role="button" data-slide="prev">
								<span class="carousel-control-prev-icon" aria-hidden="true"></span>
								<span class="sr-only">Previous</span>
							</a>
							<a class="carousel-control-next{!! $classHide !!}" href="#carouselEstatePhotos" role="button" data-slide="next">
								<span class="carousel-control-next-icon" aria-hidden="true"></span>
								<span class="sr-only">Next</span>
							</a>
						</div>
						<div class="row justify-content-between">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-5 mb-4">
								<span>Liste des documents</span>
								<div class="wrapper__content" id="list-documents">
									@foreach($medias as $media)
										@if($media['type'] === 'photos')
											<div class="wrapper__document">
												<a data-delete href="{!! route('deletemedia', $media['id']) !!}">
													<i class="bi bi-x" ></i>
												</a>
												<a href="{!! asset('photos/'.$media['name']) !!}" target="_blank" download="{!! $media['name'] !!}">{!! $media['name'] !!}</a>
												<a href="{!! asset('photos/'.$media['name']) !!}" target="_blank" download="{!! $media['name'] !!}">
													<img src="{!! asset('photos/'.$media['name']) !!}">
												</a>
											</div>
										@endif
										@if($media['type'] === 'documents')
											<div class="wrapper__document">
												<a data-delete href="{!! route('deletemedia', $media['id']) !!}">
													<i class="bi bi-x" ></i>
												</a>
												<a href="{!! asset('documents/'.$media['name']) !!}" target="_blank" download="{!! $media['name'] !!}">{!! $media['name'] !!}</a>
												<a href="{!! asset('documents/testing/DocumentTest.pdf') !!}" target="_blank" download="Document Test.pdf">
													<img src="{!! asset('img/icons/file.svg') !!}">
												</a>
											</div>
										@endif
									@endforeach
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-5">
								<span>Ajouter une photo ou un document</span>
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
										<div class="wrapper__content">
											<div class="wrapper__files">
												<span>Glisser & Déposer ICI les photos à ajouter</span>
												<input data-files="photos" data-container-files="list-documents" type="file" data-upload="{!! route('uploadphoto', 'photos') !!}" data-estate-id="{!! $id !!}" name="estate_photos" accept=".jpg, .jpeg, .png" multiple>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
										<div class="wrapper__content">
											<div class="wrapper__files">
												<span>Glisser & Déposer ICI les documents à ajouter</span>
												<input data-files="documents" data-container-files="list-documents" type="file" data-upload="{!! route('uploadphoto', 'documents') !!}" data-estate-id="{!! $id !!}" name="estate_documents" accept=".pdf, .json" multiple>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="estate-ads" class="mb-5">
			<form action="{!! route('newadvertisement') !!}" method="POST" data-form="estate-form-ads" data-reload="true">
				@csrf()
				<input type="hidden" name="estate_id" value="{!! $id !!}">
				<div class="card font-body-content">
					<div class="card-header">Annonces - sites immobiliers</div>
					<div class="card-body">
						<span class="ffhnb">Sites annonces</span>
						@foreach($advertisements as $advertisement)
							<div class="row mb-4 mt-2">
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-2">
									<input type="text" class="form-control" name="" value="{!! $advertisement['realestatename'] !!}" readonly>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-2">
									<input type="text" class="form-control" name="" value="{!! $advertisement['refrence'] !!}" readonly>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-4 mb-2">
									<a href="{!! $advertisement['url'] !!}" target="_blank">{!! $advertisement['url'] !!}</a>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 text-xl-right mb-2">
									<span class="ffhnm">Mise en ligne :</span>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-2">
									<span class="ffhnl">{!! strftime('%a', strtotime($advertisement['put_online'])) !!} {!! date('d-m-Y', strtotime($advertisement['put_online'])) !!}</span>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 d-md-none d-lg-none d-xl-block"></div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 text-xl-right mb-2">
									<span class="ffhnm">Prix affiché :</span>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 mb-2">
									<span class="ffhnl">{!! $advertisement['price'] !!}€</span>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-4 d-md-none d-lg-none d-xl-block"></div>
							</div>
						@endforeach
						<span class="ffhnb">Ajouter une entrée</span>
						<div class="row mt-2">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2">
								<select data-change-select name="estate_form_ads_site" class="form-control">
									@foreach($realestates as $site)
											<option value="{!! $site['id'] !!}">
												{!! $site['name'] !!}
											</option>
										</div>
									@endforeach
								</select>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2">
								<input data-change-input type="text" class="form-control" name="estate_ads_ref" placeholder="REF">
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 mb-2">
								<input data-change-input type="text" class="form-control" name="estate_ads_url" placeholder="URL">
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 d-none d-sm-none d-md-none d-lg-none d-xl-block text-right mb-2">
								<button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
								<button class="btn btn-lg btn-success" type="submit" data-modified="false" data-submit-hide="false" data-submit-form="estate-form-ads">Ajouter</button>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2 text-xl-right">
								<span class="ffhnm">Mise en ligne :</span>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2">
								<input data-change-input type="date" class="form-control" name="estate_ads_online" placeholder="__/__/__">
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2 d-md-none d-lg-none d-xl-block"></div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2 d-md-none d-lg-none d-xl-block"></div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2 text-xl-right">
								<span class="ffhnm">Prix affiché :</span>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2">
								<input data-change-input type="number" class="form-control" name="estate_ads_price" placeholder="">
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2 d-md-none d-lg-none d-xl-block"></div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2 d-md-none d-lg-none d-xl-block"></div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 d-xs-block d-sm-block d-md-block d-lg-block d-xl-none text-right mb-2">
								<button class="btn btn-lg btn-success" type="submit" data-submit-hide="false" data-submit-form="estate-form-ads">Ajouter</button>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="estate-rdv" class="mb-5">
			<div class="card font-body-content">
				<div class="card-header">Gestion des RDV</div>
				<div class="card-body">
					<div class="row justify-content-between">
						<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-4">
							<div class="wrapper__content mt-2">
								<div class="row mb-2">
									@if(Auth::user()->google_token != NULL)
									<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4">
										<button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#calendargoogle">Voir le calendrier</button>
									</div>
									@else
									<div class="col-xs-12 col-sm-5 col-md-5 col-lg-5 col-xl-5 mt-3">
										<span class="alert alert-danger">Connectez-vous pour voir vos rendez-vous. <a href="{!! route('connect') !!}">Cliquez ici</a></span>
									</div>
									@endif
								</div>
								<form action="{!! route('changestatus') !!}" method="POST" data-form="change-status-estate">
									@csrf()
									<input type="hidden" name="estate_id" value="{!! $id !!}">
									<div class="row mb-4">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
											<button type="submit" class="btn btn-outline-primary" data-submit-form="change-status-estate">Mettre le statut en RDV pris</button>
										</div>
									</div>
								</form>
								<div class="row {!! (!isset($eve_['events'])) ? 'd-none' : '' !!}">
									<div class="col-6">
										@if(isset($eve_['events']))
										@foreach($eve_['events'] as $key => $event)
											<div class="row">
												<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
													<ul id="list-events">
														<li>
															<span class="ffhnm" id="{!! $key !!}"><span data-date>{!! strftime('%a', strtotime($event->start->dateTime)) !!} {!! date('d-m-Y', strtotime($event->start->dateTime)) !!} </span> : <span data-start> {!! date('H:i', strtotime($event->start->dateTime)) !!}</span> - <span data-end>{!! date('H:i', strtotime($event->end->dateTime)) !!}</span></span>
														</li>
													</ul>
												</div>
											</div>
										@endforeach
										@endif
										<div class="row mb-2">
											<div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 col-xl-7 mb-4 ml-4 mt-3">
												@if($estate['rdv'] != 1)
													<button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#sendInvitation" data-dates="">Envoyér</button>
												@else
													<span class="ffhnl" style="color:green">RDV envoyé</span>
												@endif
											</div>
										</div>
									</div>
									<div class="{!! (isset($eve_['events'])) ? 'col-6' :  'col-xs-4 col-sm-4 col-md-11 col-lg-11 col-xl-11 ml-5' !!}">
										<div class="row mb-4">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
												<form action="{!! route('newcommentrdv') !!}" method="POST" data-form="form-new-comment-rdv" data-reload="true">
												@csrf()
												<input type="hidden" name="estate_id" value="{!! $id !!}">
													<span>Commentaire pour le RDV</span>
													<textarea rows="4" name="estate_comment_internal" class="form-control"></textarea>
													<div class="text-right mt-2">
														<button type="submit" class="btn btn-success" data-submit-form="form-new-comment-rdv">Ajouter commentaire</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
										<h6>RDV confirmé le</h6>
									</div>
								</div>
								<div class="ml-5">
									
									<form action="{!! route('validateconfirmation') !!}" method="POST" data-form="form-valider-confirmation">
										@csrf()
										<input type="hidden" name="estate_id" value="{!! $id !!}">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
												<div class="row">
													<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-4">
														<span class="ffhnm">Date :</span>
														<input type="date" class="form-control" id="date_confirm" name="date_confirm">
													</div>
													<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
														<span class="ffhnm">Heure initiale :</span>
														<input type="time" id="date_confirm_start" class="form-control" name="date_confirm_start">
													</div>
													<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4">
														<span class="ffhnm">Heure final :</span>
														<input type="time" id="date_confirm_end" class="form-control" name="date_confirm_end">
													</div>
												</div>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
												<div class="row">
													<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 col-xl-4 mt-3">
														<button type="submit" class="btn btn-success"data-submit-form="form-valider-confirmation" style="padding: 10px 30px;">Valider</button>
													</div>
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
												<label class="ffhnm"><input type="checkbox" name="send_reminder_half_past_eight" id="send_reminder_half_past_eight" data-url-eight="{!! route('savereminderhalfeight', [$id, 'response']) !!}" class="check-middle" {!! ($estate['send_reminder_half_past_eight'] == 1) ? 'checked' : '' !!} ><span class="align-middle"> Envoyer un rappel le jour même à 8h30</span></label>
											</div>
										</div>
										<div class="row mt-2">
											<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
												<button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmationemail" data-confirm-email>Envoyer E-mail de confirmation</button>
											</div>
											<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
												<button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmationsms" data-confirm-sms>Envoyer SMS de confirmation</button>
											</div>
										</div>
									</form>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-4" align="center">
										<span class="ffhnl" style="color:green">{!! $estate['visit_date_at'] !!}</span>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div id="estate-visit-remarks" class="mb-5">
			<form action="{!! route('updateremark') !!}" method="POST" data-form="estate-form-visit-remarks">
				@csrf()
				<input type="hidden" name="estate_id" value="{!! $id !!}">
				<div class="card font-body-content">
					<div class="card-header">
						Remarques visite
						<div class="text-right">
							<button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
							<button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-hide="true" data-submit-form="estate-form-visit-remarks">Sauvegarder</button>
						</div>
					</div>
					<div class="card-body">
						<div class="row justify-content-between">
							<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
								<span class="ffhnm">Etat maison:</span>
								<div class="row mt-2">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">&nbsp;- Intérieur:</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-2">
										<fieldset class="rating">
											<input data-change-radio type="radio" id="star5" name="interior_state" value="5" @isset($remarks['interior_state']) {!! ($remarks['interior_state'] == 5) ? 'checked' : '' !!} @endisset/><label class = "full" for="star5"></label>
											<input data-change-radio type="radio" id="star4" name="interior_state" value="4" @isset($remarks['interior_state']) {!! ($remarks['interior_state'] == 4) ? 'checked' : '' !!} @endisset/><label class = "full" for="star4" ></label>
											<input data-change-radio type="radio" id="star3" name="interior_state" value="3" @isset($remarks['interior_state']) {!! ($remarks['interior_state'] == 3) ? 'checked' : '' !!} @endisset/><label class = "full" for="star3"></label>
											<input data-change-radio type="radio" id="star2" name="interior_state" value="2" @isset($remarks['interior_state']) {!! ($remarks['interior_state'] == 2) ? 'checked' : '' !!} @endisset/><label class = "full" for="star2" ></label>
											<input data-change-radio type="radio" id="star1" name="interior_state" value="1" @isset($remarks['interior_state']) {!! ($remarks['interior_state'] == 1) ? 'checked' : '' !!} @endisset/><label class = "full" for="star1"></label>
										</fieldset>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur:</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-2">
										<fieldset class="rating">
											<input data-change-radio type="radio" id="star5ex" name="exterior_state" value="5" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state'] == 5) ? 'checked' : '' !!} @endisset/><label class = "full" for="star5ex"></label>
											<input data-change-radio type="radio" id="star4ex" name="exterior_state" value="4" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state'] == 4) ? 'checked' : '' !!} @endisset/><label class = "full" for="star4ex" ></label>
											<input data-change-radio type="radio" id="star3ex" name="exterior_state" value="3" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state'] == 3) ? 'checked' : '' !!} @endisset/><label class = "full" for="star3ex"></label>
											<input data-change-radio type="radio" id="star2ex" name="exterior_state" value="2" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state'] == 2) ? 'checked' : '' !!} @endisset/><label class = "full" for="star2ex" ></label>
											<input data-change-radio type="radio" id="star1ex" name="exterior_state" value="1" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state'] == 1) ? 'checked' : '' !!} @endisset/><label class = "full" for="star1ex"></label>
										</fieldset>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">Le quartier:</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-3">
										<fieldset class="rating">
											<input data-change-radio type="radio" id="star5dis" name="district_state" value="5" @isset($remarks['district_state']) {!! ($remarks['district_state'] == 5) ? 'checked' : '' !!} @endisset/><label class = "full" for="star5dis"></label>
											<input data-change-radio type="radio" id="star4dis" name="district_state" value="4" @isset($remarks['district_state']) {!! ($remarks['district_state'] == 4) ? 'checked' : '' !!} @endisset/><label class = "full" for="star4dis" ></label>
											<input data-change-radio type="radio" id="star3dis" name="district_state" value="3" @isset($remarks['district_state']) {!! ($remarks['district_state'] == 3) ? 'checked' : '' !!} @endisset/><label class = "full" for="star3dis"></label>
											<input data-change-radio type="radio" id="star2dis" name="district_state" value="2" @isset($remarks['district_state']) {!! ($remarks['district_state'] == 2) ? 'checked' : '' !!} @endisset/><label class = "full" for="star2dis" ></label>
											<input data-change-radio type="radio" id="star1dis" name="district_state" value="1" @isset($remarks['district_state']) {!! ($remarks['district_state'] == 1) ? 'checked' : '' !!} @endisset/><label class = "full" for="star1dis"></label>
										</fieldset>
									</div>
								</div>
								<span class="ffhnm">Points forts:</span>
								<div class="row mt-2">
									<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Intérieur:</div>
									<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="strong-points-i">
										<textarea data-change-input name="interior_highlights" class="form-control mb-2" rows="4">@isset($remarks['interior_highlights']) {!! $remarks['interior_highlights'] !!} @endisset</textarea>
									</div>
									<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur:</div>
									<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="strong-points-e">
										<textarea data-change-input name="exterior_highlights" class="form-control mb-2" rows="4">@isset($remarks['exterior_highlights']) {!! $remarks['exterior_highlights'] !!} @endisset</textarea>
									</div>
								</div>
								<span class="ffhnm">Points faibles:</span>
								<div class="row mt-2">
									<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Intérieur:</div>
									<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="weak-points-i">
										<textarea data-change-input name="interior_weak_point" class="form-control mb-2" rows="4">@isset($remarks['interior_weak_point']) {!! $remarks['interior_weak_point'] !!} @endisset</textarea>
									</div>
									<div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur:</div>
									<div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="weak-points-e">
										<textarea data-change-input name="exterior_weak_point" class="form-control mb-2" rows="4">@isset($remarks['exterior_weak_point']) {!! $remarks['exterior_weak_point'] !!} @endisset</textarea>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
								<span class="ffhnm">Sur le propriétaire</span>
								<div class="row mt-2">
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">&nbsp;- Désirs de vendre (%)</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">
										<label id="percentage" class="center-percentage"></label>
										<input data-change-radio type="range" name="desires_to_sell" id="desires_to_sell" class="form-control" value="@isset($remarks['desires_to_sell']){!! $remarks['desires_to_sell'] !!}@endisset" min="0" max="100">
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">&nbsp;- Estimation du client?:</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">
										<input data-change-input type="number" name="his_estimate" class="form-control" value="">
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">&nbsp;- Avis de l’agent:</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
										<textarea data-change-input name="agent_notice" class="form-control" rows="5">@isset($remarks['agent_notice']) {!! $remarks['agent_notice'] !!} @endisset</textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="text-right mt-4">
							<button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
							<button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-hide="true" data-submit-form="estate-form-visit-remarks">Sauvegarder</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="estate-reminders" class="mb-5">
			<div class="card mb-3 font-body-content">
				<div class="card-header">
					Configuration générale des rappels :
				</div>
				<form action="{!! route('changetime') !!}" method="POST" data-form="form-change-time">
					@csrf()
					<div class="card-body">
						<div class="row justify-content-center">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3">
								<span class="ffhnm">Changer l'heure d'envoie de tous les rappels :</span>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-3">
								<input data-change-input data-change-select class="form-control" type="time" id="time_to_send_reminder" name="time_to_send_reminder" value="{!! $estate['date_send_reminder'] !!}">
								<input type="hidden" name="estate_id" value="{!! $id !!}">
							</div>
						</div>
						<div class="text-right">
							<button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
							<a type="button" data-modified="false" data-submit-form="form-change-time" class="btn btn-success">Changer</a>
						</div>
					</div>
				</form>
			</div>
			<div class="card mb-3 font-body-content">
				<div class="card-header">
					Résumé des rappels :
				</div>
				<div class="card-body">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
						<nav style="color:#6C757D !important;">
							<ul class="nav nav-tabs" id="remindersTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link nav-p-link" href="#reminders-all" id="reminders-all-tab" data-toggle="tab" role="tab" aria-controls="reminders-all" aria-selected="true">Touts</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-p-link" href="#reminders-send" id="reminders-send-tab" data-toggle="tab" role="tab" aria-controls="reminders-send" aria-selected="false"><span class="color-status-e"></span> Envoyé</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-p-link" href="#reminders-auto" id="reminders-auto-tab" data-toggle="tab" role="tab" aria-controls="reminders-auto" aria-selected="false"><span class="color-status-a"></span> Automatique</a>
								</li>
								<li class="nav-item">
									<a class="nav-link nav-p-link" href="#reminders-manual" id="reminders-manual-tab" data-toggle="tab" role="tab" aria-controls="reminders-manual" aria-selected="false"><span class="color-status-m"></span>Manuel</a>
								</li>
							</ul>
						</nav>
					</div>
					<div class="tab-content" id="remindersTabContent">
						<div class="tab-pane fade active show" id="reminders-all" role="tabpanel" aria-labelledby="reminders-all-tab">
							<div class="row mb-4">
								@foreach($reminders as $reminder)
								<?php $putContent = true; ?>
									@foreach($reminder['content'] as $key => $content)
										@if($key == $reminder['next_reminder'] && $putContent)
										<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-3">
											<?php
											$class = '';
											$classbtn = '';
											$classbtns = '';
											if ($reminder['sent'] == 1) {
												$class = 'bg-success';
											} else {
												if ($content['type'] == 'task') {
													$class = 'bg-manual';
													$classbtn = 'btn-manual';
													$classbtns = 'btn-second-manual';
												} else {
													$class = 'bg-automatic';
													$classbtn = 'btn-automatic';
													$classbtns = 'btn-second-automatic';
												}
											}
											?>
											<div class="card reminder-content mb-4 {!! $class !!}" id="localisation">
												<div class="card-header text-white">
													{!! $reminder['name_reminder'] !!} : rappel {!! $reminder['id'] !!}
												</div>
												<div class="card-body">
													<div class="row mb-2">
														<div class="col-xs-12 col-sm-7 col-md-7 col-lg-12 col-xl-7 mb-2">
															<span class="ffhnl">Date de déclenchement : </span>
														</div>
														<div class="col-xs-12 col-sm-5 col-md-5 col-lg-12 col-xl-5 mb-2">
															<span class="ffhnl">{!! strftime('%a', strtotime($content['date'])) !!} {!! date('d-m-Y', strtotime($content['date'])) !!}</span>
														</div>
														<div class="col-xs-12 col-sm-8 col-md-8 col-lg-12 col-xl-8 mb-4">
															<span class="ffhnl">Type : </span>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 col-lg-12 col-xl-4 mb-4">
															@php
																$type = '';
																if($content['type'] 
																=== 'email') {
																	$type = 'E-mail';
																}
																if($content['type'] === 'sms') {
																	$type = 'SMS';
																}
																if($content['type'] === 'task') {
																	$type = 'Tâche';
																}
															@endphp
															<span class="ffhnl">{!! $type !!}</span>
														</div>
														@if($reminder['sent'] == 0)
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
															@if($content['type'] == 'task')
																<button type="button" class="btn {!! $classbtn !!}" data-toggle="modal" data-target="#editReminderTask-{!! $reminder['id'] !!}">Détails</button>
															@else
															<button type="button" class="btn {!! $classbtn !!}" data-toggle="modal" data-target="#editReminder-{!! $reminder['id'] !!}">Détails</button>
															@endif
														</div>
														<div class="text-right col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
															<a style="color:#fff" data-delete href="{!! route('deleterappel', $reminder['id']) !!}"><button type="button" float-right class="btn {!! $classbtns !!}">Annuler</button></a>
														</div>
														@endif
														@if($reminder['sent'] == 1)
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-green">
															<button class="btn btn-outline-success" data-toggle="modal" data-target="#seeDetails-{!! $reminder['id'] !!}">Détails</button>
														</div>
														<div class="text-right col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
															<button type="button" class="btn btn-success"><a style="color:#fff" href="{!! route('hidereminder', [$reminder['id'], $id]) !!}">Masquer</a></button>
														</div>
														@endif
													</div>
												</div>
											</div>
										</div>
										<?php $putContent = false; ?>
										@endif
										@if($reminder['next_reminder'] === count($reminder['content']) && $putContent)
										<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-3">
											<?php
											$class = 'bg-success';
											$classbtn = '';
											$classbtns = '';
											?>
											<div class="card reminder-content mb-4 {!! $class !!}" id="localisation">
												<div class="card-header text-white">
													{!! $reminder['name_reminder'] !!} : rappel {!! $reminder['id'] !!}
												</div>
												<div class="card-body">
													<div class="row mb-2">
														<div class="col-xs-12 col-sm-7 col-md-7 col-lg-12 col-xl-7 mb-2">
															<span class="ffhnl">Date de déclenchement : </span>
														</div>
														<div class="col-xs-12 col-sm-5 col-md-5 col-lg-12 col-xl-5 mb-2">
															<span class="ffhnl">{!! strftime('%a', strtotime($content['date'])) !!} {!! date('d-m-Y', strtotime($content['date'])) !!}</span>
														</div>
														<div class="col-xs-12 col-sm-8 col-md-8 col-lg-12 col-xl-8 mb-4">
															<span class="ffhnl">Type : </span>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 col-lg-12 col-xl-4 mb-4">
															@php
																$type = '';
																if($content['type'] 
																=== 'email') {
																	$type = 'E-mail';
																}
																if($content['type'] === 'sms') {
																	$type = 'SMS';
																}
																if($content['type'] === 'task') {
																	$type = 'Tâche';
																}
															@endphp
															<?php 

															 ?>
															<span class="ffhnl">{!! $type !!}</span>
														</div>
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-green">
															<button class="btn btn-outline-success" data-toggle="modal" data-target="#seeDetails-{!! $reminder['id'] !!}">Détails</button>
														</div>
														<div class="text-right col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
															<button type="button" class="btn btn-success"><a style="color:#fff" href="{!! route('hidereminder', [$reminder['id'], $id]) !!}">Masquer</a></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php $putContent = false; ?>
										@endif
									@endforeach
								@endforeach
							</div>
						</div>
						<div class="tab-pane fade" id="reminders-send" role="tabpanel" aria-labelledby="reminders-send-tab">
							<div class="row mb-4">
								@foreach($reminders as $reminder)
								<?php $putContent = true; ?>
									@foreach($reminder['content'] as $key => $content)
										@if($reminder['next_reminder'] === count($reminder['content']) && $putContent)
										<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-3">
											<?php
											$class = 'bg-success';
											$classbtn = '';
											$classbtns = '';
											?>
											<div class="card reminder-content mb-4 {!! $class !!}" id="localisation">
												<div class="card-header text-white">
													{!! $reminder['name_reminder'] !!} : rappel {!! $reminder['id'] !!}
												</div>
												<div class="card-body">
													<div class="row mb-2">
														<div class="col-xs-12 col-sm-7 col-md-7 col-lg-12 col-xl-7 mb-2">
															<span class="ffhnl">Date de déclenchement : </span>
														</div>
														<div class="col-xs-12 col-sm-5 col-md-5 col-lg-12 col-xl-5 mb-2">
															<span class="ffhnl">{!! strftime('%a', strtotime($content['date'])) !!} {!! date('d-m-Y', strtotime($content['date'])) !!}</span>
														</div>
														<div class="col-xs-12 col-sm-8 col-md-8 col-lg-12 col-xl-8 mb-4">
															<span class="ffhnl">Type : </span>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 col-lg-12 col-xl-4 mb-4">
															@php
																$type = '';
																if($content['type'] 
																=== 'email') {
																	$type = 'E-mail';
																}
																if($content['type'] === 'sms') {
																	$type = 'SMS';
																}
																if($content['type'] === 'task') {
																	$type = 'Tâche';
																}
															@endphp
															<?php 

															 ?>
															<span class="ffhnl">{!! $type !!}</span>
														</div>
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-green">
															<button class="btn btn-outline-success" data-toggle="modal" data-target="#seeDetails-{!! $reminder['id'] !!}">Détails</button>
														</div>
														<div class="text-right col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
															<button type="button" class="btn btn-success"><a style="color:#fff" href="{!! route('hidereminder', [$reminder['id'], $id]) !!}">Masquer</a></button>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php $putContent = false; ?>
										@endif
									@endforeach
								@endforeach
							</div>
						</div>
						<div class="tab-pane fade" id="reminders-auto" role="tabpanel" aria-labelledby="reminders-auto-tab">
							<div class="row mb-4">
								@foreach($reminders as $reminder)
								<?php $putContent = true; ?>
									@foreach($reminder['content'] as $key => $content)
										@if($reminder['next_reminder'] != count($reminder['content']) && $putContent)
										@if($content['type'] == 'sms' || $content['type'] == 'email')
										<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-3">
											<?php
												$class = 'bg-automatic';
												$classbtn = 'btn-automatic';
												$classbtns = 'btn-second-automatic';
											?>
											<div class="card reminder-content mb-4 {!! $class !!}" id="localisation">
												<div class="card-header text-white">
													{!! $reminder['name_reminder'] !!} : rappel {!! $reminder['id'] !!}
												</div>
												<div class="card-body">
													<div class="row mb-2">
														<div class="col-xs-12 col-sm-7 col-md-7 col-lg-12 col-xl-7 mb-2">
															<span class="ffhnl">Date de déclenchement : </span>
														</div>
														<div class="col-xs-12 col-sm-5 col-md-5 col-lg-12 col-xl-5 mb-2">
															<span class="ffhnl">{!! strftime('%a', strtotime($content['date'])) !!} {!! date('d-m-Y', strtotime($content['date'])) !!}</span>
														</div>
														<div class="col-xs-12 col-sm-8 col-md-8 col-lg-12 col-xl-8 mb-4">
															<span class="ffhnl">Type : </span>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 col-lg-12 col-xl-4 mb-4">
															@php
																$type = '';
																if($content['type'] 
																=== 'email') {
																	$type = 'E-mail';
																}
																if($content['type'] === 'sms') {
																	$type = 'SMS';
																}
																if($content['type'] === 'task') {
																	$type = 'Tâche';
																}
															@endphp
															<?php 

															 ?>
															<span class="ffhnl">{!! $type !!}</span>
														</div>
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
															<button type="button" class="btn {!! $classbtn !!}" data-toggle="modal" data-target="#editReminder-{!! $reminder['id'] !!}">Détails</button>
														</div>
														<div class="text-right col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
															<a style="color:#fff" data-delete href="{!! route('deleterappel', $reminder['id']) !!}"><button type="button" float-right class="btn {!! $classbtns !!}">Annuler</button></a>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php $putContent = false; ?>
										@endif
										@endif
									@endforeach
								@endforeach
							</div>
						</div>
						<div class="tab-pane fade" id="reminders-manual" role="tabpanel" aria-labelledby="reminders-manual-tab">
							<div class="row mb-4">
								@foreach($reminders as $reminder)
								<?php $putContent = true; ?>
									@foreach($reminder['content'] as $key => $content)
										@if($reminder['next_reminder'] != count($reminder['content']) && $putContent)
										@if($content['type'] == 'task')
										<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-3">
											<?php
												$class = 'bg-manual';
												$classbtn = 'btn-manual';
												$classbtns = 'btn-second-manual';
											?>
											<div class="card reminder-content mb-4 {!! $class !!}" id="localisation">
												<div class="card-header text-white">
													{!! $reminder['name_reminder'] !!} : rappel {!! $reminder['id'] !!}
												</div>
												<div class="card-body">
													<div class="row mb-2">
														<div class="col-xs-12 col-sm-7 col-md-7 col-lg-12 col-xl-7 mb-2">
															<span class="ffhnl">Date de déclenchement : </span>
														</div>
														<div class="col-xs-12 col-sm-5 col-md-5 col-lg-12 col-xl-5 mb-2">
															<span class="ffhnl">{!! strftime('%a', strtotime($content['date'])) !!} {!! date('d-m-Y', strtotime($content['date'])) !!}</span>
														</div>
														<div class="col-xs-12 col-sm-8 col-md-8 col-lg-12 col-xl-8 mb-4">
															<span class="ffhnl">Type : </span>
														</div>
														<div class="col-xs-12 col-sm-4 col-md-4 col-lg-12 col-xl-4 mb-4">
															@php
																$type = '';
																if($content['type'] 
																=== 'email') {
																	$type = 'E-mail';
																}
																if($content['type'] === 'sms') {
																	$type = 'SMS';
																}
																if($content['type'] === 'task') {
																	$type = 'Tâche';
																}
															@endphp
															<?php 

															 ?>
															<span class="ffhnl">{!! $type !!}</span>
														</div>
														<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 ">
															<button type="button" class="btn {!! $classbtn !!}" data-toggle="modal" data-target="#editReminder-{!! $reminder['id'] !!}">Détails</button>
														</div>
														<div class="text-right col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
															<a style="color:#fff" data-delete href="{!! route('deleterappel', $reminder['id']) !!}"><button type="button" float-right class="btn {!! $classbtns !!}">Annuler</button></a>
														</div>
													</div>
												</div>
											</div>
										</div>
										<?php $putContent = false; ?>
										@endif
										@endif
									@endforeach
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
			<button type="button" data-toggle="modal" data-target="#createReminder" class="btn btn-lg btn-success">Envoyer ou créer un rappel</button>
			<button type="button" data-toggle="modal" data-target="#createReminderTask" class="btn btn-lg btn-success">Créer une tâche simple</button>
		</div>
		<div id="estate-comments" class="mb-5">
			<form action="{!! route('newcomment') !!}" method="POST" data-form="form-estate-comments" data-reload="true">
				@csrf()
				<div class="card mb-5 font-body-content">
					<div class="card-header">Commentaire interne :</div>
					<div class="card-body">
						<textarea data-change-input name="estate_comment_internal" class="form-control" rows="5"></textarea>
						<input type="hidden" name="estate_id" value="{!! $id !!}">
						<div class="text-right mt-4">
							<button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
							<button type="submit" data-submit-hide="false" data-submit-form="form-estate-comments" data-modified="false" class="btn btn-lg btn-success">Sauvegarder le commentaire</button>
						</div>
						<span>Historique commentaires :</span>
						<div>
							@foreach($comments as $comment)
								@if($comment['estate_id'] == $estate['id'])
									{!! strftime('%a', strtotime($comment['created_at'])) !!} {!! date('d-m-Y H:i', strtotime($comment['created_at'])) !!}, {!! $comment['username'] !!} : {!! $comment['comment'] !!} <br>
								@endif
							@endforeach
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="estate-problems" class="mb-5">
			<form action="{!! route('newresolution') !!}" method="POST" data-form="estate-form-problems" data-reload="true">
				@csrf()
				<div class="card font-body-content">
					<div class="card-header">
						Problèmes signalés par le requéreur
						<div class="row">
							<div class="col-12 text-right">
								<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_problems" id="estate_include_problems">
							</div>
						</div>
					</div>
					<div class="card-body">
						<div class="mb-4">
							<span>Problème signalé dans le formulaire :</span>
							<textarea name="estate_problem_signal" rows="5" class="form-control" disabled>{!! $estateDetails['problems'] !!}</textarea>
						</div>
						<div class="mb-4">
							<span>Suivi résolution :</span>
							<div>
								@foreach($resolutions as $resolution)
									@if($resolution['estate_id'] == $estate['id'])
										{!! strftime('%a', strtotime($resolution['created_at'])) !!} {!! date('d-m-Y H:i', strtotime($resolution['created_at'])) !!}, {!! $resolution['username'] !!} : {!! $resolution['comment'] !!} <br>
									@endif
								@endforeach
							</div>
						</div>
						<div class="mb-4">
							<span>Nouveau commentaire :</span>
							<textarea data-change-input name="estate_new_problem" rows="5" class="form-control"></textarea>
							<input type="hidden" name="estate_id" value="{!! $id !!}">
						</div>
						<div class="text-right">
							<button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
							<button type="submit" data-submit-hide="false" data-submit-form="estate-form-problems" data-modified="false" class="btn btn-lg btn-success">Valider</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		@if(Auth::user()->type != 3)
		<div id="estate-offer" class="mb-5">
			<form action="{!! route('updateoffer') !!}" method="POST" data-form="estate-form-offer" class="mb-5">
				@csrf()
				<input type="hidden" name="estate_id" value="{!! $id !!}">
				<div class="card font-body-content">
					<div class="card-header">Préparation offre</div>
					<div class="card-body">
						<form action="" method="POST" data-form="estate-form-offer">
							<div>
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">Prix estimé par le requéreur :</div>
											<?php
													$priceSeller = 0;
													if (isset($offer['price_seller'])) {
														$priceSeller = $offer['price_seller'];
													}
												?>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
												<input type="number" data-input-price="{!! $priceSeller !!}" class="form-control" placeholder="€" value="{!!$priceSeller!!}" disabled>
												<label class="price">€</label>
												<input type="hidden" name="price_seller" id="price_sellerr" class="form-control" placeholder="€" value="{!! $priceSeller !!}">
											</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">Notre estimation :</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
												<?php
													$priceMarket = 0;
													if (isset($offer['price_market'])) {
														$priceMarket = $offer['price_market'];
													}
												?>
												<input type="number" data-input-price-market="{!! $priceMarket !!}"	class="form-control" placeholder="€" value="{!! $priceMarket !!}" disabled>
												<label class="price">€</label>
												<input type="hidden" name="price_market"	id="price_market" class="form-control" placeholder="€" value="{!! $priceMarket !!}">
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">Prix offert par We Sold :</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
												<?php
													$priceWesold = 0;
													if (isset($offer['price_wesold'])) {
														$priceWesold = $offer['price_wesold'];
													}
												?>
												<input type="number" data-input-price-we-sold="{!! $priceWesold !!}" class="form-control" placeholder="€" value="{!! $priceWesold !!}">
												<label class="price">€</label>
												<input type="hidden" name="price_wesold" id="price_wesold" class="form-control" placeholder="€" value="{!! $priceWesold !!}">
											</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">@isset($offer['pdf']) <h1><a href="{!! asset('pdfs/'.$offer['pdf']) !!}" target="_blank"><i class="bi bi-file-earmark-text"></i> </a></h1> <a href="{!! asset('pdfs/'.$offer['pdf']) !!}" target="_blank">Offre créée </a>@endisset</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">Notaire :</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
												<select class="form-control" name="notary" id="data_notary">
													<option value=""></option>
													@foreach($notaries as $notary)
														<option  @isset($offer['notaire']) {!! ($offer['notaire'] == $notary['name'].' '.$notary['lastname'].' ('.$notary['key'].')') ? 'selected' : '' !!} @endisset value="{!! $notary['name'] !!} {!! $notary['lastname'] !!} ({!! $notary['key'] !!})">{!! $notary['name'] !!} {!! $notary['lastname'] !!}</option>
														}
													@endforeach
												</select>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">Condition offre :</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
												<select class="form-control" name="condition" id="condition">
													<option value=""></option>
													@foreach($templates as $template)
														@if($template['type'] == 'condition')
															<option @isset($offer['condition_offer']) {!! ($offer['condition_offer'] == file_get_contents(asset('templates/'.$template['file']))) ? 'selected' : '' !!} @endisset value="{!! file_get_contents(asset('templates/'.$template['file'])) !!}">{!! file_get_contents(asset('templates/'.$template['file'])) !!}</option>
														@endif
													@endforeach
												</select>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">Validité de l offre :</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
												<input type="date" name="number_offer" class="form-control" value="@isset($offer['validity'] ) {!! $offer['validity'] !!} @endisset">
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">Texte à ajouter à l’offre :</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
												<select class="form-control" name="text_add_offer" id="text_add_offer">
													<option></option>
													@foreach($templates as $template)
														@if($template['type'] == 'text-offer')
															<option value="{!! $template['id'] !!}" > {!! $template['name'] !!} </option>
														@endif
													@endforeach
												</select>
												@foreach($templates as $template)
													@if($template['type'] == 'text-offer')
														<textarea style="display: none" name="{!! $template['id'] !!}" id="offre-{!! $template['id'] !!}">{!! file_get_contents(asset('templates/'.$template['file'])) !!}
														</textarea>
													@endif
												@endforeach
											</div>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
												<textarea name="other_offer" id="other_offer" data-height="300" data-tiny="tinyCreateTemplateTextOffer" rows="5" class="form-control">@isset($offer['textadded']) {!! $offer['textadded'] !!} @endisset</textarea>
											</div>
										</div>
									</div>
								</div>
								@if(Auth::user()->type != 3)
								<div class="text-right mt-4">
									<button type="button" class="btn btn-lg btn-success" data-toggle="modal" data-target="#editOfferPDF" data-save-data >Éditer Offre</button>
								</div>
								@endif
							</div>
						</form>
					</div>
				</div>
			</form>
			<form action="{!! route('sendemailoffer') !!}" method="POST" data-form="estate-send-offer">
				@csrf()
				<input type="hidden" name="estate_id" value="{!! $id !!}">
				<div class="card font-body-content">
					<div class="card-header">Envoi de l'offre</div>
					<div class="card-body">
						<form action="" method="POST" data-form="estate-send-offer">
							<div class="autosize-m">
								<div class="row">
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">Mail par défault du client :</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
												{!! $seller['email'] !!}
												<input type="hidden" name="emails[]" value="{!! $seller['email'] !!}">
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">Ajout un mail en copie :</div>
											<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
												<input data-change-input type="text" class="form-control" id="new_email">
												<span class="wrapper__add" id="add-email">@include('svg.iconplus')</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
												<div class="wrapper__content" id="see_emails_added">
													<div class="wrapper__remove" id="see_email">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-1 col-lg-12 col-xl-1 mb-2">Sujet :</div>
											<div class="col-xs-12 col-sm-12 col-md-11 col-lg-12 col-xl-11 mb-2">
												@foreach($templates as $template)
													@if($template['type'] == 'subject')
													<input data-change-input type="text" name="subject" class="form-control" required value="{!! $template['file'] !!}">
													@endif
												@endforeach
											</div>
										</div>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<div class="row">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">Corps :</div>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
												<select data-change-select class="form-control" name="text_add_email_offer" id="text_add_email_offer">
													<option>Choisissez un modèle</option>
													@foreach($templates as $template)
														@if($template['type'] == 'email')
															<option value="{!! $template['id'] !!}">{!! $template['name'] !!}</option>
														@endif
													@endforeach
												</select>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
												<textarea data-change-select  name="body" id="corps" required rows="5" class="form-control"></textarea>
											</div>
										</div>
									</div>
								</div>
								@if(Auth::user()->type != 3)
								<div class="text-right mt-4">
									<button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
									<button type="submit" data-modified="false" class="btn btn-lg btn-success" data-submit-form="estate-send-offer">Envoyé</button>
								</div>
								@endif
							</div>
						</form>
					</div>
				</div>
			</form>
		</div>
		@endif
		<div id="estate-tickets" class="mb-5">
			<div class="card font-body-content">
				<div class="card-header">Tickets</div>
				<div class="card-body">
					<div>
						<div class="row mb-3">
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
								<button class="btn btn-success" data-toggle="modal" data-target="#createnewticket">Créer un nouveau ticket</button>
							</div>
						</div>
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
										@if($ticket->status != 4)
										<tr>
											<?php if (Auth::user()->type == 2): ?>
												<td>Users</td>
											<?php endif ?>
											<td>{!! $ticket->id !!} {!! (in_array($ticket->id, $auxticketsNoAnswer)) ? '<sup style="color:red"><i class="bi bi-circle-fill"></i></sup>' : '' !!}</td>
											<td><a href="{!! route('viewoneticketdetails', [$ticket->id, $id]) !!}">{!! $ticket->title !!}</a></td>
											<td>{!! $ticket->requester->name !!}</td>
											<td>{!! strftime('%a', strtotime($ticket->created_at)) !!} {!! date('d-m-Y', strtotime($ticket->created_at)) !!}</td>
											<td>{!! strftime('%a', strtotime($ticket->updated_at)) !!} {!! date('d-m-Y', strtotime($ticket->updated_at)) !!}</td>
										</tr>
										@endif
									@endforeach
								</tbody>
							</table>
						</div>
						<hr>
						<h4>Résolu</h4>
						<hr>
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
								@foreach($auxTickets as $ticketsolved)
									@if($ticketsolved->status == 4)
										<tr>
											<td>{!! $ticketsolved->id !!}</td>
											<td><a href="{!! route('viewoneticketdetails', [$ticketsolved->id, $id]) !!}">{!! $ticketsolved->title !!}</td>
											<td>{!! $ticketsolved->requester->name !!}</td>
											<td>{!! strftime('%a', strtotime($ticketsolved->created_at)) !!} {!! date('d-m-Y', strtotime($ticketsolved->created_at)) !!}</td>
											<td>{!! strftime('%a', strtotime($ticketsolved->updated_at)) !!} {!! date('d-m-Y', strtotime($ticketsolved->updated_at)) !!}</td>
										</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<div id="estate-status" class="mb-5">
			<form action="{!! route('exportcsv') !!}" method="POST" data-form="estate-form-status" data-ajax="false">
				@csrf
				<input type="hidden" name="estate_id" value="{!! $id !!}">
				<div class="card font-body-content">
					<div class="card-header">Les statuts</div>
					<div class="card-body">
						<table data-table="estates-status" class="display responsive nowrap" style="width: 100%;">
							<thead>
								<tr>
									<th>Statut</th>
									<th>Start</th>
									<th>Stop</th>
									<th>User</th>
								</tr>
							</thead>
							<tbody>
								@foreach($status as $stat)
									<tr>
										<td>{!! $stat['categoryname'] !!}</td>
										<td>{!! strftime('%a', strtotime($stat['start_at'])) !!} {!! date('d-m-Y H:i', strtotime($stat['start_at'])) !!}</td>
										<td>{!! strftime('%a', strtotime($stat['stop_at'])) !!} {!! date('d-m-Y H:i', strtotime($stat['stop_at'])) !!}</td>
										<td>{!! $stat['username'] !!}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						<div class="text-right mt-4">
							<button type="submit" data-submit-hide="false" data-submit-form="estate-form-status" class="btn btn-lg btn-success">Exporter CSV</button>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="estate-log">
			<div class="card font-body-content">
				<div class="card-header">Log</div>
				<div class="card-body">
					<table data-table="estates-log" class="display responsive nowrap" style="width: 100%;">
						<thead>
							<tr>
								<th>Datetime</th>
								<th>Champ</th>
								<th>Utilisateur</th>
								<th>Valeur précédente</th>
								<th>Valeur actuelle</th>
							</tr>
						</thead>
						<tbody>
							@foreach($logs as $log)
							<tr>
								<td>{!! strftime('%a', strtotime($log['created'])) !!} {!! date('d-m-Y H:i', strtotime($log['created'])) !!}</td>
								<td>{!! __('fields.'.$log['field']) !!}</td>
								<td>{!! $log['user_id'] !!}</td>
								<td>{!! $log['old_value'] !!}</td>
								<td>{!! $log['new_value'] !!}</td>
							</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>