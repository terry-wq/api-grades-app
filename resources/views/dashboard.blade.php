@extends('layouts.app')

@section('content')
<div class="space-y-6">

    @if(($activeTab ?? 'podium') === 'podium')
        @include('partials.podium')
    @elseif(($activeTab ?? 'podium') === 'reveal')
        @include('partials.reveal')
    @elseif(($activeTab ?? 'podium') === 'list')
        @include('partials.student-list')
    @elseif(($activeTab ?? 'podium') === 'stats')
        @include('partials.stats')
    @endif

</div>
@endsection
