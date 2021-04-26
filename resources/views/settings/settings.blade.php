@extends('layouts.app')

@section('content')
	@php
		$classNavActive = ' active';
		$classTabActive = ' show active';
	@endphp
	<nav>
		<ul class="nav nav-tabs" id="tab-main" role="tablist">
			<li class="nav-item">
				<a class="nav-link active" href="#settings-templates" id="settings-templates-tab" data-href="#settings-templates" data-toggle="tab" role="tab" aria-controls="settings-templates" aria-selected="true">Templates</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#settings-users" id="settings-users-tab" data-href="#settings-users" data-toggle="tab" role="tab" aria-controls="settings-users" aria-selected="false">Utilisateurs</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#settings-notaries" id="settings-notaries-tab" data-href="#settings-notaries" data-toggle="tab" role="tab" aria-controls="settings-notaries" aria-selected="false">Notaires</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#settings-sites-immo" id="settings-sites-imm-tab" data-href="#settings-sites-immo" data-toggle="tab" role="tab" aria-controls="settings-sites-immo" aria-selected="false">Sites Immo</a>
			</li>
			@if(Auth::user()->type == 1)
			<li class="nav-item">
				<a class="nav-link" href="#settings-categories" id="settings-categories-tab" data-href="#settings-categories" data-toggle="tab" role="tab" aria-controls="settings-categories" aria-selected="false">Statut</a>
			</li>
			@endif
			<li class="nav-item">
				<a class="nav-link" href="#settings-days" id="settings-days-tab" data-href="#settings-days" data-toggle="tab" role="tab" aria-controls="settings-days" aria-selected="false">Jours fériés</a>
			</li>
			<li class="nav-item">
				<a class="nav-link" href="#settings-menu" id="settings-menu-tab" data-href="#settings-menu" data-toggle="tab" role="tab" aria-controls="settings-menu" aria-selected="false">Personnalisation</a>
			</li>
		</ul>
	</nav>
	<div id="settings-content" class="mt-4 tab-content">
		<div id="settings-templates" class="tab-pane fade active show" role="tabpanel" aria-labelledby="settings-templates-tab">
			<div class="card">
				<div class="card-header">Templates</div>
				<div class="card-body">
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-3">
						<nav>
							<ul class="nav nav-tabs" id="templatesTab" role="tablist">
								<li class="nav-item">
									<a class="nav-link" href="#templates-email" id="templates-email-tab" data-toggle="tab" role="tab" aria-controls="templates-email" aria-selected="true">Mails</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#templates-sms" id="templates-sms-tab" data-toggle="tab" role="tab" aria-controls="templates-sms" aria-selected="false">SMS</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#templates-task" id="templates-task-tab" data-toggle="tab" role="tab" aria-controls="templates-task" aria-selected="false">Tâches</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#templates-process" id="templates-process-tab" data-toggle="tab" role="tab" aria-controls="templates-process" aria-selected="false">Process</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#templates-conditions" id="templates-conditions-tab" data-toggle="tab" role="tab" aria-controls="templates-conditions" aria-selected="false">Condition de l'offre</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#templates-text" id="templates-text-tab" data-toggle="tab" role="tab" aria-controls="templates-text" aria-selected="false">Texte de l'offre</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="#templates-subject" id="templates-subject-tab" data-toggle="tab" role="tab" aria-controls="templates-subject" aria-selected="false">Sujet de l'offre</a>
								</li>
							</ul>
						</nav>
					</div>
					<div class="tab-content" id="templatesTabContent">
						<div class="tab-pane fade active show" id="templates-email" aria-labelledby="templates-email">
							<div class="autosize-s">
								<table data-table="touts" class="display responsive nowrap" style="width: 100%;">
									<thead>
										<tr>
											<th>Action</th>
											<th>Type</th>
											<th>Nom</th>
											<th>Créé par</th>
										</tr>
									</thead>
									<tbody>
										@if(Auth::user()->type == 1)
											@foreach($templates as $template)
												@if($template['type'] == 'email')
													<tr>
														<td class="text-center">
															<a href="{!! route('deletetemplate', [ $template['type'], $template['file'] ]) !!}" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#editTemplateEmail{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
														</td>
														<td>{!! $template['type'] !!}</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type == 2)
											@foreach($templates as $template)
												@if($template['type'] == 'email')
													<tr>
														<td class="text-center">
															@if(in_array($template['user_id'], $teamOfManager))
															<a href="{!! route('deletetemplate', [ $template['type'], $template['file'] ]) !!}" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#editTemplateEmail{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>{!! $template['type'] !!}</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type == 3)
											@foreach($templates as $template)
												@if($template['type'] == 'email')
													<tr>
														<td class="text-center">
															@if(Auth::user()->name == $template['user'])
															<a href="{!! route('deletetemplate', [ $template['type'], $template['file'] ]) !!}" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#editTemplateEmail{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>{!! $template['type'] !!}</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="templates-sms" aria-labelledby="templates-sms">
							<div class="autosize-s">
								<table data-table="touts" class="display responsive nowrap" style="width: 100%;">
									<thead>
										<tr>
											<th>Action</th>
											<th>Type</th>
											<th>Nom</th>
											<th>Créé par</th>
										</tr>
									</thead>
									<tbody>
										@if(Auth::user()->type == 1)
											@foreach($templates as $template)
												@if($template['type'] == 'sms')
													<tr>
														<td class="text-center">
															<a href="" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#editTemplateSMS{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
														</td>
														<td>{!! $template['type'] !!}</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type == 2)
											@foreach($templates as $template)
												@if($template['type'] == 'sms')
													<tr>
														<td class="text-center">
															@if(in_array($template['user_id'], $teamOfManager))
															<a href="" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#editTemplateSMS{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>{!! $template['type'] !!}</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type == 3)
											@foreach($templates as $template)
												@if($template['type'] == 'sms')
													<tr>
														<td class="text-center">
															@if(Auth::user()->name == $template['user'])
															<a href="" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#editTemplateSMS{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>{!! $template['type'] !!}</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="templates-task" aria-labelledby="templates-task">
							<div class="autosize-s">
								<table data-table="touts" class="display responsive nowrap" style="width: 100%;">
									<thead>
										<tr>
											<th>Action</th>
											<th>Type</th>
											<th>Nom</th>
											<th>Créé par</th>
										</tr>
									</thead>
									<tbody>
										@if(Auth::user()->type == 1)
											@foreach($templates as $template)
												@if($template['type'] == 'task')
													<tr>
														<td class="text-center">
															<a href="" data-delete="{!! route('deletetemplate', [ $template['id'], $template['file'] ]) !!}">
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#editTemplateTask{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
														</td>
														<td>{!! $template['type'] !!}</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type == 2)
											@foreach($templates as $template)
												@if($template['type'] == 'task')
													<tr>
														<td class="text-center">
															@if(in_array($template['user_id'], $teamOfManager))
															<a href="" data-delete="{!! route('deletetemplate', [ $template['id'], $template['file'] ]) !!}">
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#editTemplateTask{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>{!! $template['type'] !!}</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type == 3)
											@foreach($templates as $template)
												@if($template['type'] == 'task')
													<tr>
														<td class="text-center">
															@if(Auth::user()->name == $template['user'])
															<a href="" data-delete="{!! route('deletetemplate', [ $template['id'], $template['file'] ]) !!}">
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#editTemplateTask{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>{!! $template['type'] !!}</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="templates-process" aria-labelledby="templates-process">
							<div class="autosize-s">
								<table data-table="touts" class="display responsive nowrap" style="width: 100%;">
									<thead>
										<tr>
											<th>Action</th>
											<th>Type</th>
											<th>Nom</th>
											<th>Créé par</th>
										</tr>
									</thead>
									<tbody>
										@if(Auth::user()->type == 1)
											@foreach($reminders as $reminder)
												<tr>
													<td class="text-center">
														<a href="{!! route('deletereminders', $reminder['id']) !!}" data-delete>
															<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
														</a>
														<a href="#" data-toggle="modal" data-reminder="{!! $reminder['id'] !!}" data-target="#edit-reminder-{!! $reminder['id'] !!}">
															<img src="{!! asset('img/icons/search.svg') !!}" width="16">
														</a>
													</td>
													<td>Process</td>
													<td>{!! $reminder['name'] !!}</td>
													<td>{!! $reminder['user'] !!}</td>
												</tr>
											@endforeach
										@endif
										@if(Auth::user()->type == 2)
											@foreach($reminders as $reminder)
												<tr>
													<td class="text-center">
														@if(in_array($template['user_id'], $teamOfManager))
															<a href="{!! route('deletereminders', $reminder['id']) !!}" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-reminder="{!! $reminder['id'] !!}" data-target="#edit-reminder-{!! $reminder['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
														@endif
													</td>
													<td>Process</td>
													<td>{!! $reminder['name'] !!}</td>
													<td>{!! $reminder['user'] !!}</td>
												</tr>
											@endforeach
										@endif
										@if(Auth::user()->type == 3)
											@foreach($reminders as $reminder)
												<tr>
													<td class="text-center">
														@if(Auth::user()->name == $template['user'])
															<a href="{!! route('deletereminders', $reminder['id']) !!}" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-reminder="{!! $reminder['id'] !!}" data-target="#edit-reminder-{!! $reminder['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
														@endif
													</td>
													<td>Process</td>
													<td>{!! $reminder['name'] !!}</td>
													<td>{!! $reminder['user'] !!}</td>
												</tr>
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="templates-conditions" aria-labelledby="templates-conditions">
							<div class="autosize-s">
								<table data-table="touts" class="display responsive nowrap" style="width: 100%;">
									<thead>
										<tr>
											<th>Action</th>
											<th>Type</th>
											<th>Nom</th>
											<th>Créé par</th>
										</tr>
									</thead>
									<tbody>
										@if(Auth::user()->type == 1)
											@foreach($templates as $template)
												@if($template['type'] == 'condition')
													<tr>
														<td class="text-center">
															<a href="" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#edit-condition-{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
														</td>
														<td>
															{!! $template['type'] !!}
														</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type != 1)
											@foreach($templates as $template)
												@if($template['type'] == 'condition')
													<tr>
														<td class="text-center">
															@if(in_array($template['user_id'], $teamOfManager))
															<a href="" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#edit-condition-{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>
															{!! $template['type'] !!}
														</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type == 3)
											@foreach($templates as $template)
												@if($template['type'] == 'condition')
													<tr>
														<td class="text-center">
															@if(Auth::user()->name == $template['user'])
															<a href="" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#edit-condition-{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>
															{!! $template['type'] !!}
														</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="templates-text" aria-labelledby="templates-text">
							<div class="autosize-s">
								<table data-table="touts" class="display responsive nowrap" style="width: 100%;">
									<thead>
										<tr>
											<th>Action</th>
											<th>Type</th>
											<th>Nom</th>
											<th>Créé par</th>
										</tr>
									</thead>
									<tbody>
										@if(Auth::user()->type == 1)
											@foreach($templates as $template)
												@if($template['type'] == 'text-offer')
													<tr>
														<td class="text-center">
															<a href="" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#edit-texte-offer-{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
														</td>
														<td>
															{!! $template['type'] !!}
														</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type == 2)
											@foreach($templates as $template)
												@if($template['type'] == 'text-offer')
													<tr>
														<td class="text-center">
															@if(in_array($template['user_id'], $teamOfManager))
															<a href="" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#edit-texte-offer-{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>
															{!! $template['type'] !!}
														</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
										@if(Auth::user()->type == 3)
											@foreach($templates as $template)
												@if($template['type'] == 'text-offer')
													<tr>
														<td class="text-center">
															@if(Auth::user()->name == $template['user'])
															<a href="" data-delete>
																<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
															</a>
															<a href="#" data-toggle="modal" data-target="#edit-texte-offer-{!! $template['id'] !!}">
																<img src="{!! asset('img/icons/search.svg') !!}" width="16">
															</a>
															@endif
														</td>
														<td>
															{!! $template['type'] !!}
														</td>
														<td>{!! $template['name'] !!}</td>
														<td>{!! $template['user'] !!}</td>
													</tr>
												@endif
											@endforeach
										@endif
									</tbody>
								</table>
							</div>
						</div>
						<div class="tab-pane fade" id="templates-subject" aria-labelledby="templates-subject">
							<form method="POST">
								@csrf
								<div class="autosize-s">
									<span id="_token" class="d-none">{!! csrf_token() !!}</span>
									@foreach($templates as $template)
										@if($template['type'] == 'subject')
									<input type="hidden" id="subject_id_template" value="{!! $template['id'] !!}">
									<textarea name="subject_text" id="subject_text" data-url="{!! route('newtemplatesubjectoffer') !!}" class="form-control">{!! $template['file'] !!}</textarea>
									<span class="text-right d-none" id="ok" style="color:green"><i class="bi bi-check-circle"></i></span>
									@endif @endforeach
								</div>
							</form>
						</div>
					</div>
					<div class="text-left mt-4">
						<button type="button" class="btn btn-lg btn-success" data-submit-hide="false" data-toggle="modal"  data-target="#createTemplate" data-submit-form="form-edit-category">Créer un nouveau template</button>
					</div>
				</div>
			</div>
		</div>
		<div id="settings-users" class="tab-pane fade" role="tabpanel" aria-labelledby="settings-users-tab">
			<div class="card">
				<div class="card-header">Utilisateurs</div>
				<div class="card-body">
					<table data-url="{!! route('getusers') !!}" data-table="user" class="display responsive nowrap" style="width: 100%;">
						<thead>
							<tr>
								<th>Actions</th>
								<th>Utilisateur</th>
								<th>Nom & Prénom</th>
								<th>Groupe</th>
							</tr>
						</thead>
						<tbody>
							@foreach($users as $user)
								@if(Auth::user()->type == 3 && in_array($user['id'], $teamOfAgent)) <!-- if the account is the agent -->
									<tr>
										<td class="text-center">
											<a href="#" data-toggle="modal" data-target="#editUser" data-user-id="{!! $user['id'] !!}">
												<img src="{!! asset('img/icons/search.svg') !!}" width="16">
											</a>
										</td>
										<td>{!! $user['username'] !!}</td>
										<td>{!! $user['firstname'] !!} {!! $user['name'] !!}</td>
										<td>{!! $user['type_name'] !!}</td>
									</tr>
								@endif
								@if(Auth::user()->type == 2 && in_array($user['id'], $teamOfManager)) <!-- if the account is the manager -->
									<tr>
										<td class="text-center">
											@if(Auth::user()->id != $user['id'])
											<a href="{!! route('deleteuser', $user['id']) !!}" data-delete>
												<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
											</a>
											@endif
											<a href="#" data-toggle="modal" data-target="#editUser" data-user-id="{!! $user['id'] !!}">
												<img src="{!! asset('img/icons/search.svg') !!}" width="16">
											</a>
										</td>
										<td>{!! $user['username'] !!}</td>
										<td>{!! $user['firstname'] !!} {!! $user['name'] !!}</td>
										<td>{!! $user['type_name'] !!}</td>
									</tr>
								@endif
								@if(Auth::user()->type == 1) <!-- if the account is the admin -->
									<tr>
										<td class="text-center">
											<a href="{!! route('deleteuser', $user['id']) !!}" data-delete>
												<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
											</a>
											<a href="#" data-toggle="modal" data-target="#editUser" data-user-id="{!! $user['id'] !!}">
												<img src="{!! asset('img/icons/search.svg') !!}" width="16">
											</a>
										</td>
										<td>{!! $user['username'] !!}</td>
										<td>{!! $user['firstname'] !!} {!! $user['name'] !!}</td>
										<td>{!! $user['type_name'] !!}</td>
									</tr>
								@endif
							@endforeach
						</tbody>
					</table>
					<div class="text-right mt-4">
						<button type="button" class="btn btn-lg btn-success" data-toggle="modal" data-target="#newUser">Créer</button>
					</div>
				</div>
			</div>
		</div>
		<div id="settings-notaries" class="tab-pane fade" role="tabpanel" aria-labelledby="settings-notaries-tab">
			<div class="card">
				<div class="card-header">Notaires</div>
				<div class="card-body">
					<table data-url="" data-table="user" class="display responsive nowrap" style="width: 100%;">
						<thead>
							<tr>
								<th>Actions</th>
								<th>Notaire</th>
								<th>Nom & Prénom</th>
								<th>Adresse</th>
								<th>E-mail</th>
								<th>Téléphone</th>
								<th>Clé</th>
							</tr>
						</thead>
						<tbody>
							@foreach($notaries as $notary)
								<tr>
									<td>
										@if(Auth::user()->type == 1 || Auth::user()->type == 2)
										<a href="{!! route('deletenotary', $notary['id']) !!}" data-delete>
											<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
										</a>
										@endif
										<a href="#" data-toggle="modal" data-target="#editNotary{!! $notary['id'] !!}" data-user-id="{!! $notary['id'] !!}">
											<img src="{!! asset('img/icons/search.svg') !!}" width="16">
										</a>
									</td>
									<td>{!! $notary['title'] !!}</td>
									<td>{!! $notary['name'] !!} {!! $notary['lastname'] !!}</td>
									<td>{!! $notary['address'] !!}</td>
									<td>{!! $notary['phone'] !!}</td>
									<td>{!! $notary['email'] !!}</td>
									<td>{!! $notary['key'] !!}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
					<div class="text-right mt-4">
						<button type="button" class="btn btn-lg btn-success" data-toggle="modal" data-target="#newNotary">Créer</button>
					</div>
				</div>
			</div>
		</div>
		<div id="settings-sites-immo" class="tab-pane fade" role="tabpanel" aria-labelledby="settings-sites-immo-tab">
			<form action="{!! route('newrealestate') !!}" method="POST" data-form="form-settings-sites-immo">
				@csrf()
				<div class="card">
					<div class="card-header">Select : Sites immobiliers</div>
					<div class="card-body">
						<div class="row justify-content-between">
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-5">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
										Nom du site à ajouter :
									</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<input type="text" name="nameimmobilier" class="form-control" {!! (Auth::user()->type != 1) ? 'disabled' : '' !!}>
										<span class="wrapper__add" {!! (Auth::user()->type == 1) ? 'data-submit-form="form-settings-sites-immo"' : '' !!}>@include('svg.iconplus')</span>
									</div>
								</div>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-5">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
										Sites enregistrés :
									</div>
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<div class="wrapper__content">
											@foreach($realestates as $site)
												<div class="wrapper__remove">
													{!! $site['name'] !!}
													<span href="{!! route('deletesite', $site['id']) !!}" {!! (Auth::user()->type == 1) ? 'data-delete' : '' !!}>@include('svg.iconminus')</span>
												</div>
											@endforeach
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		@if(Auth::user()->type == 1)
		<div id="settings-categories" class="tab-pane fade" role="tabpanel" aria-labelledby="settings-categories-tab">
			<div class="card">
				<div class="card-header">Statut</div>
				<div class="card-body">
					<div class="autosize-s">
						<table data-url="{!! route('getcategories') !!}" data-table="categories" class="display responsive nowrap" style="width: 100%;">
							<thead>
								<tr>
									<th>Action</th>
									<th>ID</th>
									<th>Nom</th>
									<th>Slug</th>
									<th>Parent</th>
								</tr>
							</thead>
							<tbody>
								@foreach($aCategories as $category)
								<tr>
									<td>
										<a href="{!! route('deletecategory', $category['id']) !!}" data-delete>
											<img src="{!! asset('img/icons/delete.svg') !!}" width="16">
										</a>
										<a href="#" data-toggle="modal" data-target="#editCategory" data-category-id="{!! $category['id'] !!}">
											<img src="{!! asset('img/icons/search.svg') !!}" width="16">
										</a>
									</td>
									<td>{!! $category['id'] !!}</td>
									<td>{!! $category['name'] !!}</td>
									<td>{!! $category['slug'] !!}</td>
									<td>{!! ($category['parent'] != 0) ? $aCategories[$category['parent']]['name'] : '' !!}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
						<div class="text-right mt-4">
							<button type="button" class="btn btn-lg btn-success" data-toggle="modal" data-target="#newCategory">Créer</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
		<div id="settings-days" class="tab-pane fade" role="tabpanel" aria-labelledby="settings-days-tab">
			<form action="{!! route('savedatespecial') !!}" method="POST" data-form="form-hollyday-date">
				@csrf()
				<div class="card">
					<div class="card-header">Jours fériés</div>
					<div class="card-body">
						<div class="row">
							<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mt-2">
								<input type="date" name="" class="form-control" id="date_special" {!! (Auth::user()->type != 1) ? 'disabled' : '' !!}>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 mt-2">
								<button type="submit" class="btn btn-success" data-submit-form="form-hollyday-date" {!! (Auth::user()->type != 1) ? 'disabled' : '' !!}>Enregistrer les modifications</button>
							</div>
							<div class="wrapper__content__date col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 mt-2 ml-3 mr-3">
								<span class="ffhn">Dates</span>
								<ul id="dates_added">
									
									@foreach($dates as $date)
										@php
											$months = ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'];
											$datecomplete = $date['date_special'];
											$dayj = explode('-', $datecomplete);
											$attrib = $date["date_special"];
										@endphp
										
										<li id="{!! $date['date_special'] !!}"  >{!! $dayj[0] !!} {!! $months[round($dayj[1]) - 1] !!}<svg data-delete-date="{!! (Auth::user()->type == 1) ? $attrib : '' !!}" class="minus-date" xmlns="http://www.w3.org/2000/svg" version="1.0" viewBox="0 0 16 16"><path d="M14 1a1 1 0 011 1v12a1 1 0 01-1 1H2a1 1 0 01-1-1V2a1 1 0 011-1zM2 0a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V2a2 2 0 00-2-2z"/><path d="M4 8a.5.5 0 01.5-.5h7a.5.5 0 010 1h-7A.5.5 0 014 8z"/></svg><span><input type="hidden" name="dates[]" value="{!! $date['date_special'] !!}"></li>
									@endforeach
								</ul>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
		<div id="settings-menu" class="tab-pane fade" role="tabpanel" aria-labelledby="settings-menu-tab">
			<form action="{!! route('savemenu') !!}" method="POST" data-form="form-menu" id="form-menu">
				@csrf()
				<span id="_token_savemenu" style="display: none;">{!! csrf_token() !!}</span>
				<div class="card">
					<div class="card-header">Vue du menu</div>
					<div class="card-body">
						<div class="row">
							<div class="col-xs-3 col-sm-1 col-md-2 col-lg-2 col-xl-3"></div>
							<div align="center" class="col-xs-3 col-sm-4 col-md-3 col-lg-3 col-xl-3">
								<label>
									<input type="radio" data-change-menu name="menu" value="Menu déroulant" {!! ($typemenu == 'Menu déroulant') ? 'checked' : '' !!}> Menu déroulant
								</label><br>
								<label>
									<input type="radio" data-change-menu name="menu" value="Menu individuel" {!! ($typemenu == 'Menu individuel') ? 'checked' : '' !!}> Menu individuel
								</label><br>
							</div>
							<div class="col-xs-2 col-sm-6 col-md-4 col-lg-6 col-xl-4 d-none" id="ok-savemenu" align="right">
								<span class="text-right" style="color:green">La menu a été mise à jour. <i class="bi bi-check-circle ml-2"></i></span>
							</div>
							<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mt-2 d-none" align="right"  id="not-ok-savemenu">
								<span style="color:red">Le menu n\'a pas été mise à jour ou les informations n\'ont pas été modifiées. <i class="bi bi-x-circle ml-2"></i></span>
							</div>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
@endsection

@section('modals')
<div class="modal fade" id="createTemplate" tabindex="-1" aria-labelledby="createTemplateLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="" method="POST" data-form="form-edit-template" id="templates_create">
					@csrf()
					<span id="tokenTemplate" style="display: none;">{!! csrf_token() !!}</span>
					<input type="hidden" name="type" id="type_template" value="">
					<div class="card">
						<div class="card-header">Nouveau modèle</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">
									TYPE
								</div>
								<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">
									<select class="form-control" id="newtemplate">
										<option>Choisissez le type de modèle à créer</option>
										<option value="1" data-link="{!! route('newtemplatemail') !!}">Mails</option>
										<option value="2" data-link="{!! route('newtemplatesms') !!}">SMS</option>
										<option value="3" data-link="{!! route('newtemplatetask') !!}">Tâches</option>
										<option value="4" data-link="{!! route('saveremindera') !!}">Process</option>
										<option value="5" data-link="{!! route('newtemplatecondition') !!}">Condition de l'offre</option>
										<option value="6" data-link="{!! route('newtemplatetextoffer') !!}">Texte de l'offre</option>
									</select>
								</div>
							</div>
							<br>
							<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">
										Name
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">
										<input type="text" class="form-control" name="templateName" required>
										<input type="hidden" id="fileTem" class="form-control" name="file" required>
									</div>
								</div>
							<div id="temem">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
										<input type="text" name="templateSubject" placeholder="Objet de mon mail" value="Objet de mon mail" class="form-control" required>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
										<textarea name="templateBody" id="tinyCreateTemplate" data-height="500" data-tiny="tinyCreateTemplate" rows="40">
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
											<img src="{!! asset('img/logo.svg'); !!}" width="252">
										</textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2"><br>
										<label>Email de test</label>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6"><br>
										<input type="text" class="form-control" name="email_test" placeholder="monemaildetest@gmail.com">
									</div>
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2"><br>
										<button type="button" data-send-form="{!! route('sendemailtemplatetest') !!}" class="btn btn-lg btn-success" data-submit-form="form-edit-template">Tester</button>
									</div>
								</div>
							</div>
							<div id="temsms">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
										<textarea name="form_phone_message" class="form-control" rows="3">Cher Monsieur xxx,Afin de traiter au mieux votre dossier, bla bla bla...Bla bla bla
										</textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-3"><br>
										<label>Numéro de test</label>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6"><br>
										<input type="text" class="form-control" name="sms_test" placeholder="0032497532698">
									</div>
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2"><br>
										<button type="submit" data-send-form="{!! route('sendsmstemplatetest') !!}" class="btn btn-lg btn-success" data-submit-form="form-edit-template">Tester</button>
									</div>
								</div>
							</div>
							<div id="temtask"><br>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">
										Rangé dans
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">
										<select name="estatus_task" class="form-control">
											@foreach($categories as $category)
												<option value="{!! $category['name'] !!}">{!! $category['name'] !!}</option>
											@endforeach
										</select>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
										<textarea name="form_phone_message" id="tinyCreateTemplateTask" data-height="500" data-tiny="tinyCreateTemplateTask" rows="40">Ne pas oublier de bla bla…
										</textarea>
									</div>
								</div>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-3"><br>
										<label>Utilisateur test</label>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-6 col-lg-12 col-xl-6"><br>
										<select name="user_test" class="form-control">
											@foreach($users as $user)
												@if($user['type'] == 3)
													<option value="{!! $user['id'] !!}">{!! $user['name'] !!}</option>
												@endif
											@endforeach
										</select>
									</div>
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2"><br>
										<button type="button" class="btn btn-lg btn-success">Tester</button>
									</div>
								</div>
							</div>
							<div id="tempro"><br>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
										<strong>Rappel 1 (le premier rappel est instantané)</strong>
									</div>
								</div><hr>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">
										Type
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">
										<select name="type_rappel[]" id="type_rappel" class="form-control">
											<option value="email">Mail</option>
											<option value="sms">SMS</option>
											<option value="task">Tâches</option>
										</select>
									</div>
								</div><br>
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">
										Template
									</div>
									<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">
										<select name="type_rappel_choised[]" class="form-control" id="rappel_email">
											@foreach($templates as $template)
												@if($template['type'] == 'email')
													<option value="{!! $template['file'] !!}">{!! $template['name'] !!}</option>
												@endif
											@endforeach
										</select>
										<select name="type_rappel_choised[]" class="form-control" id="rappel_sms">
											@foreach($templates as $template)
												@if($template['type'] == 'sms')
													<option value="{!! $template['file'] !!}">{!! $template['name'] !!}</option>
												@endif
											@endforeach
										</select>
										<select name="type_rappel_choised[]" class="form-control" id="rappel_task">
											@foreach($templates as $template)
												@if($template['type'] == 'task')
													<option value="{!! $template['file'] !!}">{!! $template['name'] !!}</option>
												@endif
											@endforeach
										</select>
									</div>
								</div><br>
								<input type="hidden" name="days[]">
								<div id="new_rappels" class="mb-4"></div>
								<div class="text-left">
									<button type="button" id="add_rappel" class="btn btn-success">Ajouter une nouvelle étape</button>
								</div>
							</div>
							<div id="temcon">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
										<textarea name="form_condition" class="form-control" rows="3"> Acquisition non conditionnée à l’obtention d’un crédit bancaire.</textarea>
									</div>
								</div>
							</div>
							<div id="temtext">
								<div class="row">
									<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
										<textarea name="form_text_offer" id="tinyCreateTemplateTextOffer" data-height="400" data-tiny="tinyCreateTemplateTextOffer" rows="40">J'ai ajouté un texte qui sera ajouté au document PDF qui sera généré </textarea>
									</div>
								</div>
								
							</div>
							<br><br>
							
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-template" >Sauvegarder</button>
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
		<div class="modal fade" id="editTemplateEmail{!! $template['id'] !!}" tabindex="-1" aria-labelledby="editTemplateEmailLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('edittemplates', $template['id']) !!}" method="POST" data-form="form-edit-template-email-{!! $template['id'] !!}">
							@csrf()
							<input type="hidden" name="type" value="email">
							<input type="hidden" name="old_name_file" value="{!! $template['file'] !!}">
							<div class="card">
								<div class="card-header">Edit : {!! $template['name'] !!}</div>
								<div class="card-body">
									<div class="row mb-2">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
											<span class="ffhnl">Suject : </span>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
											<input type="text" name="templateSubject" value="{!! $template['subject'] !!}" class="form-control" required>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 mb-2">
											<span class="ffhnl">Corp : </span>
										</div>
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
											<textarea name="templateBody" id="tinyEditTemplateEmail{!! $template['id'] !!}" data-height="500" data-tiny="tinyEditTemplateEmail{!! $template['id'] !!}" rows="40">
												{!! file_get_contents(asset('templates/'.$template['file'])) !!}
											</textarea>
										</div>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-template-email-{!! $template['id'] !!}" >Sauvegarder</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	@endif
	@if($template['type'] == 'sms')
		<div class="modal fade" id="editTemplateSMS{!! $template['id'] !!}" tabindex="-1" aria-labelledby="editTemplateSMSLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('edittemplates', $template['id']) !!}" method="POST" data-form="form-edit-template-sms-{!! $template['id'] !!}">
							@csrf()
							<input type="hidden" name="type" value="sms">
							<input type="hidden" name="old_name_file" value="{!! $template['file'] !!}">
							<input type="hidden" name="templateSubject" value="">
							<div class="card">
								<div class="card-header">Edit : {!! $template['name'] !!}</div>
								<div class="card-body">
									<div class="row mb-3">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
											<textarea name="templateBody" class="form-control" rows="3">{!! file_get_contents(asset('templates/'.$template['file'])) !!}
											</textarea>
										</div>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-template-sms-{!! $template['id'] !!}" >Sauvegarder</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	@endif
	@if($template['type'] == 'task')
		<div class="modal fade" id="editTemplateTask{!! $template['id'] !!}" tabindex="-1" aria-labelledby="editTemplateTaskLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('edittemplates', $template['id']) !!}" method="POST" data-form="form-edit-template-task-{!! $template['id'] !!}">
							@csrf()
							<input type="hidden" name="type" value="task">
							<input type="hidden" name="old_name_file" value="{!! $template['file'] !!}">
							<input type="hidden" name="templateSubject" value="">
							<div class="card">
								<div class="card-header">Edit : {!! $template['name'] !!}</div>
								<div class="card-body">
									<div class="row">
										<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">
											Rangé dans
										</div>
										<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">
											<select name="estatus_task" class="form-control">
												@foreach($categories as $category)
													<option {!! ($template['subject'] == $category['name']) ? 'selected' : '' !!} value="{!! $category['name'] !!}">{!! $category['name'] !!}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
											<textarea name="templateBody" id="tinyEditTemplateTask{!! $template['id'] !!}" data-height="500" data-tiny="tinyEditTemplateTask{!! $template['id'] !!}" rows="40">{!! file_get_contents(asset('templates/'.$template['file'])) !!}
											</textarea>
										</div>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-template-task-{!! $template['id'] !!}" >Sauvegarder</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	@endif
	@if($template['type'] == 'condition')
		<div class="modal fade" id="edit-condition-{!! $template['id'] !!}" tabindex="-1" aria-labelledby="edit-condition-{!! $template['id'] !!}-Label" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('edittemplates', $template['id']) !!}" method="POST" data-form="form-edit-condition-{!! $template['id'] !!}">
							@csrf()
							<input type="hidden" name="type" value="task">
							<input type="hidden" name="old_name_file" value="{!! $template['file'] !!}">
							<input type="hidden" name="templateSubject" value="">
							<div class="card">
								<div class="card-header">Edit : {!! $template['name'] !!}</div>
								<div class="card-body">
									<div class="row mb-3">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
											<textarea name="templateBody" class="form-control" rows="3"> {!! file_get_contents(asset('templates/'.$template['file'])) !!}</textarea>
										</div>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-condition-{!! $template['id'] !!}" >Sauvegarder</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	@endif
	@if($template['type'] == 'text-offer')
		<div class="modal fade" id="edit-texte-offer-{!! $template['id'] !!}" tabindex="-1" aria-labelledby="edit-texte-offer-{!! $template['id'] !!}-Label" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('edittemplates', $template['id']) !!}" method="POST" data-form="form-edit-texte-offer-{!! $template['id'] !!}">
							@csrf()
							<input type="hidden" name="type" value="task">
							<input type="hidden" name="old_name_file" value="{!! $template['file'] !!}">
							<input type="hidden" name="templateSubject" value="">
							<div class="card">
								<div class="card-header">Edit : {!! $template['name'] !!}</div>
								<div class="card-body">
									<div class="row mb-3">
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12"><br>
											<textarea name="templateBody" id="tinyEditTemplateTextOffer{!! $template['id'] !!}" data-height="400" data-tiny="tinyEditTemplateTextOffer{!! $template['id'] !!}" rows="40">{!! file_get_contents(asset('templates/'.$template['file'])) !!}</textarea>
										</div>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-texte-offer-{!! $template['id'] !!}" >Sauvegarder</button>
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
@foreach($reminders as $reminder)
	<div class="modal fade" id="edit-reminder-{!! $reminder['id'] !!}" tabindex="-1" aria-labelledby="editTemplateTaskLabel" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
			<div class="modal-content">
				<div class="modal-body">
					<form action="{!! route('edittemplates', $reminder['id']) !!}" method="POST" data-form="form-edit-reminder-{!! $reminder['id'] !!}">
						@csrf()
						<input type="hidden" name="type" value="task">
						<input type="hidden" name="templateSubject" value="">
						<div class="card">
							<div class="card-header">Edit : {!! $reminder['name'] !!}</div>
							<div class="card-body">
								<div id="content-edit-reminder-{!! $reminder['id'] !!}">
									
								</div>
								@foreach($reminder['reminder'] as $info)
								<?php var_dump($info['template']) ?>
								<div id="rappel-{!! $info['position'] !!}">
									<div class="row" >
										<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
											<strong>Rappel {!! $info['position'] + 1 !!}</strong>
										</div>
									</div><hr>
									<div class="row mb-2">
										<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">
											Type
										</div>
										<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">
											<select name="type_rappel[]" data-edit-process="{!! $reminder['id'] !!}{!! $info['position'] !!}" id="type_rappel{!! $reminder['id'] !!}{!! $info['position'] !!}" class="form-control">
												<option {!! ($info['type_template'] == 'email') ? 'selected' : ''!!} value="email">Mail</option>
												<option {!! ($info['type_template'] == 'sms') ? 'selected' : ''!!} value="sms">SMS</option>
												<option {!! ($info['type_template'] == 'task') ? 'selected' : ''!!} value="task">Tâches</option>
											</select>
										</div>
									</div>
									<div class="row mb-3">
										<div class="col-xs-12 col-sm-12 col-md-2 col-lg-12 col-xl-2">
											Template
										</div>
										<div class="col-xs-12 col-sm-12 col-md-10 col-lg-12 col-xl-10">
											<select name="type_rappel_choised[]" class="form-control {!! ($info['type_template'] == 'email') ? '' : 'd-none'!!}" id="rappel_email{!! $reminder['id'] !!}{!! $info['position'] !!}">
												@foreach($templates as $template)
													@if($template['type'] == 'email')
														<option {!! ($info['template'] == $template['file']) ? 'selected' : '' !!} value="{!! $template['file'] !!}">{!! $template['name'] !!}</option>
													@endif
												@endforeach
											</select>
											<select name="type_rappel_choised[]" class="form-control {!! ($info['type_template'] == 'sms') ? '' : 'd-none'!!}" id="rappel_sms{!! $reminder['id'] !!}{!! $info['position'] !!}">
												@foreach($templates as $template)
													@if($template['type'] == 'sms')
														<option {!! ($info['template'] == $template['file']) ? 'selected' : '' !!} value="{!! $template['file'] !!}">{!! $template['name'] !!}</option>
													@endif
												@endforeach
											</select>
											<select name="type_rappel_choised[]" class="form-control {!! ($info['type_template'] == 'task') ? '' : 'd-none' !!}" id="rappel_task{!! $reminder['id'] !!}{!! $info['position'] !!}">
												@foreach($templates as $template)
													@if($template['type'] == 'task')
														<option {!! ($info['template'] == $template['file']) ? 'selected' : '' !!} value="{!! $template['file'] !!}">{!! $template['name'] !!}</option>
													@endif
												@endforeach
											</select>
										</div>
									</div>
									<div class="text-left mb-4">
										<button type="button" data-id-delete="rappel-{!! $info['position'] !!}" class="btn btn-danger">Supprimer ce rappel</button>
									</div>
								</div>
								@endforeach
								<div class="text-left">
									<button type="button" id="add_rappel{!! $reminder['id'] !!}" class="btn btn-success">Ajouter une nouvelle étape</button>
								</div>
								<div class="text-right">
									<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
									<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-reminder-{!! $reminder['id'] !!}" >Sauvegarder</button>
								</div>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@endforeach
<!-- <div class="modal fade" id="editTemplateEmailEmail" tabindex="-1" aria-labelledby="createTemplateEmailLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('newtemplatemail') !!}" method="POST" data-form="form-edit-template">
					@csrf()
					<span id="tokenTemplate" style="display: none;">{!! csrf_token() !!}</span>
					<div class="card">
						<div class="card-header">Template E-mail</div>
						<div class="card-body">
							<div class="mb-2">
								<input type="text" name="form_template_name" id="form_template_name" class="form-control mb-2" placeholder="Nom du modèle" disabled>
							</div>
							<div class="mb-4">
								<textarea name="form_phone_message" id="tinyCreateTemplateEmail" data-height="500" data-tiny="tinyCreateTemplateEmail" rows="40" disabled>
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
									<div style="border: 1px solid #707070; padding: 20px;">
										<div style="margin-bottom: 15px;"><span id="event_one">Le MERCREDI 10.01.21*: De 09:00 &agrave; 10:00</span> <a style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: #28a745; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 0.9rem; line-height: 1.6; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; border-color: #38c172; margin-left: 15px; text-decoration: none; cursor: pointer;" href="linkOne" id="link_one">Confirmer l&rsquo;horaire</a></div>
										<div style="margin-bottom: 15px;"><span id="event_two">Le MERCREDI 10.01.21*: De 09:00 &agrave; 10:00</span> <a style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: #28a745; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 0.9rem; line-height: 1.6; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; border-color: #38c172; margin-left: 15px; text-decoration: none; cursor: pointer;" href="linkTwo" id="link_two">Confirmer l&rsquo;horaire</a></div>
										<div><span id="event_three">Le MERCREDI 10.01.21*: De 09:00 &agrave; 10:00</span> <a style="display: inline-block; font-weight: 400; color: #fff; text-align: center; vertical-align: middle; -webkit-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; background-color: #28a745; border: 1px solid transparent; padding: 0.375rem 0.75rem; font-size: 0.9rem; line-height: 1.6; border-radius: 0.25rem; transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out; border-color: #38c172; margin-left: 15px; text-decoration: none; cursor: pointer;" href="linkThree" id="link_three">Confirmer l&rsquo;horaire</a></div>
									</div>
									<p>Cordialement, </p>
									<p>{!! Auth::user()->name !!}</p>
									<img src="{!! asset('img/logo.svg'); !!}" width="252">
								</textarea>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-success" data-edit-form="form-edit-template">Edit</button>
								<button type="button" class="btn btn-lg btn-dark" data-cancel-form="form-edit-template">Annuler</button>
								<button type="submit" data-template="{!! route('newtemplatemail') !!}" class="btn btn-lg btn-success" data-submit-hide="true" data-submit-form="form-edit-template">Sauvegarder</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div> -->

<div class="modal fade" id="createTemplateSMS" tabindex="-1" aria-labelledby="createTemplateSMSLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('newtemplatesms') !!}" method="POST" data-form="form-edit-template-sms">
					@csrf()
					<input type="hidden" name="slug-template-name-sms" id="slug-template-name-sms"  value="">
					<input type="hidden" name="type" value="sms">
					<span id="tokenTemplate" style="display: none;">{!! csrf_token() !!}</span>
					<div class="card">
						<div class="card-header">Template SMS</div>
						<div class="card-body">
							<div class="mb-2">
								<input type="text" name="form_template_name" id="name_template_sms" class="form-control mb-2" placeholder="Nom du modèle" disabled>
							</div>
							<div class="mb-4">
								<textarea name="form_phone_message" class="form-control" rows="3" disabled> Cher Monsieur xxx, Veuillez trouver, ci-dessous, notre meilleure offre pour votre bien situé à [address-estate].</textarea>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-success" data-edit-form="form-edit-template-sms">Edit</button>
								<button type="button" class="btn btn-lg btn-dark" data-cancel-form="form-edit-template-sms">Annuler</button>
								<button type="submit" data-template="{!! route('newtemplatemail') !!}" class="btn btn-lg btn-success" data-submit-hide="true" data-submit-form="form-edit-template-sms">Sauvegarder</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="createTemplateCondition" tabindex="-1" aria-labelledby="createTemplateConditionLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('newtemplatecondition') !!}" method="POST" data-form="form-edit-template-condition">
					@csrf()
					<input type="hidden" name="slug-template-name-condition" id="slug-template-name-condition"  value="">
					<input type="hidden" name="type" value="condition">
					<span id="tokenTemplate" style="display: none;">{!! csrf_token() !!}</span>
					<div class="card">
						<div class="card-header">Template Condition Notaire</div>
						<div class="card-body">
							<div class="mb-2">
								<input type="text" name="form_template_name" id="name_template_condition" class="form-control mb-2" placeholder="Nom du modèle" disabled>
							</div>
							<div class="mb-4">
								<textarea name="form_condition" class="form-control" rows="3" disabled> Acquisition non conditionnée à l’obtention d’un crédit bancaire.</textarea>
							</div>
							<div class="text-right">
								<button type="button" class="btn btn-lg btn-success" data-edit-form="form-edit-template-condition">Edit</button>
								<button type="button" class="btn btn-lg btn-dark" data-cancel-form="form-edit-template-condition">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-hide="true" data-submit-form="form-edit-template-condition">Sauvegarder</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>


@foreach($templates as $template)
	@if($template['type'] == 'email' || $template['type'] == 'offer')
		<div class="modal fade" id="editTemplate-{!! $template['id'] !!}" tabindex="-1" aria-labelledby="editTemplateLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('edittemplatemail') !!}" method="POST" data-form="form-edit-template-{!! $template['id'] !!}">
							@csrf()
							<input type="hidden" name="template_id" value="{!! $template['id'] !!}">
							<div class="card">
								<div class="card-header">Edition offre : {!! $template['name'] !!}</div>
								<div class="card-body">
									<div class="mb-2">
										<input type="text" id="form_template_name-{!! $template['id'] !!}" name="form_template_name" class="form-control mb-2" value="{!! $template['name'] !!}" disabled>
									</div>
									<div class="mb-4">
										<textarea name="form_phone_message" id="tinyEditTemplate-{!! $template['id'] !!}" data-height="500" data-tiny="tinyEditTemplate-{!! $template['id'] !!}" rows="40" disabled>
											{!! file_get_contents(asset('templates/'.$template['file'])) !!}
										</textarea>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-success" data-edit-form="form-edit-template-{!! $template['id'] !!}">Edit</button>
										<button type="button" class="btn btn-lg btn-dark" data-cancel-form="form-edit-template-{!! $template['id'] !!}">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-hide="true" data-url="{!! route('edittemplatemail') !!}" data-formeditT="{!! $template['id'] !!}" data-submit-form="form-edit-template-{!! $template['id'] !!}" data-csrf="{!! csrf_token() !!}">Sauvegarder</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	@endif
	@if($template['type'] == 'sms' || $template['type'] == 'condition')
		<div class="modal fade" id="editTemplate-{!! $template['id'] !!}" tabindex="-1" aria-labelledby="editTemplateLabel" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
				<div class="modal-content">
					<div class="modal-body">
						<form action="{!! route('edittemplatesms') !!}" method="POST" data-form="form-edit-template-{!! $template['id'] !!}">
							@csrf()
							<input type="hidden" name="template_id" value="{!! $template['id'] !!}">
							<input type="hidden" name="old_name_template" value="{!! $template['name'] !!}">
							<div class="card">
								<div class="card-header">Edition offre : {!! $template['name'] !!}</div>
								<div class="card-body">
									<div class="mb-2">
										
										<input type="text" name="template_name" class="form-control mb-2" value="{!! $template['name'] !!}" disabled>
										<input type="hidden" name="file" value="{!! $template['file'] !!}">
									</div>
									<div class="mb-4">
										<textarea name="text_of_message" class="form-control" rows="3" disabled>{!! file_get_contents(asset('templates/'.$template['file'])) !!}</textarea>
									</div>
									<div class="text-right">
										<button type="button" class="btn btn-lg btn-success" data-edit-form="form-edit-template-{!! $template['id'] !!}">Edit</button>
										<button type="button" class="btn btn-lg btn-dark" data-cancel-form="form-edit-template-{!! $template['id'] !!}">Annuler</button>
										<button type="submit" class="btn btn-lg btn-success" data-submit-hide="true"  data-submit-form="form-edit-template-{!! $template['id'] !!}" data-csrf="{!! csrf_token() !!}">Sauvegarder</button>
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
<div class="modal fade" id="newUser" tabindex="-1" aria-labelledby="newUserLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('newuser') !!}" method="POST" data-form="form-new-user" data-clean="false">
					@csrf
					<div class="card">
						<div class="card-header">Nouvel utilisateur</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Nom</label>
									<input type="text" name="name" class="form-control" required>
									<label class="ffhn">Prénom</label>
									<input type="text" name="firstname" class="form-control" required>
									<label class="ffhn">E-mail</label>
									<input type="email" name="email" class="form-control" required>
									<label class="ffhn">Google Agenda</label>
									<input type="email" name="google_email" class="form-control" required>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Login</label>
									<input type="text" name="username" class="form-control" required>
									<label class="ffhn">Mot de passe</label>
									<div class="wrapper__password">
										<input type="password" name="password" class="form-control" required>
										<span data-password="password" data-icon="bi bi-eye-slash-fill"><i class="bi bi-eye-fill"></i></span>
									</div>
									<label class="ffhn">Groupes</label>
									<select  name="type" id="type_user" class="form-control" type="select" required>
										<option value="">Groupe</option>
										@foreach($userTypes as $userType)
											<option value="{!! $userType->id !!}">{!! $userType->name !!}</option>
										@endforeach
									</select>
									<div id="manager_id">
										<label class="ffhn">Choisissez votre gestionnaire de zone</label>
										<select name="manager_id" class="form-control" type="select">
											<option value="0"></option>
											@foreach($users as $user)
												@if($user['type'] == '2')
													<option value="{!! $user['id'] !!}">{!! $user['name'] !!}</option>
												@endif
											@endforeach
										</select>
									</div>
									<div id="agents_id">
										<label class="ffhn">Vos agents</label>
										<select name="agent_id[]" class="form-control" type="select" multiple>
											<option value="0"></option>
											@foreach($users as $user)
												@foreach($agentsW as $agent)
													@if($user['id'] == $agent)
														<option value="{!! $agent !!}">{!! $user['name'] !!}</option>
													@endif
												@endforeach
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<div class="text-right mt-4">
								<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-hide="false" data-submit-form="form-new-user">Sauvegarder</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="newNotary" tabindex="-1" aria-labelledby="newNotaryLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('newnotary') !!}" method="POST" data-form="form-new-notary" data-clean="false">
					@csrf()
					<div class="card">
						<div class="card-header">Nouvel notaire</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Titre</label>
									<input type="text" name="title" class="form-control" required>
									<label class="ffhn">Nom</label>
									<input type="text" name="name" class="form-control" required>
									<label class="ffhn">Prénom</label>
									<input type="text" name="firstname" class="form-control" required>
									<label class="ffhn">Adresse</label>
									<input type="text" name="address" class="form-control" required>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Téléphone</label>
									<input type="text" name="phone" class="form-control" required>
									<label class="ffhn">E-mail</label>
									<input type="text" name="email" class="form-control" required>
									<label class="ffhn">Clé</label>
									<input type="text" name="key" class="form-control" required>
								</div>
							</div>
							<div class="text-right mt-4">
								<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-hide="false" data-submit-form="form-new-notary">Sauvegarder</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@foreach($notaries as $notary)
<div class="modal fade" id="editNotary{!! $notary['id'] !!}" tabindex="-1" aria-labelledby="editNotaryLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('editnotary') !!}" method="POST" data-form="form-edit-notary-{!! $notary['id'] !!}" data-clean="false">
					@csrf
					<input type="hidden" name="notary_id" value="{!! $notary['id'] !!}">
					<div class="card">
						<div class="card-header">Ficher notary: <span data-user-name>Notary</span> </div>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Titre</label>
									<input type="text" name="title" class="form-control" value="{!! $notary['title'] !!}" required>
									<label class="ffhn">Nom</label>
									<input type="text" name="lastname" class="form-control" value="{!! $notary['lastname'] !!}" required>
									<label class="ffhn">Prénom</label>
									<input type="text" name="name" class="form-control" value="{!! $notary['name'] !!}" required>
									<label class="ffhn">Adresse</label>
									<input type="text" name="address" class="form-control" value="{!! $notary['address'] !!}" required>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Téléphone</label>
									<input type="text" name="phone" class="form-control" value="{!! $notary['phone'] !!}" required>
									<label class="ffhn">E-mail</label>
									<input type="text" name="email" class="form-control" value="{!! $notary['email'] !!}" required>
									<label class="ffhn">Clé</label>
									<input type="text" name="key" class="form-control" value="{!! $notary['key'] !!}" required>
								</div>
							</div>
							@if(Auth::user()->type == 1 || Auth::user()->type == 2)
							<div class="text-right mt-4">
								<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-notary-{!! $notary['id'] !!}">Sauvegarder</button>
							</div>
							@endif
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
@endforeach

<div class="modal fade" id="editUser" tabindex="-1" aria-labelledby="editUserLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('edituser') !!}" method="POST" data-form="form-edit-user" data-clean="false">
					@csrf
					<input type="hidden" name="id" value="">
					<input type="hidden" id="typeUser" value="{!! Auth::user()->type !!}">
					<div class="card">
						<div class="card-header">Ficher utilisateur: <span data-user-name>Username</span> 1</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Nom</label>
									<input type="text" name="name" class="form-control" required>
									<label class="ffhn">Prénom</label>
									<input type="text" name="firstname" class="form-control"  required>
									<label class="ffhn">E-mail</label>
									<input type="email" name="email" class="form-control"  required>
									<label class="ffhn">Google Agenda</label>
									<input type="email" name="google_email" class="form-control"  required>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<div data-ed-us>
										<label class="ffhn">Login</label>
										<input type="text" name="login" class="form-control"  required>
										<label class="ffhn">Entrer un nouveau mot de passe</label>
										<div class="wrapper__password">
											<input type="password" name="new_password" class="form-control" >
											<span data-password="new_password" data-icon="bi bi-eye-slash-fill"><i class="bi bi-eye-fill"></i></span>
										</div>
										<label class="ffhn">Saisissez à nouveau le mot de passe</label>
										<div class="wrapper__password">
											<input type="password" name="new_password_confirm" class="form-control" >
											<span data-password="new_password_confirm" data-icon="bi bi-eye-slash-fill"><i class="bi bi-eye-fill"></i></span>
										</div>
									</div>
									<label class="ffhn">Groupes</label>
									<select  name="type" class="form-control" type="select"  required>
										<option value="">Groupe</option>
										@foreach($userTypes as $userType)
											<option value="{!! $userType->id !!}">{!! $userType->name !!}</option>
										@endforeach
									</select>
									<div id="choose_agents">
										<label class="ffhn">Choisissez votre agent</label>
										<select name="agent_id[]" id="agents" class="form-control" type="select" multiple >
											<option value="0"></option>
											@foreach($users as $user)
												@foreach($agentsW as $agent)
													@if($user['id'] == $agent)
														<option value="{!! $agent !!}">{!! $user['name'] !!}</option>
													@endif
												@endforeach
											@endforeach
										</select>
									</div>
								</div>
							</div>
							<input type="hidden" id="currentUser" value="{!! Auth::user()->id !!}">
							<div class="text-right mt-4" data-ed-us>
								<button type="submit" class="btn btn-lg btn-success" data-submit-form="form-edit-user">Sauvegarder</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="newCategory" tabindex="-1" aria-labelledby="newCategoryLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('newcategory') !!}" method="POST" data-form="form-new-category" data-clean="false">
					@csrf
					<div class="card">
						<div class="card-header">Nouvelle catégorie</div>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Nom</label>
									<input type="text" name="name" data-category="new-category" class="form-control" placeholder="">
									<label class="ffhn">Slug</label>
									<input type="text" name="slug" data-slug="new-category" class="form-control" placeholder="">
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Parent</label>
									<select name="parent" class="form-control">
										<option value="0">Parent</option>
										@foreach($categories as $category)
											@if($category['parent'] == 0)
												<option value="{!! $category['id'] !!}">{!! $category['name'] !!}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="text-right mt-4">
								<button type="button" class="btn btn-lg btn-dark" data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-hide="false" data-submit-form="form-new-category">Sauvegarder</button>
							</div>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="editCategory" tabindex="-1" aria-labelledby="editCategoryLabel" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
		<div class="modal-content">
			<div class="modal-body">
				<form action="{!! route('editcategory') !!}" method="POST" data-form="form-edit-category" data-clean="false">
					@csrf
					<input type="hidden" name="idCategory" value="">
					<div class="card">
						<div class="card-header">Ficher catégorie: <span data-category-name>Category</span></div>
						<div class="card-body">
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Nom</label>
									<input type="text" name="name" data-category="edit-category" class="form-control" value="" disabled>
									<label class="ffhn">Slug</label>
									<input type="text" name="slug" data-slug="edit-category" class="form-control" value="" disabled>
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
									<label class="ffhn">Parent</label>
									<select name="parent" class="form-control" disabled>
										<option value="0">Parent</option>
										@foreach($categories as $category)
											@if($category['parent'] == 0)
												<option value="{!! $category['id'] !!}">{!! $category['name'] !!}</option>
											@endif
										@endforeach
									</select>
								</div>
							</div>
							<div class="text-right mt-4">
								<button type="button" class="btn btn-lg btn-success" data-edit-form="form-edit-category">Editer</button>
								<button type="button" class="btn btn-lg btn-dark" data-cancel-form="form-edit-category" data-dismiss="modal">Annuler</button>
								<button type="submit" class="btn btn-lg btn-success" data-submit-hide="false" data-submit-form="form-edit-category">Sauvegarder</button>
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
		aUsers = {!! json_encode($users); !!}; // Varible to obtain the information directly in javascript when a editUser will be updated
		aCategories = {!! json_encode($categories); !!}; // Varible to obtain the information directly in javascript when a editUser will be updated
		aTemplates = {!! json_encode($templates); !!}; // Varible to obtain the information directly in javascript when a editUser will be updated
		aAgentManager = {!! json_encode($agentManager); !!}; // Varible to obtain the information directly in javascript when a editUser will be updated
		aReminders = {!! json_encode($reminders) !!}
	</script>
@endsection