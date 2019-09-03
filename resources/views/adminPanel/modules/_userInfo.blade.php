<?php
$developersIds = [
	1, 25
];
?>

@if (\in_array(Auth::user()->getKey(), $developersIds, true))
	<div class='col-lg-12 col-md-12 col-sm-12 col-12 mt-2 py-3 border-top'>
		<div class='row'>
			<div class='col-lg-3 mb-lg-0 col-md-12 mb-md-3 col-sm-12 mb-sm-3 col-12 mb-3 text-center'>{{ $obj->getUserIp() ?? 'IP не определен' }}</div>
			<div class='col-lg col-md-12 col-sm-12 col-12 text-center'>{{ $obj->getUserAgent() ?? 'Данные не определены'}}</div>
		</div>
	</div>
@endif