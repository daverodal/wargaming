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
import "lodash";

export class NorthVsSouthCtlr extends GameController {

    constructor($scope, $http, sync, $sce){

        super($scope, $http, sync, $sce);
        this.$scope = $scope;
    }


    vvictory(){
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

