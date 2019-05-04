/**
 * Created by david on 10/14/17.
 */
/**
 * Created by PhpStorm.
 * User: david
 * Date: 10/14/17
 * Time: 1:46 PM

 /*
 * Copyright 2012-2017 David Rodal

 * This program is free software; you can redistribute it
 * and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation;
 * either version 2 of the License, or (at your option) any later version

 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */
import {GameController} from '../../wargame-helpers/ng-global-imports';



import { syncObj } from "../../wargame-helpers/Vue/syncObj";
import "lodash";

export class CollapseCtlr extends GameController {

    constructor($scope, $http, sync, $sce){

        super($scope, $http, sync, $sce);
        this.$scope = $scope;
        this.$scope.chooseOption = this.chooseOption;
        this.$scope.addToFree    = this.addToFree;
        this.$scope.removeFromFree = this.removeFromFree;
        this.freeDeployStrength = { count: 0};
        this.freeDeployMap = {};
        $scope.freeDeployStrength = this.freeDeployStrength;
        $scope.freeDeployMap = this.freeDeployMap;
    }


    specialHexes(){
        this.sync.register("specialHexes", function(specialHexes, data) {
            $('.specialHexes').remove();
            var lab = ['unowned','<?=strtolower($forceName[1])?>','<?=strtolower($forceName[2])?>'];
            for(var i in specialHexes){
                var newHtml = lab[specialHexes[i]];
                var curHtml = $("#special"+i).html();

                if(true || newHtml != curHtml){
                    var hexPos = i.replace(/\.\d*/g,'');
                    var x = hexPos.match(/x(\d*)y/)[1];
                    var y = hexPos.match(/y(\d*)\D*/)[1];
                    $("#special"+hexPos).remove();
                    if(data.specialHexesChanges[i]){
                        $("#gameImages").append('<div id="special'+hexPos+'" style="border-radius:30px;border:10px solid black;top:'+y+'px;left:'+x+'px;font-size:205px;z-index:1000;" class="'+lab[specialHexes[i]]+' specialHexes">'+lab[specialHexes[i]]+'</div>');
                        $('#special'+hexPos).animate({fontSize:"16px",zIndex:0,borderWidth:"0px",borderRadius:"0px"},1900,function(){
                            var id = $(this).attr('id');
                            id = id.replace(/special/,'');


                            if(data.specialHexesVictory[id]){
                                var hexPos = id.replace(/\.\d*/g,'');

                                var x = hexPos.match(/x(\d*)y/)[1];
                                var y = hexPos.match(/y(\d*)\D*/)[1];
                                var newVP = $('<div style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').insertAfter('#special'+i);
                                $(newVP).animate({top:y-30,opacity:0.0},1900,function(){
                                    $(this).remove();
                                });
                            }
                        });

                    }else{
                        if(specialHexes[i] == 1 && i != 'x416y357'){
                            $("#gameImages").append('<div id="special'+i+'" class="specialHexes fa fa-adjust supply"></div>');
                            $("#special"+i).css({top:y+"px", left:x+"px"}).addClass(lab[specialHexes[i]]);
                        }else{
                            $("#gameImages").append('<div id="special'+i+'" class="specialHexes">'+lab[specialHexes[i]]+'</div>');
                            $("#special"+i).css({top:y+"px", left:x+"px"}).addClass(lab[specialHexes[i]]);                    }
                    }

                }
            }

            for(var id in data.specialHexesVictory)
            {
                if(data.specialHexesChanges[id]){
                    continue;
                }
                var hexPos = id.replace(/\.\d*/g,'');
                var x = hexPos.match(/x(\d*)y/)[1];
                var y = hexPos.match(/y(\d*)\D*/)[1];
                var newVP = $('<div  style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').appendTo('#gameImages');
                $(newVP).animate({top:y-30,opacity:0.0},1900,function(){
                    var id = $(this).attr('id');

                    $(this).remove();
                });
            }


        });
    }

}


var gameApp = angular.module('gameApp', ['ngRightClick', 'ngSanitize']);
gameApp.controller('GameController',  CollapseCtlr);




gameApp.directive('offmapUnit', () => {
    return {
        restrict: 'E',
        templateUrl: 'offmap-unit.html',
        scope:{
            unit: "<"
        }
    }
});

gameApp.directive('unit', () =>  {
    return {
        restrict: 'E',
        templateUrl: 'unit.html',
        scope:{
            unit: "<",
            rightClickMe: '&'
        }
    }
});

gameApp.directive('ghostUnit', () => {
    return {
        restrict: 'E',
        templateUrl: 'ghost-unit.html',
        scope:{
            unit: "<"
        }
    }
});

gameApp.factory('sync', () => {
    /* fetchUrl is defined in ng-global-header.blade.php */
    return syncObj;
});



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
