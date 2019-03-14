@extends('layouts.vue-page')
@section('content')
    <Hcps
        :countries="{{ json_encode($countries) }}"
        :hcp_types="{{ json_encode($hcp_types) }}"
        :states="{{ json_encode($states) }}"
        :lgas="{{ json_encode($lgas) }}"
        :hcps="{{ json_encode($hcps) }}" />
@endsection
