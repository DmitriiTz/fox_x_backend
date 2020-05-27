@extends('admin.layout')
@section('content')

	<!-- Start main -->
	<main class="main">

		<!-- Start sidebar -->
	@include('admin.blocks.left-menu')
		<!-- End sidebar -->

		<!-- Start content -->
		<div class="base">
			<div class="base__wrapper base__wrapper--games">
				<div class="nano base__games">
					<div class="nano-content">
						<div class="games__wrap">

							<!-- Jackpot statistics -->
							<div class="games__box game">
								<h4 class="game__title">Jackpot</h4>
								<table class="game__table">
									<thead>
										<th class="game__table-col1">Название игры</th>
										<th class="game__table-col2">Hash</th>
										<th class="game__table-col3">Билет</th>
										<th class="game__table-col4">Банк</th>
										<th class="game__table-col5">Статус</th>
										<th class="game__table-col6">Победитель</th>
									</thead>
								</table>
								<div class="nano game__scroll">
									<div class="nano-content">
										<table class="game__table">
											<tbody>
											@foreach($jackpotGames as $game)
												<tr>
													<td class="game__table-col1">{{ $game->type->name }} #{{ $game->id }}</td>
													<td class="game__table-col2">{{ $game->hash  }}</td>
													<td class="game__table-col3">{{ $game->winner_ticket_big }}</td>
													<td class="game__table-col4">{{ $game->participants->sum('cash') }}</td>
													<td class="game__table-col5">@if($game->status_id == 1) Создана @elseif($game->end_game_at > now() && $game->status_id != 4) Запущена @elseif($game->status_id == 4) В очереди @else Закончена @endif</td>
													@if($game->winner_ticket && $game->winner_account_id)
														<td class="game__table-col6">{{ $game->winner_ticket ? $game->winner->name.' '. $game->winner->last_name : '' }}</td>
													@else
														<td class="game__table-col6"></td>
													@endif
												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</div>
								
							</div>

							<!-- Coin flip statistics -->
							<div class="games__box game game--last">
								<h4 class="game__title">Coin Flip</h4>
								<table class="game__table game__table--seven">
									<thead>
										<th class="game__table-col1">Название игры</th>
										<th class="game__table-col2">Hash</th>
										<th class="game__table-col3">Билет</th>
										<th class="game__table-col4">Банк</th>
										<th class="game__table-col5">Статус</th>
										<th class="game__table-col6">Победитель</th>
										<th class="game__table-col7">Цвет победителя</th>
									</thead>
								</table>
								<div class="nano game__scroll">
									<div class="nano-content">
										<table class="game__table game__table--seven">
											<tbody>
											@foreach($coinflipGames as $game)
												<tr>
													<td class="game__table-col1">{{ $game->id }}</td>
													<td class="game__table-col2">{{ $game->hash }}</td>
													<td class="game__table-col3">{{ $game->winner_ticket_big }}</td>
													<td class="game__table-col4">{{ $game->participants->sum('cash') }}</td>
													<td class="game__table-col5">
														@if($game->participants->count() == 1)
															Создана
														@elseif(is_null($game->create_account_id))
															В очереди
														@else
															Законченна
														@endif

													</td>
												@if($game->winner_account_id)
													<td class="game__table-col6">{{ $game->winner->name }}</td>
												@else

													@php
														$participant = \App\Participant::where('history_game_id', $game->id)->where('min_cash_number', '<=', $game->winner_ticket)
														->where('max_cash_number', '>=', $game->winner_ticket)
														->first();
													@endphp

													@if($participant)
															<td class="game__table-col6">{{ $participant->account->name }}</td>
													@else
															<td class="game__table-col6">Неизвестно</td>
													@endif
												@endif

												@if($game->winner_ticket || $game->winner_ticket === 0)

													@if($game->winner_ticket <= 50)
														<td class="game__table-col7"><span class="game__color game__color--yellow"></span></td>
													@else
														<td class="game__table-col7"><span class="game__color game__color--blue"></span></td>
													@endif



												@else
													<td class="game__table-col7">Неизвестно</td>
												@endif


												</tr>
											@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- End content -->

	</main>
	<!-- End main -->
	<style>
		.game__table--seven .game__table-col2, .game__table-col2 {
			font-size: 13px;
		}
	</style>
@endsection