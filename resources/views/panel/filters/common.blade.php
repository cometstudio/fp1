<div class="filter grid">
    <form action="" method="get">
        <input name="q" value="{{ request('q') }}" type="hidden" />
        <div class="c2 row clearfix">
            <div class="col"><input name="query" value="{{ request('query') }}" placeholder="Найти..." type="text" /></div>
            <div class="col"><button  onclick="this.form.submit();" class="button">Показать</button></div>
        </div>
    </form>
</div>