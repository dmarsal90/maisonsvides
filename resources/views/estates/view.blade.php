@extends('layouts.estate')

@section('content')
	@if($typemenu == 'Menu déroulant')
		@include('estates.viewscroll')
	@endif
	@if($typemenu == 'Menu individuel')
		@include('estates.viewindividual')
	@endif
@endsection

@section('modals')
<div class="modal fade font-body-content" id="createReminder" tabindex="-1" aria-labelledby="createReminderLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('savereminder') !!}" method="POST" data-form="form-create-reminder" data-reload=true>
					@csrf()
					<input type="hidden" name="seller_email" value="{!! $seller['email'] !!}">
					<input type="hidden" name="seller_name" value="{!! $seller['name'] !!}">
					<input type="hidden" name="seller_id" value="{!! $seller['id'] !!}">
					<input type="hidden" name="seller_phone" value="{!! $seller['phone'] !!}">
					<input type="hidden" name="estate_id" value="{!! $id !!}">
					<div class="card">
						<div class="card-header">Créer un rappel</div>
						<div class="card-body">
							<div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<span class="ffhnm">
											Date d’envoie (instantané ou différé)
										</span><hr>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<label class="ffhnl">
											<input type="radio" class="mr-3" style="vertical-align: sub" name="change_date" id="change_date_two" checked>
											<span class="align-middle">Envoyer le message de base instantanément</span>
										</label>
									</div>
									<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
										<label class="ffhnl">
											<input type="radio" class="mr-3" style="vertical-align: sub" name="change_date" id="change_date">
											<span class="align-middle">Différer le message à une date précise</span>
										</label>
									</div>
								</div>
								<div class="row mb-2" id="date_manual">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Date</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<input type="date" name="date_changed" class="form-control">
									</div>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<span class="ffhnm">
										Nom du rappel
									</span><hr>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
									<span class="ffhnl">Nom du rappel</span>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
									<input data-change-input type="text" class="form-control" name="name_reminder" required placeholder="Nom du rappel" value="">
								</div>
							</div>
							<div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<span class="ffhnm">
											Process
										</span><hr>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<label class="ffhnl">
											<input type="checkbox" class="mr-3" style="vertical-align: sub" name="charge_process" id="charge_process">
											<span class="align-middle">Charger un process de rappels</span>
										</label>
									</div>
								</div>
								<div class="row mb-2 charge_template">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Template du process</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<select data-change-select class="form-control" name="template_process" id="template_process">
											<option value="">Choisissez un modèle</option>
											@foreach($templatesReminders as $template)
												<option value="{!! $template['id'] !!}">{!! $template['name'] !!}</option>
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="withoutProcessCharged">
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<span class="ffhnm">
											Envoie (en fonction de la section date)
										</span><hr>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Type</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<select data-change-select class="form-control" name="type_template_r" data-disabled-add-reminder="withoutProcessCharged" id="type_template_r">
											<option value="email">Mail</option>
											<option value="sms">SMS</option>
											<option value="task">Tâches</option>
										</select>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Template</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<select data-change-select class="form-control" name="file_template_name" id="file_template_name">
											<option></option>
										</select>
									</div>
								</div>
								<div class="row mb-2" id="content-subject">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Suject</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<input data-change-input type="text" class="form-control" name="subject_template" id="subject_template" placeholder="Objet du mail">
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Corps</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										@foreach($templates as $template)
											@if($template['type'] == 'email' || $template['type'] == 'sms' || $template['type'] == 'task')
												<textarea data-change-input class="form-control content-templates" name="body_mail_reminder" data-id-template="{!! $template['file'] !!}" rows="4" placeholder="Texte du mail bla bla">{!! file_get_contents(asset('templates/'.$template['file'])) !!}</textarea>
											@endif
										@endforeach
									</div>
								</div>
							</div>
							<div class="withoutProcessCharged" id="firsth">
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<span class="ffhnm">
											Rappel
										</span><hr>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Type</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<select data-change-select class="form-control" name="type_tem_create" data-disabled-add-reminder="withoutProcessCharged" id="type_tem_create">
											<option value="email">Mail</option>
											<option value="sms">SMS</option>
											<option value="task">Tâches</option>
										</select>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Template</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<select data-change-select class="form-control" name="file_template_name_create" id="file_template_name_create">
											<option></option>
										</select>
									</div>
								</div>
								<div class="row mb-2 content-s-subject">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Sujet</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<input data-change-input type="text" name="subject_template_s" id="subject_template_s" class="form-control">
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Contenu</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										@foreach($templates as $template)
											@if($template['type'] == 'email' || $template['type'] == 'sms' || $template['type'] == 'task')
												<textarea data-change-input class="form-control content-templates_rap" name="body_mail_reminder_r" data-id-template-reminder="{!! $template['file'] !!}" rows="4" placeholder="Texte du mail bla bla">{!! file_get_contents(asset('templates/'.$template['file'])) !!}</textarea>
											@endif
										@endforeach
										<div id="auxSubject"></div>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
										<span class="ffhnl">Nombre de jours ouvrables après l’envoie de base</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6 mb-2">
										<input data-change-input type="number" name="days_reminder_r" class="form-control">
									</div>
									<div class="text-left col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-7">
										<button type="button" class="btn btn-danger" data-id-delete="firsth">Supprimer ce rappel</button>
									</div>
								</div>
							</div>
							<div class="withProcessCharged">
							</div>
							<div id="remindersadded"></div>
							<div class="text-left">
								<button type="button" data-change-radio id="addReminderWPC" class="btn btn-success">Ajouter une nouvelle étape</button>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-dark" data-cancel data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-modified-modal="false" data-submit-hide="true" data-submit-form="form-create-reminder">Envoyer et programmer</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade font-body-content" id="createReminderTask" tabindex="-1" aria-labelledby="createReminderTaskLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('savereminder') !!}" method="POST" data-form="form-create-reminder-task" data-reload=true>
					@csrf()
					<input type="hidden" name="only_task" value="only_task">
					<input type="hidden" name="seller_email" value="{!! $seller['email'] !!}">
					<input type="hidden" name="seller_name" value="{!! $seller['name'] !!}">
					<input type="hidden" name="seller_id" value="{!! $seller['id'] !!}">
					<input type="hidden" name="seller_phone" value="{!! $seller['phone'] !!}">
					<input type="hidden" name="estate_id" value="{!! $id !!}">
					<div class="card">
						<div class="card-header">Créer un rappel - Tâche</div>
						<div class="card-body">
							<div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<span class="ffhnm">
											Date d’envoie
										</span>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Date</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<input data-change-input data-change-select type="date" required name="date_changed" class="form-control">
									</div>
								</div>
							</div>
							<hr>
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<span class="ffhnm">
										Nom du rappel
									</span>
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
									<span class="ffhnl">Nom du rappel</span>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
									<input data-change-input type="text" class="form-control" name="name_reminder" required placeholder="Nom du rappel" value="">
								</div>
							</div>
							<div>
								<hr><div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<span class="ffhnm">
											Envoie en fonction de la section date
										</span>
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Template</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<select data-change-select class="form-control" name="file_template_name" id="template_process_task">
											<option value="">Choisissez un modèle</option>
											@foreach($templatesTask as $template)
												<option value="{!! $template['id'] !!}">{!! $template['name'] !!}</option>
											@endforeach
										</select>
									</div>
									@foreach($templatesTask as $template)
										<textarea class="d-none" data-file-template-{!! $template['id'] !!}>{!! file_get_contents(asset('templates/'.$template['file'])) !!}</textarea>
										<input type="hidden" data-subject-task-{!! $template['id'] !!} value="{!! $template['subject'] !!}">
									@endforeach
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
										<span class="ffhnl">Rangé dans</span>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
										<input data-change-input type="text" class="form-control" data-put-subject name="subject_template" placeholder="Objet du mail">
									</div>
								</div>
								<div class="row mb-2">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<textarea data-change-input class="form-control" name="text-task-rappel" data-put-text rows="4"></textarea>
									</div>
								</div>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-dark" data-cancel data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-modified-modal="false" data-submit-hide="true" data-submit-form="form-create-reminder-task">Envoyer et programmer</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@foreach($reminders as $reminder)
	@if($reminder['sent'] == 0)
		<div class="modal fade font-body-content" id="editReminder-{!! $reminder['id'] !!}" tabindex="-1" aria-labelledby="editReminder-{!! $reminder['id'] !!}Label" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('editreminder') !!}" method="POST" data-form="form-edit-reminder-{!! $reminder['id'] !!}">
							@csrf()
							<input type="hidden" name="reminder_id" value="{!! $reminder['id'] !!}">
							<input type="hidden" name="seller_id" value="{!! $seller['id'] !!}">
							<input type="hidden" name="estate_id" value="{!! $id !!}">
							<div class="card">
								<div class="card-header">{!! $reminder['name_reminder'] !!}</div>
								<div class="card-body">
									@foreach($reminder['content'] as $key => $content)
									<div data-id-reminder="{!! $content['id'] !!}">
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
												<span class="ffhnm">
													Rappel {!! $key + 1 !!}
												</span><hr>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Date</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<input type="date" name="date_edit[]" class="form-control"  {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!} value="{!! $content['date'] !!}">
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Type</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<select class="form-control" name="type_template_edit[]"  {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!}>
													<option <?php echo ($content['type'] == 'email') ? ' selected ' : ''; ?> value="email">Mail</option>
													<option <?php echo ($content['type'] == 'sms') ? ' selected ' : ''; ?> value="sms">SMS</option>
													<option <?php echo ($content['type'] == 'task') ? ' selected ' : ''; ?> value="task">Tâches</option>
												</select>
											</div>
										</div>
										<div class="row mb-2" id="content-subject">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Suject</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<input type="text" class="form-control" name="subject_edit[]" placeholder="Objet du mail" value="{!! $content['subject'] !!}" {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!}>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Corps</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<textarea class="form-control" name="content_edit[]" rows="4" placeholder="Texte du mail bla bla" {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!}>{!! $content['content'] !!}</textarea>
											</div>
										</div>
										@if($key >= $reminder['next_reminder'])
										<div class="mb-4">
											<button type="button" class="btn btn-danger" data-id-del="{!! $content['id'] !!}" >Supprimer ce rappel</button>
										</div>
										@endif
									</div>
									@endforeach
									<div class="mb-3 add-reminder-edit"></div>
									<div class="mb-4">
										<button type="button" class="btn btn-success add-newreminder-edit">Ajouter une nouvelle étape</button>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-hide="true" data-submit-form="form-edit-reminder-{!! $reminder['id'] !!}">Sauvegarder</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="modal fade font-body-content" id="editReminderTask-{!! $reminder['id'] !!}" tabindex="-1" aria-labelledby="editReminder-{!! $reminder['id'] !!}Label" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('editreminder') !!}" method="POST" data-form="form-edit-reminder-task-{!! $reminder['id'] !!}">
							@csrf()
							<input type="hidden" name="reminder_id" value="{!! $reminder['id'] !!}">
							<input type="hidden" name="seller_id" value="{!! $seller['id'] !!}">
							<input type="hidden" name="estate_id" value="{!! $id !!}">
							<div class="card">
								<div class="card-header">{!! $reminder['name_reminder'] !!}</div>
								<div class="card-body">
									@foreach($reminder['content'] as $key => $content)
									<div data-id-reminder="{!! $content['id'] !!}">
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
												<span class="ffhnm">
													Rappel {!! $key + 1 !!}
												</span><hr>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Date</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<input type="date" name="date_edit[]" class="form-control" {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!} value="{!! $content['date'] !!}">
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Template</span>
											</div>
											<input type="hidden" name="type_template_edit[]" value="task">
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<select class="form-control" data-choisir-template {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!}>
													<option value="">Choisissez un modèle</option>
													@foreach($templatesTask as $template)
														<option value="{!! $template['id'] !!}">{!! $template['name'] !!}</option>
													@endforeach
												</select>
											</div>
										</div>
										@foreach($templatesTask as $template)
											<textarea class="d-none" data-det-file-template-{!! $template['id'] !!}>{!! file_get_contents(asset('templates/'.$template['file'])) !!}</textarea>
											<input type="hidden" data-det-subject-task-{!! $template['id'] !!} value="{!! $template['subject'] !!}">
										@endforeach
										<div class="row mb-2" id="content-subject">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Rangé dans</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<input type="text" class="form-control" data-subject-edit name="subject_edit[]" placeholder="Objet du mail" value="{!! $content['subject'] !!}" {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!}>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
												<textarea class="form-control" data-content-edit name="content_edit[]" rows="4" placeholder="Texte du mail bla bla" {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!}>{!! $content['content'] !!}</textarea>
											</div>
										</div>
									</div>
									@endforeach
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-hide="true" data-submit-form="form-edit-reminder-task-{!! $reminder['id'] !!}">Sauvegarder</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	@endif
	<div class="modal fade font-body-content" id="seeDetails-{!! $reminder['id'] !!}" tabindex="-1" aria-labelledby="seeDetails-{!! $reminder['id'] !!}Label" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form data-form="form-see-details-{!! $reminder['id'] !!}">
							<input type="hidden" name="reminder_id" value="{!! $reminder['id'] !!}">
							<input type="hidden" name="seller_id" value="{!! $seller['id'] !!}">
							<input type="hidden" name="estate_id" value="{!! $id !!}">
							<div class="card">
								<div class="card-header">{!! $reminder['name_reminder'] !!}</div>
								<div class="card-body">
									@foreach($reminder['content'] as $key => $content)
									<div data-id-reminder="{!! $content['id'] !!}">
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
												<span class="ffhnm">
													Rappel {!! $key + 1 !!}
												</span><hr>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Date</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<input type="date" class="form-control" {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!} name="date_reminder" value="{!! $content['date'] !!}">
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Type</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<select class="form-control" name="type_template_edit[]" {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!}>
													<option <?php echo ($content['type'] == 'email') ? ' selected ' : ''; ?> value="email">Mail</option>
													<option <?php echo ($content['type'] == 'sms') ? ' selected ' : ''; ?> value="sms">SMS</option>
													<option <?php echo ($content['type'] == 'task') ? ' selected ' : ''; ?> value="task">Tâches</option>
												</select>
											</div>
										</div>
										<div class="row mb-2" id="content-subject">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Suject</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<input type="text" class="form-control" name="subject_edit[]" placeholder="Objet du mail" value="{!! $content['subject'] !!}" {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!}>
											</div>
										</div>
										<div class="row mb-2">
											<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-3">
												<span class="ffhnl">Corps</span>
											</div>
											<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-9">
												<textarea class="form-control" name="content_edit[]" rows="4" placeholder="Texte du mail bla bla" {!! ($key < $reminder['next_reminder']) ? 'readonly' : '' !!}>{!! $content['content'] !!}</textarea>
											</div>
										</div>
									</div>
									@endforeach
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
									</div>
								</div>
							</div>
					</div>
				</div>
			</div>
		</div>
@endforeach

<div class="modal fade font-body-content" id="calendargoogle" tabindex="-1" aria-labelledby="calendarLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-xl">
		<div class="modal-content">
			<div class="modal-body">
				<div class="card">
					<div class="card-header">Calendrier</div>
					<div class="card-body">
						<div class="row mb-2">
							<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
								<span class="text-title-calendar">Mes agendas</span>
								<div id="colors-users-calendar" class="mt-5"></div>
							</div>
							<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9 col-xl-9">
								<div id="calendarmodalrdv" data-url="{!! route('events') !!}"></div>
							</div>
						</div>
						<div class="text-right mb-5">
							<span class="color-user-calendar" style="background:#3cd47c"></span> Visites confirmées
						</div>
						<div class="text-right">
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade font-body-content" id="confirmationemail" tabindex="-1" aria-labelledby="confirmationemailLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('confirmationrdv') !!}" method="POST" data-form="form-confirm-email" data-reload="true">
					@csrf()
					<input type="hidden" name="seller_email" value="{!! $seller['email'] !!}">
					<input type="hidden" name="seller_name" value="{!! $seller['name'] !!}">
					<input type="hidden" name="estate_id" value="{!! $id !!}">
					<input type="hidden" name="estate_reference" value="{!! date('ymdh.i', strtotime($estate['reference'])) !!}">
					<input type="hidden" name="modal_date" id="modal_date" value="">
					<input type="hidden" name="modal_date_confirm_start" id="modal_date_confirm_start" value="">
					<input type="hidden" name="modal_date_confirm_end" id="modal_modal_date_confirm_end" value="">
					<div class="card">
						<div class="card-header">Envoyer confirmation</div>
						<div class="card-body">
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">
									<span>Template</span>
								</div>
								<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
									<select class="form-control" id="nom_template">
										@foreach($templates as $template)
											@if($template['type'] == 'email')
												<option value="{!! $template['id'] !!}">{!! $template['name'] !!}</option>
											@endif
										@endforeach
									</select>
									@foreach($templates as $template)
										@if($template['type'] == 'email')
										<textarea style="display:none;" id="template{!! $template['id'] !!}">{!! file_get_contents(asset('templates/'.$template['file'])) !!}
										</textarea>
										<input type="hidden" id="subject{!! $template['id'] !!}" value="{!! $template['subject'] !!}">
										@endif
									@endforeach
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">
									<span>Subject</span>
								</div>
								<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
									<input type="text" name="subject" id="subjectrdv" class="form-control" placeholder="Message" required>
								</div>
							</div>
							<div class="mb-4">
								<textarea name="body" id="tinyConfirm" data-height="268" data-tiny="tinyConfirm" rows="40">
									<p>Cher Monsieru X,</p>
									<p>Afin de traiter au mieux votre dossier, bla bla bla...</p>
									<p>Bla bla bla</p>
								</textarea>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-confirm-email">Envoyer</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade font-body-content" id="confirmationsms" tabindex="-1" aria-labelledby="confirmationsmsLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('confirmationsmsrdv') !!}" method="POST" data-form="form-confirm-sms" data-reload="true">
					@csrf()
					<input type="hidden" name="seller_email" value="{!! $seller['email'] !!}">
					<input type="hidden" name="seller_name" value="{!! $seller['name'] !!}">
					<input type="hidden" name="estate_id" value="{!! $id !!}">
					<input type="hidden" name="estate_reference" value="{!! date('ymdh.i', strtotime($estate['reference'])) !!}">
					<input type="hidden" name="modal_date" id="modal_date_sms" value="">
					<input type="hidden" name="modal_date_confirm_start" id="modal_date_confirm_start_sms" value="">
					<input type="hidden" name="modal_date_confirm_end" id="modal_modal_date_confirm_end_sms" value="">
					<div class="card">
						<div class="card-header">Envoyer confirmation</div>
						<div class="card-body">
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">
									<span>Template</span>
								</div>
								<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
									<select class="form-control" id="nom_template_sms">
										@foreach($templates as $template)
											@if($template['type'] == 'sms')
												<option value="{!! $template['id'] !!}">{!! $template['name'] !!}</option>
											@endif
										@endforeach
									</select>
									@foreach($templates as $template)
										@if($template['type'] == 'sms')
										<textarea style="display:none;" id="template{!! $template['id'] !!}">{!! file_get_contents(asset('templates/'.$template['file'])) !!}
										</textarea>
										@endif
									@endforeach
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
									<textarea name="body" class="form-control" id="confirmSMS" rows="4"><p>Cher Monsieru X,</p><p>Afin de traiter au mieux votre dossier, bla bla bla...</p><p>Bla bla bla</p>
									</textarea>
								</div>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-confirm-sms">Envoyer</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade font-body-content" id="editOffer" tabindex="-1" aria-labelledby="editOfferLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('sendoffer') !!}" method="POST" data-form="form-edit-offer">
					@csrf()
					<input type="hidden" name="seller_email" value="{!! $seller['email'] !!}">
					<input type="hidden" name="inf_localisation" id="inf_localisation" value="">
					<input type="hidden" name="inf_seller" id="inf_seller" value="">
					<input type="hidden" name="inf_assign" id="inf_assign" value="">
					<input type="hidden" name="inf_cherche" id="inf_cherche" value="">
					<input type="hidden" name="inf_comment" id="inf_comment" value="">
					<input type="hidden" name="inf_description" id="inf_description" value="">
					<input type="hidden" name="inf_interior" id="inf_interior" value="">
					<input type="hidden" name="inf_exterior" id="inf_exterior" value="">
					<input type="hidden" name="inf_problems" id="inf_problems" value="">
					<input type="hidden" name="inf_remarks" id="inf_remarks" value="">
					<input type="hidden" name="inf_offer" id="inf_offer" value="">
					<input type="hidden" name="inf_photos" id="inf_photos" value="">
					<input type="hidden" name="show_photos_doc" id="show_photos_doc" value="0">
					<input type="hidden" name="address_estate" value="{!! $estate['street'] !!}, {!! $estate['number'] !!} - {!! $estate['code_postal'] !!} {!! $estate['city'] !!}">
					<input type="hidden" name="price_estate" value="@isset($offer['price_wesold']) {!! $offer['price_wesold'] !!} @endisset">
					@foreach($medias as $media)
						@if($media['type'] === 'photos')
							<input type="hidden" name="photos[]" value="{!! asset('photos/'.$media['name']) !!}">
						@endif
						@if($media['type'] === 'documents')
							<input type="hidden" name="documents[]" value="{!! asset('documents/'.$media['name']) !!}">
						@endif
					@endforeach
					<div class="card">
						<div class="card-header">Edition offre : <?php echo date('ymdh.i', strtotime($estate['reference'])) ?></div>
						<div class="card-body">
							<div class="mb-2">
								<input type="text" name="form_offer_title" class="form-control mb-2" placeholder="Editer l’OFFRE" disabled required>
							</div>
							<div class="mb-4">
								<textarea name="form_message" id="tinyEditOffer" data-height="500" data-tiny="tinyEditOffer" rows="40" disabled>
									<p>Cher Monsieur xxx, </p>
									<p>Veuillez trouver, ci-dessous, notre meilleure offre pour votre bien situé à [address-estate] reprenant les caractéristiques suivantes:
									<br>Localisation du bien:
									<br>[localisation-estate] 
									<br>Description du bien:
									<br>[description-estate]
									<br>Détails de l'intérieur du bien:
									<br>[details-interior-estate]
									<br>Détails de l'extérieur du bien:
									<br>[details-exterior-estate]
									<br>Remarques du requéreur:
									<br>[remarks-estate]
									<p>Cordialement, </p>
									<p>{!! Auth::user()->name !!}</p>
									<img src="{!! asset('img/logo_pdf.png'); !!}" width="252">
								</textarea>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-success" data-edit-form="form-edit-offer">Edit</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-hide="true" data-submit-form="form-edit-offer">Partager</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade font-body-content" id="editOfferPDF" tabindex="-1" aria-labelledby="editOfferPDFLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('savepdfoffer') !!}" method="POST" data-form="form-edit-offer-PDF">
					@csrf()
					<input type="hidden" name="estate_id" value="{!! $id !!}">
					<input type="hidden" name="price_seller" id="ps" value="">
					<input type="hidden" name="price_wesold" id="pw" value="">
					<input type="hidden" name="price_market" id="pm" value="">
					<input type="hidden" name="condition_offer" id="condition_offer_o" value="">
					<input type="hidden" name="notaire" id="notaire_o" value="">
					<input type="hidden" name="validity" id="validity_o" value="">
					<input type="hidden" name="texteadded" id="texteadded_o" value="">
					<div class="card">
						<div class="card-header">Préparation offre : <?php echo date('ymdh.i', strtotime($estate['reference'])) ?></div>
						<div class="card-body">
							<div class="mb-4">
								<textarea class="d-none" id="text-original-offer">
									<p><strong>WE SOLD S.A. ,</strong> inscrite en date du 28 octobre 2019 sous le numéro d’ entreprise,	0736.853.174 déclare faire une <u>offre ferme et définitive</u> pour l’acquisition d’un bien dont les références ainsi que les modalités d’acquisition sont décrites ci-dessous :</p>
									<p align="center"><strong><u>Une maison sise :</u></strong></p>
									<p align="center">{!! $estate['street'] !!}, {!! $estate['number'] !!} - {!! $estate['code_postal'] !!} {!! $estate['city'] !!}</p>
									[texte]
									<p><strong><u>Modalités de l’offre et timing opérationnel : </u></strong></p>
									<ul>
										@php $formatterFR = new NumberFormatter("fr", NumberFormatter::SPELLOUT); @endphp
										<li>Montant global de l’offre : [pricewesold] EUR ([price-letter])</li>
										<li><strong><u>[condition]</u></strong></li>
										<li>Les parties s’engagent à signer un compromis en bonne et due forme dans les plus brefs délais</li>
										<li>Notaire de l’acquéreur : [notary]</li>
										<?php
											$date_current = date("j F Y"); 
										?>
										<li>Cette présente offre est valable jusqu’au [validate], date à laquelle l’acquéreur se réserve le droit de la considérer comme nulle et non avenue.</li>
										<li>Nous insistons sur le caractère confidentiel de cette offre. </li>
									</ul>
									Fait à Bruxelles, le <?php echo date('j F Y') ?><br><br><br>
									<table width="100%">
										<tr>
											<td>
												<label>
													<strong>L’Acquéreur</strong><br>
													<strong>Pour WE SOLD</strong><br>
													Denis Vandamme<br>
													Administrateur délégué
												</label>
												<p><img src="{!! asset('img/company.png') !!}" width="250px"></p>
											</td>
											<td style="vertical-align: top;">
												<label>
													<strong>Le Propriétaire</strong><br>
													« Pour acceptation »
												</label>
											</td>
										</tr>
									</table>
								</textarea>
								<textarea name="bodyPDF" id="tinyEditOfferPDF" data-height="500" data-tiny="tinyEditOfferPDF" rows="40">
									<p><strong>WE SOLD S.A. ,</strong> inscrite en date du 28 octobre 2019 sous le numéro d’ entreprise,	0736.853.174 déclare faire une <u>offre ferme et définitive</u> pour l’acquisition d’un bien dont les références ainsi que les modalités d’acquisition sont décrites ci-dessous :</p>
									<p align="center"><strong><u>Une maison sise :</u></strong></p>
									<p align="center">{!! $estate['street'] !!}, {!! $estate['number'] !!} - {!! $estate['code_postal'] !!} {!! $estate['city'] !!}</p>
									[texte]
									<p><strong><u>Modalités de l’offre et timing opérationnel : </u></strong></p>
									<ul>
										@php $formatterFR = new NumberFormatter("fr", NumberFormatter::SPELLOUT); @endphp
										<li>Montant global de l’offre : [pricewesold] EUR ([price-letter])</li>
										<li><strong><u>[condition]</u></strong></li>
										<li>Les parties s’engagent à signer un compromis en bonne et due forme dans les plus brefs délais</li>
										<li>Notaire de l’acquéreur : [notary]</li>
										<?php
											$date_current = date("j F Y"); 
										?>
										<li>Cette présente offre est valable jusqu’au [validate], date à laquelle l’acquéreur se réserve le droit de la considérer comme nulle et non avenue.</li>
										<li>Nous insistons sur le caractère confidentiel de cette offre. </li>
									</ul>
									Fait à Bruxelles, le <?php echo date('j F Y') ?><br><br><br>
									<table width="100%">
										<tr>
											<td>
												<label>
													<strong>L’Acquéreur</strong><br>
													<strong>Pour WE SOLD</strong><br>
													Denis Vandamme<br>
													Administrateur délégué
												</label>
												<p><img src="{!! asset('img/company.png') !!}" width="250px"></p>
											</td>
											<td style="vertical-align: top;">
												<label>
													<strong>Le Propriétaire</strong><br>
													« Pour acceptation »
												</label>
											</td>
										</tr>
									</table>
								</textarea>
							</div>
							<div class="text-right">
								<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-offer-PDF">Créer un PDF</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@foreach($templates as $template)
	@if($template['type'] == 'email')
		<div class="modal fade sendemail" id="sendMail-{!! $template['id'] !!}" tabindex="-1" aria-labelledby="sendMailLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('sendemail') !!}" method="POST" data-form="form-edit-offer-{!! $template['id'] !!}" data-reload="true">
							@csrf()
							<input type="hidden" name="seller_email" value="{!! $seller['email'] !!}">
							<div class="card">
								<div class="card-header">Editer Template: {!! $template['name'] !!}</div>
								<div class="card-body">
									<div class="mb-2">
										<input type="text" name="form_offer_title" class="form-control mb-2" placeholder="Editer l’OFFRE" required disabled>
									</div>
									<div class="mb-4">
										<textarea name="form_phone_message" id="tinySendMail-{!! $template['id'] !!}" data-height="793" data-tiny="tinySendMail" rows="40" disabled>{!! file_get_contents(asset('templates/'.$template['file'])) !!}
										</textarea>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-success" data-edit-form="form-edit-offer-{!! $template['id'] !!}">Edit</button>
										<button type="button" class="btn btn-lg btn-dark" data-cancel-form="form-edit-offer-{!! $template['id'] !!}">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-hide="true" data-submit-form="form-edit-offer-{!! $template['id'] !!}">Envoyer</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	@endif
@endforeach

@foreach($templates as $template)
	@if($template['type'] == 'sms')
		<div class="modal fade font-body-content" id="sendSMS-{!! $template['id'] !!}" tabindex="-1" aria-labelledby="sendSMSLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('sendsms') !!}" method="POST" data-form="form-edit-sms-{!! $template['id'] !!}" data-reload="true">
							@csrf()
							<div class="card">
								<div class="card-header">Editer Template: {!! $template['name'] !!}</div>
								<div class="card-body">
									<div class="mb-4">
										<input type="hidden" name="username" value="{!! Auth::user()->name !!}">
										<textarea name="form_phone_message" id="form_phone_message-{!! $template['id'] !!}" class="form-control" rows="3" disabled>{!! file_get_contents(asset('templates/'.$template['file'])) !!}</textarea>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-success" data-edit-form="form-edit-sms-{!! $template['id'] !!}">Edit</button>
										<button type="button" class="btn btn-lg btn-dark" data-cancel-form="form-edit-sms-{!! $template['id'] !!}">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-hide="true" data-submit-form="form-edit-sms-{!! $template['id'] !!}">Envoyer</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	@endif
@endforeach
<div class="modal fade font-body-content" id="setVisit" tabindex="-1" aria-labelledby="sendSMSLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title ffhnb" id="calendarModalLabel" data-event-title>Sélect. Tranche</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
						<span class="ffhnm">Sélectionnez le jour de la visite</span>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
						<input id="visitDate" class="form-control">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
						<span class="ffhnm">Sélectionnez l'heure de début</span>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
						<input id="visitStart" class="form-control">
					</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
						<span class="ffhnm">Sélectionnez l'heure de fin</span>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6 mb-3">
						<input id="visitEnd" class="form-control">
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade font-body-content" id="sendInvitation" tabindex="-1" aria-labelledby="sendInvitationLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('sendrdv') !!}" method="POST" data-form="form-send-invitation">
					@csrf()
					<input type="hidden" name="seller_email" value="{!! $seller['email'] !!}">
					<input type="hidden" name="seller_name" value="{!! $seller['name'] !!}">
					<input type="hidden" name="estate_id" value="{!! $id !!}">
					<input type="hidden" id="route_confirm" value="{!! route('confirm', md5($id)) !!}">
					<div class="card">
						<div class="card-header">Envoyer un RDV</div>
						<div class="card-body">
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">
									<span>Template</span>
								</div>
								<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
									<select class="form-control" id="nom_template_rdv">
										@foreach($templates as $template)
											@if($template['type'] == 'email')
												<option value="{!! $template['id'] !!}">{!! $template['name'] !!}</option>
											@endif
										@endforeach
									</select>
									@foreach($templates as $template)
										@if($template['type'] == 'email')
										<textarea style="display:none;" id="template_rdv_{!! $template['id'] !!}">{!! file_get_contents(asset('templates/'.$template['file'])) !!}
										</textarea>
										<input type="hidden" id="subject_rdv_{!! $template['id'] !!}" value="{!! $template['subject'] !!}">
										@endif
									@endforeach
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
									<span>Subject : </span>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
									<input type="text" name="subject" id="subject_rdv" class="form-control" placeholder="Message" required>
								</div>
							</div>
							<div class="mb-4">
								<textarea name="body" id="tinysendInvitation" data-height="700" data-tiny="tinysendInvitation">
									<p>Cher Monsieru X,</p>
									<p>Afin de traiter au mieux votre dossier, bla bla bla...</p>
									<p>Bla bla bla</p>
								</textarea>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-dark"  data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-send-invitation">Envoyer</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

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

<div class="modal fade font-body-content" id="createEvent" tabindex="-1" aria-labelledby="createEventLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('createevent') !!}" method="POST" data-form="form-create-event">
					@csrf()
					<input type="hidden" name="seller_id" value="{!! $seller['id'] !!}">
					<input type="hidden" name="seller_email" value="{!! $seller['email'] !!}">
					<input type="hidden" name="estate_id" value="{!! $id !!}">
					<div class="card">
						<div class="card-header">Créer un évènement</div>
						<div class="card-body">
							<div class="mb-2">
								<span class="ffhnm">Numéro de bien : </span><span>{!! date('ymdh.i', strtotime($estate['reference'])) !!}</span>
								<input type="hidden" name="number_bien" value="{!! date('ymdh.i', strtotime($estate['reference'])) !!}">
							</div>
							<div class="mb-2">
								<span class="ffhnm">Adresse : </span> <span>{!! $estate['street'] !!} {!! ($estate['number'] == 0) ? '' : $estate['number'] !!} {!! ($estate['box'] == 0) ? '' : ','.$estate['box'] !!} {!! ($estate['city'] == '') ? '' : ','.$estate['city'] !!} {!! ($estate['code_postal'] == 0) ? '' : $estate['code_postal'] !!}</span>
								<input type="hidden" name="address_bien" value="{!! $estate['street'] !!} {!! ($estate['number'] == 0) ? '' : $estate['number'] !!} {!! ($estate['box'] == 0) ? '' : ','.$estate['box'] !!} {!! ($estate['city'] == '') ? '' : ','.$estate['city'] !!} {!! ($estate['code_postal'] == 0) ? '' : $estate['code_postal'] !!}">
							</div>
							<div class="mb-2">
								<span class="ffhnm">Nom de la peersonne de contact : </span><span>{!! $seller['name'] !!}</span>
								<input type="hidden" name="name_seller" value="{!! $seller['name'] !!}">
							</div>
							<div class="mb-2">
								<span class="ffhnm">Numéro de téléphone : </span><span>{!! $seller['phone'] !!}</span>
								<input type="hidden" name="phone_seller" value="{!! $seller['phone'] !!}">
							</div>
							<div class="row mb-2">
								<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
									<span class="ffhnm">Quand :</span>
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
									<input type="date" class="form-control" name="date_event_click_start" id="date_event_click_start">
								</div>
								<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
									<input type="time" class="form-control" name="time_event_click_start" id="time_event_click_start">
								</div>
								<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3 col-xl-3">
									<input type="date" class="form-control" name="date_event_click_end" id="date_event_click_end">
								</div>
								<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
									<input type="time" class="form-control" name="time_event_click_end" id="time_event_click_end">
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
									<i class="bi bi-calendar"></i>
								</div>
								<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<select class="form-control" name="chosen_calendar" id="chosen_calendar">
									</select>
								</div>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-dark"  data-dismiss="modal">Annuler</button>
								@if($eve_['total'] < 3 && $estate['visit_date_at'] == null)
								<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-create-event">Créer</button>
								@endif
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade font-body-content" id="createnewticket" tabindex="-1" aria-labelledby="createnewticketLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('createticket_e') !!}" method="POST" data-form="form-create-ticket">
					@csrf()
					<input type="hidden" name="estate_id" value="{!! $id !!}">
					<input type="hidden" name="name_seller" value="{!! $seller['name'] !!}">
					<input type="hidden" name="email_seller" value="{!! $seller['email'] !!}">
					<div class="card">
						<div class="card-header">Créer un nouveau ticket</div>
						<div class="card-body">
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-2 col-md-2 col-lg-2 col-xl-2">
									<span>Template</span>
								</div>
								<div class="col-xs-12 col-sm-10 col-md-10 col-lg-10 col-xl-10">
									<select class="form-control" data-nom-template>
										<option value="">Choisissez un modèle</option>
										@foreach($templates as $template)
											@if($template['type'] == 'email')
												<option value="{!! $template['id'] !!}">{!! $template['name'] !!}</option>
											@endif
										@endforeach
									</select>
									@foreach($templates as $template)
										@if($template['type'] == 'email')
										<textarea style="display:none;" data-template-email-{!! $template['id'] !!}>{!! file_get_contents(asset('templates/'.$template['file'])) !!}
										</textarea>
										<input type="hidden" data-subject-email-{!! $template['id'] !!} value="{!! $template['subject'] !!}">
										@endif
									@endforeach
								</div>
							</div>
							<div class="row mb-2">
								<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
									<span>Subject : </span>
								</div>
								<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
									<input data-change-input type="text" name="subject" data-sub class="form-control" placeholder="Message" required>
								</div>
							</div>
							<div class="mb-4">
								<textarea data-change-input name="body_ticket" id="tinycreatenewticket" data-height="400" data-tiny="tinycreatenewticket">
									<p>Cher Monsieru X,</p>
									<p>Afin de traiter au mieux votre dossier, bla bla bla...</p>
									<p>Bla bla bla</p>
								</textarea>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-dark" data-cancel data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-modified-modal="false" data-submit-form="form-create-ticket">Envoyer</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>



@endsection

@section('scripts')

<script type="text/javascript">
	window.urldeletemedias = "{!! route('deletemedia', 0) !!}";
	aTemplates = {!! json_encode($templates); !!}; // Varible to obtain the information directly in javascript when a editUser will be updated
	aTemplatesReminders = {!! json_encode($templatesReminders); !!}; // Varible to obtain the information directly in javascript when a editUser will be updated
	aEventConfirmed = {!! json_encode($eventConfirmed); !!}; // Varible to obtain the information directly in javascript when a editUser will be updated
</script>
@endsection