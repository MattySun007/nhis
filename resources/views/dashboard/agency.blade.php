@extends('layouts.vue-page')
@section('content')
    <dashboard-agency
      :data="{{ json_encode($data) }}"
    />
@endsection
