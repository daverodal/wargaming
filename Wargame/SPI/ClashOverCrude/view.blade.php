@include('wargame::global-header')
@include('wargame::SPI.ClashOverCrude.ClashOverCrudeHeader')
<link rel="stylesheet" type="text/css" href="{{elixir('vendor/wargame/spi/css/clashovercrude.css')}}">
</head>
@section('units')
    @foreach($units as $unit)
    <div class="unit {{$unit['nationality']}} {{$unit['type']}}" id="{{$unit['id']}}" alt="0">
        <div class="shadow-mask"></div>
        <img class="arrow" src="<?php echo url('js/short-red-arrow-md.png'); ?>" class="counter">
        <div class="counterWrapper">
            <div class="air-strength"></div>
            <img src="{{url('vendor/wargame/spi/clashovercrude/images/'.$unit["image"])}}" class="counter"><span class="unit-desig">{{$unit['unitDesig']}}</span>
        </div>
        <div class="unit-numbers">5 - 4</div>
    </div>
    @endforeach
    @endsection

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('unit-boxes')
    @parent
    <div class="unit-wrapper" style="display:none;" id="israel">
        <div class="close">X</div>
        <div style="right:10px;font-size:50px;font-family:sans-serif;bottom:10px;position:absolute;color:#666;">
            Retired Units
        </div>
    </div>
    <div class="unit-wrapper" style="display:none;" id="germany">
        <div class="close">X</div>
        <div style="margin-right:3px;" class="left">Exited Units</div>
        <div id="exitBox">
        </div>
        <div style="clear:both;"></div>
    </div>
    <div class="unit-wrapper" style="display:none;" id="oman">
        <div class="close">X</div>
        <div style="margin-right:3px;" class="left">Units not used.</div>
        <div id="not-used"></div>
        <div style="clear:both;"></div>
    </div>
@endsection

@section('victoryConditions')
    @include('wargame::SPI.ClashOverCrude.victoryConditions')
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
