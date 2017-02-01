@extends('panel.shortLayout')

@section('content')
    <div class="login form">
        <form action="{{ route('admin::postLogin', [], false) }}" method="post">
                <div class="row">
                    <input id="email" name="email" type="email" placeholder="Ваш e-mail" />
                </div>
                <div class="row">
                    <input id="password" name="password" type="password" placeholder="Пароль" onfocus="$(this).attr('type', 'password')" />
                </div>
                <!--<a href="/admin/forget" class="empty button">Не помню пароль</a>-->
                <button onclick="return login();" class="button">Войти</button>
        </form>
    </div>
@endsection