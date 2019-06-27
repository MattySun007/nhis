@extends('layouts.vue-page')
@section('content')
    <dashboard-hcp
      :data="{{ json_encode($data) }}"
    />
@endsection
