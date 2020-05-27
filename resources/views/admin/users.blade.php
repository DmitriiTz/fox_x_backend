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
					<table id="table1-top" class="table-base table-base--ten">
						<thead>
							<th class="table-base-col1">
								<label class="table-checkbox">
									<input type="checkbox" class="table-checkbox__inp table-check--all">
									<span class="table-checkbox__check--all"></span>
								</label>
							</th>
							<th class="table-base-col2">ID</th>
							<th class="table-base-col3"></th>
							<th data-order="{{ isset($sort) && $sort && isset($sort[0]) && $sort[0]->order == 'desc' ? 'desc' : 'asc' }}" data-column="name" class="table-base-col4 table-sort">Пользователь<span></span></th>
							<th class="table-base-col5">Ссылка</th>
							<th data-order="{{ isset($sort) && $sort && isset($sort[1]) && $sort[1]->order == 'desc' ? 'desc' : 'asc' }}" data-column="role_id" class="table-base-col6 table-sort">Тип пользователя<span></span></th>
							<th class="table-base-col7 ">Баланс<span></span></th>
							<th data-order="{{ isset($sort) && $sort && isset($sort[2]) && $sort[2]->order == 'desc' ? 'desc' : 'asc' }}" data-column="is_referral_power" class="table-base-col8 table-sort">Реферальные отчисления<span></span></th>
							<th class="table-base-col9">Блокировка</th>
							<th class="table-base-col10">Управление</th>
						</thead>

					</table>
					<div class="nano table__scroll">
						<div class="nano-content">
							<table id="table1-body" class="table-base table-base--ten">
								<tbody>
								@foreach($users as $user)
									<tr>
										<td class="table-base-col1">
											<label class="table-checkbox">
												<input type="checkbox" name="user[]" value="{{ $user->id }}" class="table-checkbox__inp table-check--simple">
												<span class="table-checkbox__check"></span>
											</label>
										</td>
										<td class="table-base-col2">{{ $user->id }}</td>
										<td class="table-base-col3">
											<span class="table-base__icon">
												{{--<svg width="34" height="40" viewBox="0 0 34 40" fill="none" xmlns="http://www.w3.org/2000/svg">--}}
													{{--<path d="M16.8551 21.5531C16.8965 21.5531 16.9379 21.5531 16.9876 21.5531C17.0041 21.5531 17.0207 21.5531 17.0372 21.5531C17.062 21.5531 17.0952 21.5531 17.12 21.5531C19.5453 21.5117 21.507 20.6585 22.9556 19.0267C26.1424 15.4318 25.6126 9.269 25.5547 8.68089C25.3478 4.26589 23.2618 2.15366 21.5401 1.16794C20.2571 0.430731 18.7589 0.0331332 17.0869 0H17.0289C17.0207 0 17.0041 0 16.9958 0H16.9462C16.0274 0 14.2229 0.149099 12.4929 1.13481C10.7546 2.12052 8.63562 4.23276 8.42868 8.68089C8.37074 9.269 7.84099 15.4318 11.0278 19.0267C12.4681 20.6585 14.4298 21.5117 16.8551 21.5531ZM10.6388 8.88797C10.6388 8.86312 10.647 8.83827 10.647 8.8217C10.9202 2.88259 15.1334 2.24477 16.9379 2.24477H16.971C16.9876 2.24477 17.0124 2.24477 17.0372 2.24477C19.2721 2.29447 23.0715 3.20563 23.3281 8.8217C23.3281 8.84655 23.3281 8.8714 23.3363 8.88797C23.3446 8.94595 23.924 14.5786 21.2918 17.544C20.2489 18.7202 18.8583 19.3001 17.0289 19.3166C17.0124 19.3166 17.0041 19.3166 16.9876 19.3166C16.971 19.3166 16.9627 19.3166 16.9462 19.3166C15.1251 19.3001 13.7262 18.7202 12.6916 17.544C10.0676 14.5952 10.6305 8.93767 10.6388 8.88797Z" fill="#666666" fill-opacity="0.5"/>--}}
													{{--<path d="M33.9977 31.7747C33.9977 31.7664 33.9977 31.7581 33.9977 31.7498C33.9977 31.6836 33.9894 31.6173 33.9894 31.5428C33.9397 29.9027 33.8321 26.0675 30.2397 24.8416C30.2149 24.8333 30.1818 24.825 30.157 24.8167C26.4238 23.8642 23.3198 21.7105 23.2867 21.6857C22.7818 21.3295 22.0865 21.4537 21.7305 21.959C21.3746 22.4643 21.4988 23.1601 22.0037 23.5163C22.1444 23.6157 25.4388 25.9101 29.561 26.9704C31.4896 27.6579 31.7048 29.7204 31.7628 31.609C31.7628 31.6836 31.7628 31.7498 31.7711 31.8161C31.7793 32.5616 31.7297 33.713 31.5972 34.3757C30.2563 35.1377 25.0001 37.7718 17.0041 37.7718C9.04121 37.7718 3.75193 35.1294 2.40271 34.3674C2.27027 33.7047 2.21233 32.5533 2.22888 31.8078C2.22888 31.7416 2.23716 31.6753 2.23716 31.6007C2.2951 29.7122 2.51032 27.6496 4.43896 26.9621C8.56112 25.9018 11.8555 23.5991 11.9963 23.508C12.5012 23.1518 12.6253 22.456 12.2694 21.9507C11.9135 21.4454 11.2182 21.3212 10.7133 21.6774C10.6801 21.7022 7.59266 23.8559 3.84298 24.8085C3.80987 24.8167 3.78504 24.825 3.76021 24.8333C0.167802 26.0675 0.0601951 29.9027 0.0105304 31.5345C0.0105304 31.609 0.0105303 31.6753 0.00225282 31.7416C0.00225282 31.7498 0.00225282 31.7581 0.00225282 31.7664C-0.00602461 32.1971 -0.0143019 34.4088 0.424402 35.5187C0.507177 35.7341 0.65617 35.9163 0.854829 36.0406C1.10315 36.2063 7.05463 40 17.0124 40C26.9701 40 32.9216 36.198 33.1699 36.0406C33.3603 35.9163 33.5176 35.7341 33.6004 35.5187C34.0142 34.4171 34.006 32.2054 33.9977 31.7747Z" fill="#666666" fill-opacity="0.5"/>--}}
												{{--</svg>--}}
												<img style="width:100%; height:100%;" src="{{ asset($user->image) }}" alt="">
											</span>
										</td>
										<td class="table-base-col4">{{ $user->name.' '.$user->lastName }}</td>
										@if($user->facebook_id)
											<td class="table-base-col5"><a href="#" class="table-base__link"></a></td>
										@elseif($user->vkontakte_id)
											<td class="table-base-col5"><a href="https://vk.com/id{{ $user->vkontakte_id }}" target="_blank"
																		   class="table-base__link">https://vk.com/id{{ $user->vkontakte_id }}</a></td>
										@else
											<td class="table-base-col5"><a href="#" class="table-base__link">Нет ссылки</a></td>
										@endif

										<td class="table-base-col6">
											<select name="role_id" onchange="MainFunction.changeData(this);" data-user-id="{{ $user->id }}">
												<option>Выбор</option>
												<option value="2" @if($user->role_id == 2) selected @endif>Admin</option>
												<option value="4" @if($user->role_id == 4) selected @endif>Youtuber</option>
												<option value="1" @if($user->role_id == 1) selected @endif>User</option>
											</select>
										</td>
										<td class="table-base-col7">{{ getBalance($user) / 10 }}</td>
										<td class="table-base-col8">
											<select name="is_referral_power" onchange="MainFunction.changeData(this);" data-user-id="{{ $user->id }}">
												<option>Выбор</option>
												<option @if($user->is_referral_power) selected @endif value="1" class="bgn-text bgn-text--green">Да</option>
												<option @if(!$user->is_referral_power) selected @endif value="0" class="bgn-text bgn-text--red">Нет</option>
											</select>

										</td>
										<td class="table-base-col9">
											<select name="is_blocked" onchange="MainFunction.changeData(this);" data-user-id="{{ $user->id }}">
												<option>Выбор</option>
												<option @if($user->is_blocked) selected @endif value="1" class="bgn-text bgn-text--green">Да</option>
												<option @if(!$user->is_blocked) selected @endif value="0" class="bgn-text bgn-text--red">Нет</option>
											</select>
										</td>
										<td class="table-base-col10">
											<a class="table-base__edit" onclick="MainFunction.getInfoUser('{{  $user->id }}');">
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
							{{ $users->appends($arraySort)->links() }}
							<p class="paginations__info">Показано от {{ ($users->currentPage() * $paginate) - $paginate + 1}} до
								{{ $users->total() < $paginate ? $paginate :  $users->total()}} из {{ $users->total() }} записей</p>
						</div>
						<!-- End paginations -->

						<button class="base__save" onclick="MainFunction.saveUser();">Сохранить</button>

					</div>
					

			</div>
		</div>
		<!-- End content -->

	</main>
	<!-- End main -->

	<!-- Start modal -->
	<div class="wrap-modal-user">

	</div>
	
	<!-- End modal -->
@endsection

@section('js')
	<script>
        setTimeout(function() {
            console.log('vlad');
            $('select').each(function(indx, element) {
                var selectOption = $(element).find('option:selected').attr("class");

                var data = $(this).data('selectric');
                $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass (function (index, className) {
                    return string = (className.match (/(^|\s)bgn-text bgn-text--\S+/g) || []).join(' ');
                });

                $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(selectOption);
                if(!$(element).find('option:selected').attr('class')) {
                    $(data.element).closest('.' + data.classes.wrapper).find('.label').removeClass(string).addClass(' ');
                }
            });
		}, 2000);
	</script>
@endsection

