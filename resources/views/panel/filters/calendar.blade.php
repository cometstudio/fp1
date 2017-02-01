<div class="filter grid">
    <form action="" method="get">
        <input name="q" value="{{ request('q') }}" type="hidden" />
        <div class="c2 row clearfix">
            <div class="col">
                <input name="_start_from" value="{{ request()->get('_start_from') }}" placeholder="дата с..." type="text" class="datepicker" autocomplete="off" style="width:49.5%;float: left;margin-right: 1%;" />
                <input name="_start_to" value="{{ request()->get('_start_to') }}" placeholder="дата по..." type="text" class="datepicker" autocomplete="off" style="width:49.5%; " />
            </div>
            <div class="col"><button  onclick="this.form.submit();" class="button">Показать</button></div>
        </div>
    </form>
</div>