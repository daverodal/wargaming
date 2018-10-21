@include('wargame::global-header')
@include('wargame::Mollwitz.header')
@if(view()->exists("$curPath.$clsName"."Header"))
    @include("$curPath.$clsName"."Header")
@else
    @include("$curPath.Header")

@endif
<link rel="stylesheet" type="text/css" href="{{mix('vendor/css/wargame/horse-musket.css')}}">
</head>
@extends('wargame::stdIncludes.view' )
@extends('wargame::Mollwitz.view')
@section('credit')
    @include('wargame::Mollwitz.credit')
@endsection

@isset($scenario->corePath)
@if(view()->exists($scenario->corePath.".victoryConditions"))
@section('victoryConditions')
    @include($scenario->corePath.".victoryConditions")
@endsection
@endif
@endisset


@if(view()->exists("$curPath.victoryConditions"))
    @section('victoryConditions')
        @include("$curPath.victoryConditions")
    @endsection
@endif


@isset($scenario->corePath)
    @if(view()->exists($scenario->corePath.".exclusiveRules"))
        @section('exclusiveRules')
            <h2>Exclusive Rules</h2>
            <ol>
                @include('wargame::Mollwitz.common-exclusive-rules');
                @include($scenario->corePath.".exclusiveRules")
            </ol>
        @endsection
    @endif
@endisset

@if(view()->exists("$curPath.exclusiveRules"))
    @section('exclusiveRules')
        <h2>Exclusive Rules</h2>
        <ol>
        @include('wargame::Mollwitz.common-exclusive-rules')
        @include("$curPath.exclusiveRules")
        </ol>
    @endsection
@endif

@section('casualty')
    <div class="dropDown"  id="CASWrapper">
        <h4 class="WrapperLabel" title='Casualities'>Cas</h4>
        <div id="TEC" style="display:none;">
            <div class="close">X</div>

            <div class="cas-container"></div>
        </div>
    </div>
@endsection
@if(view()->exists("$curPath.tec"))
@section('tec')
    @include("$curPath.tec")
@endsection
@endif
