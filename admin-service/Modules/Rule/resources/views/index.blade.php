@extends('rule::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('rule.name') !!}</p>
@endsection
