<div class="modal__container info-user">
    <form action="">
        {{ csrf_field() }}
        <div class="modal">
            <div class="modal__top">
				<span class="modal__icon">
					<img src="{{ asset($user->image) }}" alt="" style="max-width: 70px;">
				</span>
                <p class="modal__name">{{ $user->name.' '.$user->lastName }}</p>
                <div class="modal__close">
                    <a class="modal__close-link"></a>
                </div>
            </div>
            <div class="user-info">
                <div class="user-info__top">
                    <div class="user-info__box">
                        <p class="user-info__title">Имя</p>
                        <input type="text" class="user-info__field" name="name" value="{{ $user->name }}">
                    </div>
                    <div class="user-info__box">
                        <p class="user-info__title">Фамилия</p>
                        <input type="text" class="user-info__field" name="last_name" value="{{ $user->last_name }}">
                    </div>
                    <div class="user-info__box">
                        <p class="user-info__title">Ссылка</p>
                        <input type="text" class="user-info__field user-info__field--wide"
                               value="@if($user->facebook_id) {{ $user->facebook_id }} @elseif($user->vkontakte_id) https://vk.com/id{{ $user->vkontakte_id }} @endif">
                    </div>
                </div>
                <div class="user-info__bottom">
                    <div class="user-info__box">
                        <p class="user-info__title">Баланс</p>
                        <input type="text" class="user-info__field" name="balance" value="{{ getBalance($user) / 10 }}">
                    </div>
                    <div class="user-info__box">
                        <p class="user-info__title">Тип пользователя</p>
                        <select name="role_id">
                            <option>Выбор</option>
                            <option value="2" @if($user->role_id == 2) selected @endif>Admin</option>
                            <option value="4" @if($user->role_id == 4) selected @endif>Youtuber</option>
                            <option value="1" @if($user->role_id == 1) selected @endif>User</option>
                        </select>
                    </div>
                    <div class="user-info__box">
                        <p class="user-info__title">Реф. отчисления</p>
                        <select name="is_referral_power">
                            <option>Выбор</option>
                            <option @if($user->is_referral_power) selected @endif value="1" class="bgn-text bgn-text--green">Да</option>
                            <option @if(!$user->is_referral_power) selected @endif value="0" class="bgn-text bgn-text--red">Нет</option>
                        </select>
                    </div>
                    <div class="user-info__box">
                        <p class="user-info__title">Заблокировать</p>
                        <select name="is_blocked">
                            <option>Выбор</option>
                            <option @if($user->is_blocked) selected @endif value="1" class="bgn-text bgn-text--green">Да</option>
                            <option @if(!$user->is_blocked) selected @endif value="0" class="bgn-text bgn-text--red">Нет</option>
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
                    {{ $payments->links() }}
                    <a class="modal__save" onclick="MainFunction.saveInfoUser('{{ $user->id }}');">Сохранить</a>
                </div>
                <input type="hidden" name="id" value="{{ $user->id }}">
            </div>
        </div>
    </form>
</div>

<script>
    $("select").selectric();
</script>