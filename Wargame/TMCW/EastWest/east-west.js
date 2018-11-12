/**
 /**
 * Created by david on 7/14/18.
 */
/**
 * Created by PhpStorm.
 * User: david
 * Date: 7/14/18
 * Time: 9:55 AM

 /*
 * Copyright 2012-2018 David Rodal

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

import "../../../../Medieval/Medieval/ngGameMain";
import { doitNext} from "../../wargame-helpers/global-funcs";

import { GameController } from "../../../../Medieval/Medieval/game-controller";
import { Sync } from "../../wargame-helpers/Sync";
import fixHeader from '../../wargame-helpers/fix-header';

import "lodash";
let up = 0;
document.addEventListener("DOMContentLoaded",function() {

    $("#showIsrael").click(function () {
        debugger;
        up ^= 1;
        $("#israelWrapper").toggle({effect: "blind", direction: "up", complete: fixHeader});
        $(this).parent().parent().find(".WrapperLabel").click();
        fixHeader();
        return;
    });

    $("#showGermany").click(function () {
        up ^= 1;
        $("#germanyWrapper").toggle({effect: "blind", direction: "up", complete: fixHeader});
        $(this).parent().parent().find(".WrapperLabel").click();
        fixHeader();
        return;
    });

    $("#showOman").click(function () {
        up ^= 1;
        $("#omanWrapper").toggle({effect: "blind", direction: "up", complete: fixHeader});
        $(this).parent().parent().find(".WrapperLabel").click();
        fixHeader();
        return;
    });
});
export class NorthVsSouthCtlr extends GameController {

    constructor($scope, $http, sync, $sce){

        super($scope, $http, sync, $sce);
        this.$scope = $scope;
        this.$scope.chooseOption = this.chooseOption;
        this.$scope.addToFree    = this.addToFree;
        this.$scope.removeFromFree = this.removeFromFree;
        this.freeDeployStrength = { count: 0};
        this.freeDeployMap = {};
        this.$scope.joy = 'luck';
        this.$scope.victory = 'defeat';
        $scope.freeDeployStrength = this.freeDeployStrength;
        $scope.freeDeployMap = this.freeDeployMap;

    }

    $onInit(){
        this.sync.fetch(0);
    }
    specialHexes(){
        this.sync.register("specialHexes", (specialHexes, data) => {

            DR.$('.specialHexes').remove();
            var lab = ['unowned',DR.players[1],DR.players[2]];
            let cityValues = data.victory.cityValues;
            for(var i in specialHexes){
                var newHtml = lab[specialHexes[i]];
                var curHtml = DR.$("#special"+i).html();

                if(true || newHtml != curHtml){
                    var hexPos = i.replace(/\.\d*/g,'');
                    var x = hexPos.match(/x(\d*)y/)[1];
                    var y = hexPos.match(/y(\d*)\D*/)[1];
                    DR.$("#special"+hexPos).remove();
                    if(data.specialHexesChanges[i]){
                        DR.$("#gameImages").append('<div id="special'+hexPos+'" style="border-radius:30px;border:10px solid black;top:'+y+'px;left:'+x+'px;font-size:205px;z-index:1000;" class="'+lab[specialHexes[i]]+' specialHexes">'+lab[specialHexes[i]]+'</div>');
                        DR.$('#special'+hexPos).animate({fontSize:"16px",zIndex:0,borderWidth:"0px",borderRadius:"0px"},1900,function(){
                            var id = DR.$(this).attr('id');
                            id = id.replace(/special/,'');


                            if(data.specialHexesVictory[id]){
                                var hexPos = id.replace(/\.\d*/g,'');

                                var x = hexPos.match(/x(\d*)y/)[1];
                                var y = hexPos.match(/y(\d*)\D*/)[1];
                                var newVP = DR.$('<div style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').insertAfter('#special'+i);
                                DR.$(newVP).animate({top:y-30,opacity:0.0},1900,function(){
                                    DR.$(this).remove();
                                });
                            }
                        });

                    }else{

                        let hexNum = data.specialHexesMap[hexPos];
                        hexNum = hexNum.replace(/^0/,'');
                        DR.$("#gameImages").append('<div id="special'+i+'" class="specialHexes">'+lab[specialHexes[i]]+ ' ' + cityValues[hexNum] + '</div>');
                        DR.$("#special"+i).css({top:y+"px", left:x+"px"}).addClass(lab[specialHexes[i]]);
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
                var newVP = DR.$('<div  style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').appendTo('#gameImages');
                DR.$(newVP).animate({top:y-30,opacity:0.0},1900,function(){
                    var id = DR.$(this).attr('id');

                    DR.$(this).remove();
                });
            }


        });

    }

    mapUnits(){
        let $scope = this.$scope;
        this.sync.register('mapUnits',  (mapUnits, data) => {
            var gameUnits = {};
            var deployUnits = [];
            var retiredUnits = [];
            var notUsedUnits = [];
            var reinforcements = {};
            this.clearHexes();


            var hexesMap = $scope.hexesMap;
            var newUnitHexes = {};
            var unitsMap = $scope.unitsMap;
            var newHexUnits = {};
            $scope.deployMap = {};
            this.freeDeplpyMap = {};
            $scope.freeDeployMap = {}

            $scope.floatMessage.body = $scope.floatMessage.header = '';
            for (var i in mapUnits) {
                var newUnit = $scope.units[i];
                Object.keys(mapUnits[i]).forEach(function (cur, index, arr) {
                    newUnit[cur] = mapUnits[i][cur];
                });
                if(i == 4){
                    console.log(mapUnits[i].x);
                }
                newUnit.hq = mapUnits[i].class === "hq";
                newUnit.commandRadius = 0;
                var range = 0;
                if (mapUnits[i].class === "hq") {
                    range = mapUnits[i].commandRadius;
                    newUnit.commandRadius = ".........".slice(0, range);
                }
                newUnit.supplyRadius = 0;
                if (mapUnits[i].class === "supply") {
                    range = mapUnits[i].supplyRadius;
                }
                if (mapUnits[i].parent === 'gameImages') {
                    newUnit.shift = 0;
                    if (unitsMap[i] === undefined) {
                        unitsMap[i] = mapUnits[i].hexagon;
                        if (hexesMap[mapUnits[i].hexagon] === undefined) {
                            hexesMap[mapUnits[i].hexagon] = [];
                        }
                        hexesMap[mapUnits[i].hexagon].push(i);
                    } else {

                        if (unitsMap[i] !== mapUnits[i].hexagon) {
                            /* unit moved */
                            var dead = hexesMap[unitsMap[i]].indexOf(i);
                            hexesMap[unitsMap[i]].splice(dead, 1);
                            if (hexesMap[mapUnits[i].hexagon] === undefined) {
                                hexesMap[mapUnits[i].hexagon] = [];
                            }
                            hexesMap[mapUnits[i].hexagon].push(i);
                            unitsMap[i] = mapUnits[i].hexagon;
                        }
                    }
                    let zIndex = 1;
                    if (Object.keys(hexesMap[mapUnits[i].hexagon]).length) {
                        let unitsHere = hexesMap[mapUnits[i].hexagon];
                        let sortedUnits = _.sortBy(unitsHere, o => o-0);
                        newUnit.shift = sortedUnits.indexOf(i) * 5;
                        zIndex = 3 - unitsHere.indexOf(i);
                    } else {
                    }
                    newUnit.maxMove = mapUnits[i].maxMove;
                    newUnit.name = mapUnits[i].name;
                    newUnit.command = mapUnits[i].command;
                    newUnit.unitDesig = mapUnits[i].unitDesig;
                    newUnit.moveAmountUsed = mapUnits[i].moveAmountUsed;
                    newUnit.wrapperstyle = {};
//                        newUnit.facingstyle = {};
                    newUnit.wrapperstyle.transform = "rotate(" + mapUnits[i].facing * 60 + "deg)";
                    newUnit.wrapperstyle.top = newUnit.shift + mapUnits[i].y - 20 + "px";
                    newUnit.wrapperstyle.left = newUnit.shift + mapUnits[i].x - 20 + "px";
                    if(i == 4){
                        console.log(newUnit.shift);
                        console.log(newUnit.wrapperstyle.left);
                    }
                    /*
                     * Blaaaaaa Very non angular way to live one's life.........
                     * Should not be removed and reinserted every mouse click.
                     * only about 8 of them so for now :'( tears will stay this way.....
                     */
                    if (mapUnits[i].class === "hq" || mapUnits[i].class === "supply") {
                        DR.hasHq = true;

                        var hexSideLen = 32.0;
                        var b = hexSideLen * .866;

                        /* jquery way */
                        drawHex(b * (range * 2 + 1), mapUnits[i]);
                    }
                    newUnit.wrapperstyle.zIndex = zIndex + 1;
                    newUnit.facing = mapUnits[i].facing;
                    newUnit.strength = mapUnits[i].strength;
                    newUnit.steps = mapUnits[i].steps;
                    newUnit.orgStatus = mapUnits[i].orgStatus;
                    var orgDisp = newUnit.orgStatus == 0 ? 'B' : 'D';
                    if(mapUnits[i].forceMarch){
                        orgDisp = 'M';
                    }
                    newUnit.unitNumbers = newUnit.strength + ' - ' + (newUnit.maxMove - newUnit.moveAmountUsed);
                    newUnit.infoLen = "infoLen" + newUnit.unitNumbers.length;
                    this.decorateUnit(newUnit, data);
                    gameUnits[i] = newUnit;

                } else {
                    if (unitsMap[i] !== undefined) {
                        var dead = hexesMap[unitsMap[i]].indexOf(i);
                        hexesMap[unitsMap[i]].splice(dead, 1);
                        unitsMap[i] = undefined;
                    }
                }
                if (mapUnits[i].parent === 'deployBox' || mapUnits[i].parent === 'israel' || mapUnits[i].parent === 'germany' || mapUnits[i].parent === 'oman') {
                    newUnit.wrapperstyle = {};
                    newUnit.style = {};
                    newUnit.oddsDisp = null;
                    newUnit.strength = mapUnits[i].strength;


                    newUnit.strength = mapUnits[i].strength;
                    newUnit.steps = mapUnits[i].steps;
                    newUnit.orgStatus = mapUnits[i].orgStatus;
                    var orgDisp = newUnit.orgStatus == 0 ? 'B' : 'D';

                    if (mapUnits[i].status == STATUS_DEPLOYING || mapUnits[i].status == STATUS_REINFORCING) {
                        newUnit.style.boxShadow = "5px 5px 5px #333";
                    }
                    newUnit.unitNumbers = newUnit.strength + ' - ' + (newUnit.maxMove - newUnit.moveAmountUsed);
                    newUnit.infoLen = "infoLen" + newUnit.unitNumbers.length;
                    this.decorateUnit(newUnit, data);
                    deployUnits.push(newUnit);
                    let mapIndex = newUnit.strength+'-'+newUnit.maxMove;
                    if(!$scope.deployMap[newUnit.strength+'-'+newUnit.maxMove]){
                        $scope.deployMap[newUnit.strength+'-'+newUnit.maxMove] = {unit: newUnit, count: 0, units: []};
                    }
                    $scope.deployMap[newUnit.strength+'-'+newUnit.maxMove].count++;
                    $scope.deployMap[newUnit.strength+'-'+newUnit.maxMove].units.push(newUnit);
                }

                if (mapUnits[i].parent.match(/gameTurn/)) {
                    if (reinforcements[mapUnits[i].parent] === undefined) {
                        reinforcements[mapUnits[i].parent] = [];
                    }
                    reinforcements[mapUnits[i].parent].push(newUnit);
                }
                if (mapUnits[i].parent === 'deadpile') {
                    newUnit.style = {};
                    newUnit.strength = mapUnits[i].strength;
                    newUnit.style.borderColor = 'rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204)';
                    this.decorateUnit(newUnit, data);
                    retiredUnits.push(newUnit);
                }
            }
            $scope.mapUnits = gameUnits;
            $scope.deployUnits = deployUnits;
            $scope.retiredUnits = retiredUnits;
            $scope.notUsedUnits = notUsedUnits;
            $scope.reinforcements = reinforcements;

        });

    }

    addToFree(key,maplet){
        let totalFreeCount = this.freeDeployStrength.count;
        if(totalFreeCount >= 25){
            return;
        }
        if(totalFreeCount + maplet.unit.strength > 25){
            return;
        }
        if(!this.freeDeployMap[key]){
            this.freeDeployMap[key] = { unit: maplet.unit, count: 0, units:[]}
        }
        let unit = this.deployMap[key].units.pop();
        this.freeDeployMap[key].units.push(unit);
        this.freeDeployMap[key].count++;
        this.deployMap[key].count--;
        this.freeDeployStrength.count += maplet.unit.strength;
        if(this.deployMap[key].count === 0){
            delete this.deployMap[key];
        }
    }

    removeFromFree(key,maplet){
        let totalFreeCount = this.freeDeployStrength.count;
        if(!this.deployMap[key]){
            this.deployMap[key] = { unit: maplet.unit, count: 0, units: []}
        }
        this.deployMap[key].count++;
        let unit = this.freeDeployMap[key].units.pop();
        this.deployMap[key].units.push(unit);
        this.freeDeployMap[key].count--;
        this.freeDeployStrength.count -= maplet.unit.strength;
        if(this.freeDeployMap[key].count === 0){
            delete this.freeDeployMap[key];
        }
    }

    victory(){
        let $myScope = this.$scope;
        this.sync.register("victory", (victory, data) => {

            const gvp = victory.victoryPoints[2];
            const svp = victory.victoryPoints[1];
            this.$scope.ratio = "";
            this.$scope.winner = "";
            this.$scope.airXferPts = victory.airXferPts;
            if(gvp > 0 && svp > 0) {
                this.$scope.ratio = Number.parseFloat(gvp / svp).toFixed(2);
                let winner = "Soviet Decisive";
                if (this.$scope.ratio >= .2){
                    winner = "Soviet Marginal";
                }
                if (this.$scope.ratio >= .5) {
                    winner = "German Marginal"
                }
                if (this.$scope.ratio >= 1.0) {
                    winner = "German Decisive";
                }
                this.$scope.winner = winner;

            }
            this.$scope.victory =
                this.$scope.vp = victory.victoryPoints;
        });
    }

    mapSymbols(){
        this.sync.register("mapSymbols",  (mapSymbols, data) => {
            let $scope = this.$scope;
            $scope.mapSymbols = mapSymbols;
            $scope.imagesBase = imagesBase;
            debugger;
            for(var i in mapSymbols){
                mapSymbols[i].id = i;

                var hexPos = i.replace(/\.\d*/g, '');
                var x = hexPos.match(/x(\d*)y/)[1];
                var y = hexPos.match(/y(\d*)\D*/)[1];
                mapSymbols[i].x = x +'px';
                mapSymbols[i].y = y + 'px';
                mapSymbols[i].image = imagesBase + '/' + mapSymbols[i].airfields.image;
            }
            // $(".mapSymbols").remove();
            // for (var i in mapSymbols) {
            //
            //     var hexPos = i.replace(/\.\d*/g, '');
            //     var x = hexPos.match(/x(\d*)y/)[1];
            //     var y = hexPos.match(/y(\d*)\D*/)[1];
            //     $("#mapSymbol" + i).remove();
            //
            //     for (var symbolName in mapSymbols[i]) {
            //         var newHtml;
            //
            //         var c = mapSymbols[i][symbolName].class
            //         $("#mapSymbol" + hexPos + " " + c).remove();
            //         newHtml = '<i class="' + c + '"></i>';
            //         if (mapSymbols[i][symbolName].image) {
            //             newHtml = '<img src="'+imagesBase + '/' + mapSymbols[i][symbolName].image + '" class="' + c + '">';
            //         }
            //         $("#gameImages").append('<div id="mapSymbol' + i + '" class="mapSymbols">' + newHtml + '</div>');
            //         $("#mapSymbol" + i).css({top: y + "px", left: x + "px"});
            //
            //     }
            //
            // }
        });
    }
    chooseOption(){
        let finalResult = _.reduce(this.freeDeployMap, function(result, value, key) {
            let units = _.reduce(value.units, (result, value, key) => {
                result.push(value.id);
                return result;
            },result);
            return result;
        }, []);
        doitNext(finalResult);
    }

//     decorateUnit(unit, data){
//         const $scope = this.$scope;
//
//         const force = data.force;
//         let color = "#ccc #666 #666 #ccc";
//         let style = "solid";
//         let boxShadow = "none";
//         let shadow = true;
//         if (unit.forceId !== force.attackingForceId) {
//             shadow = false;
//         }
//
//         if (unit.isImproved === true) {
//             style = 'dotted';
//             color = 'black';
//             var colour = $("#" + i).css('color');
//             if (colour === "rgb(255, 255, 255)") {
//                 color = 'white';
//             }
//         }
//
//         switch (unit.status) {
//             case STATUS_CAN_REINFORCE:
//             case STATUS_CAN_DEPLOY:
//                 color = "#ccc #666 #666 #ccc";
//                 shadow = false;
//                 // if (unit.reinforceTurn) {
//                 //     shadow = true;
//                 // }
//                 break;
//             case STATUS_READY:
//                 if (unit.forceId === force.attackingForceId) {
//
//                     shadow = false;
//                 } else {
//                 }
//                 break;
//             case STATUS_REINFORCING:
//             case STATUS_DEPLOYING:
//                 shadow = false;
//                 boxShadow = '5px 5px 5px #333';
//
//
//                 break;
//             case STATUS_MOVING:
//                 color = "lightgreen";
//                 shadow = false;
//                 break;
//
//             case STATUS_STOPPED:
//                 color = "#ccc #666 #666 #ccc";
//                 break;
//             case STATUS_DEFENDING:
//                 color = "orange";
//
//                 break;
//             case STATUS_BOMBARDING:
//
//             case STATUS_ATTACKING:
//
//                 shadow = false;
//                 break;
//
//             case STATUS_CAN_RETREAT:
//                 if (data.gameRules.mode == RETREATING_MODE) {
//                     status = "Click on the Purple Unit to start retreating";
//                 }
//                 color = "purple";
//                 break;
//             case STATUS_RETREATING:
//                 color = "yellow";
//                 if (data.gameRules.mode == RETREATING_MODE) {
//
//                     status = "Now click on a green unit. The yellow unit will retreat there. ";
//                 }
//                 break;
//             case STATUS_CAN_ADVANCE:
//             case STATUS_MUST_ADVANCE:
//                 if (data.gameRules.mode == ADVANCING_MODE) {
//                     status = 'Click on one of the black units to advance it.';
//                 }
//                 color = "black";
//                 if(unit.status === STATUS_MUST_ADVANCE){
//                     color = "lime";
//                     status = 'Click on one of the lime units to advance it.';
//                 }
//                 shadow = false;
//
//                 break;
//             case STATUS_ADVANCING:
//                 if (data.gameRules.mode == ADVANCING_MODE) {
//
//                     status = 'Now click on one of the turquoise units to advance or stay put..';
//                 }
//
//                 shadow = false;
//                 color = "cyan";
//                 break;
//
//             case STATUS_CAN_LOAD:
//                 color = "fuchsia";
//                 shadow = false;
//                 break;
//             case STATUS_CAN_TRANSPORT:
//                 color = "lime";
//                 shadow = false;
//                 break;
//
//             case STATUS_CAN_EXCHANGE:
//                 if (data.gameRules.mode == EXCHANGING_MODE) {
//                     var result = data.combatRules.lastResolvedCombat.combatResult;
// //                    $("#floatMessage header").html(result+' Exchanging Mode');
//                     status = "Click on one of the red units to reduce it."
//                 }
//             case STATUS_CAN_ATTACK_LOSE:
//                 if (data.gameRules.mode == ATTACKER_LOSING_MODE) {
//                     status = "Click on one of the red units to reduce it."
//                 }
//                 color = "red";
//                 break;
//             case STATUS_CAN_DEFEND_LOSE:
//                 if (data.gameRules.mode == DEFENDER_LOSING_MODE) {
//                     status = "Click on one of the red units to reduce it."
//                 }
//                 color = "red";
//                 break;
//             case STATUS_REPLACED:
//                 color = "blue";
//                 break;
//             case STATUS_REPLACING:
//                 color = "orange";
//                 break;
//             case STATUS_CAN_UPGRADE:
//             case STATUS_CAN_REPLACE:
//                 if (unit.forceId === force.attackingForceId) {
//                     shadow = false;
//                     color = "turquoise";
//                 }
//                 break;
//
//             case STATUS_ELIMINATED:
//                 break;
//
//         }
//         if (status) {
//             var x = unit.wrapperstyle.left.replace(/px/, '');
//             var y = unit.wrapperstyle.top.replace(/px/, '');
//             y /= DR.globalZoom;
//             x /= DR.globalZoom;
//
//             var mapWidth = $("body").width();
//             var mapHeight = $("#gameViewer").height() / DR.globalZoom;
//
//
//             var mapOffset = $("#gameImages").position().top;
//
//             if (mapOffset === "auto") {
//                 mapOffset = 0;
//             }
//             var moveAmt;
//
//             if (mapOffset + y > 2 * mapHeight / 3) {
//                 moveAmt = (100 + (mapOffset + y) / 3);
//                 if (moveAmt > 250) {
//                     moveAmt = 250;
//                 }
//                 y -= moveAmt;
//
//
//             } else {
//                 moveAmt = (mapHeight - (mapOffset + y )) / 2;
//                 if (moveAmt > 200) {
//                     moveAmt = 200;
//                 }
//                 y += moveAmt;
//             }
//
//             if (DR.floatMessageDragged != true) {
//                 DR.$floatMessagePanZoom.panzoom('reset');
//                 $scope.floatMessage.top = y + "px";
//                 $scope.floatMessage.left = x + "px";
//             }
//             $scope.floatMessage.body = status;
//             $scope.floatMessage.header = status;
//             status = "";
//         }
//
//         unit.style = {};
//         unit.style.borderColor = color;
//         unit.style.borderStyle = style;
//         unit.style.boxShadow = boxShadow;
//
//         if (shadow) {
//             unit.shadow = 'shadowy';
//         } else {
//             unit.shadow = '';
//         }
//     }
//     force(){
//         let $scope = this.$scope;
//         this.sync.register('force',  (force, data) => {
//             var units = data.mapUnits;
//
//             var showStatus = false;
//             var totalAttackers = 0;
//             var totalDefenders = 0;
//             var i;
//             var color, style, boxShadow, shadow;
//
//             $scope.floatMessage.body = $scope.floatMessage.header = null;
// //                $("#floatMessage").hide();
//
//             return;
//         });
//
//     }
//     clearHexes(){
//         $('#arrow-svg .range-hex').remove();
//     }
//     mapUnits(){
//         let $scope = this.$scope;
//         this.sync.register('mapUnits',  (mapUnits, data) => {
//             var gameUnits = {};
//             var deployUnits = [];
//             var retiredUnits = [];
//             var notUsedUnits = [];
//             var reinforcements = {};
//             this.clearHexes();
//
//
//             var hexesMap = $scope.hexesMap;
//             var newUnitHexes = {};
//             var unitsMap = $scope.unitsMap;
//             var newHexUnits = {};
//             $scope.deployMap = {};
//             this.freeDeplpyMap = {};
//             $scope.freeDeployMap = {}
//
//             for (var i in mapUnits) {
//                 var newUnit = $scope.units[i];
//                 Object.keys(mapUnits[i]).forEach(function (cur, index, arr) {
//                     newUnit[cur] = mapUnits[i][cur];
//                 });
//                 newUnit.hq = mapUnits[i].class === "hq";
//                 newUnit.commandRadius = 0;
//                 var range = 0;
//                 if (mapUnits[i].class === "hq") {
//                     range = mapUnits[i].commandRadius;
//                     newUnit.commandRadius = ".........".slice(0, range);
//                 }
//                 newUnit.supplyRadius = 0;
//                 if (mapUnits[i].class === "supply") {
//                     range = mapUnits[i].supplyRadius;
//                 }
//                 if (mapUnits[i].parent === 'gameImages') {
//                     newUnit.shift = 0;
//                     if (unitsMap[i] === undefined) {
//                         unitsMap[i] = mapUnits[i].hexagon;
//                         if (hexesMap[mapUnits[i].hexagon] === undefined) {
//                             hexesMap[mapUnits[i].hexagon] = [];
//                         }
//                         hexesMap[mapUnits[i].hexagon].push(i);
//                     } else {
//
//                         if (unitsMap[i] !== mapUnits[i].hexagon) {
//                             /* unit moved */
//                             var dead = hexesMap[unitsMap[i]].indexOf(i);
//                             hexesMap[unitsMap[i]].splice(dead, 1);
//                             if (hexesMap[mapUnits[i].hexagon] === undefined) {
//                                 hexesMap[mapUnits[i].hexagon] = [];
//                             }
//                             hexesMap[mapUnits[i].hexagon].push(i);
//                             unitsMap[i] = mapUnits[i].hexagon;
//                         }
//                     }
//                     if (Object.keys(hexesMap[mapUnits[i].hexagon]).length) {
//                         newUnit.shift = hexesMap[mapUnits[i].hexagon].indexOf(i) * 5;
//                     } else {
//                     }
//                     newUnit.maxMove = mapUnits[i].maxMove;
//                     newUnit.name = mapUnits[i].name;
//                     newUnit.command = mapUnits[i].command;
//                     newUnit.unitDesig = mapUnits[i].unitDesig;
//                     newUnit.moveAmountUsed = mapUnits[i].moveAmountUsed;
//                     newUnit.wrapperstyle = {};
// //                        newUnit.facingstyle = {};
//                     newUnit.wrapperstyle.transform = "rotate(" + mapUnits[i].facing * 60 + "deg)";
//                     newUnit.wrapperstyle.top = newUnit.shift + mapUnits[i].y - 20 + "px";
//                     newUnit.wrapperstyle.left = newUnit.shift + mapUnits[i].x - 20 + "px";
//                     /*
//                      * Blaaaaaa Very non angular way to live one's life.........
//                      * Should not be removed and reinserted every mouse click.
//                      * only about 8 of them so for now :'( tears will stay this way.....
//                      */
//                     if (mapUnits[i].class === "hq" || mapUnits[i].class === "supply") {
//                         DR.hasHq = true;
//
//                         var hexSideLen = 32.0;
//                         var b = hexSideLen * .866;
//
//                         /* jquery way */
//                         drawHex(b * (range * 2 + 1), mapUnits[i]);
//                     }
//                     newUnit.wrapperstyle.zIndex = newUnit.shift + 1;
//                     newUnit.facing = mapUnits[i].facing;
//                     newUnit.strength = mapUnits[i].strength;
//                     newUnit.steps = mapUnits[i].steps;
//                     newUnit.orgStatus = mapUnits[i].orgStatus;
//                     var orgDisp = newUnit.orgStatus == 0 ? 'B' : 'D';
//                     if(mapUnits[i].forceMarch){
//                         orgDisp = 'M';
//                     }
//                     newUnit.unitNumbers = newUnit.strength + ' ' + orgDisp + ' ' + (newUnit.maxMove - newUnit.moveAmountUsed);
//                     newUnit.infoLen = "infoLen" + newUnit.unitNumbers.length;
//                     this.decorateUnit(newUnit, data);
//                     gameUnits[i] = newUnit;
//
//                 } else {
//                     if (unitsMap[i] !== undefined) {
//                         var dead = hexesMap[unitsMap[i]].indexOf(i);
//                         hexesMap[unitsMap[i]].splice(dead, 1);
//                         unitsMap[i] = undefined;
//                     }
//                 }
//                 if (mapUnits[i].parent === 'deployBox') {
//                     newUnit.wrapperstyle = {};
//                     newUnit.style = {};
//                     newUnit.oddsDisp = null;
//                     newUnit.strength = mapUnits[i].strength;
//
//
//                     newUnit.strength = mapUnits[i].strength;
//                     newUnit.steps = mapUnits[i].steps;
//                     newUnit.orgStatus = mapUnits[i].orgStatus;
//                     var orgDisp = newUnit.orgStatus == 0 ? 'B' : 'D';
//
//                     if (mapUnits[i].status == STATUS_DEPLOYING || mapUnits[i].status == STATUS_REINFORCING) {
//                         newUnit.style.boxShadow = "5px 5px 5px #333";
//                     }
//                     this.decorateUnit(newUnit, data);
//                     deployUnits.push(newUnit);
//                     let mapIndex = newUnit.strength+'-'+newUnit.maxMove;
//                     if(!$scope.deployMap[newUnit.strength+'-'+newUnit.maxMove]){
//                         $scope.deployMap[newUnit.strength+'-'+newUnit.maxMove] = {unit: newUnit, count: 0, units: []};
//                     }
//                     $scope.deployMap[newUnit.strength+'-'+newUnit.maxMove].count++;
//                     $scope.deployMap[newUnit.strength+'-'+newUnit.maxMove].units.push(newUnit);
//                 }
//
//                 if (mapUnits[i].parent.match(/gameTurn/)) {
//                     if (reinforcements[mapUnits[i].parent] === undefined) {
//                         reinforcements[mapUnits[i].parent] = [];
//                     }
//                     reinforcements[mapUnits[i].parent].push(newUnit);
//                 }
//                 if (mapUnits[i].parent === 'deadpile') {
//                     newUnit.style = {};
//                     newUnit.strength = mapUnits[i].strength;
//                     newUnit.style.borderColor = 'rgb(204, 204, 204) rgb(102, 102, 102) rgb(102, 102, 102) rgb(204, 204, 204)';
//                     this.decorateUnit(newUnit, data);
//                     retiredUnits.push(newUnit);
//                 }
//             }
//             $scope.mapUnits = gameUnits;
//             $scope.deployUnits = deployUnits;
//             $scope.retiredUnits = retiredUnits;
//             $scope.notUsedUnits = notUsedUnits;
//             $scope.reinforcements = reinforcements;
//
//             $scope.$apply();
//         });
//
//     }


}


var gameApp = angular.module('gameApp', ['ngRightClick', 'ngSanitize']);
gameApp.controller('GameController',  NorthVsSouthCtlr);




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
    var sync = new Sync(fetchUrl);
    return sync;
});

