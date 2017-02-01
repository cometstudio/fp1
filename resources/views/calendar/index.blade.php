@extends('master')

@section('content')

<div class="content-wrapper">
    <div class="s0 section">
        <div class="wrapper">
            <div class="grid">
                <div class="x2 row clearfix">
                    <div class="column">
                        <nav>
                            <a href="">MP</a>
                            <span>&mdash;</span>
                            <span>Diary</span>
                            <span>&mdash;</span>
                            <span>Day X</span>
                        </nav>
                    </div>
                    <div class="datepicker-wrapper column">
                        @include('common.dateSelector')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="s1 section">
        <div class="wrapper">
            <h1>Head</h1>
            Within the field of literary criticism, "text" also refers to the original information content
            of a particular piece of writing; that is, the "text" of a work is that primal symbolic arrangement
            of letters as originally composed, apart from later alterations, deterioration, commentary, translations, paratext, etc.
        </div>
    </div>

    <div class="s2 section">
        <div class="wrapper">
            <div class="media">
                <div class="wrapper">
                    <div class="image">
                        <div class="img"></div>
                        <div class="caption">Caption</div>
                    </div>
                    <div class="image">
                        <div class="img"></div>
                        <div class="caption">Caption</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="s3 section">
        <div class="wrapper">
            <h2>Head 2</h2>
            <p class="b">Title</p>
            Within the field of literary criticism, "text" also refers to the original information content of letters as originally composed, apart from later alterations, deterioration, commentary, translations, paratext, etc.
            <p>&nbsp;</p>
            <p class="b">Title</p>
            Within the field of literary criticism, "text" also refers to the original information content of letters as originally composed, apart from later alterations, deterioration, commentary, translations, paratext, etc.
            <p>&nbsp;</p>
        </div>
    </div>

</div>

@endsection