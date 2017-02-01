<form action="{{ route('calendar:index', [], false) }}" method="GET">
    <span>&larr; <a href="{{ route('calendar:index', ['date'=>\Date::getDateFromTime($startAt - 86400, 1)], false) }}" rel="nofollow">назад</a></span>
    <span class="datepicker-container">
        <i class="fa fa-calendar"></i>
        <input name="date" class="datepicker" value="{{ Date::getDateFromTime($startAt, 1) }}" onchange="this.form.submit();" type="text" />
    </span>
    <span><a href="{{ route('calendar:index', ['date'=>\Date::getDateFromTime($startAt + 86400, 1)], false) }}" rel="nofollow">вперёд</a> &rarr;</span>
</form>