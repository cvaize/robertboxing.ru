<div class='row'>
	<div class='col-lg'>
		{{ Form::open(['route' => $route, 'method' => 'GET', 'id' => 'search-panel']) }}
		<div class="form-group form-group-custom row mb-0">
			<label for="staticEmail" class="col-lg-1 col-form-label ">Поиск:</label>
			<div class="input-group input-group-custom col-lg-6 mb-lg-0 mb-2 ">
				<input type="text" class="form-control" id="search" name='search' aria-describedby="search-append" placeholder='{{ $placeholder }}' value='{{ $frd['search'] ?? null }}'>
				<div class="input-group-append" onclick='event.preventDefault(); document.getElementById("search-panel").submit();'>
					<span class="input-group-text" id="search-append">
						<i class="fas fa-search fa"></i>
					</span>
				</div>
			</div>
			<div class="form-group col-lg-3 mb-0" style='display: {{ $selectFeedback ? 'flex' : 'none' }}'>
				<select id="status" name='status_id' class="form-control" title='Статус отзыва'>
					<option {{ (!isset($frd['status_id'])) ? 'selected' : '' }} disabled>Статус</option>
					<option {{ (isset($frd['status_id']) && intval($frd['status_id']) === 0) ? 'selected' : '' }} value='0'>Все</option>
					<option {{ (isset($frd['status_id']) && intval($frd['status_id']) === 1) ? 'selected' : '' }} value='1'>В обработке</option>
					<option {{ (isset($frd['status_id']) && intval($frd['status_id']) === 2) ? 'selected' : '' }} value='2'>Одобрено</option>
					<option {{ (isset($frd['status_id']) && intval($frd['status_id']) === 3) ? 'selected' : '' }} value='3'>Не одобрено</option>
				</select>
			</div>
			<div class='form-group button--search col-lg-2 mb-0 d-flex flex-column'>
				<button class='btn btn-outline-primary' type='submit'>Найти</button>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>