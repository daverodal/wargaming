@include('wargame::global-header')
@include('wargame::TMCW.Chawinda1965.chawinda1965Header')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/tmcw/chawinda1965/css/all.css')}}">


<style type="text/css">
</style>
</head>

@section('innerNextPhaseWrapper')
    @parent
    <button class="dynamicButton movementButton" id="splitEvent">s</button>
    <button class="dynamicButton movementButton" id="combineEvent">c</button>
@endsection
@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection
@section('exclusiveRules')
    @include('wargame::TMCW.Chawinda1965.exclusiveRules')
@endsection
@section('victoryConditions')
    @include('wargame::TMCW.Chawinda1965.victoryConditions')
@endsection
@section('obc')
    @include('wargame::TMCW.Chawinda1965.obc')
    <div style="display:none;" id="combinedWrapper">
        <div class="close">X</div>
        <div style="margin-right:3px;" class="left">Combined Units</div>
        <div id="combined-box">
        </div>
        <div style="clear:both;"></div>
    </div>
@endsection

@section('inner-crt')
    @include('wargame::stdIncludes.inner-crt', ['topCrt'=> new \Wargame\TMCW\Chawinda1965\CombatResultsTable()])
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
@include('wargame::stdIncludes.view' )
<script type="text/javascript">
function renderCrtDetails(combat) {
var atk = combat.attackStrength;
var def = combat.defenseStrength;
var div = atk / def;
var ter = combat.terrainCombatEffect;
var combatCol = combat.index + 1;

var html = "<div id='crtDetails'>" + combat.combatLog + "</div><div class='clear'>Attack = " + atk + " / Defender " + def + " = " + div + "<br>Final Column = " + $(".col" + combatCol).html() + "</div>"
/*+ atk + " - Defender " + def + " = " + diff + "</div>";*/
return html;
}
</script>
