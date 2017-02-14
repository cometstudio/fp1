<div class="footer section">
    <div class="wrapper">
        <div class="logo">
            @if(Request::is('/'))
                <span>{{ $settings->name }}</span>
            @else
                <a href="{{ route('index', [], false) }}">{{ $settings->name }}</a>
            @endif
        </div>
    </div>
</div>