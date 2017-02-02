@extends('emails.master')

@section('content')
    @if(!empty($subscription->text))
        {!! $subscription->text !!}
    @endif
@endsection