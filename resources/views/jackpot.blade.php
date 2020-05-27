@extends('layout')

@section('content')


	<div class="change-game-wrap-jackpot">
		@include('blocks.change-game-wrap-jackpot')
	</div>

    <div class="modal modal--bet" id="betModal">
        <div class="modal__top">
            <h6 class="modal__title">СДЕЛАТЬ СТАВКУ</h6>
            <a class="modal__close" data-izimodal-close><i class="ic-close"></i></a>
        </div>
        <div class="modal__body">
            <div class="conclusion__balance">
                <p class="conclusion__balance-text">Текущий баланс:</p>
                <p class="conclusion__balance-value">{{ getBalance(auth()->user()) }} <span>coins</span></p>
            </div>
            <form class="bet__form">
                <input type="hidden" name="balance" value="{{ getBalance(auth()->user()) }}">
                <div class="bet__form-wrap">
                    <label class="bet__form-label">COINS</label>
                    <div id="coins" class="bet__form-box">
                        @if($gameTypeId == 1)
                            <input type="number" onchange="MainFunction.calculate(this, event, 'jackpot');" onkeyup="MainFunction.calculate(this, event, 'jackpot');" step="2"
                                   class="bet__form-input cash-coins" min="300" value="300" onkeypress='validate(event)'>
                        @else
                            <input type="number" onchange="MainFunction.calculate(this, event, 'jackpot');" onkeyup="MainFunction.calculate(this, event);"
                                   class="bet__form-input cash-coins" min="10" max="300" value="10" onkeypress='validate(event)'>
                        @endif
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
                <span class="bet__form-sum">=</span>
                <div class="bet__form-wrap bet__form-wrap--gray">
                    <label class="bet__form-label">руб</label>
                    <div id="coins-result" class="bet__form-box count-money">
                        @if($gameTypeId == 1)
                            <input type="number" class="bet__form-input" readonly value="30">
                        @else
                            <input type="number" class="bet__form-input" readonly value="1">
                        @endif
                        <span class="bet__form-up"></span>
                        <span class="bet__form-down"></span>
                    </div>
                </div>
            </form>

            <div class="warning__balance hide">
                <p class="warning__balance-text">* На вашем счете недостаточно средств для ставки</p>
            </div>
            <div class="modal__buttons">
                <a href="javascript:void(0);" onclick="MainFunction.placeBet(this);" class="button button--gradient">СДЕЛАТЬ СТАВКУ</a>
                <a href="#" class="button" data-izimodal-open="#inputModal" data-izimodal-transitionin="fadeInDown">Пополнить</a>
            </div>
        </div>
    </div>

	@include('account.blocks.popups')
@endsection

