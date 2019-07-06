@extends('layouts.vue-page')
@section('content')
    <Biometric
        :message="{{ json_encode($message) }}"
        :url="{{ json_encode($url) }}"
        :data="{{ json_encode($data) }}"
        :success="{{ json_encode($success) }}"
    />
@endsection
