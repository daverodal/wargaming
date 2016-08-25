<?php
/* not happy about this part :( */
/*
 * The template passes $topCrt
 * that is how a templates tells us which crt to render
 */
$crts = $topCrt->crts;
?>
<div ng-show="crtName == curCrt" ng-repeat="(crtName, crt) in topCrt.crts">
    <div id="odds">
        <span>&nbsp;</span>
        <span ng-repeat="(colId, col) in crt.header" ng-class="{pinned:colId == crt.pinned, selected:colId == crt.selected}" > @{{ col }}</span>
        <div class="clear"></div>
    </div>
    <div class="shadow-wrapper">
        <div ng-class="topScreen" class="screen screen-one shadowy"></div>
        <div ng-repeat="(rowId, row) in crt.table" class="roll " ng-class="(rowId %2 == 0)? playerName:''">
            <span >@{{ rowId + topCrt.rowNum }}</span>
            <span ng-repeat="(cellId, cell) in row track by $index" ng-class="{pinned:cellId == crt.pinned, selected:cellId == crt.selected, 'die-roll':cellId == crt.selected && rowId == crt.combatRoll }" >@{{ resultsNames[cell] }}</span>
            <div class="clear"></div>
        </div>
        <div ng-class="bottomScreen" class="screen screen-two shadowy"></div>
    </div>
    <h5 class="crt-odds"> @{{ crtOdds }}</h5>
    <div ng-show="showDetails" ng-bind-html="crt.crtOddsExp"></div>
</div>