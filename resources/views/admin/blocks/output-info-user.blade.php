<div class="modal__container info-user">
    <form action="">
        {{ csrf_field() }}
        <div class="modal">
            <div class="modal__top">
				<span class="modal__icon">
                    <img style="    width: 100%;
    border-radius: 5px;" src="{{asset($user->image)}}" alt="avatar">
				</span>
                    @if($user->vkontakte_id)
                                            <p class="modal__name"><a target="_blank" href="https://vk.com/id{{ $user->vkontakte_id }}">
                                                    {{ $user->name.' '.$user->last_name }}</a></p>
                                        @else
                                            <p class="modal__name">{{ $user->name.' '.$user->last_name }}</p>
                                        @endif
                <div class="modal__close">
                    <a class="modal__close-link"></a>
                </div>
            </div>
            <div class="output-info">
                <div class="output-info__top">
                    <div class="output-info__box">
                        <p class="output-info__title">Тип пользователя</p>
                        <span class="output-info__value">{{ $user->role->role }}</span>
                    </div>
                    <div class="output-info__box">
                        <p class="output-info__title">Реф. Отчисления</p>
                        <span class="output-info__value">{{ $user->is_referral ? 'Да' : 'Нет' }}</span>
                    </div>
                    <div class="output-info__box">
                        <p class="output-info__title">Заблокирован</p>
                        <span class="output-info__value">{{ $user->is_blocked ? 'Да' : 'Нет' }}</span>
                    </div>
                    <div class="output-info__box">
                        <p class="output-info__title">Система</p>
                        <span class="output-info__value">{{ $application->payment_system->name }}</span>
                    </div>
                    <div class="output-info__box">
                        <p class="output-info__title">Реквизиты</p>
                        <span class="output-info__value">{{ $application->phone }}</span>
                    </div>
                </div>
                <div class="output-info__bottom">
                    <div class="output-info__box">
                        <p class="output-info__title">Баланс, coins:</p>
                        <span class="output-info__value">{{ getBalance($user) }}</span>
                    </div>
                    <div class="output-info__box">
                        <p class="output-info__title">Баланс, руб:</p>
                        <span class="output-info__value">{{ getBalance($user) / 10 }}</span>
                    </div>
                    <div class="output-info__box">
                        <p class="output-info__title">Сумма к выводу</p>
                        <input type="text" name="" readonly class="output-info__field" value="{{ $application->price }}">
                    </div>
                    <div class="output-info__box">
                        <p class="output-info__title">Статус вывода</p>
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
                    </div>
                </div>
            </div>


            <div class="modal__statistics">
                <p class="modal__statistics-title">Сводная статистика</p>
                <table class="modal-table">
                    <thead>
                    <tr>
                        <th>Общая сумма вывода</th>
                        <th>Общая сумма пополнений</th>
                        <th>Общая сумма реф. начислений</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{ getOutputSum($user) }}</td>
                        <td>{{ getInputSum($user) }}</td>
                        <td>{{ getReferralSum($user) }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="wrap-paginate-user">
                <div class="modal__history">
                    <p class="modal__history-title">История пополнений/вывода</p>
                    <table class="modal-table">
                        <thead>
                        <tr>
                            <th>Сумма</th>
                            <th>Назначение</th>
                            <th>Дата</th>
                        </tr>
                        </thead>
                    </table>
                    <div class="nano modal-table__wrap">
                        <div class="nano-content">
                            <table class="modal-table modal-table--spacing">
                                <tbody>
                                @php
                                    $payments = $user->payments()->where('is_admin', 0)->whereIn('payment_type_id', [1,2,3])->paginate(10);
                                @endphp

                                @foreach($payments as $payment)

                                    @if($payment->payment_type_id == 1)
                                        <tr class="modal-table--green">
                                            <td>{{ $payment->price }}</td>
                                            <td>Пополнение</td>
                                            <td>{{ date('d-m-Y', strtotime($payment->created_at)) }}</td>
                                        </tr>
                                    @endif

                                    @if($payment->payment_type_id == 2)
                                        <tr class="modal-table--red">
                                            <td>{{ $payment->price }}</td>
                                            <td>Вывод</td>
                                            <td>{{ date('d-m-Y', strtotime($payment->created_at)) }}</td>
                                        </tr>
                                    @endif

                                    @if($payment->payment_type_id == 3)
                                        <tr class="modal-table--blue">
                                            <td>{{ $payment->price }}</td>
                                            <td>Реф. начисления</td>
                                            <td>{{ date('d-m-Y', strtotime($payment->created_at)) }}</td>
                                        </tr>
                                    @endif

                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
                <div class="modal__bottom">
                    <div></div>

                    <!-- Start paginations -->
                    {{ $payments->links() }}
                    <a class="modal__save" onclick="MainFunction.saveInfoApplication('{{ $user->id }}');">Сохранить</a>
                </div>
            </div>


            <input type="hidden" name="id" value="{{ $user->id }}">
            <input type="hidden" name="applicationId" value="{{ $application->id }}">
        </div>
    </form>
</div>