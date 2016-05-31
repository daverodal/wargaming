<?php
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
 *
 *  This program is free software; you can redistribute it
 *  and/or modify it under the terms of the GNU General Public License
 *  as published by the Free Software Foundation;
 *  either version 2 of the License, or (at your option) any later version
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
?><link href='http://fonts.googleapis.com/css?family=Nosifer' rel='stylesheet' type='text/css'>
<style type="text/css">
<?php @include "all.css";?>
</style>
<script type="text/javascript">
x.register("vp", function(vp){
});
x.register("victory", function(victory){
        var vp = victory.victoryPoints;
        var dismissed = victory.dismissed ? " dismissed" : "";
        $("#victory").html(" Victory: <span class='playerOneFace'><?=$forceName[1]?> </span>"+vp[1]+ " <span class='playerTwoFace'><?=$forceName[2];?> </span>"+vp[2]+" Surrounded Soviets "+vp[3] + dismissed);
        //        $("#victory").html(" Victory: <span class='playerOneFace'><?//=$forceName[1]?>// </span>"+vp[1]+ " <span class='playerTwoFace'><?//=$forceName[2];?>// </span>"+vp[2]+" Surrounded Soviets "+vp[3]);
});
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
        var symb = unit.supplied !== false ? " - " : " <span class='reduced'>u</span> ";
//        symb = "-"+unit.defStrength+"-";
        var html = reduceDisp + str + symb + move + "</span>";
//        $("#"+unit.id+" .steps").html(unit.steps);
        var stepsLost = unit.origSteps - unit.steps;
        var stepHtml = "";
        for(var i = 0;i < unit.origSteps;i++){
                if(i < stepsLost){
                        stepHtml += '<div class="white step"></div>';
                }else{
                        stepHtml += '<div class="step"></div>';
                }
        }
        $("#"+unit.id+" .steps").html(stepHtml);
        return html;



}
function renderCrtDetails(combat){
        var atk = combat.attackStrength;
        var def = combat.defenseStrength;
        var div = atk / def;
        var ter = combat.terrainCombatEffect;
        var combatCol = combat.index + 1;

        var html = "<div id='crtDetails'>"+combat.combatLog+"</div><div>Attack = " + atk + " / Defender " + def + " = " + div + "<br>Terrain Effects Shift  " + ter + " = " + $(".col" + combatCol).html() + "</div>"
        /*+ atk + " - Defender " + def + " = " + diff + "</div>";*/
        return html;
}
</script>