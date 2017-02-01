<div id="comments">
    <input name="_comments_thread_url" value="{{ route('comments:thread', ['hash'=>$commentsHash], false) }}" type="hidden" />

    <h3>Комментарии</h3>
    <div class="comments-grid">
        <div class="form item clearfix">
            <div class="c1 column">
                <div class="wrapper">
                    @if(!empty($currentUser))
                        <img src="/images/thumbs/{{ $currentUser->getThumbnail() }}.jpg" />
                    @else
                        <img src="/images/thumbs/empty.jpg" />
                    @endif
                </div>
            </div>
            <div class="c2 column">
                <div class="wrapper">
                    <form action="{{ route('comments:submit', ['hash'=>$commentsHash], false) }}" method="POST">
                        <div class="info grid">
                            <div class="x2 row clearfix">
                                <div class="b column">
                                    Ваш ({{ !empty($currentUser) ? $currentUser->name : 'Incognito' }}) текст:
                                </div>
                                <div class="note column">
                                    @if(empty($currentUser))
                                        <a href="{{ route('login', [], false) }}" rel="nofollow">Авторизуйтесь</a>, чтобы комментировать от своего имени
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <textarea name="text"></textarea>
                        </div>
                        <div class="row">
                            <button onclick="return submitComment();" class="button">Отправить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="thread-container comments-grid"></div>
</div>