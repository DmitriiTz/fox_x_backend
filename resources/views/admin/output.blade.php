@extends('admin.layout')
@section('content')
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
					<table id="table1-top" class="table-base table-base--nine">
						<thead>
							<th class="table-base-col1">
								<label class="table-checkbox">
									<input type="checkbox" class="table-checkbox__inp table-check--all">
									<span class="table-checkbox__check--all"></span>
								</label>
							</th>
							<th class="table-base-col2 table-sort" data-order="{{ isset($sort) && $sort && isset($sort[0]) && $sort[0]->order == 'desc' ? 'desc' : 'asc' }}" data-column="id">ID<span></span></th>
							<th class="table-base-col3">Пользователь</th>
							<th class="table-base-col4 table-sort" data-order="{{ isset($sort) && $sort && isset($sort[0]) && $sort[0]->order == 'desc' ? 'desc' : 'asc' }}" data-column="payment_system_id">Система<span></span></th>
							<th class="table-base-col5 table-sort" data-order="{{ isset($sort) && $sort && isset($sort[0]) && $sort[0]->order == 'desc' ? 'desc' : 'asc' }}" data-column="phone">Реквизиты<span></span></th>
							<th class="table-base-col6">Баланс</th>
							<th class="table-base-col7 table-sort" data-order="{{ isset($sort) && $sort && isset($sort[0]) && $sort[0]->order == 'desc' ? 'desc' : 'asc' }}" data-column="price">Сумма к выводу<span></span></th>
							<th class="table-base-col8 table-sort" data-order="{{ isset($sort) && $sort && isset($sort[0]) && $sort[0]->order == 'desc' ? 'desc' : 'asc' }}" data-column="status_id">Статус вывода<span></span></th>
							<th class="table-base-col9">Управление<span></span></th>
						</thead>
						
					</table>
					<div class="nano table__scroll">
						<div class="nano-content">
							<table id="table1-body" class="table-base table-base--nine">
								<tbody>
								@foreach($applications as $application)
									<tr>
										<td class="table-base-col1">
											<label class="table-checkbox">
												<input type="checkbox" name="outputId[]" value="{{ $application->id }}" class="table-checkbox__inp table-check--simple">
												<span class="table-checkbox__check"></span>
											</label>
										</td>
										<td class="table-base-col2">{{ $application->id }}</td>
										@if($application->account->vkontakte_id)
											<td class="table-base-col3"><a target="_blank" href="https://vk.com/id{{ $application->account->vkontakte_id }}">
													{{ $application->account->name.' '.$application->account->last_name }}</a></td>
										@else
											<td class="table-base-col3">{{ $application->account->name.' '.$application->account->last_name }}</td>
										@endif
										<td class="table-base-col4">{{ $application->payment_system->name }}</td>
										<td class="table-base-col5">{{ $application->phone }}</td>
										<td class="table-base-col6">{{ getBalance($application->account) / 10 }}</td>
										<td class="table-base-col7">{{ $application->price }}</td>
										<td class="table-base-col8">
											@if($application->status_id == 4)
												Готово
											@else
												  <select name="status_id">
                                                    <option>Выбор</option>
                                                    <option @if($application->status_id == 2) selected @endif value="2" class="bgn-text bgn-text--orange">Ожидает</option>
                                                    <option @if($application->status_id == 1) selected @endif value="1" class="bgn-text bgn-text--blue">К выводу</option>
                                                    <option @if($application->status_id == 5) selected @endif value="5" class="bgn-text bgn-text--green">Согласовано</option>
                                                    <option @if($application->status_id == 6) selected @endif value="6" class="bgn-text bgn-text--gray">Перевод</option>
                                                    <option @if($application->status_id == 7) selected @endif value="7" class="bgn-text bgn-text--darkblue">Выплачено</option>
                                                    <option @if($application->status_id == 3) selected @endif value="3" class="bgn-text bgn-text--red">Отказано</option>

                                                    <option @if($application->status_id == 8) selected @endif value="8" class="bgn-text bgn-text--purple">Заморожено</option>

                                                    <option @if($application->status_id == 9) selected @endif value="9" class="bgn-text bgn-text--yellow">Сбой</option>
                                                    
                                                    {{--<option @if($application->status_id == 4) selected @endif value="4" class="bgn-text bgn-text--green">Готово</option>--}}
                                                </select>
											@endif
										</td>
										<td class="table-base-col9">
											<a class="table-base__edit" onclick="MainFunction.getInfoOutputUser('{{ $application->account->id }}', '{{ $application->id }}');">
												<svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
													<path d="M19.0474 0.952381C17.7768 -0.31746 15.7102 -0.31746 14.4396 0.952381L1.38535 14.0063C1.37919 14.0124 1.37557 14.02 1.37015 14.0265C1.36254 14.0352 1.35603 14.0446 1.34915 14.054C1.32996 14.0808 1.31512 14.1094 1.3039 14.1406C1.30064 14.15 1.29594 14.1583 1.2934 14.1677C1.29232 14.1713 1.29014 14.1749 1.28906 14.1786L0.00941183 19.554C0.00361993 19.5783 0.00217196 19.6029 0.00144797 19.6272C0.00144797 19.6319 0 19.6362 0 19.6409C0.000361993 19.6822 0.00941183 19.7224 0.0235296 19.7611C0.0267875 19.7701 0.0304074 19.7781 0.0343894 19.7868C0.052127 19.8255 0.0742087 19.8628 0.10534 19.8939C0.139729 19.9283 0.180273 19.9548 0.224074 19.9729C0.267875 19.9909 0.314572 20 0.361269 20C0.389143 20 0.417378 19.9967 0.44489 19.9902L5.82049 18.7106C5.83027 18.7084 5.83895 18.703 5.84837 18.6998C5.86067 18.6958 5.87226 18.6911 5.8842 18.6856C5.91027 18.6737 5.93452 18.6588 5.95696 18.6411C5.96565 18.6342 5.9747 18.6292 5.98303 18.6216C5.98592 18.6187 5.98954 18.6172 5.99244 18.6143L19.047 5.56008C20.3176 4.28952 20.3176 2.22258 19.0474 0.952381ZM18.5355 1.46423C19.4361 2.36521 19.5129 3.77984 18.7708 4.77168L15.228 1.22894C16.2202 0.486869 17.6349 0.56361 18.5355 1.46423ZM16.7436 6.83969L13.1599 3.25605L13.6718 2.7442L17.2555 6.32785L16.7436 6.83969ZM1.79585 18.2035C1.65431 18.0619 1.42553 18.0619 1.28399 18.2035L1.00996 18.4775L1.93449 14.5941L3.53523 14.4164L3.32925 16.2705C3.32781 16.2842 3.33287 16.2969 3.33287 16.3103C3.33287 16.324 3.32781 16.3364 3.32925 16.3501C3.3307 16.3621 3.33686 16.3718 3.33939 16.3834C3.34373 16.4044 3.34989 16.4236 3.35749 16.4431C3.3669 16.4667 3.37812 16.488 3.39188 16.5087C3.4031 16.5253 3.41432 16.5402 3.42808 16.5546C3.44545 16.5731 3.46428 16.5887 3.48491 16.6031C3.5012 16.6144 3.51677 16.6249 3.53487 16.6335C3.55876 16.6448 3.58373 16.6513 3.6098 16.6574C3.62355 16.6607 3.63478 16.6683 3.64889 16.6701C3.66193 16.6716 3.67496 16.6723 3.68835 16.6723C3.70138 16.6723 3.71622 16.6716 3.72926 16.6701L5.58339 16.4641L5.40565 18.0648L1.52182 18.9893L1.79585 18.7153C1.93703 18.5741 1.93703 18.345 1.79585 18.2035ZM6.33887 16.2202L14.4396 8.11931C14.5811 7.97777 14.5811 7.749 14.4396 7.60746C14.298 7.46593 14.0692 7.46593 13.9277 7.60746L5.82701 15.7083L4.09921 15.9002L4.29107 14.1724L12.3925 6.07193C12.534 5.93039 12.534 5.70162 12.3925 5.56008C12.2509 5.41854 12.0222 5.41854 11.8806 5.56008L3.77921 13.6606L2.62699 13.7887L12.648 3.7679L16.2318 7.35154L6.21072 17.3724L6.33887 16.2202ZM17.7674 5.816L14.1836 2.23236L14.6958 1.72015L18.2796 5.3038L17.7674 5.816Z" fill="black" fill-opacity="0.5"/>
												</svg>
											</a>
										</td>
									</tr>
								@endforeach

								</tbody>
							</table>
						</div>
					</div>
					<div class="base__bottom">

						<!-- Start paginations -->
						<div class="paginations">
							{{ $applications->appends(['search' => $search,'sort' => (json_encode($sort) != 'null') ? json_encode($sort) : ''])->links() }}
							<p class="paginations__info">Показано от {{ ($applications->currentPage() * $paginate) - $paginate + 1}} до
								{{ $applications->total() < $paginate ? $applications->total() : $paginate}} из {{ $applications->total() }} записей</p>
						</div>
						<!-- End paginations -->

						<button class="base__save" onclick="MainFunction.saveStatusUser();">Сохранить</button>

					</div>
					

			</div>
		</div>
		<!-- End content -->

	</main>
	<div class="wrap-modal-user">

	</div>

@endsection