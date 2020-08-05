@extends('admin.layout')
@section('content')
	<!-- End header -->

	<!-- Start main -->
	<main class="main">

		<!-- Start sidebar -->
		@include('admin.blocks.left-menu')
		<!-- End sidebar -->

		<!-- Start content -->
		<div class="base">
			<div class="base__wrapper">
				<div class="nano base__statistics">
					<div class="nano-content">
						<table class="statistics">
						<tbody>

							<tr>
								<td class="statistics__title">Статистика пополнения</td>
								<td>
									<span class="statistics__number">{{ $paymentsInputToday }}</span>
									<p class="statistics__period">За сегодня</p>
								</td>
								<td>
									<span class="statistics__number">{{ $paymentsInputYesterday }}</span>
									<p class="statistics__period">За вчера</p>
								</td>
								<td>
									<span class="statistics__number">{{ $paymentsInput7days }}</span>
									<p class="statistics__period">За 7 дней</p>
								</td>
								<td>
									<span class="statistics__number">{{ $paymentsInputMonth }}</span>
									<p class="statistics__period">За месяц</p>
								</td>
								<td>
									<span class="statistics__number">{{ $paymentsInput3Months }}</span>
									<p class="statistics__period">За 3 месяца</p>
								</td>
							</tr>

							<tr>
								<td class="statistics__title">Статистика вывода</td>
								<td>
									<span class="statistics__number">{{ 0 - $paymentsOutputToday }}</span>
									<p class="statistics__period">За сегодня</p>
								</td>
								<td>
									<span class="statistics__number">{{ 0 - $paymentsOutputYesterday }}</span>
									<p class="statistics__period">За вчера</p>
								</td>
								<td>
									<span class="statistics__number">{{ 0 - $paymentsOutput7days }}</span>
									<p class="statistics__period">За 7 дней</p>
								</td>
								<td>
									<span class="statistics__number">{{ 0 - $paymentsOutputMonth }}</span>
									<p class="statistics__period">За месяц</p>
								</td>
								<td>
									<span class="statistics__number">{{ 0 -$paymentsOutput3Months }}</span>
									<p class="statistics__period">За 3 месяца</p>
								</td>
							</tr>

							<tr>
								<td class="statistics__title">Статистика дохода</td>
								<td>
									<span class="statistics__number">{{ $incomeToday }}</span>
									<p class="statistics__period">За сегодня</p>
								</td>
								<td>
									<span class="statistics__number">{{ $incomeYesterday }}</span>
									<p class="statistics__period">За вчера</p>
								</td>
								<td>
									<span class="statistics__number">{{ $income7days }}</span>
									<p class="statistics__period">За 7 дней</p>
								</td>
								<td>
									<span class="statistics__number">{{ $incomeMonth }}</span>
									<p class="statistics__period">За месяц</p>
								</td>
								<td>
									<span class="statistics__number">{{ $income3Months }}</span>
									<p class="statistics__period">За 3 месяца</p>
								</td>
							</tr>

							<tr>
								<td class="statistics__title">Количество новых пользователей</td>
								<td>
									<span class="statistics__number">{{ $newUsersToday }}</span>
									<p class="statistics__period">За сегодня</p>
								</td>
								<td>
									<span class="statistics__number">{{ $newUsersYesterday }}</span>
									<p class="statistics__period">За вчера</p>
								</td>
								<td>
									<span class="statistics__number">{{ $newUsers7days }}</span>
									<p class="statistics__period">За 7 дней</p>
								</td>
								<td>
									<span class="statistics__number">{{ $newUsersMonth }}</span>
									<p class="statistics__period">За месяц</p>
								</td>
								<td>
									<span class="statistics__number">{{ $newUsers3Months }}</span>
									<p class="statistics__period">За 3 месяца</p>
								</td>
							</tr>

							<tr>
								<td class="statistics__title">Статистика реф. начислений</td>
								<td>
									<span class="statistics__number">{{ $paymentsReferralToday }}</span>
									<p class="statistics__period">За сегодня</p>
								</td>
								<td>
									<span class="statistics__number">{{ $paymentsReferralYesterday }}</span>
									<p class="statistics__period">За вчера</p>
								</td>
								<td>
									<span class="statistics__number">{{ $paymentsReferral7days }}</span>
									<p class="statistics__period">За 7 дней</p>
								</td>
								<td>
									<span class="statistics__number">{{ $paymentsReferralMonth }}</span>
									<p class="statistics__period">За месяц</p>
								</td>
								<td>
									<span class="statistics__number">{{ $paymentsReferral3Months }}</span>
									<p class="statistics__period">За 3 месяца</p>
								</td>
							</tr>

							<tr>
								<td class="statistics__title">Статистика по JACKPOT</td>
								<td>
									<span class="statistics__number">{{ $jackpotStatisticsToday }}</span>
									<p class="statistics__period">За сегодня</p>
								</td>
								<td>
									<span class="statistics__number">{{ $jackpotStatisticsYesterday }}</span>
									<p class="statistics__period">За вчера</p>
								</td>
								<td>
									<span class="statistics__number">{{ $jackpotStatistics7days }}</span>
									<p class="statistics__period">За 7 дней</p>
								</td>
								<td>
									<span class="statistics__number">{{ $jackpotStatisticsMonth }}</span>
									<p class="statistics__period">За месяц</p>
								</td>
								<td>
									<span class="statistics__number">{{ $jackpotStatistics3Months }}</span>
									<p class="statistics__period">За 3 месяца</p>
								</td>
							</tr>

							<tr>
								<td class="statistics__title">Статистика по COINFLIP</td>
								<td>
									<span class="statistics__number">{{ $coinflipStatisticsToday }}</span>
									<p class="statistics__period">За сегодня</p>
								</td>
								<td>
									<span class="statistics__number">{{ $coinflipStatisticsYesterday }}</span>
									<p class="statistics__period">За вчера</p>
								</td>
								<td>
									<span class="statistics__number">{{ $coinflipStatistics7days }}</span>
									<p class="statistics__period">За 7 дней</p>
								</td>
								<td>
									<span class="statistics__number">{{ $coinflipStatisticsMonth }}</span>
									<p class="statistics__period">За месяц</p>
								</td>
								<td>
									<span class="statistics__number">{{ $coinflipStatistics3Months }}</span>
									<p class="statistics__period">За 3 месяца</p>
								</td>
							</tr>

							<tr>
								<td class="statistics__title">Статистика по KING OF THE HILL</td>
								<td>
									<span class="statistics__number">{{ $kingStatisticsToday }}</span>
									<p class="statistics__period">За сегодня</p>
								</td>
								<td>
									<span class="statistics__number">{{ $kingStatisticsYesterday }}</span>
									<p class="statistics__period">За вчера</p>
								</td>
								<td>
									<span class="statistics__number">{{ $kingStatistics7days }}</span>
									<p class="statistics__period">За 7 дней</p>
								</td>
								<td>
									<span class="statistics__number">{{ $kingStatisticsMonth }}</span>
									<p class="statistics__period">За месяц</p>
								</td>
								<td>
									<span class="statistics__number">{{ $kingStatistics3Months }}</span>
									<p class="statistics__period">За 3 месяца</p>
								</td>
							</tr>

						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- End content -->

	</main>
	<!-- End main -->

@endsection

