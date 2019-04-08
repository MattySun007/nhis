@extends('layouts.vue-page')
@section('content')
  <Claims
    :page-title="{{ json_encode($pageTitle) }}"
    :paid="{{ json_encode($paid) }}"
    :states="{{ json_encode($states) }}" />
@endsection
