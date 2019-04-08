@extends('layouts.vue-page')
@section('content')
    <institution-users
        :users="{{ json_encode($users) }}"
        :blood-groups="{{ json_encode($bloodGroups) }}"
        :genotypes="{{ json_encode($genotypes) }}"
        :genders="{{ json_encode($genders) }}"
        :marital-statuses="{{ json_encode($maritalStatuses) }}"
        :institution="{{ json_encode($institution) }}" />
@endsection
