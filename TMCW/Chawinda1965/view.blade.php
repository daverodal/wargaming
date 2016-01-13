@include('wargame::global-header')
@include('wargame::TMCW.Chawinda1965.chawinda1965Header')


<style type="text/css">
</style>
</head>
<?php
global $force_name;
$playerNum = !empty($playerNum) ? $playerNum : 0;
$player = $forceName[$playerNum];
$youAre = $forceName[$playerNum];
$playerOne = $forceName[1];
$playerTwo = $forceName[2];
        $deployOne = $deployName[1];
    $deployTwo = $deployName
    ;
    //@include_once "view.php";
        ?>
@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
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
function renderOuterUnit(id, unit){
if(unit.isReduced){
$("#"+id+" .unitSize").html('x');
}else{
$("#"+id+" .unitSize").html('xx');
}
if(unit.supplied){
$("#"+id+" .unsupplied").removeClass('show');
}else{
$("#"+id+" .unsupplied").addClass('show');
}
}

function renderUnitNumbers(unit, moveAmount){


var  move = unit.maxMove - unit.moveAmountUsed;
if(moveAmount !== undefined){
move = moveAmount-0;
}
move = move.toFixed(2);
move = move.replace(/\.00$/,'');
move = move.replace(/(\.[1-9])0$/,'$1');
var str = unit.strength;
var reduced = unit.isReduced;
var reduceDisp = "<span class='unit-info'>";
        if(reduced){
            reduceDisp = "<span class='unit-info reduced'>";
        }
        var supSymb = '-';

        var symb = supSymb+unit.defStrength+supSymb;
        var html = reduceDisp + str + symb + move + "</span>";
        return html;



    }
</script>
    <style type="text/css">
    <?php
    include_once "TMCW/Chawinda1965/all.css";
    ?>
</style>