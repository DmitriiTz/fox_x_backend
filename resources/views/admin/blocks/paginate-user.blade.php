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