<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="content-type" content="text/html; charset=us-ascii">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	</head>
	<img src="{!! $historic['header'] !!}" width="100%">
		<div align="center"><img src="{!! $historic['img_logo'] !!}" style="opacity: 0.5"></div>
		<br><br>
		<blockquote type="cite">
			<div dir="ltr">
					@if(!empty($historic['localisation']) || $historic['localisation'] != '')
						<h2>Localisation</h2>
						{!! $historic['localisation'] !!}
					@endif
					@if(!empty($historic['seller']) || $historic['seller'] != '')
						<h2>Requéreur</h2>
						{!! $historic['seller'] !!}
					@endif
					@if(!empty($historic['assign']) || $historic['assign'] != '')
						<h2>A assigner à</h2>
						{!! $historic['assign'] !!}
					@endif
					@if(!empty($historic['cherche']) || $historic['cherche'] != '')
						<h2>Cherche</h2>
						{!! $historic['cherche'] !!}
					@endif
					@if(!empty($historic['comment']) || $historic['comment'] != '')
						<h2>Commentaire</h2>
						{!! $historic['comment'] !!}
					@endif
					@if(!empty($historic['description']) || $historic['description'] != '')
						<h2>Le bien - description</h2>
						{!! $historic['description'] !!}
					@endif
					@if(!empty($historic['interior']) || $historic['interior'] != '')
						<h2>Composition : Intérieur</h2>
						{!! $historic['interior'] !!}
					@endif
					@if(!empty($historic['exterior']) || $historic['exterior'] != '')
						<h2>Composition : Extérieur</h2>
						{!! $historic['exterior'] !!}
					@endif
					@if(!empty($historic['problems']) || $historic['problems'] != '')
						<h2>Problèmes signalés par le requéreur</h2>
						{!! $historic['problems'] !!}
					@endif
					@if(!empty($historic['remarks']) || $historic['remarks'] != '')
						<h2>Remarques visite</h2>
						{!! $historic['remarks'] !!}
					@endif
					@if(!empty($historic['offer']) || $historic['offer'] != '')
						<h2>Offre</h2>
						{!! $historic['offer'] !!}
					@endif
				@if($historic['validpd'] == 1)
					@if(!empty($historic['photos']) || $historic['photos'] != '')
						<h2>Photographies du bien et documents</h2>
						<h4>Photos</h4>
						@foreach($historic['photos'] as $photo)
							<img src="{!! $photo !!}" width="300px">
						@endforeach
						<h4>Documents</h4>
						@foreach($historic['documents'] as $document)
							{!! $document !!} <br>
						@endforeach
					@endif
				@endif
			</div>
		</blockquote>
	</body>
	<footer style="position: fixed;text-align: center;bottom: 30px; height: 1px;right: 0px;;opacity: 0.5;">
		<img src="{!! $historic['footer'] !!}" width="100%">
		WE SOLD SA <br>
		Avenue Louise 375/7 – 1050 IXELLES <br>
		Email : info@wesold.immo <br>
	</footer>
</html>
