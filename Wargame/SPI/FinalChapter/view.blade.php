@include('wargame::global-header')
@include('wargame::SPI.FinalChapter.finalChapterHeader')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/spi/finalchapter/css/all.css')}}">
</head>

@section('victoryConditions')
    @include('wargame::SPI.FinalChapter.victoryConditions')
@endsection

@section('tec')
    @include("wargame::SPI.FinalChapter.tec")
@endsection


@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Wargame\SPI\FinalChapter\CombatResultsTable()])
@endsection

@section('commonRules')
    @include('wargame::SPI.commonRules')
@endsection

@section('commonUnitsRules')
    @include("wargame::SPI.FinalChapter.commonUnitsRules")
@endsection



@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection
@section('unit-boxes')
    <div class="unit-wrapper" style="display:none;" id="deadpile">
        <div class="close">X</div>
        <div style="right:10px;font-size:50px;font-family:sans-serif;bottom:10px;position:absolute;color:#666;">
            Retired Units
        </div>
        <div class="dead-country" id="western">
            <div class="dead-country-label">
                Western
            </div>
        </div>
        <div class="dead-country" id="westGerman">
            <div class="dead-country-label">
                West German
            </div>
        </div>
        <div class="dead-country" id="eastGerman">
            <div class="dead-country-label">
                East German
            </div>
        </div>
        <div class="dead-country" id="eastern">
            <div class="dead-country-label">
                Eastern
            </div>
        </div>
        <div class="clear"></div>
    </div>
    <div class="unit-wrapper" style="display:none;" id="undeadpile"></div>
@endsection


@section('outer-units-menu')
    <div class="dropDown" id="hideShow">
        <h4 class="WrapperLabel" title="Offmap Units">Units</h4>
    </div>
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
