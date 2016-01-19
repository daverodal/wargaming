@include('wargame::global-header')
@include('wargame::Mollwitz.header')
@include("$curPath.$clsName"."Header")
<style type="text/css">

<?php  include_once "Wargame/Mollwitz/$clsName/all.css";?>
</style>
@if(view()->exists("$curPath.victoryConditions"))
    @section('victoryConditions')
        @include("$curPath.victoryConditions")
    @endsection
@endif

@if(view()->exists("$curPath.exclusiveRules"))
    @section('exclusiveRules')
        @include("$curPath.exclusiveRules")
    @endsection
@endif

@include('wargame::Mollwitz.view')
@include('wargame::stdIncludes.view' )
