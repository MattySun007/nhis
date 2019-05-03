@extends('layouts.vue-page')
@section('content')
  <Claims
    :page-title="{{ json_encode($pageTitle) }}"
    :paid-main="{{ json_encode($paid) }}"
    :item-title-main="{{ json_encode($itemTitle) }}"
    :institutions="{{ json_encode($institutions) }}"
    :treatments="{{ json_encode($treatments) }}"
    :states="{{ json_encode($states) }}" />
@endsection
