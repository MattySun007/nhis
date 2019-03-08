@extends('layouts.vue-page')
@section('content')
    <Institutions
        :countries="{{ json_encode($countries) }}"
        :states="{{ json_encode($states) }}"
        :lgas="{{ json_encode($lgas) }}"
        :institutions="{{ json_encode($institutions) }}" />
@endsection
