@extends('admin.layout')
@section('content')

	<!-- Start main -->
	<main class="main">

		<!-- Start sidebar -->
	@include('admin.blocks.left-menu')
		<!-- End sidebar -->

		<!-- Start content -->
		<div class="base">
			<div class="base__wrapper base__wrapper--promo">
					<div class="table-base__checker checker">
						<p class="checker__text">Выбрано <span class="check-counter">10</span> записей</p>
						<a class="checker__all">Выбрать все записи<span></span></a>
						<a class="checker__cancel">Отменить выделение<span></span></a>
					</div>
				<form action="{{ route(Route::currentRouteName())  }}" style="display:none;" class="sorted-form">
					<input type="text" name="sort">
				</form>
					<table id="table1-top" class="table-base">
						<thead>
							<th class="table-base-col1">
								<label class="table-checkbox">
									<input type="checkbox" class="table-checkbox__inp table-check--all">
									<span class="table-checkbox__check--all"></span>
								</label>
							</th>
							<th class="table-base-col2 table-sort"
								data-order="{{ isset($sort) && $sort && isset($sort[0]) && $sort[0]->order == 'desc' ? 'desc' : 'asc' }}" data-column="id">ID<span></span></th>
							<th class="table-base-col3">Промокод</th>
							<th class="table-base-col4 table-sort" data-order="{{ isset($sort) && $sort && isset($sort[1]) && $sort[1]->order == 'desc' ? 'desc' : 'asc' }}" data-column="price">Сумма<span></span></th>
						</thead>
					</table>
					<div class="nano table__scroll">
						<div class="nano-content">
							<table id="table1-top" class="table-base">
								<tbody>
								@foreach($promocodes as $promocode)
									<tr>
											<td class="table-base-col1">
												<label class="table-checkbox">
													<input type="checkbox" class="table-checkbox__inp table-check--simple">
													<span class="table-checkbox__check"></span>
												</label>
											</td>
											<td class="table-base-col2">{{ $promocode->id }}</td>
											<td class="table-base-col3">{{ $promocode->code }}</td>
											<td class="table-base-col4">{{ $promocode->price }}</td>
										</tr>
								@endforeach
								</tbody>
							</table>
						</div>
					</div>
					<div class="base__bottom">

						<!-- Start paginations -->
						<div class="paginations">
							{{ $promocodes->appends($arraySort)->links() }}
							<p class="paginations__info">Показано от {{ ($promocodes->currentPage() * $paginate) - $paginate + 1}} до
								{{ $promocodes->total() < $paginate ? $promocodes->total() : $paginate}} из {{ $promocodes->total() }} записей</p>
						</div>
						<!-- End paginations -->

					</div>
					

			</div>
		</div>
		<!-- End content -->

	</main>
	<!-- End main -->
@endsection

