@include('wargame::global-header')
@include('wargame::SPI.TinCans.TinCansHeader')
<style type="text/css">
    <?php
         include_once "Wargame/SPI/TinCans/all.css";
?>
</style>
</head>

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('units')
    @foreach($units as $unit)
    <div class="unit {{$unit['nationality']}} {{$unit['type']}}" id="{{$unit['id']}}" alt="0">
        <div class="shadow-mask"></div>
        <img class="heading" src="<?php echo asset('vendor/wargame/spi/tincans/images/blackArrow.svg'); ?>" class="counter">
        <img class="arrow" src="<?php echo url('js/short-red-arrow-md.png'); ?>" class="counter">
        <div class="counterWrapper">
            <div class="top-numbers"><span class="gunnery">{{$unit['origStrength']}}</span><i class="spotted fa"></i> <span class="gun-range">{{$unit['gunRange']}}</span></div>
            <div class="ship-desig">{{$unit['unitSize']}}</div>
            <div class="bottom-numbers unit-numbers"><span class="defense">{defenseStrength}</span> <span class="mp"></span> <span class="torpedo">{torpedoStrength}</span></div>
        </div>
    </div>
    @endforeach
@endsection

@section('crt')
    <h1> War </h1>
    <?php $topCrt = new \Wargame\SPI\TinCans\CombatResultsTable();?>
@endsection

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Wargame\SPI\TinCans\CombatResultsTable()])
@endsection

@section('victoryConditions')
    @include('wargame::SPI.TinCans.victoryConditions')
@endsection

@section('commonRules')
    {{--@include('wargame::SPI.commonRules')--}}
@endsection

@section('exclusiveRules')
    @include('wargame::SPI.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::SPI.FinalChapter.obc')
@endsection

@include('wargame::stdIncludes.view' )
