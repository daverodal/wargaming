@include('wargame::global-header')
@include('wargame::TMCW.KievCorps.kievHeader')
<style type="text/css">
    <?php
    include_once "Wargame/TMCW/KievCorps/all.css";
?>
</style>
</head>

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Wargame\TMCW\KievCorps\CombatResultsTable()])
@endsection

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.Amph.victoryConditions')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::TMCW.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::TMCW.obc')
@endsection
@section('units')
    @foreach ($units as $unit)
        <div class="unit {{$unit['nationality']}}" id="{{$unit['id']}}" alt="0">
            <div class="steps">
                <div class="step"></div>
                <div class="step"></div>
            </div>
            <div class="shadow-mask"></div>
            <div class="unitSize">{{$unit['unitSize']}}</div>
            <img class="arrow" src="{{asset('js/short-red-arrow-md.png')}}" class="counter">
            <div class="counterWrapper">
                <img src="{{asset("js/".$unit['image'])}}" class="counter"><span class="unit-desig"><?=$unit['unitDesig']?></span>
            </div>
            <div class="unit-numbers">5 - 4</div>
        </div>
    @endforeach
@show
@include('wargame::stdIncludes.view' )
