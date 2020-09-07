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
import "angular"
import "angular-sanitize"
import "angular-right-click"
import 'jquery-ui-bundle';
import initialize from "../../wargame-helpers/initialize";
import {doitNext, DR} from "@markarian/wargame-helpers";

import {GameController} from '../../wargame-helpers/game-controller';
import { syncObj } from "@markarian/wargame-helpers";
import "lodash";
DR.globalZoom = 1;
DR.playerNameMap = ["Zero", "One", "Two", "Three", "Four"];

DR.players = ["observer", DR.playerOne, DR.playerTwo, DR.playerThree, DR.playerFour];
DR.crtDetails = false;
DR.showArrows = false;
document.addEventListener("DOMContentLoaded",function() {
    initialize();
    var $panzoom = $('#gameContainer').panzoom({
        cursor: "normal", animate: true, maxScale: 2.0, minScale: .3, onPan: function (e, panzoom, e2, e3, e4) {
        },
        onZoom: function (e, p, q) {
            DR.globalZoom = q;
            DR.doingZoom = true;
            var out = DR.globalZoom.toFixed(1);

            $("#zoom .defaultZoom").html(out);
        },
        onEnd: function (a, b, c, d, e) {

            let clientX = a.clientX;
            let clientY = a.clientY;
            if (a.originalEvent.changedTouches) {
                clientX = a.originalEvent.changedTouches[0].clientX;
                clientY = a.originalEvent.changedTouches[0].clientY;
            }

            var xDrag = Math.abs(clientX - DR.clickX);
            var yDrag = Math.abs(clientY - DR.clickY);

            if (xDrag > 20 || yDrag > 20) {
                DR.dragged = true;
            } else {
                if (DR.doingZoom !== true && a.originalEvent.changedTouches) {
                    if (a.target.id === 'arrow-svg') {
                        mapClick(a.originalEvent);
                    } else {
                        const now = Date.now() - 0;
                        const time = now - DR.startTime;
                        if (time > 600) {
                            a.ctrlKey = false;
                            rotateUnits(a, a.target.parentElement);
                        } else {
                            counterClick(a);
                        }
                    }
                }
            }

            DR.doingZoom = false;
            return false;
        },
        onStart: function (a, b, c, d, e) {

            DR.doingZoom = false;

            DR.dragged = false;
            DR.startTime = Date.now() - 0;
            if (c.changedTouches) {
                DR.clickX = c.changedTouches[0].clientX;
                DR.clickY = c.changedTouches[0].clientY;
            } else {
                DR.clickX = c.clientX;
                DR.clickY = c.clientY;
            }
        }
    });

    $panzoom.parent().on('mousewheel DOMMouseScroll MozMousePixelScroll', function (e) {
        e.preventDefault();
        var delta = e.delta || e.originalEvent.wheelDelta;

        var zoomOut = delta ? delta < 0 : e.originalEvent.deltaY > 0;

        $panzoom.panzoom('zoom', zoomOut, {
            increment: 0.1,
            animate: false,
            focal: e
        });
    });

    DR.$panzoom = $panzoom;
    // fixHeader();
});
export class CollapseCtlr extends GameController {

    constructor($scope, $http, sync, $sce){

        /* did it */

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
        this.sync.register("vp", (vp, data) => {
            /* do nothing */
        });
        this.sync.register("victory", (victory, data) => {
            const gvp = victory.victoryPoints[2];
            const svp = victory.victoryPoints[1];
            this.$scope.ratio = "";
            this.$scope.winner = "";
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
        this.sync.register("mapSymbols", function (mapSymbols, data) {
            $(".mapSymbols").remove();
            for (var i in mapSymbols) {

                var hexPos = i.replace(/\.\d*/g, '');
                var x = hexPos.match(/x(\d*)y/)[1];
                var y = hexPos.match(/y(\d*)\D*/)[1];
                $("#mapSymbol" + i).remove();

                for (var symbolName in mapSymbols[i]) {
                    var newHtml;

                    var c = mapSymbols[i][symbolName].class
                    $("#mapSymbol" + hexPos + " " + c).remove();
                    newHtml = '<i class="' + c + '"></i>';
                    if (mapSymbols[i][symbolName].image) {
                        newHtml = '<img src="'+rowSvg + '" class="' + c + '">';
                    }
                    $("#gameImages").append('<div id="mapSymbol' + i + '" class="mapSymbols">' + newHtml + '</div>');
                    $("#mapSymbol" + i).css({top: y + "px", left: x + "px"});

                }

            }
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

