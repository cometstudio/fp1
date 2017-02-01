@if(!empty($comments) && $comments->count())
    <div class="thread">
        @foreach($comments as $comment)
            <div class="item clearfix">
                <div class="c1 column">
                    <div class="wrapper">
                        <img src="/images/thumbs/{{ $comment->getThumbnail() }}.jpg" />
                    </div>
                </div>
                <div class="c2 column">
                    <div class="wrapper">
                        <div class="b info">{{ $comment->user_name }} {{ \Date::getDateFromTime($comment->created_at->timestamp, 3) }}</div>
                        {{ $comment->text }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif