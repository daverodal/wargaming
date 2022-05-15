import Vue from "vue";
import {syncObj} from '@markarian/wargame-helpers';
import {DR} from "@markarian/wargame-helpers";
import {mapGetters, mapMutations} from "vuex";
function svgRefresh(){
    var svgHtml = $('#svgWrapper').html();
    $('#svgWrapper').html(svgHtml);
}
export class SyncController {

    constructor() {
        this.mapData = {};

        this.init();
        this.flashMessages();

        this.gameRules();
        this.mapUnits();


        this.combatRules();


        this.moveRules();

        this.phaseClicks();

        this.click();

        this.users();
        this.victory();
        this.mapSymbols();
        this.specialHexes();
        this.vp();
        this.mapViewer();
        this.sentBreadCrumbs();
        this.complete();
    }
    renderCrtDetails(combat) {
        var atk = combat.attackStrength;
        var def = combat.defenseStrength;
        var div = atk / def;
        var ter = combat.terrainCombatEffect;
        var combatCol = combat.index + 1;
        var oddsDisp;

        let xyz = vueStore.getters.currentTable;
        const selectedTable = vueStore.state.crt.selectedTable;
        oddsDisp = "";
        if(!Number.isInteger(combat.index)) {
            var html = "<div id='crtDetails'>No attackers selected</div>"
            return html;

        }
        const crtHeader = vueStore.getters.currentTable.header;
        oddsDisp = crtHeader[combat.index] || "< " + crtHeader[0];
        div = div.toFixed(2);
        var html = "<div id='crtDetails'>" + combat.combatLog + "</div><div class='clear'>Attack = " + atk + " / Defender " + def + " = " + div + "<br>Final Column  = " + oddsDisp + "</div>"
        /*+ atk + " - Defender " + def + " = " + diff + "</div>";*/
        return html;
    }

    unitDecorate(unit, data) {

        var showStatus = false;
        var force = data.force;
        var color = "#ccc #666 #666 #ccc";
        var style = "solid";
        let id = unit.id;
        if(data.force.combatRequired){
            if(data.force.requiredAttacks[id] === true) {
                color='black'
                style = 'dotted';
            }
            if(data.force.requiredDefenses[id] === true) {
                style = 'dotted';
                color='black';
            }
        }

        var boxShadow = "none";
        var shadow = true;
        if (unit.forceId !== force.attackingForceId) {
            shadow = false;
        }

        if (unit.isImproved === true) {
            style = 'dotted';
            color = 'black';
            var colour = $("#" + i).css('color');
            if (colour === "rgb(255, 255, 255)") {
                color = 'white';
            }
        }
        switch (unit.status) {
            case STATUS_CAN_REINFORCE:
            case STATUS_CAN_DEPLOY:
                color = "#ccc #666 #666 #ccc";
                shadow = false;
                if (unit.reinforceTurn) {
                    shadow = true;
                }
                break;
            case STATUS_READY:
                if (unit.forceId === force.attackingForceId) {

                    shadow = false;
                } else {
                }
                break;
            case STATUS_REINFORCING:
            case STATUS_DEPLOYING:
                shadow = false;
                boxShadow = '5px 5px 5px #333';


                break;
            case STATUS_MOVING:

                color = "lightgreen";
                shadow = false;
                // DR.lastMoved = i;
                break;

            case STATUS_STOPPED:
                color = "#ccc #666 #666 #ccc";
                break;

            case STATUS_CAN_TRANSPORT:
                color = "fuchsia";
                shadow = false;
                break;

            case STATUS_LOADING:
                color = "lime";
                shadow = false;

                break;


            case STATUS_CAN_UNLOAD:
                color = "aliceblue";
                shadow = false;
                break;

            case STATUS_UNLOADING:
                color = "beige";
                shadow = false;

                break;

            case STATUS_DEFENDING:
                color = "orange";

                break;
            case STATUS_BOMBARDING:

            case STATUS_ATTACKING:

                shadow = false;
                break;

            case STATUS_CAN_RETREAT:
                if (data.gameRules.mode == RETREATING_MODE) {
                    status = "Click on the Purple Unit to start retreating";
                }
                color = "purple";
                break;
            case STATUS_RETREATING:
                color = "yellow";
                if (data.gameRules.mode == RETREATING_MODE) {

                    status = "Now click on a green unit. The yellow unit will retreat there. ";
                }
                break;
            case STATUS_CAN_ADVANCE:
                if (data.gameRules.mode == ADVANCING_MODE) {
                    status = 'Click on one of the black units to advance it.';
                }
                color = "black";
                shadow = false;

                break;
            case STATUS_ADVANCING:
                if (data.gameRules.mode == ADVANCING_MODE) {

                    status = 'Now click on one of the turquoise units to advance or stay put..';
                }

                shadow = false;
                color = "cyan";
                break;
            case STATUS_CAN_EXCHANGE:
                if (data.gameRules.mode == EXCHANGING_MODE) {
                    var result = data.combatRules.lastResolvedCombat.combatResult;
                    vueStore.commit('floatMessage/setHeader',  result+' Exchanging Mode');

                    status = "Click on one of the red units to reduce it."
                }
            case STATUS_CAN_ATTACK_LOSE:
                if (data.gameRules.mode == ATTACKER_LOSING_MODE) {
                    status = "Click on one of the red units to reduce it."
                }
                color = "red";
                break;

            case STATUS_CAN_DEFEND_LOSE:
                if (data.gameRules.mode == DEFENDER_LOSING_MODE) {
                    status = "Click on one of the red units to reduce it."
                }
                color = "red";
                break;

            case STATUS_REPLACED:
                color = "blue";
                break;
            case STATUS_REPLACING:
                color = "orange";
                break;
            case STATUS_CAN_UPGRADE:
            case STATUS_CAN_REPLACE:
                if (unit.forceId === force.attackingForceId) {
                    shadow = false;
                    color = "turquoise";
                }
                break;

            case STATUS_ELIMINATED:
                break;

        }
        if (status) {
            showStatus = true;
            var x = unit.x;
            var y = unit.y;
            y /= DR.globalZoom;
            x /= DR.globalZoom;

            var mapWidth = $("#main-viewer").width();
            var mapHeight = $("#gameViewer").height() / DR.globalZoom;


            var mapOffset = $("#gameImages").position().top;

            if (mapOffset === "auto") {
                mapOffset = 0;
            }
            var moveAmt;

            if (mapOffset + y > 2 * mapHeight / 3) {
                moveAmt = (100 + (mapOffset + y) / 3);
                if (moveAmt > 250) {
                    moveAmt = 250;
                }
                y -= moveAmt;


            } else {
                moveAmt = (mapHeight - (mapOffset + y )) / 2;
                if (moveAmt > 200) {
                    moveAmt = 200;
                }
                y += moveAmt;
            }

            vueStore.commit('floatMessage/setX', x);
            vueStore.commit('floatMessage/setY', y);
            vueStore.commit('floatMessage/setMessage', status);

            status = "";
        }

        unit.borderColor = color;
        unit.borderStyle = style;
        unit.shadow = shadow;
        unit.boxShadow = boxShadow;

    }

    sentBreadCrumbs(){
        syncObj.register("sentBreadcrumbs", function (breadcrumbs, data) {
            $('svg g').remove();
            var lastUnit = '';
            var lastMoves = '';
            var combatBreadcrumbs = [];
            var disp = "none";
            if(DR.showArrows){
                disp = "inline";
            }
            for (var unitId in breadcrumbs) {
                var g = $('svg').append('<g style="display:' +disp+ '" class="unit-path unitPath' + unitId + '">');
                var prevPath = "";
                for (var moves in breadcrumbs[unitId]) {
                    if (breadcrumbs[unitId][moves].type == "move" || breadcrumbs[unitId].fromHex) {
                        var path = "";
                        if(prevPath){
                            $('g.unitPath' + unitId).append(prevPath);
                            prevPath = "";
                        }
                        if (breadcrumbs[unitId][moves - 0 + 1]) {
                            path += "<path stroke-width='15'";
                        } else {
                            path += "<path marker-end='url(#head)' stroke-width='15'";
                        }
                        if (typeof breadcrumbs[unitId][moves].fromX == "undefined") {
                            continue;
                        }
                        var d = 'M' + breadcrumbs[unitId][moves].fromX + ',' + breadcrumbs[unitId][moves].fromY;
                        d += ' L' + breadcrumbs[unitId][moves].toX + ',' + breadcrumbs[unitId][moves].toY;
                        path += ' d="' + d + '"/>';
                        var circle = '<circle cx="' + breadcrumbs[unitId][moves].toX + '" cy="' + breadcrumbs[unitId][moves].toY + '" r="7"/>';
                        $('g.unitPath' + unitId).append(path);
                        // $('g.unitPath' + unitId).append(circle);
                        prevPath = circle;
                        lastMoves = moves;
                    }
                    if (breadcrumbs[unitId][moves].type == "combatResult") {
                        var x = breadcrumbs[unitId][moves].hexX - 0;
                        x -= 8;
                        var y = breadcrumbs[unitId][moves].hexY - 0;
                        var circle = '<circle cx="' + breadcrumbs[unitId][moves].hexX + '" cy="' + breadcrumbs[unitId][moves].hexY + '" r="20" stroke-width="5" fill="white"/>';
                        var text = '<text x="' + x + '" y="' + y + '" font-family="sans-serif" font-size="12px" stroke="black" fill="black">' + breadcrumbs[unitId][moves].result + '</text>';
                        y += 10;
                        x += 4;
                        text += '<text x="' + x + '" y="' + y + '" font-family="sans-serif" font-size="12px" stroke="black" fill="black">' + breadcrumbs[unitId][moves].dieRoll + '</text>';
                        combatBreadcrumbs.push(circle);
                        combatBreadcrumbs.push(text);
                    }


                }

            }
            for (var i in combatBreadcrumbs) {
                $('g.unitPath' + unitId).append(combatBreadcrumbs[i]);
            }
            svgRefresh();

        });

    }
    init(){
        syncObj.registerInit((data) => {
            vueStore.commit('floatMessage/clear')
        });
    }
    complete() {
        syncObj.registerComplete((data) => {
        })
    }
    flashMessages(){
        syncObj.register("flashMessages",  (messages, data) => {
            let msgQueue = [];
            while(messages.length> 0){
                let msg = messages.shift();
                if (msg.match(/^@/)){
                    if (msg.match(/^@hex/)) {
                        var hexPos = msg.replace(/\.\d*/g, '');
                        var x = hexPos.match(/x(\d*)y/)[1] - 0;
                        var y = hexPos.match(/y(\d*)\D*/)[1] - 0;
                        topVue.rowSvg = {x,y}
                    }
                    if (msg.match(/^@show/)) {
                        let matches =  msg.match(/^@show ([^,]*)/);
                        let id = matches[1];
                        if(id === 'crt'){
                            clickThrough[id] = true;
                        }else{
                            clickThrough.show.units[id] = true;
                        }
                        continue;
                    }
                    if (msg.match(/^@hide/)) {
                        let game = msg.match(/^@hide ([^,]*)/);
                        let id = game[1];
                        if(id === 'crt'){
                            vueStore.commit('closeCrt');
                        }else{
                            clickThrough.show.units[id] = false;
                        }
                        continue;
                    }
                    if(msg.match(/^@gameover/)){
                        msgQueue.push('Game Over');
                    }
                }else{
                    msgQueue.push(msg);
                }

            }
            floatersMessages.messages = msgQueue;
        });

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
                    mapUnits[i].x = -15;
                    mapUnits[i].y = 0;

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
                    mapUnits[i].x -= 18 - mapUnits[i].shift;
                    mapUnits[i].y -= 18 - mapUnits[i].shift;
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
    gameRules() {
        syncObj.register("gameRules",  (gameRules, data) => {

            if (gameRules.options && gameRules.options.length > 0) {
                var inputs = "";
                for (var i in gameRules.options) {
                    inputs += "<input type='radio' name='options' value='" + i + "'>" + gameRules.options[i] + "<br>"
                }
                $("#options-box").html(inputs);
                $("#options-pane").show();
            } else {
                $("#options-pane").hide();
            }
            if (gameRules.mode ===  MOVING_MODE) {
                vueStore.commit('headerData/setDynamicButton', {id: 'move', value: true})
            }else{
                vueStore.commit('headerData/setDynamicButton', {id: 'move', value: false})
            }
            if(DR.hasHq){
                vueStore.commit('headerData/setDynamicButton', {id: 'showHexes', value: true})
            }else{
                vueStore.commit('headerData/setDynamicButton', {id: 'showHexes', value: false})
            }
            if (gameRules.mode ===  COMBAT_SETUP_MODE) {
                vueStore.commit('headerData/setDynamicButton', {id: 'determined', value: true})
                vueStore.commit('headerData/setDynamicButton', {id: 'combat', value: true})
            }else{
                vueStore.commit('headerData/setDynamicButton', {id: 'determined', value: false})
                vueStore.commit('headerData/setDynamicButton', {id: 'combat', value: false})
            }

            var status = "";
            var turn = gameRules.turn;
            var maxTurn = gameRules.maxTurn
            if ("gameTurn" + turn != $("#turnCounter").parent().attr("id")) {
                $("#gameTurn" + turn).prepend($("#turnCounter"));
            }

            var pix = turn + (turn - 1) * 36 + 1;
            var playerName = "header-" + (DR.players[gameRules.attackingForceId].replace(/ /g, '-').replace(/\//gi, '_'));
            ;
            Vue.set(crt.crtOptions, 'playerName', playerName);
            // clickThrough.crtOptions.playerName = playerName;
            var removeThese = "";
            clickThrough.headerPlayer = playerName;
            // $("#header").removeClass().addClass(playerName);
            $("#turnCounter").css("background", "rgb(0,128,0)");
            $("#turnCounter").css("color", "white");

            var alsoRemoveThese = DR.players.join('@@@').trim();
            alsoRemoveThese = alsoRemoveThese.replace(/ /g, '-');
            alsoRemoveThese = alsoRemoveThese.replace(/\//g, '_');
            alsoRemoveThese = alsoRemoveThese.replace(/@@@/g, ' ');
            alsoRemoveThese = alsoRemoveThese.replace(/([^ ]+)/g, "player$1");
            removeThese += " " + alsoRemoveThese;

            var html = "<span id='turn'>Turn " + turn + " of " + maxTurn + "</span> ";
            var phase = phase_name[gameRules.phase];
            phase = phase.replace(/fNameOne/, DR.playerOne);
            phase = phase.replace(/playerOneFace/, "player" + DR.playerOne.replace(/ /g, '-') + "Face");
            phase = phase.replace(/playerTwoFace/, "player" + DR.playerTwo.replace(/ /g, '-') + "Face");
            phase = phase.replace(/playerThreeFace/, "player" + DR.playerThree.replace(/ /g, '-') + "Face");
            phase = phase.replace(/playerFourFace/, "player" + DR.playerFour.replace(/ /g, '-') + "Face");

            phase = phase.replace(/fNameTwo/, DR.playerTwo);
            phase = phase.replace(/fNameThree/, DR.playerThree);
            phase = phase.replace(/fNameFour/, DR.playerFour);
            html += "<span id='phase'>" + phase;
            if (mode_name[gameRules.mode]) {
                html += " " + mode_name[gameRules.mode];
            }
            html += "</span>";

            switch (gameRules.phase) {
                case BLUE_REPLACEMENT_PHASE:
                case RED_REPLACEMENT_PHASE:
                case TEAL_REPLACEMENT_PHASE:
                case PURPLE_REPLACEMENT_PHASE:
                    if (gameRules.replacementsAvail !== false && gameRules.replacementsAvail != null) {
                        status = "There are " + gameRules.replacementsAvail + " available";
                    }
                    break;
            }
            switch (gameRules.mode) {
                case EXCHANGING_MODE:
                    var result = data.combatRules.lastResolvedCombat.combatResult;

                    vueStore.commit('floatMessage/setHeader', result + ": Exchanging Mode");
                    vueStore.commit( 'floatMessage/setAdvisory',"Lose at least " + data.force.exchangeAmount + " strength points");
                    break;

                case DEFENDER_LOSING_MODE:

                    var result = data.combatRules.lastResolvedCombat.combatResult;
                    vueStore.commit('floatMessage/setHeader', result + ": Defender Loss Mode.");
                    vueStore.commit( 'floatMessage/setAdvisory',"Lose at least " + data.force.exchangeAmount + " strength points");

//            html += "<br>Lose at least "+gameRules.exchangeAmount+" strength points from the units outlined in red";
                    break;

                case ATTACKER_LOSING_MODE:
                    var result = data.combatRules.lastResolvedCombat.combatResult;


                    vueStore.commit('floatMessage/setHeader',  result + ": Attacker Loss Mode.");

                    vueStore.commit( 'floatMessage/setAdvisory', "Lose at least " + data.force.exchangeAmount + " strength points");

                    break;
                case ADVANCING_MODE:
                    var result = data.combatRules.lastResolvedCombat.combatResult;

                    vueStore.commit('floatMessage/setHeader',  result + ": Advancing Mode");
                    break;
                case RETREATING_MODE:
                    var result = data.combatRules.lastResolvedCombat.combatResult;

                    vueStore.commit('floatMessage/setHeader',  result + ": Retreating Mode");
                    break;
            }
            var log = "";
            for(var i in gameRules.flashLog){
                var message = gameRules.flashLog[i];
                if(message.match(/^@/)){
                    continue;
                }
                log += "<li>"+gameRules.flashLog[i]+"</li>";

            }
            vueStore.commit('headerData/setTurn', turn);
            vueStore.commit('headerData/setMaxTurn', maxTurn);
            vueStore.commit('headerData/log',log);
            vueStore.commit('headerData/topStatus', html);
            vueStore.commit('headerData/status', status);
        });

    }
    combatRules(){
        syncObj.register("combatRules",  (combatRules, data) => {

            const selectedTable = vueStore.state.crt.selectedTable;
            const defaultTable = vueStore.state.crt.defaultTable;
            const crtHeader = vueStore.state.crtData.crts[selectedTable].header;
            _.forEach(topVue.units, (mapUnit) => {
                mapUnit.thetas = [];
            })

            var title = "Combat Results ";
            var cdLine = "";
            var activeCombat = false;
            var activeCombatLine = "";
            vueStore.commit('headerData/combatStatus',  "");
            let crt = {};
            // crt.details = "";
            vueStore.commit('setCrtDetails', "");

            crt.index = false;
            crt.roll = null;
            if (combatRules) {
                var cD = combatRules.currentDefender;
                if (combatRules.combats && Object.keys(combatRules.combats).length > 0) {
                    if (cD !== false) {
                        var defenders = combatRules.combats[cD].defenders;
                        for (var loop in defenders) {
                            // topVue.unitsMap[loop].borderColor = 'yellow';
                            vueStore.commit('mD/decorateUnit', {id: loop, key: 'borderColor', value: 'yellow'})
                        }
                        vueStore.commit('crtSelfOpened', true);
                        if (Object.keys(combatRules.combats[cD].attackers).length != 0) {
                            if (combatRules.combats[cD].pinCRT !== false) {
                                combatCol = combatRules.combats[cD].pinCRT + 1;
                                if (combatCol >= 1) {
                                    crt.pinned = combatCol - 1;
                                }
                            }else{
                                crt.pinned = false;
                            }

                            combatCol = combatRules.combats[cD].index + 1;

                            if (combatCol >= 1) {
                                crt.index = combatCol - 1;
                                if (combatRules.combats[cD].Die !== false) {
                                    crt.roll = combatRules.combats[cD].Die;
                                }
                            }
                        }
                        var details = this.renderCrtDetails(combatRules.combats[cD]);
                        const oddsCol = combatCol -1;
                        let oddsDisplay = crtHeader[combatCol-1] || "< "+ crtHeader[0];
                        let newLine = isNaN(oddsCol)? '' : "<h5>odds = " + oddsDisplay + " </h5>";
                        newLine += details;
                        vueStore.commit('setCrtDetails', details);
                        // crt.details = newLine;
                    }
                    cdLine = "";
                    var combatIndex = 0;
                    for (var i in combatRules.combats) {
                        if (combatRules.combats[i].index !== null) {


                            var attackers = combatRules.combats[i].attackers;
                            var defenders = combatRules.combats[i].defenders;
                            var thetas = combatRules.combats[i].thetas;

                            var theta = 0;

                            for (var j in attackers) {
                                let thetasList = [];

                                var numDef = Object.keys(defenders).length;
                                for (var k in defenders) {
                                    theta = thetas[j][k];
                                    theta *= 15;
                                    theta += 180;
                                    thetasList.push(theta)
                                }
                                vueStore.commit('mD/decorateUnit', {id: j, key: 'thetas', value: thetasList})
                            }
                            // topVue.unitsMap[j].thetas.push(theta);

                            var useAltColor = combatRules.combats[i].useAlt ? " altColor" : "";
                            if (combatRules.combats[i].useDetermined) {
                                useAltColor = " determinedColor";
                            }

                            var odds = Math.floor(atk / def);
                            var oddsDisp;
                            var currentCombatCol;
                            var currentOddsDisp;

                            if (typeof combatRules.combats[i].index === "object") {
                                currentCombatCol = combatRules.combats[i].index + '';
                                currentOddsDisp = currentCombatCol;
                                oddsDisp = currentCombatCol;
                            } else {

                                var currentCombatCol;
                                var currentOddsDisp;


                                currentCombatCol = combatRules.combats[i].index + 1;
                                if (currentCombatCol <= 0) {
                                    currentOddsDisp = '<' + crtHeader[0];
                                }

                                if (currentCombatCol > 0) {
                                    currentOddsDisp = crtHeader[currentCombatCol - 1];
                                }
                            }
                            if (combatRules.combats[i].pinCRT !== false) {
                                currentCombatCol = combatRules.combats[i].pinCRT + 1;
                                currentOddsDisp = crtHeader[currentCombatCol - 1];
                                useAltColor = " pinnedColor";
                            }

                            vueStore.commit('mD/decorateUnit', {id: i, key: 'odds', value: currentOddsDisp})
                            vueStore.commit('mD/decorateUnit', {id: i, key: 'oddsColor', value: useAltColor})
                            // topVue.unitsMap[i].odds = currentOddsDisp;
                            // topVue.unitsMap[i].oddsColor = useAltColor;

                            combatIndex++;
                        }

                    }
                    vueStore.commit('headerData/combatStatus', "There are " + combatIndex + " Combats");
                }else{
                    vueStore.commit('crtSelfOpened', false);
                }

                if (combatRules.combatsToResolve) {
                    cD = combatRules.currentDefender;
                    if (cD !== false) {
                        if(combatRules.combatsToResolve[cD]) {
                            let currentDefender = combatRules.defenders[cD];
                            var defenders = combatRules.combatsToResolve[currentDefender].defenders;
                            for (var loop in defenders) {
                                // topVue.unitsMap[loop].borderColor = 'yellow';
                                vueStore.commit('mD/decorateUnit', {id: loop, key: 'borderColor', value: 'lightblue'})
                            }

                            combatCol = combatRules.combatsToResolve[cD].index + 1;

                            if (combatCol >= 1) {
                                crt.index = combatCol - 1;
                                if (combatRules.combatsToResolve[cD].Die !== false) {
                                    crt.roll = combatRules.combatsToResolve[cD].Die;
                                }
                            }
                            var details = this.renderCrtDetails(combatRules.combatsToResolve[cD]);
                            let currentOddsDisp;

                            if(combatCol <= 0){
                                currentOddsDisp =  '<' + crtHeader[0];
                            }

                            if(combatCol > 0){
                                currentOddsDisp = crtHeader[combatCol - 1];
                            }
                            // if (thisCombat.pinCRT !== false) {
                            //     currentCombatCol = thisCombat.pinCRT + 1;
                            //     currentOddsDisp = crtHeader[currentCombatCol - 1];
                            //     useAltColor = " pinnedColor";
                            // }
                            vueStore.commit('setCrtDetails', "<h5>odds = " + currentOddsDisp + "</h5>" + details);

                        }
                        if(combatRules.resolvedCombats && combatRules.resolvedCombats[cD]) {
                            var defenders = combatRules.resolvedCombats[cD].defenders;
                            for (var loop in defenders) {
                                // topVue.unitsMap[loop].borderColor = 'yellow';
                                // vueStore.commit('mD/decorateUnit', {id: loop, key: 'borderColor', value: 'lightblue'})
                            }

                            combatCol = combatRules.resolvedCombats[cD].index + 1;

                            if (combatCol >= 1) {
                                crt.index = combatCol - 1;
                                if (combatRules.resolvedCombats[cD].Die !== false) {
                                    crt.roll = combatRules.resolvedCombats[cD].Die;
                                }
                            }
                            var details = this.renderCrtDetails(combatRules.resolvedCombats[cD]);
                            let currentOddsDisp;

                            if(combatCol <= 0){
                                currentOddsDisp =  '<' + crtHeader[0];
                            }

                            if(combatCol > 0){
                                currentOddsDisp = crtHeader[combatCol - 1];
                            }
                            // if (thisCombat.pinCRT !== false) {
                            //     currentCombatCol = thisCombat.pinCRT + 1;
                            //     currentOddsDisp = crtHeader[currentCombatCol - 1];
                            //     useAltColor = " pinnedColor";
                            // }
                            vueStore.commit('setCrtDetails', "<h5>odds = " + currentOddsDisp + "</h5>" + details);

                        }

                    }else if (combatRules.lastResolvedCombat) {
                        let thisCombat = combatRules.lastResolvedCombat;
                        let toResolveLog = "Current Combat or Last Combat<br>";
                        combatCol = thisCombat.index;

                        let combatRoll = thisCombat.Die;
                        crt.combatResult = thisCombat.combatResult

                        crt.index = combatCol;
                        let pin = thisCombat.pinCRT;
                        if (pin !== false) {
                            if (pin < combatCol) {
                                combatCol = pin;
                                vueStore.commit('setCrt', {pinned: pin});
                            }
                        }else {
                            crt.pinned = false;
                        }

                        crt.roll = combatRoll;

                        if (thisCombat.useAlt) {
                            crt.selectedTable = 'cavalry'
                        } else {
                            if (thisCombat.useDetermined) {
                                crt.selectedTable = 'determined'

                            } else {
                                crt.selectedTable = defaultTable
                            }
                        }

                        let details = this.renderCrtDetails(thisCombat);

                        let currentCombatCol;
                        let currentOddsDisp;


                        currentCombatCol = thisCombat.index + 1;
                        if(currentCombatCol <= 0){
                            currentOddsDisp =  '<' + crtHeader[0];
                        }

                        if(currentCombatCol > 0){
                            currentOddsDisp = crtHeader[currentCombatCol - 1];
                        }
                        if (thisCombat.pinCRT !== false) {
                            currentCombatCol = thisCombat.pinCRT + 1;
                            currentOddsDisp = crtHeader[currentCombatCol - 1];
                            useAltColor = " pinnedColor";
                        }


                        // crt.details = "<h5>odds = " + currentOddsDisp + "</h5>" + details;
                        vueStore.commit('setCrtDetails', "<h5>odds = " + currentOddsDisp + "</h5>" + details);
                    }
                    let noCombats = false;
                    if (Object.keys(combatRules.combatsToResolve) == 0) {
                        noCombats = true;
                    }
                    let combatsToResolve = 0;
                    for (var i in combatRules.combatsToResolve) {
                        combatsToResolve++;
                        if (combatRules.combatsToResolve[i].index !== null) {
                            attackers = combatRules.combatsToResolve[i].attackers;
                            defenders = combatRules.combatsToResolve[i].defenders;

                            var atk = combatRules.combatsToResolve[i].attackStrength;
                            var atkDisp = atk;
                            ;

                            var def = combatRules.combatsToResolve[i].defenseStrength;
                            var ter = combatRules.combatsToResolve[i].terrainCombatEffect;
                            var combatCol = combatRules.combatsToResolve[i].index + 1;
                            var useAltColor = combatRules.combatsToResolve[i].useAlt ? " altColor" : "";

                            if (combatRules.combatsToResolve[i].pinCRT !== false) {
                                combatCol = combatRules.combatsToResolve[i].pinCRT;
                            }
                            var odds = Math.floor(atk / def);

                            currentCombatCol = combatRules.combatsToResolve[i].index + 1;
                            if(currentCombatCol <= 0){
                                currentOddsDisp =  '<' + crtHeader[0];
                            }

                            if(currentCombatCol > 0){
                                currentOddsDisp = crtHeader[currentCombatCol - 1];
                            }
                            if (combatRules.combatsToResolve[i].pinCRT !== false) {
                                currentCombatCol = combatRules.combatsToResolve[i].pinCRT + 1;
                                currentOddsDisp = crtHeader[currentCombatCol - 1];
                                useAltColor = " pinnedColor";
                            }

                            vueStore.commit('mD/decorateUnit',{ id: i, key: 'odds', value: currentOddsDisp})
                            vueStore.commit('mD/decorateUnit',{ id: i, key: 'oddsColor', value: useAltColor})
                            // topVue.unitsMap[i].odds = currentOddsDisp;
                            // topVue.unitsMap[i].oddsColor = useAltColor;
                        }

                    }

                    let resolvedCombats = 0;
                    if(combatRules.resolvedCombats){
                        resolvedCombats = Object.keys(combatRules.resolvedCombats).length;
                    }
                    if (!noCombats) {
                        vueStore.commit('headerData/combatStatus',
                            "Combats: " + resolvedCombats + " of " + (resolvedCombats + combatsToResolve));
                    }else{
                        vueStore.commit('headerData/combatStatus',"0 combats to resolve");
                    }
                }
            }
            vueStore.commit('setCrt', crt)

        });
    }
    moveRules(){
        const mapData = this.mapData;
        syncObj.register("moveRules",  (moveRules, data) => {
            vueStore.commit('mD/clearMoves');

            var str;
            if (moveRules.movingUnitId >= 0) {
                var opacity = .4;
                var borderColor = "#ccc #333 #333 #ccc";
                if (moveRules.moves) {
                    var id = moveRules.movingUnitId;
                    let newUnit = _.clone(data.mapUnits[id]);
                    let width = 32;
                    let ghosts = [];
                    let ghostMap = {};
                    for (var i in moveRules.moves) {

                        if (data.gameRules.phase == RED_COMBAT_PHASE || data.gameRules.phase == BLUE_COMBAT_PHASE || data.gameRules.phase == TEAL_COMBAT_PHASE || data.gameRules.phase == PURPLE_COMBAT_PHASE) {
                            borderColor = 'turquoise';
                        }
                        let ghostUnit  = _.clone(newUnit);
                        ghostUnit.x = moveRules.moves[i].pixX - width / 2
                        ghostUnit.y = moveRules.moves[i].pixY - width / 2
                        ghostUnit.pathToHere = moveRules.moves[i].pathToHere;
                        ghostUnit.id = id + "Hex" + i;
                        ghostUnit.maxMove = moveRules.moves[i].pointsLeft;
                        ghostUnit.moveAmountUsed = 0;
                        ghostUnit.isOccupied = moveRules.moves[i].isOccupied;
                        ghostUnit.showOff = false;
                        ghostUnit.borderColor = borderColor;
                        // vueStore.commit('mD/addUnit', {hex: i, unit: ghostUnit});
                        ghosts.push(ghostUnit);
                        ghostMap[i] = ghostUnit;
                    }
                    vueStore.commit('mD/addUnits', {map: ghostMap, units: ghosts});
                }
            }
        });

    }
    phaseClicks(){
        syncObj.register("phaseClicks", function (clicks, data) {
            var str = "";
            var phaseClickNames = data.gameRules.phaseClickNames;
            if (syncObj.timeTravel) {
                clicks = DR.clicks;
                phaseClickNames = DR.phaseClickNames;
            } else {
                DR.phaseClickNames = phaseClickNames;
                DR.clicks = clicks;
                DR.maxClick = data.click;
                DR.playTurnClicks = data.gameRules.playTurnClicks;
            }
            var maxClick = DR.maxClick;

            var i;
            var num = clicks.length;
            var ticker;
            ticker = clicks[0];
            var q = 0;
            for (var i = 0; i < num; i++) {
                str += '<div class="newPhase"><a class="phaseClick" data-click="' + ticker + '">';
                if (data.gameRules.phaseClickNames) {
                    str += phaseClickNames[q++];
                    str += '</a><br><div class="newTick tickShim"></div>';

                }
                if (i + 1 < num) {
                    while (ticker < clicks[i + 1]) {
                        str += '<div class="newTick" data-click="' + ticker + '"><a class="phaseClick" data-click="' + ticker + '">' + ticker + '</a></div>';
                        ticker++;
                    }
                } else {
                    while (ticker <= maxClick) {
                        str += '<div class="newTick" data-click="' + ticker + '"><a class="phaseClick" data-click="' + ticker + '">' + ticker + '</a></div>';
                        ticker++;
                    }
                    if (syncObj.timeTravel) {
                        str += '<div class="newTick"><a class="phaseClick realtime" >realtime</a></div>';
                    }
                }
                str += '</div>';

            }
            $("#phaseClicks").html(str);
            var click = data.click;
            if (syncObj.timeTravel) {
                $(".newTick[data-click='" + click + "']").addClass('activeTick');
            }
        });

    }
    click() {
        syncObj.register("click",  (click) => {
            if (syncObj.timeTravel) {
                vueStore.commit('setCurrentClick', 'time travel ' + click)
                // vueStore.state.timeTravel.currentClick = 'time travel ' + click
            } else {
                vueStore.commit('setCurrentClick', 'realtime ' + click)

                // vueStore.state.timeTravel.currentClick = 'realtime ' + click
            }
            DR.currentClick = click;
        });

    }
    users() {
    }
    victory() {
        syncObj.register("victory", (vp, data) => {
        });

    }
    vp() {
        syncObj.register("vp", (vp, data) => {
            let p1 = DR.playerOne;
            let p2 = DR.playerTwo;
            let p1Class = p1.replace(/ /g, '-');
            let p2Class = p2.replace(/ /g, '-');

            p1Class = 'player' + p1Class.replace(/\//ig, '_') + 'Face';
            p2Class = 'player' + p2Class.replace(/\//ig, '_') + 'Face';
            let victory = "Victory: <span class='" + p1Class + "'>" + p1 + " </span>" + vp[1];
            victory += " <span class='" + p2Class + "'>" + p2 + " </span>" + vp[2];
            vueStore.commit('headerData/victory',victory);
        });
    }
    mapSymbols() {
        syncObj.register("mapSymbols",  (mapSymbols, data) => {
            topVue.mapSymbols = [];
            for (var i in mapSymbols) {
                for (var symbolName in mapSymbols[i]) {
                    var newHtml;
                    var hexPos = i.replace(/\.\d*/g, '');
                    var x = hexPos.match(/x(\d*)y/)[1];
                    var y = hexPos.match(/y(\d*)\D*/)[1];
                    let mapSymbol = {
                        ...mapSymbols[i][symbolName],
                        x: x, y: y,
                        id: i+'x'+symbolName};
                    topVue.mapSymbols.push(mapSymbol);

                }
            }
        });
    }
    specialHexes() {
        syncObj.register("specialHexes", (specialHexes, data) => {
            topVue.specialEvents = [];
            topVue.specialHexes = [];
            var lab = ['unowned','<?=strtolower($forceName[1])?>','<?=strtolower($forceName[2])?>'];
            for(var i in specialHexes){

                    var hexPos = i.replace(/\.\d*/g,'');
                    var x = hexPos.match(/x(\d*)y/)[1];
                    var y = hexPos.match(/y(\d*)\D*/)[1];

                    let mapSymbol = {x: x, y: y, text: DR.players[specialHexes[i]], class: DR.players[specialHexes[i]].replace(/ /g,'-'), change: false};
                    if(data.specialHexesChanges[i]){
                        mapSymbol.change = true;
                    }
                    topVue.specialHexes.push(mapSymbol);
            }

            for(var id in data.specialHexesVictory)
            {
                // if(data.specialHexesChanges[id]){
                //     continue;
                // }

                var hexPos = id.replace(/\.\d*/g,'');
                var x = hexPos.match(/x(\d*)y/)[1];
                var y = hexPos.match(/y(\d*)\D*/)[1];
                topVue.specialEvents.push({x: x, y: y, text:data.specialHexesVictory[id], id: hexPos});
            }

            if(topVue.specialEvents.length > 0){
                // setTimeout(()=>{
                //     topVue.specialEvents = [];
                // }, 3000);
            }
        });
    }
    mapViewer(){
        syncObj.register('mapViewer', function (mapViewer) {
            vueStore.commit('mD/setTrueRows', mapViewer.trueRows)
            vueStore.commit('mD/setMirror', mapViewer.mirror)
            // var src = $('#map').attr('src');
            // src = src.replace(/Left.png$/, '.png');
            // if (mapViewer.trueRows) {
            //     src = src.replace(/.png$/, 'Left.png');
            // }
            // $('#map').attr('src', src);


        });

    }
}


