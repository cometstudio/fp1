@extends('emails.master')

@section('content')
    Подтвердите e-mail пройдя по ссылке <a href="{{ $verificationURL }}">{{ $verificationURL }}</a>.
@endsection