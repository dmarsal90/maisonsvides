@extends('layouts.app')

@section('content')
<header>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="{{ asset('css/peb.css') }}">
</header>
<div class="card font-body-content" id="visitStep1">
    <div class="card-header" id="visits-google-map">
        Dossier: <?php echo date('ymd', strtotime($estate['reference'])) ?>
        <div class="wrapper__icons">
            <!-- <span data-action="open-camera">
					@include('svg.iconcamera')
				</span>
				<span data-action="open-gallery">
					@include("svg.iconpicture")
				</span> -->
        </div>
    </div>
    <div class="card-body">

        @if(session('message'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('message') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <script>
            setTimeout(function() {
                $('.alert').fadeOut();
            }, 3000);
        </script>
        @endif
        <br>
        <form action="{!! route('editdetails') !!}" method="POST" data-form="form-visit-information" data-reload="true">
            @csrf()
            <input type="hidden" name="estate_id" value="{!! $id !!}">
            <input type="hidden" name="seller_id" value="{!! $seller['id'] !!}">
            <input type="hidden" name="offer_id" value="@isset($offer['id']) {!! $offer['id'] !!} @endisset">
            <div class="autosize-l mb-5">
                <div class="wrapper__content">
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Contact [ 20- seller_name ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="seller_name" class="form-control" @isset($details['seller_name']) value="{!! $details['seller_name'] !!}" @else value="{!! $estate['name'] !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Tel [ 21- seller_phone ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="seller_phone" class="form-control" @isset($details['seller_phone']) value="{!! $details['seller_phone'] !!}" @else value="{!! $estate['phone'] !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Mail [ 22- seller_email ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="seller_email" class="form-control" @isset($details['seller_email']) value="{!! $details['seller_email'] !!}" @else value="{!! $estate['email'] !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Année de construction [ 8- year_construction ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="year_construction" class="form-control" @isset($details['year_construction']) value="{!! $details['year_construction'] !!}" @else value="{!! $estate['construction'] !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Année de rénovation [ 9- year_renovation ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="year_renovation" @isset($details['year_renovation']) value="{!! $details['year_renovation'] !!}" @else value="{!! $estate['renovation'] !!}" @endisset class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">PEB [ 12- peb ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="peb" id="peb-input" @isset($details['peb']) value="{!! $details['peb'] !!}" @else value="{!! $estate['peb'] !!}" @endisset class="form-control peb-@isset($details['peb']){{ strtolower($details['peb']) }}@else{{ strtolower($estate['peb']) }}@endisset" oninput="updatePebColor()">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Surface approximative en mètres carrés [ 44- surface ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="surface" @isset($details['surface']) value="{!! $details['surface'] !!}" @else value="{!! $estate['surface'] !!}" @endisset class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Town planning [ 23- town_planning ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="town_planning" @isset($details['town_planning']) value="{!! $details['town_planning']== 1 ? 'oui' : 'non' !!}" @else value="{!! $estate['town_planning']== 1 ? 'oui' : 'non' !!}" @endisset class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Plus de chambres? [ 24- more_habitations ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="more_habitations" @isset($details['more_habitations']) value="{!! $details['more_habitations']== 1 ? 'oui' : 'non' !!}" @else value="" @endisset class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Nbre de chambres [ 25- rooms ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="rooms" @isset($details['rooms']) value="{!! $details['rooms'] !!}" @else value="{!! $estate['rooms'] !!}" @endisset class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Nbre de sdb [ 26- bathrooms ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="bathrooms" @isset($details['bathrooms']) value="{!! $details['bathrooms'] !!}" @else value="{!! $estate['bathrooms'] !!}" @endisset class="form-control">
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Description générale [ 27- estate_description ]:</div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                <textarea data-change-input name="estate_description" class="form-control" rows="5">@isset($details['estate_description']){{ $details['estate_description'] }}@endisset</textarea>

                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Adresse du bien [ 22- seller_email ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="estate_street" class="form-control" id="estate-street-input" @isset($details['estate_street']) value="{!! $details['estate_street'] !!}" @else value="{!! $estate['street'] !!}" @endisset oninput="updateCoordinates()">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <img data-toggle="modal" data-target="#showCoordinates" src="{!! asset('img/icons/coordinates.svg') !!}" width="22">
                                    <span class="ffhnm">Coordonnées x et y [ 10,11- coordinate_x, coordinate_y ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="coordinate_x" id="coordinate_x" class="form-control mb-2" placeholder="50.87040108685863" @isset($details['coordinate_x']) value="{!! $details['coordinate_x'] !!}" @else value="" @endisset>
                                    <input data-change-input type="text" name="coordinate_y" id="coordinate_y" class="form-control" placeholder="4.39901196259919" @isset($details['coordinate_y']) value="{!! $details['coordinate_y'] !!}" @else value="" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Prix fixé par le client [ 45- price_client ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="text" name="price_client" class="form-control" placeholder="€" @isset($details['price_client']) value="{!! $details['price_client'] !!}" @else value="{!! $estate['price_published_himself'] !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Jardin [ 29- jardin ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="text" name="jardin" class="form-control" placeholder="" @isset($details['jardin']) value="{!! $details['jardin']== 1 ? 'oui' : 'non' !!}" @else value="{!! $estate['garden']== 1 ? 'oui' : 'non' !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Terrasse [ 46- terrese ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="text" name="terrase" class="form-control" placeholder="€" @isset($details['terrese']) value="{!! $details['terrese']== 1 ? 'oui' : 'non' !!}" @else value="{!! $estate['terrase']== 1 ? 'oui' : 'non' !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Garage [ 47- garage ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="text" name="garage" class="form-control" placeholder="€" @isset($details['garage']) value="{!! $details['garage']== 1 ? 'oui' : 'non' !!}" @else value="{!! $estate['garage']== 1 ? 'oui' : 'non' !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Nbre de compteurs de gaz [ 30- gaz ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="number" name="gaz" class="form-control" placeholder="" @isset($details['gaz']) value="{!! $details['gaz'] !!}" @else value="{!! $estate['number_gas'] !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Nbre de compteurs électriques [ 31- electrique ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="number" name="electrique" class="form-control" placeholder="" @isset($details['number_electric']) value="{!! $details['number_electric'] !!}" @else value="{!! $estate['number_electric'] !!}" @endisset>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Type de propriété [ 48- type_estate ]:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="text" name="type_estate" class="form-control" placeholder="" @isset($details['type_estate']) value="{!! $details['type_estate'] !!}" @else value="{!! $estate['type_estate'] !!}" @endisset>
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12 mb-2 ml-2">
                            <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Un commentaire ? [ 32- details_commentaire ] :</div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                <textarea data-change-input name="details_commentaire" class="form-control" rows="5">@isset($details['details_commentaire']){{ $details['details_commentaire'] }}@endisset</textarea>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-between">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <span class="ffhnm">État de la propriété:</span>
                                    <div class="row mt-2">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">&nbsp;- Intérieur [ 33- interior_state ]:</div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-2">
                                            <fieldset class="rating">
                                                <input data-change-radio type="radio" id="star5" name="interior_state" value="5" @isset($details['interior_state']) {!! ($details['interior_state']==5) ? 'checked' : '' !!} @endisset /><label class="full" for="star5"></label>
                                                <input data-change-radio type="radio" id="star4" name="interior_state" value="4" @isset($details['interior_state']) {!! ($details['interior_state']==4) ? 'checked' : '' !!} @endisset /><label class="full" for="star4"></label>
                                                <input data-change-radio type="radio" id="star3" name="interior_state" value="3" @isset($details['interior_state']) {!! ($details['interior_state']==3) ? 'checked' : '' !!} @endisset /><label class="full" for="star3"></label>
                                                <input data-change-radio type="radio" id="star2" name="interior_state" value="2" @isset($details['interior_state']) {!! ($details['interior_state']==2) ? 'checked' : '' !!} @endisset /><label class="full" for="star2"></label>
                                                <input data-change-radio type="radio" id="star1" name="interior_state" value="1" @isset($details['interior_state']) {!! ($details['interior_state']==1) ? 'checked' : '' !!} @endisset /><label class="full" for="star1"></label>
                                                <!-- <input data-change-radio type="radio" id="star1" name="interior_state" value="1" @isset($remarks['interior_state']) {!! ($remarks['interior_state']==1) ? 'checked' : '' !!} @endisset /><label class="full" for="star1"></label> -->
                                            </fieldset>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur [ 34- exterior_state ]:</div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-2">
                                            <fieldset class="rating">
                                                <input data-change-input type="radio" id="star5ex" name="exterior_state" value="5" @isset($details['exterior_state']) {!! ($details['exterior_state']==5) ? 'checked' : '' !!} @endisset /><label class="full" for="star5ex"></label>
                                                <input data-change-input type="radio" id="star4ex" name="exterior_state" value="4" @isset($details['exterior_state']) {!! ($details['exterior_state']==4) ? 'checked' : '' !!} @endisset /><label class="full" for="star4ex"></label>
                                                <input data-change-input type="radio" id="star3ex" name="exterior_state" value="3" @isset($details['exterior_state']) {!! ($details['exterior_state']==3) ? 'checked' : '' !!} @endisset /><label class="full" for="star3ex"></label>
                                                <input data-change-input type="radio" id="star2ex" name="exterior_state" value="2" @isset($details['exterior_state']) {!! ($details['exterior_state']==2) ? 'checked' : '' !!} @endisset /><label class="full" for="star2ex"></label>
                                                <input data-change-input type="radio" id="star1ex" name="exterior_state" value="1" @isset($details['exterior_state']) {!! ($details['exterior_state']==1) ? 'checked' : '' !!} @endisset /><label class="full" for="star1ex"></label>
                                            </fieldset>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">Le quartier [ 35- district_state ]:</div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-3">
                                            <fieldset class="rating">
                                                <input data-change-radio type="radio" id="star5dis" name="district_state" value="5" @isset($details['district_state']) {!! ($details['district_state']==5) ? 'checked' : '' !!} @endisset /><label class="full" for="star5dis"></label>
                                                <input data-change-radio type="radio" id="star4dis" name="district_state" value="4" @isset($details['district_state']) {!! ($details['district_state']==4) ? 'checked' : '' !!} @endisset /><label class="full" for="star4dis"></label>
                                                <input data-change-radio type="radio" id="star3dis" name="district_state" value="3" @isset($details['district_state']) {!! ($details['district_state']==3) ? 'checked' : '' !!} @endisset /><label class="full" for="star3dis"></label>
                                                <input data-change-radio type="radio" id="star2dis" name="district_state" value="2" @isset($details['district_state']) {!! ($details['district_state']==2) ? 'checked' : '' !!} @endisset /><label class="full" for="star2dis"></label>
                                                <input data-change-radio type="radio" id="star1dis" name="district_state" value="1" @isset($details['district_state']){!! ($details['district_state']=='1' ) ? 'checked' : '' !!}@endisset /><label class="full" for="star1dis"></label>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <span class="ffhnm">Points forts:</span>
                                    <div class="row mt-2">
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Intérieur [ 36- interior_highlights ]:</div>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="strong-points-i">
                                            <textarea data-change-input name="interior_highlights" class="form-control mb-2" rows="4">@isset($details['interior_highlights']){{ $details['interior_highlights'] }}@endisset</textarea>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur [ 37- exterior_highlights ]:</div>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="strong-points-e">
                                            <textarea data-change-input name="exterior_highlights" class="form-control mb-2" rows="4">@isset($details['exterior_highlights']){{ $details['exterior_highlights'] }}@endisset</textarea>
                                        </div>
                                    </div>
                                    <span class="ffhnm">Points faibles:</span>
                                    <div class="row mt-2">
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Intérieur [ 38- interior_weak_point ]:</div>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="weak-points-i">
                                            <textarea data-change-input name="interior_weak_point" class="form-control mb-2" rows="4">@isset($details['interior_weak_point']){{ $details['interior_weak_point'] }}@endisset</textarea>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur [ 39- exterior_weak_point ]:</div>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="weak-points-e">
                                            <textarea data-change-input name="exterior_weak_point" class="form-control mb-2" rows="4">@isset($details['exterior_weak_point']){{ $details['exterior_weak_point'] }}@endisset</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <span class="ffhnm">Sur le propriétaire</span>
                                    <div class="row mt-2">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">&nbsp;- Désirs de vendre (%) [ 40- desires_to_sell ]</div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">
                                            <label id="percentage" class="center-percentage"></label>
                                            <input data-change-radio type="range" name="desires_to_sell" id="desires_to_sell" class="form-control" min="0" max="100" @isset($details['desires_to_sell']) value="{!! isset($details['desires_to_sell']) ? $details['desires_to_sell'] : 0 !!}" @endisset>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2t">
                                            <span class="ffhnm">Prix évalué par le client [ 13- price_evaluated ]:</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                            <input data-change-input type="text" name="price_evaluated" class="form-control" placeholder="Entrez le prix évalué" @isset($details['price_evaluated']) value="{!! isset($details['price_evaluated']) ? $details['price_evaluated'] : '' !!}" @endisset>
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2t">
                                            <span class="ffhnm">Prix du marché [ 14- price_market ]:</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                            <input data-change-input type="text" name="price_market" class="form-control" placeholder="Entrez le prix du marché" @isset($details['price_market']) value="{!! isset($details['price_market']) ? $details['price_market'] : '' !!}" @endisset>
                                        </div>

                                        <div class="mb-2 ml-2">
                                            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12" align="center">
                                                <span class="ffhnm mr-4">Entièrement à rénover à l'intérieur [ 42- details_state_interior ]</span>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='1' ) ? 'checked' : '' !!}@endisset value="1" data-save>1</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='2' ) ? 'checked' : '' !!} @endisset value="2" data-save>2</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='3' ) ? 'checked' : '' !!} @endisset value="3" data-save>3</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='4' ) ? 'checked' : '' !!} @endisset value="4" data-save>4</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='5' ) ? 'checked' : '' !!} @endisset value="5" data-save>5</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='6' ) ? 'checked' : '' !!} @endisset value="6" data-save>6</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='7' ) ? 'checked' : '' !!} @endisset value="7" data-save>7</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='8' ) ? 'checked' : '' !!} @endisset value="8" data-save>8</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='9' ) ? 'checked' : '' !!} @endisset value="9" data-save>9</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='10' ) ? 'checked' : '' !!}@endisset value="10" data-save>10</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['details_state_interior']){!! ($details['details_state_interior']=='0' ) ? 'checked' : '' !!}@endisset value="0" data-save>Neuf</label>

                                            </div>
                                        </div>
                                        <div class="mb-2 ml-2">
                                            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12" align="center">
                                                <span class="ffhnm mr-4">Entièrement à rénover à l'extérieur [ 43- details_state_exterior ]</span>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='1' ) ? 'checked' : '' !!}@endisset value="1" data-save>1</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='2' ) ? 'checked' : '' !!}@endisset value="2" data-save>2</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='3' ) ? 'checked' : '' !!}@endisset value="3" data-save>3</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='4' ) ? 'checked' : '' !!}@endisset value="4" data-save>4</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='5' ) ? 'checked' : '' !!}@endisset value="5" data-save>5</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='6' ) ? 'checked' : '' !!}@endisset value="6" data-save>6</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='7' ) ? 'checked' : '' !!}@endisset value="7" data-save>7</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='8' ) ? 'checked' : '' !!}@endisset value="8" data-save>8</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='9' ) ? 'checked' : '' !!}@endisset value="9" data-save>9</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='10' ) ? 'checked' : '' !!}@endisset value="10" data-save>10</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['details_state_exterior']){!! ($details['details_state_exterior']=='0' ) ? 'checked' : '' !!}@endisset value="0" data-save>Neuf</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">&nbsp;- Avis de l’agent [ 41- agent_notice ]:</div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                                        <textarea data-change-input name="agent_notice" class="form-control" rows="5">@isset($details['agent_notice']){{ $details['agent_notice'] }}@endisset</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <div class="text-right mt-4 mb-4 mr-3">
        <a href="/visits" class="btn btn-lg btn-danger">Annuler</a>
        <button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-hide="true" data-submit-form="form-visit-information">Sauvegarder</button>
    </div>

    <div class="card bg-light col-lg-12 my-4">
        <div class="card-body">
            <div class="text-right autosize-s">
                <div class="mt-4">
                    <label class="mr-3"><input type="checkbox" id="status-visite" checked="false"> Passer le status du bien en visite fini</label>
                </div>
                <div class="mt-2">
                    <a href="{!! route('estatevisited', [$id, 'option']) !!}" data-terminer class="btn btn-lg btn-danger">Terminer</a>
                </div>
            </div>
        </div>
    </div>
    </form>
    <form action="{!! route('newcomment') !!}" method="POST" data-form="form-visit-comment-estate" data-reload="true">
        @csrf()
        <div class="autosize-s">
            <div class="card font-body-content">
                <div class="card-header">Enregistrer un commentaire interne:</div>
                <div class="card-body card-body-white">
                    <textarea data-change-input name="estate_comment_internal" rows="5" class="form-control"></textarea>
                    <input type="hidden" name="estate_id" value="{!! $id !!}">
                    <div class="text-right mt-4">
                        <button type="reset" class="btn btn-lg btn-dark mr-1" data-cancel>Annuler</button>
                        <button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-hide="false" data-submit-form="form-visit-comment-estate">Sauvegarder le commentaire</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
</div>



</div>
</div>
@endsection

@section('modals')
<div class="modal fade font-body-content" id="showCoordinates" tabindex="-1" aria-labelledby="showCoordinatesLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="card">
                    <div class="card-header">Obtenir les coordonnées</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div id="map"></div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 ">
                                Coordonnées: <div class="wrapper__content" id="coordinates"></div>
                            </div>
                        </div>
                        <form action="{!! route('editdetails') !!}" method="POST" data-form="form-coordinates" data-reload="true">
                            @csrf()
                            <input type="hidden" name="estate_id" value="{!! $id !!}">
                            <input type="hidden" name="save__coordinate_x" id="save__coordinate_x" value="">
                            <input type="hidden" name="save__coordinate_y" id="save__coordinate_y" value="">
                            Adresse: <input type="text" class="form-control" name="estate__street" id="estate___street" value="">
                            <div class="mt-3 text-right">
                                <button type="submit" data-save-coordinates data-dismiss="modal" class="btn btn-lg btn-success" data-submit-form="form-coordinates">Sauvegarder</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&callback=initMap" async defer></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
<script>
    function updatePebColor() {
        var input = document.getElementById("peb-input");
        var peb = input.value.toLowerCase();
        var className = "peb-" + peb;
        input.className = "form-control " + className;
    }
</script>
<script>
    function updateCoordinates() {
        var street = document.getElementById("estate-street-input").value;
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({
            'address': street
        }, function(results, status) {
            if (status === 'OK') {
                var lat = results[0].geometry.location.lat();
                var lng = results[0].geometry.location.lng();
                document.getElementById("coordinate_x").value = lat;
                document.getElementById("coordinate_y").value = lng;
                // habilitar los campos de entrada
                document.getElementById("coordinate_x").disabled = false;
                document.getElementById("coordinate_y").disabled = false;
            } else {
                console.log('Geocode was not successful for the following reason: ' + status);
                // deshabilitar los campos de entrada
                document.getElementById("coordinate_x").disabled = true;
                document.getElementById("coordinate_y").disabled = true;
            }
        });
    }

    function initMap() {
        var geocoder = new google.maps.Geocoder();

        // Obtener el campo de entrada "estate_street" por su identificador
        var estateStreetInput = document.getElementById("estate-street-input");

        // Escuchar el evento "input" en el campo de entrada "estate_street"
        estateStreetInput.addEventListener("input", function() {
            // Obtener el valor actual del campo de entrada "estate_street"
            var address = estateStreetInput.value;

            // Realizar una solicitud de geocodificación para obtener las coordenadas de la dirección
            geocoder.geocode({
                address: address
            }, function(results, status) {
                if (status === "OK") {
                    // Obtener las coordenadas de la primera ubicación encontrada
                    var location = results[0].geometry.location;
                    var lat = location.lat();
                    var lng = location.lng();

                    // Actualizar los campos de entrada "coordinate_x" y "coordinate_y" con las coordenadas
                    var coordinateXInput = document.getElementById("coordinate_x");
                    var coordinateYInput = document.getElementById("coordinate_y");
                    coordinateXInput.value = lat.toFixed(14);
                    coordinateYInput.value = lng.toFixed(14);
                } else {
                    console.log("Geocode was not successful for the following reason: " + status);
                }
            });
        });
    }
</script>
@endsection
