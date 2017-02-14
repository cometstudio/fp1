<div class="media-grid grid">
    <div class="x2 row clearfix">
        @foreach($diary as $day)
            <div class="column">
                <a href="{{ route('diary:item', ['id'=>$day->id], false) }}" class="image">
                    <div class="label">{{ count($day->getGallery()) }} фото</div>
                    <img src="/images/small/{{ $day->getThumbnail() }}.jpg" />
                </a>
                <div class="info clearfix">
                    <div class="stat clearfix">
                        <div class="l">{{ \Date::getDateFromTime($day->start_at) }}, день {{ \Date::seasonDaysLeft($day->start_at) }}</div>
                        <div class="r"><i class="fa fa-eye"></i> {{ $day->views }} <i class="fa fa-comment-o"></i> {{ $day->comments_total }}</div>
                    </div>
                    @if(!empty($day->title))
                        <div class="title"><a href="{{ route('diary:item', ['id'=>$day->id]) }}">{{ str_limit(strip_tags($day->title), 120) }}</a></div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>