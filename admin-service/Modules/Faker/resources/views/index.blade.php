@extends('faker::layouts.master')

@section('content')
    <h1>Hello World</h1>

    <p>Module: {!! config('faker.name') !!}</p>
@endsection
