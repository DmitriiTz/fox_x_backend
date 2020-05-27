<span>Регистрация</span>
<form action="" >
    {{ csrf_field() }}
    <ul>
        <li>
            <input type="text" name="login" value="{{ isset($request->login) ? $request->login : '' }}" placeholder="Логин">
            @if($errors->first('login'))
                <div class="error-message">{{ $errors->first('login') }}</div>
            @endif
        </li>
        <li>
            <input type="text" name="email" value="{{ isset($request->email) ? $request->email : '' }}" placeholder="Email">
            @if($errors->first('email'))
                <div class="error-message">{{ $errors->first('email') }}</div>
            @endif
        </li>
        <li><input type="text" name="name" value="{{ isset($request->name) ? $request->name : '' }}" placeholder="Имя">
            @if($errors->first('name'))
                <div class="error-message">{{ $errors->first('name') }}</div>
            @endif
        </li>
        <li><input type="text" name="last_name" value="{{ isset($request->last_name) ? $request->last_name : '' }}" placeholder="Фамилия">
            @if($errors->first('last_name'))
                <div class="error-message">{{ $errors->first('last_name') }}</div>
            @endif
        </li>
        <li>
            <input type="password" name="password" placeholder="Пароль">
            @if($errors->first('password'))
                <div class="error-message">{{ $errors->first('password') }}</div>
            @endif
        </li>
        <li>
            <input type="password" name="repeatPassword" placeholder="Повторите пароль"><br>
            @if($errors->first('repeatPassword'))
                <div class="error-message">{{ $errors->first('repeatPassword') }}</div>
            @endif
        </li>
		<li style="width: 300px;">
			<div class="g-recaptcha" data-sitekey="6LfBW58UAAAAANNC0-8TUsM_TeR7mRzbzgWdLRpr"></div>
			 @if($errors->first('g-recaptcha-response'))
                <div class="error-message">{{ $errors->first('g-recaptcha-response') }}</div>
            @endif
			@if(isset($recaptcha) && $recaptcha != '')
                <div class="error-message">Повторите ввод капчи</div>
            @endif
		</li>
    </ul>
	
    <input onclick="MainFunction.registration(this, event);" type="submit" name="Зарегистрироваться"><br>
    <a data-izimodal-open="#authLogin" data-izimodal-transitionin="fadeInDown">Уже зарегистрированы?</a>
    {{--
	<script src="https://www.google.com/recaptcha/api.js" async defer></script>
    --}}
</form>
