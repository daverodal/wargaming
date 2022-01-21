import {SyncController} from "@markarian/wargame-vue-components";
import {syncObj} from "@markarian/wargame-helpers";

import {DR} from "@markarian/wargame-helpers";
import Vue from "vue";
export class TinCansSyncController extends SyncController{
    constructor(){
        super();
    }

    mapUnits(){
        syncObj.register("mapUnits",  (mapUnits, data) => {

            var str;
            var fudge;
            var x, y;
            DR.stackModel = {};
            DR.stackModel.ids = {};
            clickThrough.deployBox = [];
            clickThrough.deadpile = [];
            clickThrough.exitBox = [];
            clickThrough.notUsed = [];
            // topVue.units = [];
            // clickThrough.allBoxes = {};
            vueStore.commit('bd/clearBoxes')
            vueStore.commit('mD/clearUnitsMaps')

            const { unitsMap, hexesMap } = vueStore.getters['mD/getUnitsMaps'];

            var phasingForceId = data.gameRules.attackingForceId;

            var phasingUnitsLeft = 0;

            let dispUnits = [];
            for (var i in mapUnits) {
                if (mapUnits[i].forceId === phasingForceId && mapUnits[i].parent === "deployBox") {
                    phasingUnitsLeft++;
                }
                var width = 38;
                var height = 38;

                x = mapUnits[i].x;
                y = mapUnits[i].y;
                if (DR.stackModel[x] === undefined) {
                    DR.stackModel[x] = {};
                }
                if (DR.stackModel[x][y] === undefined) {
                    DR.stackModel[x][y] = {count: 0, ids: {}};
                }
                fudge = 0;
                if (DR.stackModel[x][y].count) {
                    fudge = DR.stackModel[x][y].count * 4;
                }
                DR.stackModel[x][y].count++;
                var zIndex = DR.stackModel[x][y].count;
                /* really looking at the keys so the value can be the same */
                DR.stackModel[x][y].ids[i] = i;
                DR.stackModel.ids[i] = {x: x, y: y};

                if(mapUnits[i].parent !== "gameImages"){
                    mapUnits[i].id = i - 0;
                    mapUnits[i].shadow = false;
                    let slot = mapUnits[i].parent.replace(/-/,"");

                    this.unitDecorate(mapUnits[i], data);
                    vueStore.commit('mD/clearUnitMap', i - 0)
                    vueStore.commit('bd/putUnit', {slot: slot, unit: mapUnits[i]})

                    // if(!Array.isArray(clickThrough.allBoxes[slot])){
                    //     Vue.set(clickThrough.allBoxes, slot, []);
                    // }
                    // clickThrough.allBoxes[slot].push(mapUnits[i]);
                    continue;
                }

                width += 6;
                height += 6;
                if (mapUnits[i].parent === "gameImages") {



                    mapUnits[i].shift = 0;
                    vueStore.commit('mD/unitHexMapper',{i: i, unit: mapUnits[i]})
                    // if (unitsMap[i] === undefined) {
                    //     unitsMap[i] = mapUnits[i].hexagon;
                    //     if (hexesMap[mapUnits[i].hexagon] === undefined) {
                    //         hexesMap[mapUnits[i].hexagon] = [];
                    //     }
                    //     hexesMap[mapUnits[i].hexagon].push(i);
                    // } else {
                    //
                    //     if (unitsMap[i] !== mapUnits[i].hexagon) {
                    //         /* unit moved */
                    //         var dead = hexesMap[unitsMap[i]].indexOf(i);
                    //         hexesMap[unitsMap[i]].splice(dead, 1);
                    //         if (hexesMap[mapUnits[i].hexagon] === undefined) {
                    //             hexesMap[mapUnits[i].hexagon] = [];
                    //         }
                    //         hexesMap[mapUnits[i].hexagon].push(i);
                    //         unitsMap[i] = mapUnits[i].hexagon;
                    //     }
                    // }
                    // if (Object.keys(hexesMap[mapUnits[i].hexagon]).length) {
                    //     let unitsHere = hexesMap[mapUnits[i].hexagon];
                    //     let sortedUnits = _.sortBy(unitsHere, o => o-0);
                    //     mapUnits[i].shift = sortedUnits.indexOf(i) * 5;
                    // } else {
                    // }


                    mapUnits[i].zIndex = zIndex;
                    mapUnits[i].id = i - 0;
                    mapUnits[i].x -= 18 ;
                    mapUnits[i].y -= 18 ;
                    mapUnits[i].odds = "";
                    mapUnits[i].oddsColor = '';
                    this.unitDecorate(mapUnits[i], data);

                    dispUnits.push(mapUnits[i]);
                    // topVue.units.push(mapUnits[i]);
                    // Vue.set(topVue.unitsMap,  mapUnits[i].id, mapUnits[i] );
                    // var hex = unitsMap[i];
                    //
                    // for (var i in hexesMap[hex]) {
                    //     topVue.unitsMap[hexesMap[hex][i]].zIndex = 3 - i - 0 + 1;
                    // }

                }




            }
            vueStore.commit('mD/dispUnits', dispUnits);
            let emptyDeploy = true;
            if(vueStore.state.gameRules.prevPhase !== data.gameRules.phase
                && data.gameRules.mode === DEPLOY_MODE || data.gameRules.mode === MOVING_MODE){
                let attackingForceId = data.gameRules.attackingForceId;
                let boxes = vueStore.getters['bd/allBoxes'];
                _.forEach(boxes, (box, key) => {
                    if(key === 'deadpile'){
                        return;
                    }
                    if(key.match(/^gameTurn/)){
                        return;
                    }
                    if(box.length > 0){
                        _.forEach(box, (unit)=>{
                            if(unit.forceId === attackingForceId){
                                emptyDeploy = false;
                            }
                        })
                    }
                })


                if(emptyDeploy){
                    clickThrough.show.units.deployWrapper = false;
                }else{
                    clickThrough.show.units.deployWrapper = true;
                }
            }
            vueStore.commit('setPrevPhase', data.gameRules.phase)
            // vueStore.state.gameRules.prevPhase = data.gameRules.phase;



            // if(data.gameRules.mode ===  DEPLOY_MODE && clickThrough.deployBox.length > 0){
            //     clickThrough.show.units.deployBox = true;
            // }else{
            //     clickThrough.show.units.deployBox = false;
            //
            // }

        });

    }

    vp(){

    }
        victory(){
        syncObj.register("victory", (victory, data) => {

            let p1 = DR.playerOne;
            let p2 = DR.playerTwo;
            let p1Class = p1.replace(/ /g, '-');
            let p2Class = p2.replace(/ /g, '-');
            let vp = victory.victoryPoints;
            p1Class =  p1Class.replace(/\//ig, '_') + 'Face';
            p2Class = p2Class.replace(/\//ig, '_') + 'Face';

            let vlabel = " Victory: <span class='" + p1Class + "'>" + p1 + " </span>" + vp[1];
            vlabel += " <span class='" + p2Class + "'>" + p2 + " </span>" + vp[2];
            vueStore.commit('headerData/victory',vlabel);
        });

    }
}