<?php
/* not happy about this part :( */
/*
 * The template passes $topCrt
 * that is how a templates tells us which crt to render
 */
$crts = $topCrt->crts;
?>
<div id="crt-buttons">
        <div  ng-click="showCrtTable(crtName)" ng-repeat="(crtName, crt) in topCrt.crts"  ng-show="crtName == curCrt" class="crt-switch" id="@{{crtName}}Table">show  @{{ crt.next }} Table</div>
</div>
<div ng-show="crtName == curCrt" ng-repeat="(crtName, crt) in topCrt.crts">
    <h4>@{{ crtName }} Combat Results Table</h4>
    <div id="odds">
        <span>&nbsp;</span>
        <span ng-repeat="(colId, col) in crt.header" ng-class="{pinned:colId == crt.pinned, selected:colId == crt.selected}" > @{{ col }}</span>
        <div class="clear"></div>
    </div>
        <div ng-repeat="(rowId, row) in crt.table" class="roll " ng-class="(rowId %2 == 1)? playerName:''">
            <span >@{{ rowId + 1}}</span>
            <span ng-repeat="(cellId, cell) in row track by $index" ng-class="{pinned:cellId == crt.pinned, selected:cellId == crt.selected, 'die-roll':cellId == crt.selected && rowId == crt.combatRoll }" >@{{ resultsNames[cell] }}</span>
            <div class="clear"></div>
        </div>
    <h5>@{{ crtOdds }}</h5>
    <div ng-show="showDetails" ng-bind-html="crt.crtOddsExp"></div>
</div>