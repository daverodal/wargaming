import '../wargame';

window._ = require('lodash');
var chattyCrt;
import Vue from "vue";
import VueResource from 'vue-resource';
Vue.use(VueResource);
import {playAudio, playAudioBuzz, playAudioLow, counterClick, mapClick, doitOption, doitNext, nextPhaseMouseDown, doitKeypress, showCrtTable, fixItAll, doitSaveGame, rotateUnits, toggleFullScreen, doitCRT} from "../wargame-helpers/global-funcs";

import VueDraggableResizable from 'vue-draggable-resizable'
import FloatMessage from './FloatMessage';
import VueCrt    from './VueCrt';
import {DR} from "../global-header";
import x from "../wargame-helpers/common-sync";
Vue.component('vue-crt', VueCrt);

Vue.component('float-message', FloatMessage);
Vue.component('vue-draggable-resizable', VueDraggableResizable)
/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

Vue.component('unit-component', require('./UnitComponent.vue'));
Vue.component('units-component', require('./UnitsComponent.vue'));
Vue.component('map-symbol', require('./MapSymbol.vue'));

Vue.component('special-event', require('./SpecialEvent.vue'));
Vue.directive('new-light',{
    bind(el, arg){
        el.style.backgroundColor = 'fuchsia';
        console.log("Eele ");

    }
});

document.addEventListener("DOMContentLoaded",function(){

    window.topVue = new Vue({
        el: '#gameImages',
        data:{
            units:[],
            moveUnits: [],
            mapSymbols: [],
            specialEvents: []
        },
        methods:{
            wheelo(e){
            },
            unitClick(e){
                counterClick(e);
            }
        }

    });

    window.floaters = new Vue({
        el: "#floaters",
        data:{
            why: "Why not!",
            header: "",
            message: "",
            x: 0,
            y: 0
        }
    });
    // window.header = new Vue({
    //     el: "#unitsWrapper"
    // });

    window.clickThrough = new Vue({
        el: "#headerContent",
        data:{
            submenu: false,
            log: false,
            rules: false,
            menu: false,
            info: false,
            crt: false,
            crtClass: 'normalCrt',
            crtOptions: {roll: 3,
            pinIndex: 5},
            dynamicButtons:{
                move: false,
                showHexes: false,
                determined: false
            }
        },
        methods:{
            fullScreen(){
                toggleFullScreen()
            },
            bugReport(){
              bug
            },
            nextPhase(){
                nextPhaseMouseDown();
            },
            menuClick(id){
                headerVue.menuClick(id);
            },
            showArrows(){
                if (!DR.showArrows) {
                    $("#arrowButton").html("hide arrows");
                    DR.showArrows = true;
                    $('#arrow-svg .unit-path').show();
                } else {
                    $("#arrowButton").html("show arrows");
                    DR.showArrows = false;
                    $('#arrow-svg .unit-path').hide();
                }
            },
            mute(){
                if (!mute) {
                    $("#muteButton").html("un-mute");
                    muteMe();

                } else {
                    $("#muteButton").html("mute");
                    unMuteMe();
                    playAudio();
                }
            },
            zoom(){
                DR.globalZoom = 1.0;
                $("#zoom .defaultZoom").html(DR.globalZoom.toFixed(1));
                DR.$panzoom.panzoom('reset');
            },
            changeCrt(){
                this.crt = !this.crt;
            },
            clickCrt(){
            }

        }
    })
    window.headerVue = new Vue({
        el: '#secondHeader',
        data:{
            deployBox: [],
            deadpile: [],
            exitBox: [],
            notUsed: [],
            show:{
                units:{
                    submenu:false,
                    deployBox: false,
                    deadpile: false,
                    exitBox: false
                }
            }
        },
        methods:{
            wheelo(e){
            },
            unitClick(e){
                counterClick(e);
            },
            menuClick(id){
                if(id === 'all'){
                    this.show.units.submenu = false;
                    this.show.units.deployBox = false;
                    this.show.units.deadpile = false;
                    this.show.units.exitBox = false;
                    return;
                }
                this.show.units[id] = !this.show.units[id];
                this.show.units.submenu = false;
            }
        }

    });



    const x = DR.sync;
    x.register("victory", function(vp, data){
        $ = DR.$;
        var ownerObj = data.specialHexes;
        var owner;
        let i;
        for(i in ownerObj){
            owner = ownerObj[i];
            break;
        }
        var name;
        if(owner == 0){
            name = "Nobody Owns the tree";
        }
        if(owner == 1){
            name = "<span class='playerRedFace'>Red owns the tree </span>";
        }
        if(owner == 2){
            name = "<span class='playerBlueFace'>Blue owns the tree </span>";
        }
        $("#victory").html(name);

    });

    x.register("vp", function(vp, data){
    });



    $("#altTable").on('click', function(){
        $(this).hide();
        $("#mainTable").show();
        $('.tableWrapper.main').hide();
        $('.tableWrapper.alt').show();
    });
    $("#mainTable").on('click', function(){
        $(this).hide();
        $("#altTable").show();
        $('.tableWrapper.alt').hide();
        $('.tableWrapper.main').show();
    });
    $("#altTable").show();
    $("#mainTable").hide();
    $(".tableWrapper.alt").hide();
    $(".tableWrapper.main").show();

    let fake = 1;
    x.register("moveRules", function (moveRules, data) {
        topVue.moveUnits = [];

        var str;
        $(".clone").remove();
        if (moveRules.movingUnitId >= 0) {
            if (moveRules.hexPath) {
                var id = moveRules.movingUnitId;
                for (var i in moveRules.hexPath) {
                    var newId = id + "Hex" + i;

                    $("#" + id).clone(true).attr('id', newId).appendTo('#gameImages');
                    $("#" + newId + " .arrow").hide();
                    $("#" + newId).addClass("clone");
                    $("#" + newId).css("top", 20);
                    var width = $("#" + newId).width();
                    var height = $("#" + newId).height();

                    $("#" + newId).css("left", moveRules.hexPath[i].pixX - width / 2 + "px");
                    $("#" + newId).css("top", moveRules.hexPath[i].pixY - height / 2 + "px");
                    $("#" + newId + " div.unit-numbers span").html("24 - " + moveRules.hexPath[i].pointsLeft);
                    $("#" + newId).css("opacity", .9);
                    $("#" + newId).css("z-index", 101);


                }
            }
            var opacity = .4;
            var borderColor = "#ccc #333 #333 #ccc";
            if (moveRules.moves) {
                var id = moveRules.movingUnitId;
                let newUnit = _.clone(data.mapUnits[id]);
                let width = 32;
                for (var i in moveRules.moves) {
                    // console.log(moveRules.moves[i])
                    // if(moveRules.moves[i].isOccupied){
                    //     console.log('==========> Occupied <===========');
                    //     continue;
                    // }

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
                    // unitDecorate(ghostUnit, data)
                    ghostUnit.borderColor = borderColor;
                    topVue.moveUnits.push(ghostUnit);
                }
                return;
                secondGenClone.appendTo('#gameImages').css({
                    left: moveRules.moves[i].pixX - width / 2 + "px",
                    top: moveRules.moves[i].pixY - height / 2 + "px"
                });

                var newId = "firstclone";
                width = $("#" + id).width();
                var height = $("#" + id).height();

                var MYCLONE = $("#" + id).clone(true).detach();
                MYCLONE.find(".arrow").hide();
                MYCLONE.addClass("clone");
                MYCLONE.find('.shadow-mask').css({backgroundColor: 'transparent'});
                MYCLONE.hover(function () {
                        if (opacity != 1) {
                            $(this).css("border-color", "#fff");
                        }
                        $(this).css("opacity", 1.0)
                        var path = $(this).attr("path");
                        var pathes = path.split(",");
                        for (var i in pathes) {
                            $("#" + id + "Hex" + pathes[i]).css("opacity", 1.0).css("border-color", "#fff")
                            $("#" + id + "Hex" + pathes[i] + ".occupied").css("display", "block");

                        }
                    },
                    function () {
                        if (opacity != 1) {
                            $(this).css("border-color", "#ccc #333 #333 #ccc");
                        }
                        $(this).css("opacity", opacity).css('box-shadow', 'none');
                        var path = $(this).attr("path");
                        var pathes = path.split(",");
                        for (var i in pathes) {
                            $("#" + id + "Hex" + pathes[i]).css("opacity", .4).css("border-color", "#ccc #333 #333 #ccc").css('box-shadow', 'none');
                            $("#" + id + "Hex" + pathes[i] + ".occupied").css("display", "none");

                        }

                    });

                var label = MYCLONE.find("div.unit-numbers span").html();
                if (data.gameRules.phase == RED_COMBAT_PHASE || data.gameRules.phase == BLUE_COMBAT_PHASE || data.gameRules.phase == TEAL_COMBAT_PHASE || data.gameRules.phase == PURPLE_COMBAT_PHASE) {
                    if (data.gameRules.mode == ADVANCING_MODE) {
                        var unit = moveRules.movingUnitId;

                        var thetas = data.combatRules.resolvedCombats[data.combatRules.currentDefender].thetas[unit]
                        for (var k in thetas) {
                            $("#" + unit + " .arrow").clone().addClass('arrowClone').addClass('arrow' + k).insertAfter("#" + unit + " .arrow").removeClass('arrow');
                            var theta = thetas[k];
                            theta *= 15;
                            theta += 180;
                            $("#" + unit + " .arrow" + k).css({opacity: "1.0"});
                            $("#" + unit + " .arrow" + k).css({webkitTransform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(45px)"});
                            $("#" + unit + " .arrow" + k).css({transform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(45px)"});

                            _.forEach(topVue.units, (mapUnit) =>{
                                if(mapUnit.id == unit){
                                    mapUnit.thetas.push(theta);
                                }
                            })

                        }
                    }
                    opacity = 1.;
                    borderColor = "turquoise";
                }
                MYCLONE.css({
                        opacity: opacity,
                        zIndex: 102,
                        borderColor: borderColor,
                        boxShadow: "none",
                        position: "absolute"
                    }
                );
                var diff = 0;
                var counter = 0;
                for (var i in moveRules.moves) {
                    counter++;
                    newId = id + "Hex" + i;

                    var secondGenClone = MYCLONE.clone(true).attr(
                        {
                            id: newId,
                            path: moveRules.moves[i].pathToHere
                        }
                    );

//                var newLabel = label.replace(/((?:<span[^>]*>)?[-+ru](?:<\/span>)?).*/,"$1 "+moveRules.moves[i].pointsLeft);
                    var newLabel;
                    // if(window.renderUnitNumbers){
                    //     newLabel = window.renderUnitNumbers(data.mapUnits[id], moveRules.moves[i].pointsLeft, moveRules.moves[i], secondGenClone);
                    //
                    // }else{
                    //     newLabel = renderUnitNumbers(data.mapUnits[id], moveRules.moves[i].pointsLeft, moveRules.moves[i], secondGenClone);
                    //
                    // }
                    var txt = secondGenClone.find('div.unit-numbers .unit-info').html(newLabel).text();
                    // secondGenClone.find('div.unit-numbers .unit-info').addClass('infoLen' + txt.length);
                    // secondGenClone.find('.counterWrapper .guard-unit').addClass('infoLen' + newLabel.length);
                    if (moveRules.moves[i].isOccupied) {
                        secondGenClone.addClass("occupied");
                        secondGenClone.css('display')


                    }
                    /* left and top need to be set after appendTo() */

                    secondGenClone.appendTo('#gameImages').css({
                        left: moveRules.moves[i].pixX - width / 2 + "px",
                        top: moveRules.moves[i].pixY - height / 2 + "px"
                    });
                    /* apparently cloning attaches the mouse events */
                }

                $("#firstclone").remove();
            }

        }
    });



    x.register("mapUnits", function (mapUnits, data) {
        floaters.message = '';
        var str;
        var fudge;
        var x, y;
        var beforeDeploy = $("#deployBox").children().length;
        DR.stackModel = {};
        DR.stackModel.ids = {};
        // clearHexes();
        headerVue.deployBox = [];
        topVue.units = [];
        topVue.moveUnits = [];


        var phasingForceId = data.gameRules.attackingForceId;

        var phasingUnitsLeft = 0;

        for (var i in mapUnits) {
            if (typeof mapUnits[i].parent == "undefined") {
                $('#' + i).hide();
                continue;
            } else {
                $('#' + i).css("display", "");
            }
            if (mapUnits[i].forceId === phasingForceId && mapUnits[i].parent === "deployBox") {
                phasingUnitsLeft++;
            }
            var width = $("#" + i).width();
            var height = $("#" + i).height();
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
                let slot = mapUnits[i].parent;
                if(!headerVue[slot]){
                    headerVue[slot] = [];
                }
                // if(!DR.units[slot]){
                //     DR.units[slot] = [];
                // }
                unitDecorate(mapUnits[i], data);
                headerVue[slot].push(mapUnits[i]);
                // DR.units[slot].push(mapUnits[i]);
                continue;
            }
            // if (mapUnits[i].parent != $("#" + i).parent().attr("id")) {
            //     $("#" + i).appendTo($("#" + mapUnits[i].parent));
            //     if (mapUnits[i].parent != "gameImages") {
            //         $("#" + i).css({top: "0"});
            //         $("#" + i).css({left: "0"});
            //         if (!mapUnits[i].parent.match(/^gameTurn/)) {
            //             $("#" + i).css({float: "left"});
            //         }
            //         $("#" + i).css({position: "relative"});
            //     } else {
            //         $("#" + i).css({float: "none"});
            //         $("#" + i).css({position: "absolute"});
            //
            //     }
            // }
            width += 6;
            height += 6;
            if (mapUnits[i].parent == "gameImages") {
                mapUnits[i].id = i - 0;
                mapUnits[i].x -= 18;
                mapUnits[i].y -= 18;
                unitDecorate(mapUnits[ i], data);


                topVue.units.push(mapUnits[i]);
                // DR.units.units.push(mapUnits[i]);

            }



            // var str = mapUnits[i].strength;
            // var symb = mapUnits[i].supplied !== false ? " - " : " <span class='reduced'>u</span> ";
//        symb = "-"+mapUnits[i].defStrength+"-";
//         var html = reduceDisp + str + symb + move + "</span>";
            // renderOuterUnit(i, mapUnits[i]);
            // if(window.renderUnitNumbers){
            //     html = window.renderUnitNumbers(mapUnits[i]);
            // }else{
            //     html = renderUnitNumbers(mapUnits[i]);
            // }
            // if (html) {
            //     $("#" + i + " .unit-numbers").html(html);
            // }
            // if(mapUnits[i].range > 1){
            //     $("#" + i + " .range").html(mapUnits[i].range);
            // }else{
            //     $("#" + i + " .range").html('');
            // }
            // var len = $("#" + i + " .unit-numbers").text().length;
            // $("#" + i + " div.unit-numbers span ").addClass("infoLen" + len);
            // $("#" + i + " .counterWrapper .guard-unit ").addClass("infoLen" + len);
            // $("#" + i).attr("src", img);
        }
        gmRules(data);

        var dpBox = $("#deployBox").children().length;
        if (dpBox != beforeDeploy) {
            fixHeader();
            beforeDeploy = dpBox;

        }
        if ((dpBox == 0 || (phasingUnitsLeft === 0 && data.gameRules.mode !==  DEPLOY_MODE)) && $("#deployBox").is(":visible")) {
            $("#deployWrapper").hide({effect: "blind", direction: "up", complete: fixHeader});
        }


        // svgRefresh();
        // if(DR.showHexes){
        //     $("#showHexes").addClass('negative');
        // }else{
        //     $("#showHexes").removeClass('negative');
        // }
        // if(DR.showHexes){
        //     $('.range-hex').addClass('hovering');
        // }else{
        //     $('.range-hex').removeClass('hovering');
        // }
    });


    x.register("specialHexes", function(specialHexes, data) {
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
    x.register("force", function (force, data) {

        return;
        var units = data.mapUnits;

        var status = "";
        var boxShadow;
        var shadow;
        $("#floatMessage").hide();
        var showStatus = false;
        var totalAttackers = 0;
        var totalDefenders = 0;
        for (var i in units) {
            var color = "#ccc #666 #666 #ccc";
            var style = "solid";
            $("#" + i + " .arrow").css({opacity: "0.0"});
            $("#" + i + " .arrowClone").remove();
            boxShadow = "none";
            shadow = true;
            if (units[i].forceId !== force.attackingForceId) {
                shadow = false;
            }
            if (units[i].forceMarch) {
                $("#" + i + " .forceMarch").show();
                $("#" + i + " .range").hide();
            } else {
                $("#" + i + " .forceMarch").hide();
                $("#" + i + " .range").show();
            }
            if (force.requiredDefenses && force.requiredDefenses[i] === true) {

                color = "black";
                style = "dotted";
                totalDefenders++;
            }
            if (units[i].isImproved === true) {
                style = 'dotted';
                color = 'black';
                var colour = $("#" + i).css('color');
                if (colour === "rgb(255, 255, 255)") {
                    color = 'white';
                }
            }
            switch (units[i].status) {
                case STATUS_CAN_REINFORCE:
                case STATUS_CAN_DEPLOY:
                    color = "#ccc #666 #666 #ccc";
                    shadow = false;
                    if (units[i].reinforceTurn) {
                        shadow = true;
                    }
                    break;
                case STATUS_READY:
                    if (units[i].forceId === force.attackingForceId) {
                        $("#" + i + " .arrow").css({opacity: "0.0"});

                        shadow = false;
                    } else {
                    }
                    if (force.requiredAttacks && force.requiredAttacks[i] === true) {
                        color = "black";
                        style = "dotted";
                        totalAttackers++;
                    }
                    break;
                case STATUS_REINFORCING:
                case STATUS_DEPLOYING:
                    shadow = false;
                    boxShadow = '5px 5px 5px #333';


                    break;
                case STATUS_MOVING:
                    // if (units[i].forceMarch) {
                    //     $("#" + i + " .forceMarch").show();
                    //     $("#" + i + " .range").hide();
                    //
                    //     color = "#f00 #666 #666 #f00";
                    // } else {
                    //     $("#" + i + " .forceMarch").hide();
                    //     $("#" + i + " .range").show();
                    //
                    //     color = "#ccc #666 #666 #ccc";
                    //
                    // }
                    // $("#" + i).css({zIndex: 4});
                    color = "lightgreen";
                    shadow = false;
                    DR.lastMoved = i;
                    break;

                case STATUS_STOPPED:
                    if (i === DR.lastMoved) {
                        $("#" + i).css({zIndex: 4});
                    }
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
//                    $("#floatMessage header").html(result+' Exchanging Mode');
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
                    if (units[i].forceId === force.attackingForceId) {
                        shadow = false;
                        color = "turquoise";
                    }
                    break;

                case STATUS_ELIMINATED:
                    break;

            }
            if (status) {
                showStatus = true;

                var x = $("#" + i).position().left;
                var y = $("#" + i).position().top;
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

                // if (DR.floatMessageDragged != true) {
                //     DR.$floatMessagePanZoom.panzoom('reset');
                //     $("#floatMessage").css('top', y + "px");
                //     $("#floatMessage").css('left', x + "px");
                // }
                // $("#floatMessage").show();
                // $("#floatMessage p").html(status);
                floaters.message = status;
                floaters.x = x;
                floaters.y = y;
                status = "";
            }else{
                floaters.message = "";
            }

            $("#" + i).css({borderColor: color, borderStyle: style});
            if (shadow) {
                $("#" + i + " .shadow-mask").addClass("shadowy");
            } else {
                $("#" + i + " .shadow-mask").removeClass("shadowy");
            }
            if (units[i].isDisrupted || units[i].pinned) {
                var disp = '';
                if (units[i].pinned) {
                    disp = 'P'
                }
                if (units[i].isDisrupted) {
                    disp = 'D';
                }
                if (units[i].disruptLen > 1) {
                    disp = 'DD';
                }
                if (units[i].disruptLevel) {
                    disp = 'D' + units[i].disruptLevel;
                }
                if (units[i].isDisrupted === true || units[i].pinned === true) {
                    $("#" + i + " .shadow-mask").addClass("red-shadowy").html("<span class='disrupted'>" + disp + "</span>");
                }
            } else {
                $("#" + i + " .shadow-mask").removeClass("red-shadowy").html('');
            }
            $("#" + i).css({boxShadow: boxShadow});


        }
        if (totalAttackers || totalDefenders) {
            $("#requiredCombats").html(totalAttackers + " attackers " + totalDefenders + " defenders required");
        } else {
            $("#requiredCombats").html('');
        }

        if (!showStatus) {
            DR.floatMessageDragged = false;
        }

    });

    function unitDecorate(unit, data) {

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
        // $("#" + i + " .arrow").css({opacity: "0.0"});
        // $("#" + i + " .arrowClone").remove();
        var boxShadow = "none";
        var shadow = true;
        if (unit.forceId !== force.attackingForceId) {
            shadow = false;
        }
        // if (unit.forceMarch) {
        //     $("#" + i + " .forceMarch").show();
        //     $("#" + i + " .range").hide();
        // } else {
        //     $("#" + i + " .forceMarch").hide();
        //     $("#" + i + " .range").show();
        // }
        // if (force.requiredDefenses && force.requiredDefenses[i] === true) {
        //
        //     color = "black";
        //     style = "dotted";
        //     totalDefenders++;
        // }
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
                    // $("#" + i + " .arrow").css({opacity: "0.0"});

                    shadow = false;
                } else {
                }
                // if (force.requiredAttacks && force.requiredAttacks[i] === true) {
                //     color = "black";
                //     style = "dotted";
                //     totalAttackers++;
                // }
                break;
            case STATUS_REINFORCING:
            case STATUS_DEPLOYING:
                shadow = false;
                boxShadow = '5px 5px 5px #333';


                break;
            case STATUS_MOVING:
                // if (unit.forceMarch) {
                //     $("#" + i + " .forceMarch").show();
                //     $("#" + i + " .range").hide();
                //
                //     color = "#f00 #666 #666 #f00";
                // } else {
                //     $("#" + i + " .forceMarch").hide();
                //     $("#" + i + " .range").show();
                //
                //     color = "#ccc #666 #666 #ccc";
                //
                // }
                // $("#" + i).css({zIndex: 4});
                color = "lightgreen";
                shadow = false;
                // DR.lastMoved = i;
                break;

            case STATUS_STOPPED:
                // if (i === DR.lastMoved) {
                //     $("#" + i).css({zIndex: 4});
                // }
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
                    floaters.header = result+' Exchanging Mode'
//                    $("#floatMessage header").html(result+' Exchanging Mode');
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
            //
            // if (DR.floatMessageDragged != true) {
            //     DR.$floatMessagePanZoom.panzoom('reset');
            //     $("#floatMessage").css('top', y + "px");
            //     $("#floatMessage").css('left', x + "px");
            // }
            // $("#floatMessage").show();
            // $("#floatMessage p").html(status);
            floaters.message = status;
            floaters.x = x;
            floaters.y = y;
            status = "";
        }

        // $("#" + i).css({borderColor: color, borderStyle: style});
        unit.borderColor = color;
        unit.borderStyle = style;
        unit.shadow = shadow;
        unit.boxShadow = boxShadow;
        // if (shadow) {
        //     $("#" + i + " .shadow-mask").addClass("shadowy");
        // } else {
        //     $("#" + i + " .shadow-mask").removeClass("shadowy");
        // }
        // if (unit.isDisrupted || unit.pinned) {
        //     var disp = '';
        //     if (unit.pinned) {
        //         disp = 'P'
        //     }
        //     if (unit.isDisrupted) {
        //         disp = 'D';
        //     }
        //     if (unit.disruptLen > 1) {
        //         disp = 'DD';
        //     }
        //     if (unit.disruptLevel) {
        //         disp = 'D' + unit.disruptLevel;
        //     }
        //     if (unit.isDisrupted === true || unit.pinned === true) {
        //         $("#" + i + " .shadow-mask").addClass("red-shadowy").html("<span class='disrupted'>" + disp + "</span>");
        //     }
        // } else {
        //     // $("#" + i + " .shadow-mask").removeClass("red-shadowy").html('');
        // }




    }
    x.register("combatRules", function (combatRules, data) {
        _.forEach(topVue.units, (mapUnit) => {
            mapUnit.thetas = [];
        })

        for (var combatCol = 1; combatCol <= 11; combatCol++) {
            $(".col" + combatCol).css({background: "transparent"});
        }
        var title = "Combat Results ";
        var cdLine = "";
        var activeCombat = false;
        var activeCombatLine = "";
        str = "";
        var toResolveLog = "";
        $('.unit .unitOdds').remove();

        if (combatRules) {
            var cD = combatRules.currentDefender;
            if (combatRules.combats && Object.keys(combatRules.combats).length > 0) {
                if (cD !== false) {
                    var defenders = combatRules.combats[cD].defenders;
                    if (combatRules.combats[cD].useAlt) {
                        showCrtTable($('#cavalryTable'));
                    } else {
                        if (combatRules.combats[cD].useDetermined) {
                            showCrtTable($('#determinedTable'));
                        } else {
                            showCrtTable($('#normalTable'));
                        }
                    }
                    if (data.gameRules.phase ==  BLUE_TORP_COMBAT_PHASE || data.gameRules.phase ==  RED_TORP_COMBAT_PHASE) {
                        showCrtTable($('#torpedoTable'));
                        $('.torpedoHitOneTable').show();
                        $('.torpedoHitTwoTable').show();

                    }
                    for (var loop in defenders) {

                        _.forEach(topVue.units, (mapUnit) =>{
                            if(mapUnit.id == loop){
                                mapUnit.borderColor = 'yellow';
                            }
                        })
                    }
                    if (!chattyCrt) {
                        $("#crt").show({effect: "blind", direction: "up"});
                        $("#crtWrapper").css('overflow', 'visible');
                        chattyCrt = true;
                    }
                    if (Object.keys(combatRules.combats[cD].attackers).length != 0) {
                        if (combatRules.combats[cD].pinCRT !== false) {
                            combatCol = combatRules.combats[cD].pinCRT + 1;
                            if (combatCol >= 1) {
                                Vue.set(clickThrough.crtOptions,'index', combatCol-1);
                                $(".col" + combatCol).css('background-color', "rgba(255,0,255,.6)");
                                if (combatRules.combats[cD].Die !== false) {
                                    $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('font-size', "110%");
                                    $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('background', "#eee");
                                }
                            }
                        }
                        var combatCols;
                        if (typeof combatRules.combats[cD].index === 'object') {
                            combatCols = combatRules.combats[cD].index.map(function (val) {
                                return val + 4;
                            });
                        } else {
                            combatCols = [combatRules.combats[cD].index + 1];
                        }
                        for (var index in combatCols) {
                            combatCol = combatCols[index];
                            if (combatCol >= 1) {
                                $(".col" + combatCol).css('background-color', "rgba(255,255,1,.6)");
                                Vue.set(clickThrough.crtOptions,'index', combatCol-1);

                                if (combatRules.combats[cD].Die !== false) {
                                    $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('font-size', "110%");
                                    $(".row" + combatRules.combats[cD].Die + " .col" + combatCol).css('background', "#eee");
                                }
                                $(".torpedoHitOneTable .col" + combatCol).css({background: "transparent"});
                                $(".torpedoHitTwoTable .col" + combatCol).css({background: "transparent"});

                                if (data.gameRules.phase ==  BLUE_TORP_COMBAT_PHASE || data.gameRules.phase ==  RED_TORP_COMBAT_PHASE) {
                                    var oneHitCol = combatRules.combats[cD].oneHitCol + 1;
                                    var twoHitCol = combatRules.combats[cD].twoHitCol + 1;

                                    $(".torpedoHitOneTable .col" + oneHitCol).css('background-color', "rgba(255,255,1,.6)");
                                    $(".torpedoHitTwoTable .col" + twoHitCol).css('background-color', "rgba(255,255,1,.6)");
                                }
                            }
                        }
                    }
                }
                var str = "";
                cdLine = "";
                var combatIndex = 0;
                $('.unit').removeAttr('title');
                $('.unit .unitOdds').remove();
                for (var i in combatRules.combats) {
                    if (combatRules.combats[i].index !== null) {


                        var attackers = combatRules.combats[i].attackers;
                        var defenders = combatRules.combats[i].defenders;
                        var thetas = combatRules.combats[i].thetas;

                        var theta = 0;

                        for (var j in attackers) {

                            var numDef = Object.keys(defenders).length;
                            for (var k in defenders) {
                                theta = thetas[j][k];
                                theta *= 15;
                                theta += 180;
                                _.forEach(topVue.units, (mapUnit) =>{
                                    if(mapUnit.id == j){
                                        mapUnit.thetas.push(theta);
                                    }
                                })
                            }
                        }

                        var atk = combatRules.combats[i].attackStrength;
                        var atkDisp = atk;

                        var def = combatRules.combats[i].defenseStrength;
                        var ter = combatRules.combats[i].terrainCombatEffect;
                        var idx = combatRules.combats[i].index + 1;
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
                            currentCombatCol = combatRules.combats[i].index + 1;
                            if(currentCombatCol <= 0){
                                currentOddsDisp =  '<' + $(".col" + 1).html();
                            }else{
                                currentOddsDisp = $(".col" + currentCombatCol).html();
                            }
                            if(combatCol <= 0){
                                oddsDisp = '< ' +  $(".col" + 1).html();

                            }else{
                                oddsDisp = $(".col" + combatCol).html();
                            }
                        }
                        if (combatRules.combats[i].pinCRT !== false) {
                            currentCombatCol = combatRules.combats[i].pinCRT + 1;
                            currentOddsDisp = $(".col" + currentCombatCol).html();
                            useAltColor = " pinnedColor";
                        }
                        $("#" + i).attr('title', currentOddsDisp).prepend('<div class="unitOdds' + useAltColor + '">' + currentOddsDisp + '</div>');
                        ;

                        var details = renderCrtDetails(combatRules.combats[i]);
                        var newLine = "<h5>odds = " + oddsDisp + " </h5>" + details;
                        if (cD !== false && cD == i) {
                            activeCombat = combatIndex;
                            activeCombatLine = newLine;
                            /*cdLine = "<fieldset><legend>Current Combat</legend><strong>"+newLine+"</strong></fieldset>";
                             newLine = "";*/
                        }
                        combatIndex++;
//                            str += newLine;
                    }

                }
                str += "There are " + combatIndex + " Combats";
                if (cD !== false) {
                    var attackers = combatRules.combats[cD].attackers;
//                    var theta = 0;
//                    for(var i in attackers){
//                                      theta = attackers[i];
//                        theta *= 15;
//                        theta += 180;
//                        $("#"+i+ " .arrow").css({display: "block"});
//                        $("#"+i+ " .arrow").css({opacity: "1.0"});
//                        $("#"+i+ " .arrow").css({webkitTransform: 'scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
//
//
//                    }
                }
                str += "";
                $("#crtOddsExp").html(activeCombatLine);
                $("#status").html(cdLine + str);
                if (DR.crtDetails) {
                    $("#crtDetails").toggle();
                }
                $("#status").show();

            } else {
                chattyCrt = false;
            }


            var lastCombat = "";
            if (combatRules.combatsToResolve) {
                $('.unit').removeAttr('title');
                $('.unit .unitOdds').remove();
                if (combatRules.lastResolvedCombat) {
                    toResolveLog = "Current Combat or Last Combat<br>";
                    title += "<strong style='margin-left:20px;font-size:150%'>" + combatRules.lastResolvedCombat.Die + " " + combatRules.lastResolvedCombat.combatResult + "</strong>";
                    combatCol = combatRules.lastResolvedCombat.index + 1;

                    var combatRoll = combatRules.lastResolvedCombat.Die;
                    if (data.gameRules.phase ==  BLUE_TORP_COMBAT_PHASE || data.gameRules.phase ==  RED_TORP_COMBAT_PHASE) {
                        $(".torpedoTable .col" + combatCol).css('background-color', "rgba(255,255,1,.6)");
                        $(".torpedoTable .row" + combatRoll + " .col" + combatCol).css('background-color', "cyan");

                    } else {
                        $(".col" + combatCol).css('background-color', "rgba(255,255,1,.6)");
                        var pin = combatRules.lastResolvedCombat.pinCRT;
                        if (pin !== false) {
                            pin++;
                            if (pin < combatCol) {
                                combatCol = pin;
                                $(".col" + combatCol).css('background-color', "rgba(255, 0, 255, .6)");
                            }
                        }

                        $(".row" + combatRoll + " .col" + combatCol).css('background-color', "cyan");
                    }
                    if (combatRules.lastResolvedCombat.useAlt) {
                        showCrtTable($('#cavalryTable'));
                    } else {
                        if (combatRules.lastResolvedCombat.useDetermined) {
                            showCrtTable($('#determinedTable'));
                        } else {
                            showCrtTable($('#normalTable'));
                        }
                    }
                    if (data.gameRules.phase ==  BLUE_TORP_COMBAT_PHASE || data.gameRules.phase ==  RED_TORP_COMBAT_PHASE) {
                        showCrtTable($('#torpedoTable'));
                        var hitRoll = combatRules.lastResolvedCombat.hitDie;
                        var hitCol = combatRules.lastResolvedCombat.hitCol;

                        if (combatRules.lastResolvedCombat.hits ===  'H' ) {
                            $('.torpedoHitOneTable').show();
                            $(".torpedoHitOneTable .col" + hitCol).css('background-color', "rgba(255,255,1,.6)");
                            $(".torpedoHitOneTable .row" + hitRoll + " .col" + hitCol).css('background-color', "cyan");
                        }
                        if (combatRules.lastResolvedCombat.hits ===  'HH' ) {
                            $('.torpedoHitTwoTable').show();
                            $(".torpedoHitTwoTable .col" + hitCol).css('background-color', "rgba(255,255,1,.6)");
                            $(".torpedoHitTwoTable .row" + hitRoll + " .col" + hitCol).css('background-color', "cyan");
                        }
                    }
                    var atk = combatRules.lastResolvedCombat.attackStrength;
                    var atkDisp = atk;
                    ;

                    var def = combatRules.lastResolvedCombat.defenseStrength;
                    var ter = combatRules.lastResolvedCombat.terrainCombatEffect;
                    var idx = combatRules.lastResolvedCombat.index + 1;
                    var odds = Math.floor(atk / def);
                    var oddsDisp = $(".col" + combatCol).html();
                    var details = renderCrtDetails(combatRules.lastResolvedCombat);

                    newLine = "<h5>odds = " + oddsDisp + "</h5>" + details;

                    toResolveLog += newLine;
                    toResolveLog += "Roll: " + combatRules.lastResolvedCombat.Die + " result: " + combatRules.lastResolvedCombat.combatResult + "<br><br>";

                    $("#crtOddsExp").html(newLine);
//                    $(".row"+combatRoll+" .col"+combatCol).css('color',"white");
                }
                str += "";
                var noCombats = false;
                if (Object.keys(combatRules.combatsToResolve) == 0) {
                    noCombats = true;
                    str += "0 combats to resolve";
                }
                var combatsToResolve = 0;
                toResolveLog += "Combats to Resolve<br>";
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
                        var oddsDisp = $(".col" + combatCol).html();
                        var useAltColor = combatRules.combatsToResolve[i].useAlt ? " altColor" : "";
                        if (combatRules.combatsToResolve[i].useDetermined) {
                            useAltColor = " determinedColor";
                        }
                        if (combatRules.combatsToResolve[i].pinCRT !== false) {
                            oddsDisp = combatRules.combatsToResolve[i].pinCRT + 1;
                            oddsDisp = $(".col" + oddsDisp).html();

                            useAltColor = " pinnedColor";
                        }
                        $("#" + i).attr('title', oddsDisp).prepend('<div class="unitOdds' + useAltColor + '">' + oddsDisp + '</div>');
                        ;
                        var details = renderCrtDetails(combatRules.combatsToResolve[i]);

                        newLine = "<h5>odds = " + oddsDisp + "</h5>" + details;
                        toResolveLog += newLine;
                    }

                }
                if (combatsToResolve) {
//                str += "Combats To Resolve: " + combatsToResolve;
                }
                var resolvedCombats = 0;
                toResolveLog += "<br>Resolved Combats <br>";
                for (var i in combatRules.resolvedCombats) {
                    resolvedCombats++;
                    if (combatRules.resolvedCombats[i].index !== null) {
                        atk = combatRules.resolvedCombats[i].attackStrength;
                        atkDisp = atk;

                        def = combatRules.resolvedCombats[i].defenseStrength;
                        ter = combatRules.resolvedCombats[i].terrainCombatEffect;
                        idx = combatRules.resolvedCombats[i].index + 1;
                        newLine = "";
                        if (combatRules.resolvedCombats[i].Die) {
                            // var x = $("#" + cD).css('left').replace(/px/, "");
                            // var mapWidth = $("body").css('width').replace(/px/, "");
                        }
                        var oddsDisp = $(".col" + combatCol).html()

                        newLine += " Attack = " + atkDisp + " / Defender " + def + atk / def + "<br>odds = " + Math.floor(atk / def) + " : 1<br>Combined Arms Shift " + ter + " = " + oddsDisp + "<br>";
                        newLine += "Roll: " + combatRules.resolvedCombats[i].Die + " result: " + combatRules.resolvedCombats[i].combatResult + "<br><br>";
                        if (cD === i) {
                            newLine = "";
                        }
                        toResolveLog += newLine;
                    }

                }
                if (!noCombats) {
                    str += "Combats: " + resolvedCombats + " of " + (resolvedCombats + combatsToResolve);
                }
                $("#status").html(lastCombat + str);
                $("#status").show();

            }
        }
        $("#CombatLog").html(toResolveLog);
        $("#crt h3").html(title);


    });
    function renderCrtDetails(combat) {
        var atk = combat.attackStrength;
        var def = combat.defenseStrength;
        var div = atk / def;
        var ter = combat.terrainCombatEffect;
        var combatCol = combat.index + 1;
        var oddsDisp;
        if(combatCol <= 0){
            oddsDisp = '< '+$(".col" + 1).html();
        }else{
            oddsDisp = $(".col" + combatCol).html();
        }
        div = div.toFixed(2);
        var html = "<div id='crtDetails'>" + combat.combatLog + "</div><div class='clear'>Attack = " + atk + " / Defender " + def + " = " + div + "<br>Final Column  = " + oddsDisp + "</div>"
        /*+ atk + " - Defender " + def + " = " + diff + "</div>";*/
        return html;
    }

    x.register("mapSymbols", function (mapSymbols, data) {
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
                    newHtml = '<img src="'+mapSymbolsBefore + mapSymbols[i][symbolName].image + '" class="' + c + '">';
                }
                $("#gameImages").append('<div id="mapSymbol' + i + '" class="mapSymbols">' + newHtml + '</div>');
                $("#mapSymbol" + i).css({top: y + "px", left: x + "px"});

            }

        }
    });
    x.register("specialHexes", function(specialHexes, data) {
        topVue.mapSymbols = [];
        topVue.specialEvents = [];
        topVue.specialEvents.splice(0,topVue.specialEvents.length)
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
                    /* i didn't do it */
                    if(specialHexes[i] == 1 && i != 'x416y357'){
                        topVue.mapSymbols.push({x: x, y: y})
                    }else{
                        topVue.mapSymbols.push({x: x, y: y, text: "bad guys"})

                    }
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
            topVue.specialEvents.push({x: x, y: y, text:data.specialHexesVictory[id], id: hexPos});
            // var newVP = $('<div  style="z-index:1000;border-radius:0px;border:0px;top:'+y+'px;left:'+x+'px;font-size:60px;" class="'+' specialHexesVP">'+data.specialHexesVictory[id]+'</div>').appendTo('#gameImages');
            // $(newVP).animate({top:y-30,opacity:0.0},6900,function(){
            //     var id = $(this).attr('id');
            // });
        }


    });
    function gmRules(data) {
        switch (data.gameRules.mode) {
            case EXCHANGING_MODE:
                var result = data.combatRules.lastResolvedCombat.combatResult;

                floaters.header = (result + ": Exchanging Mode");

            case DEFENDER_LOSING_MODE:
                var result = data.combatRules.lastResolvedCombat.combatResult;

                floaters.header = (result + ": Defender Loss Mode.");
                var floatStat = floaters.message;

                floatStat = "Lose at least " + data.force.exchangeAmount + " strength points<br>" + floatStat;
                floaters.message = (floatStat);

//            html += "<br>Lose at least "+gameRules.exchangeAmount+" strength points from the units outlined in red";
                break;

            case ATTACKER_LOSING_MODE:
                var result = data.combatRules.lastResolvedCombat.combatResult;

                floaters.header = (result + ": Attacker Loss Mode.");
                var floatStat = floaters.message;

                floatStat = "Lose at least " + data.force.exchangeAmount + " strength points<br>" + floatStat;
                // $("#floatMessage p").html(floatStat);
                floaters.message = floatStat;

//            html += "<br>Lose at least "+gameRules.exchangeAmount+" strength points from the units outlined in red";
                break;
            case ADVANCING_MODE:
//            html += "<br>Click on one of the black units to advance it.<br>then  click on a hex to advance, or the unit to stay put.";
                var result = data.combatRules.lastResolvedCombat.combatResult;

                floaters.headers = (result + ": Advancing Mode");
                break;
            case RETREATING_MODE:
                var result = data.combatRules.lastResolvedCombat.combatResult;

                floaters.header = (result + ": Retreating Mode");
                break;
        }
    }

    x.register("gameRules",  (gameRules, data) => {

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
        // $(".dynamicButton").hide();
        if (gameRules.mode ===  MOVING_MODE) {
           clickThrough.dynamicButtons.move = true;
        }

        if(DR.hasHq){
            clickThrough.dynamicButtons.showHexes = true;
            $('#showHexes').show();
        }
        if (gameRules.mode ===  COMBAT_SETUP_MODE) {
            clickThrough.dynamicButtons.determined = true;
            $(".combatButton").show();
        }
        if (gameRules.display) {
            if (gameRules.display.currentMessage) {
                $("#display").html(gameRules.display.currentMessage + "<button onclick='doitNext()'>Next</button>").show();
            } else {
                $("#display").html("").hide();
            }
        }
        var status = "";
        var turn = gameRules.turn;
        var maxTurn = gameRules.maxTurn
        if ("gameTurn" + turn != $("#turnCounter").parent().attr("id")) {
            $("#gameTurn" + turn).prepend($("#turnCounter"));
        }

        var pix = turn + (turn - 1) * 36 + 1;
        var playerName = "player" + (DR.players[gameRules.attackingForceId].replace(/ /g, '-').replace(/\//gi, '_'));
        ;
        Vue.set(clickThrough.crtOptions, 'playerName', playerName);
        // clickThrough.crtOptions.playerName = playerName;
        var removeThese = "";
        $("#header").removeClass().addClass(playerName);
        $("#turnCounter").css("background", "rgb(0,128,0)");
        $("#turnCounter").css("color", "white");

        var alsoRemoveThese = DR.players.join('@@@').trim();
        alsoRemoveThese = alsoRemoveThese.replace(/ /g, '-');
        alsoRemoveThese = alsoRemoveThese.replace(/\//g, '_');
        alsoRemoveThese = alsoRemoveThese.replace(/@@@/g, ' ');
        alsoRemoveThese = alsoRemoveThese.replace(/([^ ]+)/g, "player$1");
        removeThese += " " + alsoRemoveThese;
        $("#crt").removeClass(removeThese).addClass(playerName);
        $(".row1,.row3,.row5").removeClass(removeThese).addClass(playerName);
        $("#revolt-table").removeClass(removeThese).addClass(playerName);

        var html = "<span id='turn'>Turn " + turn + " of " + maxTurn + "</span> ";
        var phase = gameRules.phase_name[gameRules.phase];
        phase = phase.replace(/fNameOne/, DR.playerOne);
        phase = phase.replace(/playerOneFace/, "player" + DR.playerOne.replace(/ /g, '-') + "Face");
        phase = phase.replace(/playerTwoFace/, "player" + DR.playerTwo.replace(/ /g, '-') + "Face");
        phase = phase.replace(/playerThreeFace/, "player" + DR.playerThree.replace(/ /g, '-') + "Face");
        phase = phase.replace(/playerFourFace/, "player" + DR.playerFour.replace(/ /g, '-') + "Face");

        phase = phase.replace(/fNameTwo/, DR.playerTwo);
        phase = phase.replace(/fNameThree/, DR.playerThree);
        phase = phase.replace(/fNameFour/, DR.playerFour);
        html += "<span id='phase'>" + phase;
        if (gameRules.mode_name[gameRules.mode]) {
            html += " " + gameRules.mode_name[gameRules.mode];
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

                $("#floatMessage header").html(result + ": Exchanging Mode");

            case DEFENDER_LOSING_MODE:
                var result = data.combatRules.lastResolvedCombat.combatResult;

                $("#floatMessage header").html(result + ": Defender Loss Mode.");
                var floatStat = $("#floatMessage p").html();

                floatStat = "Lose at least " + data.force.exchangeAmount + " strength points<br>" + floatStat;
                $("#floatMessage p").html(floatStat);

//            html += "<br>Lose at least "+gameRules.exchangeAmount+" strength points from the units outlined in red";
                break;

            case ATTACKER_LOSING_MODE:
                var result = data.combatRules.lastResolvedCombat.combatResult;

                $("#floatMessage header").html(result + ": Attacker Loss Mode.");
                var floatStat = $("#floatMessage p").html();

                floatStat = "Lose at least " + data.force.exchangeAmount + " strength points<br>" + floatStat;
                $("#floatMessage p").html(floatStat);

//            html += "<br>Lose at least "+gameRules.exchangeAmount+" strength points from the units outlined in red";
                break;
            case ADVANCING_MODE:
//            html += "<br>Click on one of the black units to advance it.<br>then  click on a hex to advance, or the unit to stay put.";
                var result = data.combatRules.lastResolvedCombat.combatResult;

                $("#floatMessage header").html(result + ": Advancing Mode");
                break;
            case RETREATING_MODE:
                var result = data.combatRules.lastResolvedCombat.combatResult;

                $("#floatMessage header").html(result + ": Retreating Mode");
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
        $("#logWrapper").html(log);
        $("#topStatus").html(html);
        if (status) {
            $("#status").html(status);
            $("#status").show();

        } else {
            $("#status").html(status);
            $("#status").hide();

        }
    });

});
