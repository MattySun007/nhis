@extends('layouts.vue-page')
@section('content')
    <adoption
        :adoptees="{{ json_encode($adoptees) }}"
        :user_id="{{ json_encode($user_id) }}"/>
@endsection
