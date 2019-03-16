@section('tec')
    @include("wargame::TMCW.Manchuria1976.tec")
@endsection
@section('SOP')
    @include('wargame::TMCW.Manchuria1976.commonSequenceOfPlay')
@endsection
@include('wargame::TMCW.Manchuria1976.unitsRules')

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt',['topCrt'=> $top_crt = new \Wargame\TMCW\Manchuria1976\CombatResultsTable()])
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.Manchuria1976.victoryConditions')
@endsection
@section('supply-rules')
    <span class="big">Supply</span>
    In order for units to move and attack at full strength they must be in supply. The two
    sides units get supply in different ways.
    <ol>
        <li>
            <span class="lessBig">PRC Supply</span>
            <ol>
                <li>
                    All PRC units are always in supply when inside China.
                </li>
                <li>
                    If they are inside the soviet union, they must
                    trace a line of contiguous hexes back to any hex in china.
                </li>
            </ol>
        </li>
        <li>
            <span class="lessBig">Soviet Supply</span>
            <ol>
                <li>
                    Each unit of the Soviet must trace a line of 10 hexes back to the Soviet Union OR to a road that can be traced back to the
                    Soviet Union.
                </li>
                <li>
                    A unit may trace a line of hexes no longer than 6 hexes back to a soviet supply unit.
                </li>
            </ol>

        </li>
        <li>
            <span class="lessBig">Supply Lines</span>
            <ol>
                <li>
                    For both sides supply line may never enter prohibited terrain nor may the enter an enemy ZOC. Friendly units
                    to block enemy ZOC's for purposes of tracing supply.
                </li>
            </ol>
        </li>
    </ol>
@endsection
@section('credit')
    <div id="credits">
        <h2><?= $name ?></h2>
        <h4>Design Credits</h4>

        <h4>Game Design:</h4>
        Lance Runolfsson
        <h4>Graphics and Rules:</h4>
        Lance Runolfsson & David M. Rodal
        <h4>HTML 5 Version:</h4>
        David M. Rodal
    </div>
@endsection
@section('exclusiveRules')
    @include('wargame::TMCW.Manchuria1976.exclusiveRules')
@endsection


@section('obc')
    @include('wargame::TMCW.Manchuria1976.obc')
@endsection

@extends('wargame::TMCW.commonRules')