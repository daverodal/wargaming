import {SyncController} from "../../wargame-helpers/Vue/sync-controller";
import {syncObj} from "../../wargame-helpers/Vue/syncObj";
import {DR} from "../../wargame-helpers/DR";
import Vue from "vue";
export class KievCorpsSyncController extends SyncController{
    constructor(){
        super();
    }

    vp(){

    }
    gameRules(gameRules, data) {
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

            const date = (turn - 1) * 5 + 1;
            var html = "Sep " + date + " <span id='turn'>Turn " + turn + " of " + maxTurn + "</span> ";
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

                    topVue.header = result + ": Exchanging Mode";

                case DEFENDER_LOSING_MODE:
                    var floatStat = floaters.message;

                    var result = data.combatRules.lastResolvedCombat.combatResult;

                    topVue.header = result + ": Defender Loss Mode.";

                    topVue.message = "Lose at least " + data.force.exchangeAmount + " strength points<br>" + floatStat;

//            html += "<br>Lose at least "+gameRules.exchangeAmount+" strength points from the units outlined in red";
                    break;

                case ATTACKER_LOSING_MODE:
                    var result = data.combatRules.lastResolvedCombat.combatResult;


                    topVue.header = result + ": Attacker Loss Mode.";

                    topVue.message = "Lose at least " + data.force.exchangeAmount + " strength points<br>" + floatStat;

//            html += "<br>Lose at least "+gameRules.exchangeAmount+" strength points from the units outlined in red";
                    break;
                case ADVANCING_MODE:
//            html += "<br>Click on one of the black units to advance it.<br>then  click on a hex to advance, or the unit to stay put.";
                    var result = data.combatRules.lastResolvedCombat.combatResult;

                    topVue.header = result + ": Advancing Mode";
                    break;
                case RETREATING_MODE:
                    var result = data.combatRules.lastResolvedCombat.combatResult;

                    topVue.header = result + ": Retreating Mode";
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
            vlabel += " Surrounded " + vp[3];
            vueStore.commit('headerData/victory',vlabel);
        });
    }
}