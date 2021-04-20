@extends('layouts.app')

@section('content')
	<?php setlocale(LC_TIME, "fr_FR"); ?>
	@php
		$classNavActive = ' active';
		$classTabActive = ' show active';
	@endphp
	<nav>
		<ul class="nav nav-tabs bg" id="estatesTab" role="tablist">
			@foreach($categories as $cat)
				<li class="nav-item" role="presentation">
					<a href="#nav-{!! $cat['slug'] !!}" class="nav-link{!! ($category === $cat['slug']) ? $classNavActive : '' !!}" id="tab-category-{!! $cat['slug'] !!}" data-toggle="tab" role="tab" aria-controls="nav-{!! $cat['slug'] !!}" aria-selected="true">{!! $cat['name'] !!}{!! ($cat['count'] !== 0) ? '('.$cat['count'].')' : '' !!}</a>
				</li>
			@endforeach
		</ul>
	</nav>
	<div class="tab-content pt-4 bg wrapper__table" id="estatesTabContent">
		@foreach($categories as $cat)
			<div class="tab-pane fade{!! ($category === $cat['slug']) ? $classTabActive : '' !!}" id="nav-{!! $cat['slug'] !!}" role="tabpanel" aria-labelledby="tab-category-{!! $cat['slug'] !!}">
				@if($cat['has_child'] === 1 || $cat['id'] === 1)
					<nav>
						<ul class="nav nav-tabs bg" id="category-{!! $cat['slug'] !!}-tab" role="tablist">
							@if($cat['id'] === 1)
								<li class="nav-item" role="presentation">
									<a href="#nav-sub-category-all-{!! $cat['slug'] !!}" class="nav-link{!! ($subCategory == 'all') ? $classNavActive : '' !!}" id="tab-sub-category-all-{!! $cat['slug'] !!}" data-toggle="tab" role="tab" aria-controls="nav-sub-category-all-{!! $cat['slug'] !!}" aria-selected="false">Tous</a>
								</li>
							@endif
							@foreach($subCategories as $subCat)
								@if($subCat['parent'] === $cat['id'])
									<li class="nav-item" role="presentation">
										<a href="#nav-sub-category-{!! $subCat['slug'] !!}-{!! $cat['slug'] !!}" class="nav-link{!! ($subCategory == $subCat['slug']) ? $classNavActive : '' !!}" id="tab-sub-category-{!! $subCat['slug'] !!}-{!! $cat['slug'] !!}" data-toggle="tab" role="tab" aria-controls="nav-sub-category-{!! $subCat['slug'] !!}-{!! $cat['slug'] !!}" aria-selected="false">{!! $subCat['name'] !!}{!! ($cat['count'] !== 0) ? '('.$cat['count'].')' : '' !!}</a>
									</li>
								@endif
							@endforeach
						</ul>
					</nav>
					<div class="tab-content py-5 px-3 bg bg--darker" id="category-{!! $cat['slug'] !!}-all-content">
						@if($cat['id'] === 1)
							<div class="tab-pane fade{!! ($subCategory === 'all') ? $classTabActive : '' !!}" id="nav-sub-category-all-{!! $cat['slug'] !!}" role="tabpanel" aria-labelledby="tab-sub-category-all-{!! $cat['slug'] !!}">
								<table data-table="all" class="display responsive nowrap" style="width: 100%;">
									<thead>
										<tr class="bg">
											<th></th>
											<th></th>
											<th class="table-order">Dossier</th>
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
										</tr>
									</thead>
									<tbody>
										@foreach($estates as $estate)
											<tr>
												<td></td>
												<td>
													<a href="{!! route('estate', $estate['id']) !!}" class="btn btn-primary">Détails</a>
												</td>
												<td><?php echo date('ymdh.i', strtotime($estate['reference'])) ?></td>
												<td>{!! $estate['name']!!}</td>
												<td>{!! strftime('%a', strtotime($estate['created_at'])) !!} {!! date('d-m-Y', strtotime($estate['created_at'])) !!}</td>
												<td>{!! date('h:i', strtotime($estate['created_at'])) !!}</td>
												<td>{!! $estate['type_estate'] !!}</td>
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
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>
						@endif
						@foreach($subCategories as $subCat)
							@if($subCat['parent'] === $cat['id'])
								<div class="tab-pane fade{!! ($subCategory === $subCat['slug']) ? $classTabActive : '' !!}" id="nav-sub-category-{!! $subCat['slug'] !!}-{!! $cat['slug'] !!}" role="tabpanel" aria-labelledby="tab-sub-category-{!! $subCat['slug'] !!}-{!! $cat['slug'] !!}">
									<table data-table="{!! $subCat['slug'] !!}" class="display responsive nowrap" style="width: 100%;">
										<thead>
											<tr class="bg">
												<th></th>
												<th></th>
												<th class="table-order">Dossier</th>
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
											</tr>
										</thead>
										<tbody>
											@foreach($estates as $estate)
												@if($estate['category'] === $subCat['id'])
													<tr>
														<td></td>
														<td>
															<a href="{!! route('estate', $estate['id']) !!}" class="btn btn-primary">Détails</a>
														</td>
														<td><?php echo date('ymdh.i', strtotime($estate['reference'])) ?></td>
														<td>{!! $estate['name']!!}</td>
														<td>{!! strftime('%a', strtotime($estate['created_at'])) !!} {!! date('d-m-Y', strtotime($estate['created_at'])) !!}</td>
														<td>{!! date('h:i', strtotime($estate['created_at'])) !!}</td>
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
													</tr>
												@endif
											@endforeach
										</tbody>
									</table>
								</div>
							@endif
						@endforeach
					</div>
				@else
					<div class="tab-content py-5 px-3 bg bg--darker">
						<table data-table="{!! $cat['slug'] !!}" class="display responsive nowrap" style="width: 100%;">
							<thead>
								<tr class="bg">
									<th></th>
									<th></th>
									<th class="table-order">Dossier</th>
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
								</tr>
							</thead>
							<tbody>
								@foreach($estates as $estate)
									@if($estate['category'] === $cat['id'])
										<tr>
											<td></td>
											<td>
												<a href="{!! route('estate', $estate['id']) !!}" class="btn btn-primary">Détails</a>
											</td>
											<td><?php echo date('ymdh.i', strtotime($estate['reference'])) ?></td>
											<td>{!! $estate['name']!!}</td>
											<td>{!! strftime('%a', strtotime($estate['created_at'])) !!} {!! date('d-m-Y', strtotime($estate['created_at'])) !!}</td>
												<td>{!! date('h:i', strtotime($estate['created_at'])) !!}</td>
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
										</tr>
									@endif
								@endforeach
							</tbody>
						</table>
					</div>
				@endif
			</div>
		@endforeach
	</div>
@endsection