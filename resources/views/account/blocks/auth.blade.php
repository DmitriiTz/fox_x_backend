<span>Авторизация</span>
<form action="">
    {{ csrf_field() }}
    <ul>
        <li><input type="text" name="email" value="{{ isset($request->email) ? $request->email : '' }}" placeholder="Email">
            @if($errors->first('email'))
                <div class="error-message">{{ $errors->first('email') }}</div>
            @endif</li>
        <li><input type="password" name="password" placeholder="Пароль"><br></li>
    </ul>


    <input onclick="MainFunction.authUser(this, event);" type="submit" name="Зарегистрироваться"><br>
    <a  data-izimodal-open="#authRegistration" data-izimodal-transitionin="fadeInDown">Нет аккаунта?</a>
</form>