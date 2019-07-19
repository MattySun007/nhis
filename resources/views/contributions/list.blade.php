@extends('layouts.vue-page')
@section('content')
    <Contributions
        :years="{{ json_encode($years) }}"
        :months="{{ json_encode($months) }}"
        :user="{{ json_encode($user) }}"
        :institutions="{{ json_encode($institutions) }}"
        :adoptees="{{ json_encode($adoptees) }}"
        :item_title="{{ json_encode($itemTitle) }}"
    />
@endsection
