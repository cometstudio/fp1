@extends('emails.master')

@section('content')
    Ваш новый пароль для авторизации на сайте: <b>{{ $password }}</b>
@endsection