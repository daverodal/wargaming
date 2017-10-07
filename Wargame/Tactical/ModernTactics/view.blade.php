@include('wargame::global-header')
@include('wargame::Tactical.header')
@include('wargame::Tactical.ModernTactics.ModernTacticsHeader')
<style type="text/css">
    <?php
    include_once "Wargame/Tactical/ModernTactics/all.css";
?>
</style>
</head>

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Wargame\Tactical\ModernTactics\CombatResultsTable(BLUE_FORCE)])
@endsection
@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('units')
    @foreach ($units as $unit)
        <div class="unit {{$unit['class']}} {{$unit['type']}} topDiv smallUnit" id="{{$unit['id']}}">
            <div class="shadow-mask"></div>
            <img class="arrow" src="{{asset('js/short-red-arrow-md.png')}}" class="counter">
            <span class="unit-desig">{{$unit['unitDesig']}}</span>

            <div class="counterWrapper">
                <div class="unitNumbers attack">
                    6
                </div>
                <div class="unitNumbers rangewe">
                    {{$unit['range']}}
                </div>
                <div class="unitNumbers weapons">
                    {{$unit['weapons']}}
                </div>
                <div class="type-wrapper artillery-svg">
                    <svg width="15" height="21" viewBox="0 0 10 20">
                        <line x1="5" x2="5" y1="0" y2="20" stroke-width="2"></line>
                        <line x1="0" x2="10" y1="10" y2="10"></line>
                        <line x1="1" x2="1" y1="4" y2="16"></line>
                        <line x1="9" x2="9" y1="4" y2="16"></line>
                    </svg>
                </div>
                <div class="type-wrapper howitzer-svg">
                    <svg width="15" height="21" viewBox="0 0 10 20">
                        <line x1="5" x2="5" y1="0" y2="14" stroke-width="2"></line>
                        <line x1="0" x2="10" y1="9" y2="9"></line>
                        <line x1="1" x2="1" y1="4" y2="14"></line>
                        <line x1="9" x2="9" y1="4" y2="14"></line>
                        <circle r="2" cx="5" cy="17" fill="transparent"></circle>
                    </svg>
                </div>
                <div class="type-wrapper anti-tank-svg">
                    <svg width="15" height="21" viewBox="0 0 10 20">
                        <line x1="5" x2="5" y1="0" y2="14" stroke-width="2"></line>
                        <line x1="0" x2="10" y1="9" y2="9"></line>
                        <line x1="1" x2="1" y1="4" y2="14"></line>
                        <line x1="9" x2="9" y1="4" y2="14"></line>
                        <line x1="2" x2="5" y1="17" y2="14"></line>
                        <line x1="8" x2="5" y1="17" y2="14"></line>
                    </svg>
                </div>
                <div class="type-wrapper mg-svg">
                    <svg width="10" height="20" viewBox="0 0 10 20">
                        <line x1="5" x2="5" y1="0" y2="20" stroke-width="2"></line>
                        <line x1="5" x2="0" y1="1" y2="5"></line>
                        <line x1="5" x2="10" y1="1" y2="5"></line>
                        <line x1="0" x2="10" y1="10" y2="10"></line>
                        <line x1="0" x2="10" y1="13" y2="13"></line>
                    </svg>
                </div>

                <div class="type-wrapper mech-svg">
                    <svg  width='20' height='10'>
                        <g transform="scale(0.1)">
                            <rect x="0" y="0" width="200" height="100" storke="white" stroke-width="13" fill="none"></rect>
                            <path d="M 70,25 C 20,25 20,75 70,75" stroke="black" stroke-width="8" fill="none" />
                            <path d="M 130,25 C 180,25 180,75 130,75" stroke="black" stroke-width="8" fill="none" />
                            <line x1="0" y1="0" x2="199" y2="99" stroke="black" stroke-width="6" fill="none"/>
                            <line x1="0" y1="99" x2="199" y2="0" stroke="black" stroke-width="6" fill="none"/>
                            <line x1="70" y1="25" x2="130" y2="25" stroke="black" stroke-width="8" fill="none" />
                            <line x1="70" y1="75" x2="130" y2="75" stroke="black" stroke-width="8" fill="none"  />
                        </g>
                    </svg>
                </div>
                <div class="type-wrapper armor-svg">
                    <svg  width='20' height='10'>
                        <g transform="scale(0.1)">
                            <rect x="0" y="0" width="200" height="100" storke="white" stroke-width="13" fill="none"></rect>
                            <path d="M 70,25 C 20,25 20,75 70,75" stroke="black" stroke-width="8" fill="none" />
                            <path d="M 130,25 C 180,25 180,75 130,75" stroke="black" stroke-width="8" fill="none" />
                            <line x1="70" y1="25" x2="130" y2="25" stroke="black" stroke-width="8" fill="none" />
                            <line x1="70" y1="75" x2="130" y2="75" stroke="black" stroke-width="8" fill="none"  />
                        </g>
                    </svg>
                </div>
                <div class="type-wrapper infantry-svg">
                    <svg width="20" height="10" viewBox="0 0 40 20">
                        <line x1="1" x2="1" y1="0" y2="20" stroke-width="2"></line>
                        <line x1="0" x2="40" y1="19" y2="19" stroke-width="2"></line>
                        <line x1="39" x2="39" y1="0" y2="20" stroke-width="2"></line>
                        <line x1="0" x2="40" y1="1" y2="1" stroke-width="2"></line>
                        <line x1="1" x2="39" y1="1" y2="19" stroke-width="2"></line>
                        <line x1="1" x2="39" y1="19" y2="1" stroke-width="2"></line>
                    </svg>
                </div>
                <div class="type-wrapper cavalry-svg">
                    <svg width="12" height="12" viewBox="0 0 20 20">
                        <line x1="1" x2="1" y1="0" y2="20" stroke-width="2"></line>
                        <line x1="0" x2="20" y1="19" y2="19" stroke-width="2"></line>
                        <line x1="19" x2="19" y1="0" y2="20" stroke-width="2"></line>
                        <line x1="0" x2="20" y1="1" y2="1" stroke-width="2"></line>
                        <line x1="1" x2="19" y1="19" y2="1" stroke-width="2"></line>
                    </svg>
                </div>
                <div class="type-wrapper truck"><i class="fa fa-truck"></i></div>
                <div class="unitNumbers defense">
                    [13]
                </div>
                <div class="unitNumbers movement">
                    3
                </div>
            </div>
        </div>
    @endforeach
@endsection




@section('victoryConditions')
    @include('wargame::Tactical.ModernTactics.victoryConditions')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::TMCW.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::Tactical.obc')
@endsection

@include('wargame::stdIncludes.view' )
