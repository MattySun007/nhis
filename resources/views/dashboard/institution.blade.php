@extends('layouts.vue-page')
@section('content')
    <dashboard-institution
      :data="{{ json_encode($data) }}"
    />
@endsection
