<div class="header section">
    <div class="wrapper clearfix">
        <div class="l index">
            <span>
                <a href="{{ route('index', [], false) }}">{{ $settings->name }}</a>
                <a onclick="$('.header .menu').toggle();" class="responsive-menu-control fa fa-bars" href="javascript:void(0);"></a>
            </span>
        </div>

        <div class="l social-links">
            <span>в соцсетях:
                <a href="https://vk.com/fitnespraktika" title="ВКонтакте" class="fa fa-vk" target="_blank"></a>
                <a href="https://www.instagram.com/fitnespraktika" title="Instagram" class="fa fa-instagram" target="_blank"></a>
                <a href="https://www.youtube.com/channel/UCZIsiLeWkbynnERIgYIrYBg" title="YouTube" class="fa fa-youtube" target="_blank"></a>
                <a href="https://www.facebook.com/fitnespraktika" title="Facebook" class="fa fa-facebook-square" target="_blank"></a>
                <!--<a href="https://telegram.me/fitnespraktika_bot" title="Telegram (бот)" class="fa fa-telegram" target="_blank"></a>-->
            </span>
        </div>

        <div class="r menu">
            <nav>
                <span><a href="{{ route('calendar:index', [], false) }}">Календарь</a></span>
                <span><a href="{{ route('diary:index', [], false) }}">Дневник</a></span>
                <!--<span><a href="{{ route('videos:index', [], false) }}">Видеоотчёты</a></span>-->

                @if(!empty($currentUser))
                    <span><img class="profile-picture-img" src="/images/thumbs/{{ $currentUser->getThumbnail() }}.jpg" title="Редактировать профиль" /> <a href="{{ route('my:index', [], false) }}">Профиль</a></span>
                    <span><a href="{{ route('logout', [], false) }}">Выйти</a></span>
                @else
                   <span><i class="fa fa-unlock"></i><a href="{{ route('login', [], false) }}" rel="nofollow">Войти</a></span>
                @endif
            </nav>
        </div>
    </div>
</div>