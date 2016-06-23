@include('wargame::global-header')
@include('wargame::TMCW.Airborne.airborneHeader')
<style type="text/css">
    <?php
    include_once "Wargame/TMCW/Airborne/all.css";
?>
</style>
</head>
@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Wargame\TMCW\KievCorps\CombatResultsTable(REBEL_FORCE)])
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
    @include('wargame::TMCW.Airborne.obc')
@endsection

@section('unitzs')
    @foreach ($units as $unit)
        <div class="unit {{$unit['nationality']}}" id="{{$unit['id']}}" alt="0">
            <div class="shadow-mask"></div>
            <div class="unitSize">{{$unit['unitSize']}}</div>
            <img class="arrow" src="{{asset('js/short-red-arrow-md.png')}}" class="counter">
            <div class="counterWrapper">
                @if($unit['image'] == 'multiInf.png')
                <svg class="counter-symbol" width="18" height="12" viewBox="0 0 20 10">
                    <line x1="1" x2="1" y1="0" y2="12"  stroke-width="1.2"></line>
                    <line x1="1" x2="19.5" y1="11" y2="11"  stroke-width="1.2"></line>
                    <line x1="19" x2="19" y1="0" y2="11"  stroke-width="1.2"></line>
                    <line x1="0.5" x2="19.5" y1="0" y2="0"   stroke-width="1.2"></line>
                    <line x1="1" x2="19" y1="0" y2="12"  stroke-width="1.2"></line>
                    <line x1="1" x2="19" y1="12" y2="0" stroke-width="1.2"></line>
                </svg>
                @endif

                    @if($unit['image'] == 'multiGlider.png')
                        <svg class="counter-symbol" width="18" height="9" viewBox="0 0 20 10">
                            <line x1="1" x2="1" y1="0" y2="10" stroke-width="1.5"></line>
                            <line x1="0" x2="20" y1="9" y2="9" stroke-width="1.5"></line>
                            <line x1="19" x2="19" y1="0" y2="10" stroke-width="1.5"></line>
                            <line x1="0" x2="20" y1="1" y2="1" stroke-width="1.5"></line>
                            <line x1="3" x2="17" y1="4.5" y2="4.5" stroke-width="1.5"></line>\
                            <line x1="1" x2="19" y1="1" y2="9" stroke-width="1.5"></line>
                            <line x1="1" x2="19" y1="9" y2="1" stroke-width="1.5"></line>
                        </svg>
                    @endif

                <span class="unit-desig"><?=$unit['unitDesig']?></span>
            </div>
            <div class="unit-numbers">5 - 4</div>
        </div>
    @endforeach
@endsection
@include('wargame::stdIncludes.view' )
