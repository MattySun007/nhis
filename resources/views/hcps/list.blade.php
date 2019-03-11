@extends('layouts.vue-page')
@section('content')
    <Hcps
        :countries="{{ json_encode($countries) }}"
        :states="{{ json_encode($states) }}"
        :lgas="{{ json_encode($lgas) }}"
        :hcps="{{ json_encode($hcps) }}" />
@endsection
