@extends('layouts.vue-page')
@section('content')
  <Treatment
    :hcps="{{ json_encode($hcps) }}" />
@endsection
