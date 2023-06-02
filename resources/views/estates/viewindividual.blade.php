@php
setlocale(LC_TIME, "fr_BE");
$tabActive = isset($_COOKIE['tab-active']) ? $_COOKIE['tab-active'] : "estate-information";
@endphp
<header>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</header>
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    {{ session('success') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ session('error') }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif



<ul style="color:#ffffff !important;" class="nav nav-tabs wrapper__anchors view-individual" role="tablist" id="menu-scrolling">
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-information" || $tabActive==="" ) ? ' active' : '' !!}" href="#estate-information" id="estate-information-tab" data-toggle="tab" role="tab" aria-controls="estate-information" aria-selected="true">Infos</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-photos-docs") ? ' active' : '' !!}" href="#estate-photos-docs" id="estate-photos-docs-tab" data-toggle="tab" role="tab" aria-controls="estate-photos-docs" aria-selected="false">Pics & docs</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-ads") ? ' active' : '' !!}" href="#estate-ads" id="estate-ads-tab" data-toggle="tab" role="tab" aria-controls="estate-ads" aria-selected="false">Annonces</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-rdv") ? ' active' : '' !!}" href="#estate-rdv" id="estate-rdv-tab" data-toggle="tab" role="tab" aria-controls="estate-rdv" aria-selected="false">RDV</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-visit-remarks") ? ' active' : '' !!}" href="#estate-visit-remarks" id="estate-visit-remarks-tab" data-toggle="tab" role="tab" aria-controls="estate-visit-remarks" aria-selected="false">Remarques visite</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-reminders") ? ' active' : '' !!}" href="#estate-reminders" id="estate-reminders-tab" data-toggle="tab" role="tab" aria-controls="estate-reminders" aria-selected="false">Rappels</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-comments") ? ' active' : '' !!}" href="#estate-comments" id="estate-comments-tab" data-toggle="tab" role="tab" aria-controls="estate-comments" aria-selected="false">Commentaires internes</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-problems") ? ' active' : '' !!}" href="#estate-problems" id="estate-problems-tab" data-toggle="tab" role="tab" aria-controls="estate-problems" aria-selected="false">Problèmes</a>
    </li>
    @if(Auth::user()->type != 3)
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-offer") ? ' active' : '' !!}" href="#estate-offer" id="estate-offer-tab" data-toggle="tab" role="tab" aria-controls="estate-offer" aria-selected="false">Offre</a>
    </li>
    @endif
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-tickets") ? ' active' : '' !!}" href="#estate-tickets" id="estate-tickets-tab" data-toggle="tab" role="tab" aria-controls="estate-tickets" aria-selected="false">Tickets <sup class="text-danger">{!! ($countTicketsNoAnswer != 0) ? '('.$countTicketsNoAnswer.')' : '' !!}</sup></a>
    </li>
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-status") ? ' active' : '' !!}" href="#estate-status" id="estate-status-tab" data-toggle="tab" role="tab" aria-controls="estate-status" aria-selected="false">Status</a>
    </li>
    <li class="nav-item">
        <a class="nav-link{!! ($tabActive === " estate-log") ? ' active' : '' !!}" href="#estate-log" id="estate-log-tab" data-toggle="tab" role="tab" aria-controls="estate-log" aria-selected="false">Log</a>
    </li>
</ul>

@if (session('status'))
<div class="alert alert-success">
    {{ session('status') }}
    <button type="button" class="close" data-dismiss="alert">&times;</button>
</div>
<script>
    setTimeout(function() {
        $('.alert').slideUp();
    }, 3000);
</script>
@endif
<div class="tab-content" id="remindersTabContent">
    <div class="tab-pane fade mb-5{!! ($tabActive === 'estate-information' || $tabActive === "") ? ' active show' : '' !!}" role="tabpanel" id="estate-information" aria-labelledby="estate-information-tab">
        <form action="{!! route('editinformations') !!}" method="POST" data-form="form-estate-info" enctype="multipart/form-data">
            @csrf()
            <span id="token" style="display: none;">{!! csrf_token() !!}</span>
            <input type="hidden" name="estate_id" value="{!! $id !!}">
            <input type="hidden" name="seller_id" value="{!! $seller['id'] !!}">

            <input type="hidden" name="type_estate" @isset($estateDetails['type_estate']) value="{!! $estateDetails['type_estate'] !!}" @else value="{!! $estate['type_estate'] !!}" @endisset>
            <div class="card font-body-content">
                <div class="card-header">
                    <div class="text-left">
                        Informations & commentaires
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
                        <button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-form="form-estate-info">Sauvegarder</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mb-1">
                            <div class="wrapper__content">
                                <div class="block-16-9">
                                    <div>
                                        <img src="{!! ($estate['main_photo'] != '' ) ? asset('mainImages/'.$estate['main_photo']) : '' !!}" data-img-view="main">
                                    </div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                                        <div class="wrapper__content">
                                            <div class="wrapper__files text-center ffhnl align-middle">
                                                Changer l'image principale
                                                <input data-image="main" data-upload="{!! route('uploadphoto',['disk' => 'mainImages']) !!}" data-estate-id="{!! $id !!}" type="file" name="estate_photos" accept=".jpg, .jpeg, .png" onchange="previewFile(event)">
                                            </div>
                                        </div>
                                        <div class="mt-2" id="imagePreview"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5 mb-1">
                            <span class="ffhnm">STATUTS</span>
                            <div class="wrapper__content mb-5">
                                <!-- <div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_technic_layout" id="estate_include_status" disabled>
										</div>
									</div> -->
                                <div class="row mb-2 mt-2">
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
                                            <option value="{!! $agent['id'] !!}" <?php echo ($estate['agent'] == $agent['id']) ? 'selected' : ''; ?>>{!! $agent['name'] !!}</option>
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
                                <!-- <div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_localisation" id="estate_include_localisation">
										</div>
									</div> -->
                                <div class="row mb-2 mt-2">
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
                                <div class="row mb-2 ml-2 d-none">
                                    <div class="col-xs-12 col-md-3 col-lg-3 col-xl-3">Ville : </div>
                                    <div class="col-xs-12 col-md-9 col-lg-9 col-xl-9 mb-2">
                                        <input data-change-input type="text" name="estate__city" class="form-control" value="{!! $estate['city'] !!}" data-save>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-1">
                            <span class="ffhnm">CLIENT</span>
                            <div class="wrapper__content mb-5">
                                <!-- <div class="row mb-2">
										<div class="col-12 text-right">
											<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_seller" id="estate_include_seller" disabled>
										</div>
									</div> -->
                                <div class="row mb-2 mt-2">
                                    <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5"></div>
                                </div>
                                <div class="row mb-2 ml-2">

                                </div>
                                <div class="row mb-2 ml-2">
                                    <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Nom :</div>
                                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6 mb-2">
                                        <input data-change-input type="text" name="details__name" class="form-control" @isset($estateDetails['seller_name']) value="{!! $estateDetails['seller_name'] !!}" @else value="{!! $estate['name'] !!}" @endisset data-save>
                                    </div>
                                </div>
                                <div class="row mb-2 ml-2">
                                    <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Mail :</div>
                                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                        <input data-change-input type="email" name="details__email" class="form-control" @isset($estateDetails['seller_email']) value="{!! $estateDetails['seller_email'] !!}" @else value="{!! $estate['email'] !!}" @endisset data-save>
                                    </div>
                                </div>
                                <div class="row mb-2 ml-2">
                                    <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Téléphone :</div>
                                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                        <input data-change-input type="tel" name="details__tel" class="form-control" @isset($estateDetails['seller_phone']) value="{!! $estateDetails['seller_phone'] !!}" @else value="{!! $estate['phone'] !!}" @endisset data-save>
                                    </div>
                                </div>
                                <div class="row mb-2 ml-2">
                                    <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Êtes-vous le propriétaire du bien ? :</div>
                                    <div class="col-xs-12 col-md-6 col-lg-6 col-xl-6">
                                        @if (isset($estateDetails['owner']))
                                        <label class="mr-3"><input data-change-radio type="radio" class="radio-middle" name="details__owner" value="Oui" {!! ($estateDetails['owner']=='1' ) ? 'checked' : '' !!} data-save> Oui</label>
                                        <label class="mr-3"><input data-change-radio type="radio" class="radio-middle" name="details__owner" value="Non" {!! ($estateDetails['owner']=='0' ) ? 'checked' : '' !!} data-save> Non</label>
                                        @endif
                                    </div>
                                </div>
                                <div class="row mb-2 ml-2">
                                    <div class="col-xs-12 col-md-5 col-lg-5 col-xl-5">Type de vente :</div>
                                    <div class="col-xs-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                                        <select data-change-select class="form-control" name="sale__type_of_sale" data-sale>
                                            <option value="">Choisis une option</option>
                                            <option {!! ($estate['type_of_sale']=='Par agence' ) ? 'selected' : '' !!} value="Par agence">Par agence</option>
                                            <option {!! ($estate['type_of_sale']=='Par lui même' ) ? 'selected' : '' !!} value="Par lui même">Par lui même</option>
                                            <option {!! ($estate['type_of_sale']=='Le deux' ) ? 'selected' : '' !!} value="Le deux">Le deux</option>
                                            <option {!! ($estate['type_of_sale']=='Non' ) ? 'selected' : '' !!} value="Non">Non</option>
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
                    <div class="text-right">
                        <button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
                        <button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-form="form-estate-info">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <div class="tab-pane fade mb-5{!! ($tabActive === 'estate-photos-docs') ? ' active show' : '' !!}" role="tabpanel" id="estate-photos-docs" aria-labelledby="estate-photos-docs-tab">
        <form action="" method="POST" data-form="form-estate-documents-photos" enctype="multipart/form-data">
            <div class="card font-body-content">
                <div class="card-header">
                    Photographies du bien et documents
                    <!-- <div class="row">
							<div class="col-12 text-right">
								<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_documents_photos" id="estate_include_documents_photos">
							</div>
						</div> -->
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
                        $classHide = (count($photos) <= 1) ? ' d-none' : '' ; @endphp <ol class="carousel-indicators{!! $classHide !!}">
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
                            $classHide = (count($photos) <= 1) ? ' d-none' : '' ; @endphp <a class="carousel-control-prev{!! $classHide !!}" href="#carouselEstatePhotos" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                                </a>
                                <a class="carousel-control-next{!! $classHide !!}" href="#carouselEstatePhotos" role="button" data-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="sr-only">Next</span>
                                </a>
                    </div>
                    <div class="row justify-content-between">
                        <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-4 mb-4">
                            <span>Liste des documents</span>
                            <div class="wrapper__content" id="list-documents">
                                @foreach($medias as $media)
                                @if($media['type'] === 'photos')
                                <div class="wrapper__document">
                                    <a data-delete href="{!! route('deletemedia', $media['id']) !!}">
                                        <i class="bi bi-x"></i>
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
                                        <i class="bi bi-x"></i>
                                    </a>
                                    <a href="{!! asset('documents/'.$media['name']) !!}" target="_blank" download="{!! $media['name'] !!}">{!! $media['name'] !!}</a>
                                    <a href="{!! asset('documents/testing/DocumentTest.pdf') !!}" target="_blank" download="Document Test.pdf">
                                        <img src="{!! asset('img/icons/file.svg') !!}">
                                    </a>
                                </div>
                                @endif
                                @endforeach
                                <div class="wrapper__content" id="list-documents">
                                    <ul>
                                        @foreach($medias as $media)
                                        @if($media['type'] === 'documents')
                                        <li>{{ $media['name'] }}</li>
                                        @endif
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-8">
                            <span class="upload-title mb-2">Ajouter une photo ou un document</span>
                            <form action="{{ route('addfiles') }}" method="POST" enctype="multipart/form-data">
                                @csrf()
                                <div class="row">
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">

                                        <span>Glisser & Déposer ICI les photos à ajouter</span>
                                        <!-- <input data-files="photos" data-container-files="list-documents" type="file" data-upload="{!! route('uploadphoto', 'photos') !!}" data-estate-id="{!! $id !!}" name="estate_photos" accept=".jpg, .jpeg, .png" onchange="previewFiles()" multiple> -->
                                        <!-- Input para cargar fotos -->
                                        <div class="wrapper__content">
                                            <div class="wrapper__files text-center ffhnl align-middle">
                                                <input type="file" name="estate_photos" data-upload="{!! route('addfiles') !!}" accept=".jpg, .jpeg, .png" multiple onchange="previewFiles(event)">Ajouter des photos</input>
                                            </div>
                                        </div>
                                        <div class="mt-2" id="previewImages"></div>

                                    </div>
                                    <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">

                                        <span>Glisser & Déposer ICI les documents à ajouter</span>
                                        <!-- Input para cargar documentos -->
                                        <div class="wrapper__content">
                                            <div class="wrapper__files text-center ffhnl align-middle">
                                                <input type="file" name="estate_documents" data-upload="{!! route('addfiles') !!}" accept=".pdf, .json, .txt, .doc, .docx" multiple onchange="previewFiles(event)">Ajouter les documents</input>
                                                <!-- <input data-files="documents" data-container-files="list-documents" type="file" data-upload="{!! route('uploadphoto', 'documents') !!}" data-estate-id="{!! $id !!}" name="estate_documents" accept=".pdf, .json" onchange="showSelectedDocuments()" multiple> -->
                                            </div>
                                        </div>
                                        <div class="mt-2" id="previewDocuments"></div>
                                    </div>
                                </div>
                                <div class="text-right mt-2">
                                    <button type="submit" class="btn btn-lg btn-success">Sauvegarder</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="tab-pane fade mb-5{!! ($tabActive === 'estate-ads') ? ' active show' : '' !!}" role="tabpanel" id="estate-ads" aria-labelledby="estate-ads-tab">
        <form action="{!! route('newadvertisement') !!}" method="POST">
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
                        <button class="btn btn-lg btn-success" type="submit">Ajouter</button>
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


                        <input type="number" class="form-control" id="estate_ads_price" name="estate_ads_price" placeholder="€">
                    </div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2 d-md-none d-lg-none d-xl-block"></div>
                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-3 mb-2 d-md-none d-lg-none d-xl-block"></div>
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-3 d-xs-block d-sm-block d-md-block d-lg-block d-xl-none text-right mb-2">
                        <button class="btn btn-lg btn-success" type="submit">Ajouter</button>
                    </div>
                </div>
            </div>
    </div>
    </form>
</div>
<div class="tab-pane fade mb-5{!! ($tabActive === 'estate-rdv') ? ' active show' : '' !!}" role="tabpanel" id="estate-rdv" aria-labelledby="estate-rdv-tab">
    <div class="card font-body-content">
        <div class="card-header">Gestion des RDV</div>
        <div class="card-body">
            <div class="row">
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <div class="row mb-2">
                        @if(Auth::user()->google_token != NULL)
                        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4 mb-3">
                            <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#calendargoogle">Ajouter un rendez-vous</button>
                        </div>
                        @else
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-3">
                            <span class="alert alert-danger"><a href="{!! route('connect') !!}">Connectez-vous pour voir vos rendez-vous.</a></span>
                        </div>
                        @endif
                    </div>
                    <div class="row {!! (!isset($eve_['events'])) ? 'd-none' : '' !!}">
                        @if(isset($eve_['events']))
                        @foreach($eve_['events'] as $key => $event)
                        <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-2">
                            <ul id="list-events">
                                <li>
                                    <span class="ffhnm" id="{!! $key !!}"><span data-date>{!! strftime('%a', strtotime($event->start->dateTime)) !!} {!! date('d-m-Y', strtotime($event->start->dateTime)) !!} </span> : <span data-start> {!! date('H:i', strtotime($event->start->dateTime)) !!}</span> - <span data-end>{!! date('H:i', strtotime($event->end->dateTime)) !!}</span></span>
                                </li>
                            </ul>
                        </div>
                        @endforeach
                        @endif
                    </div>
                    <div class="row">
                        <div class="col-xs-7 col-sm-7 col-md-7 col-lg-7 col-xl-7 mb-4 mt-3">
                            @if($estate['rdv'] != 1)
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal" data-target="#sendInvitation" data-dates="">Envoyer</button>
                            @else
                            <span class="ffhnl" style="color:green">RDV envoyé</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
                    <form action="{!! route('changestatus') !!}" method="POST" data-form="change-status-estate">
                        @csrf()
                        <input type="hidden" name="estate_id" value="{!! $id !!}">
                        <div class="row mb-4">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 text-right">
                                <button type="submit" class="btn btn-outline-primary" data-submit-form="change-status-estate">Mettre le statut en RDV pris</button>
                            </div>
                        </div>
                    </form>
                    <div class="row">
                        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                            <form action="{!! route('newcommentrdv') !!}" method="POST" data-form="form-new-comment-rdv" data-reload="true">
                                @csrf()
                                <input type="hidden" name="estate_id" value="{!! $id !!}">
                                <span>Note interne</span>
                                <textarea rows="4" name="estate_comment_internal" class="form-control"></textarea>
                                <div class="text-left mt-2">
                                    <button type="submit" class="btn btn-success" data-submit-form="form-new-comment-rdv">Ajouter commentaire</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                    <h6>RDV confirmé le</h6>
                </div>
            </div>
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
                                <button type="submit" class="btn btn-success" data-submit-form="form-valider-confirmation" style="padding: 10px 30px;">Valider</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <label class="ffhnm"><input type="checkbox" name="send_reminder_half_past_eight" id="send_reminder_half_past_eight" data-url-eight="{!! route('savereminderhalfeight', [$id, 'response']) !!}" class="check-middle" {!! ($estate['send_reminder_half_past_eight']==1) ? 'checked' : '' !!}><span class="align-middle"> Envoyer un rappel le jour même à 8h30</span></label>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#sendconfirmationemail" data-confirm-email>Envoyer E-mail de confirmation</button>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#confirmationsms" data-confirm-sms>Envoyer SMS de confirmation</button>
                        <h6 class="ffhnm d-inline-block ml-2" style="color:green">{!! $estate['visit_date_at'] !!}</h6>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="tab-pane fade mb-5{!! ($tabActive === 'estate-visit-remarks') ? ' active show' : '' !!}" role="tabpanel" id="estate-visit-remarks" aria-labelledby="estate-visit-remarks-tab">
    <form action="{!! route('updateremark') !!}" method="POST" data-form="estate-form-visit-remarks">
        @csrf()
        <input type="hidden" name="estate_id" value="{!! $id !!}">
        <div class="card font-body-content">
            <div class="card-header">
                Remarques visite
                <div class="text-right">
                    <button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
                    <button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-form="estate-form-visit-remarks">Sauvegarder</button>
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
                                    <input data-change-radio type="radio" id="star5" name="interior_state" value="5" @isset($remarks['interior_state']) {!! ($remarks['interior_state']==5) ? 'checked' : '' !!} @endisset /><label class="full" for="star5"></label>
                                    <input data-change-radio type="radio" id="star4" name="interior_state" value="4" @isset($remarks['interior_state']) {!! ($remarks['interior_state']==4) ? 'checked' : '' !!} @endisset /><label class="full" for="star4"></label>
                                    <input data-change-radio type="radio" id="star3" name="interior_state" value="3" @isset($remarks['interior_state']) {!! ($remarks['interior_state']==3) ? 'checked' : '' !!} @endisset /><label class="full" for="star3"></label>
                                    <input data-change-radio type="radio" id="star2" name="interior_state" value="2" @isset($remarks['interior_state']) {!! ($remarks['interior_state']==2) ? 'checked' : '' !!} @endisset /><label class="full" for="star2"></label>
                                    <input data-change-radio type="radio" id="star1" name="interior_state" value="1" @isset($remarks['interior_state']) {!! ($remarks['interior_state']==1) ? 'checked' : '' !!} @endisset /><label class="full" for="star1"></label>
                                </fieldset>
                            </div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-4 col-xl-4 mb-2">&nbsp;- Extérieur:</div>
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-6 col-xl-6 mb-2">
                                <fieldset class="rating">
                                    <input data-change-radio type="radio" id="star5ex" name="exterior_state" value="5" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==5) ? 'checked' : '' !!} @endisset /><label class="full" for="star5ex"></label>
                                    <input data-change-radio type="radio" id="star4ex" name="exterior_state" value="4" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==4) ? 'checked' : '' !!} @endisset /><label class="full" for="star4ex"></label>
                                    <input data-change-radio type="radio" id="star3ex" name="exterior_state" value="3" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==3) ? 'checked' : '' !!} @endisset /><label class="full" for="star3ex"></label>
                                    <input data-change-radio type="radio" id="star2ex" name="exterior_state" value="2" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==2) ? 'checked' : '' !!} @endisset /><label class="full" for="star2ex"></label>
                                    <input data-change-radio type="radio" id="star1ex" name="exterior_state" value="1" @isset($remarks['exterior_state']) {!! ($remarks['exterior_state']==1) ? 'checked' : '' !!} @endisset /><label class="full" for="star1ex"></label>
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
                                <input data-change-input type="number" name="his_estimate" class="form-control" value="@isset($remarks['his_estimate']){!! $remarks['his_estimate'] !!}@endisset">
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">&nbsp;- Accepté pour le client:</div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6 mb-2">
                                <input data-change-input type="text" name="accept_price" class="form-control" value="@isset($remarks['accept_price']){!! $remarks['accept_price'] !!}@endisset">
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
                    <button type="submit" class="btn btn-lg btn-success" data-modified="false" data-submit-form="estate-form-visit-remarks">Sauvegarder</button>
                </div>
            </div>
        </div>
    </form>
</div>
<div class="tab-pane fade mb-5{!! ($tabActive === 'estate-reminders') ? ' active show' : '' !!}" role="tabpanel" id="estate-reminders" aria-labelledby="estate-reminders-tab">
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
                    <button type="reset" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
                    <button type="submit" class="btn btn-lg btn-success" data-submit-form="form-change-time" data-modified="false">Changer</button>
                    <!-- <a type="button" data-modified="false" data-submit-form="form-change-time" class="btn btn-success">Changer</a> -->
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
<div class="tab-pane fade mb-5{!! ($tabActive === 'estate-comments') ? ' active show' : '' !!}" role="tabpanel" id="estate-comments" aria-labelledby="estate-comments-tab">
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
<div class="tab-pane fade mb-5{!! ($tabActive === 'estate-problems') ? ' active show' : '' !!}" role="tabpanel" id="estate-problems" aria-labelledby="estate-problems-tab">
    <form action="{!! route('newresolution') !!}" method="POST" data-form="estate-form-problems" data-reload="true">
        @csrf()
        <div class="card font-body-content">
            <div class="card-header">
                Problèmes signalés par le requéreur
                <!-- <div class="row">
							<div class="col-12 text-right">
								<label class="checktext">Ajouter au pdf</label><input type="checkbox" class="check-pdf" name="estate_include_problems" id="estate_include_problems">
							</div>
						</div> -->
            </div>
            <div class="card-body">
                <div class="mb-4">
                    <span>Problème signalé dans le formulaire :</span>
                    @foreach($estates as $est)
                    @if($est['id'] == $estate['id'])
                    <textarea name="estate_problem_signal" rows="5" class="form-control" disabled>@isset($est['problems']){!! $est['problems'] !!}@endisset</textarea>
                    @endif
                    @endforeach
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
<div class="tab-pane fade mb-5{!! ($tabActive === 'estate-offer') ? ' active show' : '' !!}" role="tabpanel" id="estate-offer" aria-labelledby="estate-offer-tab">
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
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2"><strong>Prix estimé par le requéreur :</strong></div>
                                    <?php

                                    if (isset($offer['price_seller'])) {
                                        $priceSeller = $offer['price_seller'];
                                    } else if (isset($estate['estimate'])) {
                                        $priceSeller = $estate['estimate'];
                                    }
                                    ?>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                        <input type="number" data-input-price="{!! $priceSeller !!}" class="form-control" placeholder="€" value="{!!$priceSeller!!}" disabled>
                                        <label class="price">€</label>
                                        <input type="hidden" name="price_seller" id="price_sellerr" class="form-control" placeholder="€" value="{!! $priceSeller !!}">
                                    </div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2"><strong>Notre estimation :</strong></div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                        <?php

                                        if (isset($offer['price_market'])) {
                                            $priceMarket = $offer['price_market'];
                                        } else if (isset($estate['market'])) {
                                            $priceMarket = $estate['market'];
                                        }
                                        ?>
                                        <input type="number" data-input-price-market="{!! $priceMarket !!}" class="form-control" placeholder="€" value="{!! $priceMarket !!}" disabled>
                                        <label class="price">€</label>
                                        <input type="hidden" name="price_market" id="price_market" class="form-control" placeholder="€" value="{!! $priceMarket !!}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-12 col-xl-6">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2"><strong>Prix offert par We Sold :</strong></div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">

                                        <input type="number" data-input-price-we-sold="@isset($offer['price_wesold']){{ $offer['price_wesold'] }}@endisset" @isset($offer['price_wesold']) value="{{ $offer['price_wesold'] }}" @endisset class="form-control" placeholder="" oninput="setPriceWesold(event)">
                                        <label class="price">€</label>
                                        <input type="hidden" name="price_wesold" id="price_wesold" value="0">
                                        <script>
                                            function setPriceWesold(event) {
                                                var priceWesoldInput = document.getElementById("price_wesold");
                                                priceWesoldInput.value = event.target.value;
                                            }
                                        </script>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2"><strong>Notaire :</strong></div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                        <select class="form-control" name="notary" id="data_notary">
                                            <option value=""></option>
                                            @foreach($notaries as $notary)
                                            <option @isset($offer['notaire']) {!! ($offer['notaire']==$notary['name'].' '.$notary['lastname'].' ('.$notary['key'].')') ? 'selected' : '' !!} @endisset value="{!! $notary['name'] !!} {!! $notary['lastname'] !!} ({!! $notary['key'] !!})">{!! $notary['name'] !!} {!! $notary['lastname'] !!}</option>
                                            }
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2"><strong>Validité de l offre :</strong></div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                        <input type="date" name="validity" class="form-control" value="@isset($offer['validity'] ){!! $offer['validity'] !!}@endisset">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-2"><strong>Condition offre :</strong></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-2">
                                <select class="form-control" name="condition" id="condition">
                                    <option value="">Choisissez un modèle</option>
                                    @foreach($templates as $template)
                                    @if($template['type'] == 'condition')
                                    <option @isset($offer['condition']) {!! ($offer['condition']==file_get_contents(asset('templates/'.$template['file']))) ? 'selected' : '' !!} @endisset value="{!! file_get_contents(asset('templates/'.$template['file'])) !!}">{!! file_get_contents(asset('templates/'.$template['file'])) !!}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
                                <textarea id="tinyconditionOffer" name="condition" data-height="200" data-tiny="tinyconditionOffer" rows="5" class="form-control">@isset($offer['condition']){!! $offer['condition'] !!}@endisset</textarea>
                            </div>
                        </div>
                        <div class="row ">
                            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2"><strong>Texte à ajouter à l’offre :</strong></div>
                                    <div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6 mb-2">
                                        <select class="form-control" name="textadded" id="text_add_offer">
                                            <option>Choisissez un modèle</option>
                                            @foreach($templates as $template)
                                            @if($template['type'] == 'text-offer')
                                            <option value="{!! $template['id'] !!}"> {!! $template['name'] !!} </option>
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
                                        <textarea name="other_offer" id="other_offer" data-height="200" data-tiny="tinyconditionOffer" rows="5" class="form-control">@isset($offer['other_offer']) {!! $offer['other_offer'] !!} @endisset</textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @if(Auth::user()->type != 3)
                        <div class="text-right mt-4">
                            <button type="button" class="btn btn-lg btn-danger" data-toggle="modal" data-target="#editOfferPDF" data-save-data>Générer l'offre</button>
                            <button type="submit" class="btn btn-lg btn-success">Sauvegarder</button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </form>
    @isset($offer['pdf'])
    <form action="{!! route('sendemailoffer') !!}" method="POST" data-form="estate-send-offer">
        @csrf()
        <input type="hidden" name="estate_id" value="{!! $id !!}">
        <div class="card font-body-content">
            <div class="card-header">Envoi de l'offre</div>
            <div class="card-body">
                <div class="autosize-m">
                    <div class="row mb-4">
                        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">
                            @isset($offer['pdf'])
                            <a href="{!! asset('pdfs/'.$offer['pdf']) !!}" data-pdf-offer class="btn btn-outline-primary" target="_blank">Document PDF généré de l'offre </a>
                            @endisset
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
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
                                @foreach($templates as $template)
                                @if($template['type'] == 'email')
                                <textarea class="d-none" data-email-template-{!! $template['id'] !!}>{!! file_get_contents(asset('templates/'.$template['file'])) !!}</textarea>
                                @endif
                                @endforeach
                                <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                                    <textarea data-change-select name="body" id="tinyCorps" data-height="300" data-tiny="tinyCorps" rows="5" required rows="5" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if(Auth::user()->type != 3)
                    <div class="text-right mt-4">
                        <button type="button" class="btn btn-lg btn-dark" data-cancel>Annuler</button>
                        <button type="submit" data-modified="false" class="btn btn-lg btn-success" data-submit-form="estate-send-offer" data-send-offer>Envoyé</button>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </form>
    @endisset
</div>
<div class="tab-pane fade mb-5{!! ($tabActive === 'estate-tickets') ? ' active show' : '' !!}" role="tabpanel" id="estate-tickets" aria-labelledby="estate-tickets-tab">
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
                                <?php if (Auth::user()->type == 2) : ?>
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
                                <?php if (Auth::user()->type == 2) : ?>
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
                            <?php if (Auth::user()->type == 2) : ?>
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
<div class="tab-pane fade mb-5{!! ($tabActive === 'estate-status') ? ' active show' : '' !!}" role="tabpanel" id="estate-status" aria-labelledby="estate-status-tab">
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
<div class="tab-pane fade mb-5{!! ($tabActive === 'estate-log') ? ' active show' : '' !!}" role="tabpanel" id="estate-log" aria-labelledby="estate-log-tab">
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
@section('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="{{ asset('js/preview.js') }}"></script>

<script>
    function previewFile() {
        var preview = document.querySelector('#imagePreview');
        var file = document.querySelector('input[type=file]').files[0];
        var reader = new FileReader();

        reader.addEventListener("load", function() {
            var image = new Image();
            image.src = reader.result;
            preview.innerHTML = '';
            preview.appendChild(image);
        }, false);

        if (file) {
            reader.readAsDataURL(file);
        }
    }

    function previewFiles(event) {
        var previewImages = document.getElementById("previewImages");
        var previewDocuments = document.getElementById("previewDocuments");

        var imageFiles = [];
        var docFiles = [];

        var files = event.target.files;
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            var fileType = file.type.split("/")[0];

            if (fileType === "image") {
                imageFiles.push(file);
            } else {
                docFiles.push(file);
            }
        }

        for (var i = 0; i < imageFiles.length; i++) {
            var img = document.createElement("img");
            img.src = URL.createObjectURL(imageFiles[i]);
            img.classList.add("preview-image");
            previewImages.appendChild(img);
        }

        for (var i = 0; i < docFiles.length; i++) {
            var p = document.createElement("p");
            p.innerText = docFiles[i].name;
            previewDocuments.appendChild(p);
        }
    }
</script>
@endsection
