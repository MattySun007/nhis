@extends('layouts.vue-page')
@section('content')
    <Permission
        :perms="{{ json_encode($perms) }}"
        :item_title="{{ json_encode($itemTitle) }}"
    />
@endsection
