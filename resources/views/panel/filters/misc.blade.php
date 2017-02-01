<div class="filter grid">
    <form action="" method="get">
        <input name="q" value="{{ request('q') }}" type="hidden" />
        <div class="c2 row clearfix">
            <div class="col"><input name="query" value="{{ request('query') }}" placeholder="Название..." type="text" /></div>
            <div class="col">
                @if(!empty($options['misc']) && $options['misc']->count())
                    <div class="col">
                        <select name="pid" style="width: 100%">
                            <option value="">выберите родителя...</option>
                            @foreach($options['misc'] as $miscItem)
                                <option value="{{ $miscItem->id }}"{{ $miscItem->id == request('pid') ? ' selected' : '' }}>{{ !empty($miscItem->short_name) ? strip_tags($miscItem->short_name) : $miscItem->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
            </div>
        </div>
        <div class="c4 row clearfix">

            <div class="col"><button  onclick="this.form.submit();" class="button">Показать</button></div>
        </div>
    </form>
</div>