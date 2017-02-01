@extends('master')

@section('content')

    <div class="content-wrapper">
        <div class="s0 section">
            <div class="wrapper">
                <h1>Войти на сайт</h1>
                <div class="login form grid">
                    <form action="{{ route('login', [], false) }}" method="post">
                        <div class="x2 row clearfix">
                            <div class="column">
                                <div class="row">
                                    <input name="_new" type="hidden" value="0" />
                                    <label><input name="_new" type="checkbox" value="1" onclick="$('.hidden').toggle(); $('#forgotten').toggle();" /> я здесь впервые, зарегистрируйте меня</label>
                                </div>
                                <div class="hidden row">
                                   <div class="label">Имя</div>
                                    <input id="name" name="name" type="text" />
                                </div>
                                <div class="row">
                                   <div class="label clearfix">
                                       E-mail
                                   </div>
                                    <input id="email" name="email" type="text" />
                                </div>
                                <div class="hidden row">
                                    <input name="subscribed" type="hidden" value="0" />
                                    <label><input name="subscribed" type="checkbox" value="1" checked /> изредка получать от нас интересное на этот e-mail</label>
                                </div>
                                <div class="row">
                                   <div class="label">Пароль <a href="{{ route('forgot', [], false) }}" id="forgotten" style="float: right;">Не помню пароль</a></div>
                                    <i class="fa fa-eye-slash" onclick="$(this).parent().find('input').prop('type', 'text');$(this).removeClass('fa-eye-slash').addClass('fa-eye');"></i>
                                    <input id="password" name="password" type="password" />
                                </div>
                                <div class="hidden row">
                                    <div class="label clearfix">
                                        Повторите символы
                                    </div>
                                    <img id="captcha-img" src="{!! captcha_src() !!}" style="float: left; margin-right: 5px;" /><input id="captcha" name="captcha" type="text" style="width: 50%;" maxlength="4" />
                                </div>
                                <div class="row">
                                   <button onclick="return login();" class="button">Войти</button>
                                </div>
                            </div>
                            <div class="column" style="padding-top: 61px;"></div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection