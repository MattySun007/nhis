@extends('layouts.vue-page')
@section('content')
    <contribution-users
        :blood-groups="{{ json_encode($bloodGroups) }}"
        :page-title="{{ json_encode($pageTitle) }}"
        :item-title="{{ json_encode($itemTitle) }}"
        :genotypes="{{ json_encode($genotypes) }}"
        :genders="{{ json_encode($genders) }}"
        :marital-statuses="{{ json_encode($maritalStatuses) }}"
        :users="{{ json_encode($users) }}" />
@endsection
