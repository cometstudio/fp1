@extends('master')

@section('content')

    <div class="content-wrapper">
        <div class="s0 section">
            <div class="wrapper">
                <h1>Получить новый пароль</h1>
                <div class="forgot form grid">
                    <form action="{{ route('postForgot', [], false) }}" method="post">
                        <div class="x2 row clearfix">
                            <div class="column">
                                <div class="row">
                                   <div class="label clearfix">
                                       E-mail, указанный при регистрации
                                   </div>
                                    <input id="email" name="email" type="text" />
                                </div>
                                <div class="row">
                                    <div class="label clearfix">
                                        Повторите символы
                                    </div>
                                    <img id="captcha-img" src="{!! captcha_src() !!}" style="float: left; margin-right: 5px;" /><input id="captcha" name="captcha" type="text" style="width: 50%;" maxlength="4" />
                                </div>
                                <div class="row">
                                   <button onclick="return forgot();" class="button">Запросить пароль</button>
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