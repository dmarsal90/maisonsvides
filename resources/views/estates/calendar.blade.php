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
                            <iframe data-coordinates frameborder="0" style="border:0;" allowfullscreen=""
                                aria-hidden="false" tabindex="0"></iframe>
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
                    <form method="POST" action="{{ route('setrdv') }}">
                        @csrf

                        <div class="estateinfo">
                            <div class="estateinfo__card">
                                <label for="contact" class="ffhnm">Contact</label>
                                <input type="text" id="contact" name="contact" required>
                            </div>
                            <div class="estateinfo__card">
                                <label for="tel" class="ffhnm">Tél</label>
                                <input type="phone" id="tel" name="tel" required>
                            </div>
                            <div class="estateinfo__card">
                                <label for="mail" class="ffhnm">Mail</label>
                                <input type="email" id="mail" name="mail" required>

                            </div>
                            <div class="estateinfo__card">
                                <label for="type" class="ffhnm">Type</label>
                                <input type="text" id="type" name="type" required>
                            </div>
                            <div class="estateinfo__card">
                                <div class="row">
                                    <div class="col-6">
                                        <label for="inicio" class="ffhnm">Heure de début</label>
                                <input type="time" id="inicio" name="inicio">
                                    </div>
                                    <div class="col-6">
                                        <label for="fin" class="ffhnm">Heure de fin</label>
                                <input type="time" id="fin" name="fin">
                                    </div>
                                </div>



                            </div>
                            <div class="estateinfo__card">
                                <label for="localisation" class="ffhnm">Localisation</label>
                                <input type="text" id="localisation" name="localisation"
                                    placeholder="Saisissez votre localisation" required>
                            </div>
                            {{-- <div class="estateinfo__location">
                                <!-- Campos ocultos para almacenar la latitud y longitud -->

                                @if (!empty(old('localisation')))
                                    <input type="hidden" name="latitud" id="latitud">
                                    <input type="hidden" name="longitud" id="longitud">
                                    <div id="map"></div>
                                @endif
                            </div> --}}
                            <div class="estateinfo__card">
                                <label for="descriptif" class="ffhnm">Descriptif du bien</label>
                                <textarea id="descriptif" name="descriptif" rows="5" required></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Créer</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
{{-- @section('scripts')
    <script>
        function validarFormulario() {
            // Obtener los campos del formulario
            var contacto = document.getElementById("contact");
            var telefono = document.getElementById("tel");
            var correo = document.getElementById("mail");
            var tipo = document.getElementById("type");
            var descriptif = document.getElementById("descriptif");
            var localizacion = document.getElementById("localisation");

            // Verificar que los campos obligatorios estén completos
            if (contacto.value == "" || telefono.value == "" || correo.value == "" || tipo.value == "" || descriptif
                .value == "" || localizacion.value == "") {
                alert("Veuillez remplir tous les champs obligatoires");
                return false;
            }

            // Verificar que el correo tenga un formato válido
            var correoRegex = /^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/;
            if (!correoRegex.test(correo.value)) {
                alert("Veuillez saisir une adresse e-mail valide");
                return false;
            }

            // Verificar que el número de teléfono tenga un formato válido
            var telefonoRegex = /^\+?\d{0,15}$/;
            if (!telefonoRegex.test(telefono.value)) {
                alert("Veuillez saisir un numéro de téléphone valide (y compris le préfixe international + si nécessaire)");
                return false;
            }

            // Si todas las validaciones pasan, enviar el formulario
            return true;
        }
    </script>
@endsection --}}
