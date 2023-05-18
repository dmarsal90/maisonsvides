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
                                    <input data-change-input type="text" name="seller_name" class="form-control" value="{!! $seller['name'] !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Tel:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="seller_phone" class="form-control" value="{!! $seller['phone'] !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Mail:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="seller_email" class="form-control" value="{!! $seller['email'] !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Année de construction:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="year_construction" class="form-control" value="{!! $estate['construction'] !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Année de rénovation:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="year_renovation" value="{!! $estate['renovation'] !!}" class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">PEB:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="peb" value="{!! $estate['peb'] !!}" class="form-control peb-{{ strtolower($estate['peb']) }}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Surface approximative en mètres carrés:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="surface" value="{!! $estate['surface'] !!}" class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Town planning:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="town_planning" value="{!! $estate['town_planning'] !!}" class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">More habitations:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="more_habitations" value="{!! $estate['more_habitations'] == 1 ? 'oui' : 'non' !!}" class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Nbre de chambres:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="rooms" value="{!! $estate['number_rooms'] !!}" class="form-control">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <span class="ffhnm">Nbre de sdb:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="number" name="bathrooms" value="{!! $estate['number_bathroom'] !!}" class="form-control">
                                </div>
                            </div>

                            <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Description générale :</div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                <textarea data-change-input name="estate_description" class="form-control" rows="5"></textarea>

                            </div>
                        </div>

                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6">
                            <div class="row">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Adresse du bien:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="estate_street" class="form-control" id="estate_street" value="{!! $estate['street'] !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <img data-toggle="modal" data-target="#showCoordinates" src="{!! asset('img/icons/coordinates.svg') !!}" width="22">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                    <input data-change-input type="text" name="coordinate_x" id="coordinate_x" class="form-control mb-2" placeholder="50.87040108685863" value="{!! $estate['coordinate_x'] !!}">
                                    <input data-change-input type="text" name="coordinate_y" id="coordinate_y" class="form-control" placeholder="4.39901196259919" value="{!! $estate['coordinate_y'] !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Prix fixé par le client:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="text" name="price_evaluated" class="form-control" placeholder="€" value="{!! $estate['price_published_himself'] !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Jardin:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="text" name="jardin" class="form-control" placeholder="" value="{!! $estate['jardin'] == 1 ? 'oui' : 'non' !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Terrasse:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="text" name="price_evaluated" class="form-control" placeholder="€" value="{!! $estate['terrase'] == 1 ? 'oui' : 'non' !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Garage:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="text" name="price_evaluated" class="form-control" placeholder="€" value="{!! $estate['garage'] == 1 ? 'oui' : 'non' !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Nbre de compteurs de gaz:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="number" name="gaz" class="form-control" placeholder="" value="{!! $estate['number_gas'] !!}">
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <span class="ffhnm">Nbre de compteurs électriques:</span>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                    <input data-change-input type="number" name="electrique" class="form-control" placeholder="" value="{!! $estate['number_electric'] !!}">
                                </div>

                            </div>
                        </div>

                        <div class="col-md-12 mb-2 ml-2">
                            <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Un commentaire ? :</div>
                            <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                <textarea data-change-input name="details_commentaire" class="form-control" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row justify-content-between">
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <span class="ffhnm">État de la propriété:</span>
                                    <div class="row mt-2">
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">&nbsp;- Intérieur:</div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-2">
                                            <fieldset class="rating">
                                                <input data-change-radio type="radio" id="star5" name="interior_state" value="5" /><label class="full" for="star5"></label>
                                                <input data-change-radio type="radio" id="star4" name="interior_state" value="4" /><label class="full" for="star4"></label>
                                                <input data-change-radio type="radio" id="star3" name="interior_state" value="3" /><label class="full" for="star3"></label>
                                                <input data-change-radio type="radio" id="star2" name="interior_state" value="2" /><label class="full" for="star2"></label>
                                                <input data-change-radio type="radio" id="star1" name="interior_state" value="1" /><label class="full" for="star1"></label>
                                                <!-- <input data-change-radio type="radio" id="star1" name="interior_state" value="1" @isset($remarks['interior_state']) {!! ($remarks['interior_state']==1) ? 'checked' : '' !!} @endisset /><label class="full" for="star1"></label> -->
                                            </fieldset>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur:</div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-2">
                                            <fieldset class="rating">
                                                <input data-change-input type="radio" id="star5ex" name="exterior_state" value="5" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==5) ? 'checked' : '' !!} @endisset /><label class="full" for="star5ex"></label>
                                                <input data-change-input type="radio" id="star4ex" name="exterior_state" value="4" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==4) ? 'checked' : '' !!} @endisset /><label class="full" for="star4ex"></label>
                                                <input data-change-input type="radio" id="star3ex" name="exterior_state" value="3" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==3) ? 'checked' : '' !!} @endisset /><label class="full" for="star3ex"></label>
                                                <input data-change-input type="radio" id="star2ex" name="exterior_state" value="2" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==2) ? 'checked' : '' !!} @endisset /><label class="full" for="star2ex"></label>
                                                <input data-change-input type="radio" id="star1ex" name="exterior_state" value="1" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==1) ? 'checked' : '' !!} @endisset /><label class="full" for="star1ex"></label>
                                            </fieldset>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">Le quartier:</div>
                                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-3">
                                            <fieldset class="rating">
                                                <input data-change-radio type="radio" id="star5dis" name="district_state" value="5" @isset($remarks['district_state']) {!! ($remarks['district_state']==5) ? 'checked' : '' !!} @endisset /><label class="full" for="star5dis"></label>
                                                <input data-change-radio type="radio" id="star4dis" name="district_state" value="4" @isset($remarks['district_state']) {!! ($remarks['district_state']==4) ? 'checked' : '' !!} @endisset /><label class="full" for="star4dis"></label>
                                                <input data-change-radio type="radio" id="star3dis" name="district_state" value="3" @isset($remarks['district_state']) {!! ($remarks['district_state']==3) ? 'checked' : '' !!} @endisset /><label class="full" for="star3dis"></label>
                                                <input data-change-radio type="radio" id="star2dis" name="district_state" value="2" @isset($remarks['district_state']) {!! ($remarks['district_state']==2) ? 'checked' : '' !!} @endisset /><label class="full" for="star2dis"></label>
                                                <input data-change-radio type="radio" id="star1dis" name="district_state" value="1" @isset($remarks['district_state']) {!! ($remarks['district_state']==1) ? 'checked' : '' !!} @endisset /><label class="full" for="star1dis"></label>
                                            </fieldset>
                                        </div>
                                    </div>
                                    <span class="ffhnm">Points forts:</span>
                                    <div class="row mt-2">
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Intérieur:</div>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="strong-points-i">
                                            <textarea data-change-input name="interior_highlights" class="form-control mb-2" rows="4"></textarea>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur:</div>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="strong-points-e">
                                            <textarea data-change-input name="exterior_highlights" class="form-control mb-2" rows="4"></textarea>
                                        </div>
                                    </div>
                                    <span class="ffhnm">Points faibles:</span>
                                    <div class="row mt-2">
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Intérieur:</div>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="weak-points-i">
                                            <textarea data-change-input name="interior_weak_point" class="form-control mb-2" rows="4"></textarea>
                                        </div>
                                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur:</div>
                                        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8 mb-2" id="weak-points-e">
                                            <textarea data-change-input name="exterior_weak_point" class="form-control mb-2" rows="4"></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
                                    <span class="ffhnm">Sur le propriétaire</span>
                                    <div class="row mt-2">
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">&nbsp;- Désirs de vendre (%)</div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">
                                            <label id="percentage" class="center-percentage"></label>
                                            <input data-change-radio type="range" name="desires_to_sell" id="desires_to_sell" class="form-control" min="0" max="100">
                                        </div>
                                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2t">
                                            <span class="ffhnm">Prix évalué par le client:</span>
                                        </div>
                                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2 text-xs-left text-sm-left text-md-right text-lg-left text-xl-right">
                                            <input data-change-input type="text" name="price_evaluated" class="form-control" placeholder="Entrez le prix évalué">
                                        </div>

                                        <div class="mb-2 ml-2">
                                            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12" align="center">
                                                <span class="ffhnm mr-4">Entièrement à rénover à l'intérieur</span>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='1' ) ? 'checked' : '' !!}@endisset value="1" data-save>1</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='2' ) ? 'checked' : '' !!} @endisset value="2" data-save>2</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='3' ) ? 'checked' : '' !!} @endisset value="3" data-save>3</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='4' ) ? 'checked' : '' !!} @endisset value="4" data-save>4</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='5' ) ? 'checked' : '' !!} @endisset value="5" data-save>5</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='6' ) ? 'checked' : '' !!} @endisset value="6" data-save>6</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='7' ) ? 'checked' : '' !!} @endisset value="7" data-save>7</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='8' ) ? 'checked' : '' !!} @endisset value="8" data-save>8</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='9' ) ? 'checked' : '' !!} @endisset value="9" data-save>9</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='10' ) ? 'checked' : '' !!}@endisset value="10" data-save>10</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_interior" @isset($details['state_interior']){!! ($details['state_interior']=='0' ) ? 'checked' : '' !!}@endisset value="0" data-save checked>Neuf</label>

                                            </div>
                                        </div>
                                        <div class="mb-2 ml-2">
                                            <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12" align="center">
                                                <span class="ffhnm mr-4">Entièrement à rénover à l'extérieur</span>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='1' ) ? 'checked' : '' !!}@endisset value="1" data-save>1</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='2' ) ? 'checked' : '' !!}@endisset value="2" data-save>2</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='3' ) ? 'checked' : '' !!}@endisset value="3" data-save>3</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='4' ) ? 'checked' : '' !!}@endisset value="4" data-save>4</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='5' ) ? 'checked' : '' !!}@endisset value="5" data-save>5</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='6' ) ? 'checked' : '' !!}@endisset value="6" data-save>6</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='7' ) ? 'checked' : '' !!}@endisset value="7" data-save>7</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='8' ) ? 'checked' : '' !!}@endisset value="8" data-save>8</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='9' ) ? 'checked' : '' !!}@endisset value="9" data-save>9</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_exterior']){!! ($details['state_exterior']=='10' ) ? 'checked' : '' !!}@endisset value="10" data-save>10</label>
                                                <label><input data-change-radio type="radio" class="radio-middle mr-1" name="details_state_exterior" @isset($details['state_interior']){!! ($details['state_interior']=='0' ) ? 'checked' : '' !!}@endisset value="0" data-save checked>Neuf</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">&nbsp;- Avis de l’agent:</div>
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                                        <textarea data-change-input name="agent_notice" class="form-control" rows="5"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

    <div class="text-right mt-4 mb-4">
        <a href="/visits" class="btn btn-lg btn-danger">Annuler</a>
        <button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-hide="true" data-submit-form="form-visit-information">Sauvegarder</button>
    </div>

    <div class="card bg-light col-lg-12 my-4">
        <div class="card-body">
            <div class="text-right autosize-s">
                <div class="mt-4">
                    <label class="mr-3"><input type="checkbox" id="status-visite" checked="true"> Passer le status du bien en visite fini</label>
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
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDUt6_wutCJwLVFx1TbZD_ai4l9sK30jCs" async></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
@endsection
