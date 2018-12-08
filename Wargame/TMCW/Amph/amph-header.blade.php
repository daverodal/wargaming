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
?>
<script type="text/javascript">
    x.register("specialHexes", function(specialHexes, data) {
        $ = DR.$;
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
    function renderOuterUnit(id, unit) {

    }

    function renderUnitNumbers(unit, moveAmount) {

        var move = unit.maxMove - unit.moveAmountUsed;
        if (moveAmount !== undefined) {
            move = moveAmount - 0;
        }
        move = move.toFixed(2);
        move = move.replace(/\.00$/, '');
        move = move.replace(/(\.[1-9])0$/, '$1');
        var str = unit.strength;
        var reduced = unit.isReduced;
        var reduceDisp = "<span class='unit-info'>";
        if (reduced) {
            reduceDisp = "<span class='unit-info reduced'>";
        }
        var symb = unit.supplied !== false ? " - " : " <span class='reduced'>u</span> ";
//        symb = "-"+unit.defStrength+"-";
        var html = reduceDisp + str + symb + move + "</span>";
        return html;


    }

    function svgRefresh(){
        var svgHtml = $('#svgWrapper').html();
        $('#svgWrapper').html(svgHtml);
    }

    function clearHexes() {
        $('#arrow-svg path.range-hex').remove();
    }
    x.register("mapUnits", function (mapUnits, data) {
        $ = DR.$;
        var str;
        var fudge;
        var x, y;
        var beforeDeploy = $("#deployBox").children().length;
        DR.stackModel = {};
        DR.stackModel.ids = {};
        clearHexes();


        var phasingForceId = data.gameRules.attackingForceId;

        var phasingUnitsLeft = 0;

        for (var i in mapUnits) {
            if (typeof mapUnits[i].parent == "undefined") {
                $('#' + i).hide();
                continue;
            } else {
                $('#' + i).css("display", "");
            }
            if (mapUnits[i].forceId === phasingForceId &&
                ( mapUnits[i].parent === "deployBox" ||
                    mapUnits[i].parent === "beach-landing" ||
                    mapUnits[i].parent === "airdrop" ||
                    mapUnits[i].parent === "east" ||
                    mapUnits[i].parent === "west" ||
                    mapUnits[i].parent === "south"
                )) {
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

            if (mapUnits[i].parent != $("#" + i).parent().attr("id")) {
                $("#" + i).appendTo($("#" + mapUnits[i].parent));
                if (mapUnits[i].parent != "gameImages") {
                    $("#" + i).css({top: "0"});
                    $("#" + i).css({left: "0"});
                    if (!mapUnits[i].parent.match(/^gameTurn/)) {
                        $("#" + i).css({float: "left"});
                    }
                    $("#" + i).css({position: "relative"});
                } else {
                    $("#" + i).css({float: "none"});
                    $("#" + i).css({position: "absolute"});

                }
            }
            width += 6;
            height += 6;
            if (mapUnits[i].parent == "gameImages") {

                $("#" + i).css({
                    left: mapUnits[i].x - width / 2 - fudge + "px",
                    top: mapUnits[i].y - height / 2 - fudge + "px",
                    zIndex: zIndex
                });

                if(mapUnits[i].class === "hq" && data.scenario.commandControl === true) {
                    DR.hasHq = true;
                    var hexSideLen = 32;
                    var b = hexSideLen * .866;
                    var unit = mapUnits[i];
                    var range = mapUnits[i].cmdRange;
                    unit.id = i;
                    globalFuncs.drawHex(hexSideLen, unit, 'short');
                    globalFuncs.drawHex(b * (range * 2 + 1), unit);
                    $("#" + i).hover(function () {
                        var id = $(this).attr('id');
                        $('#arrow-svg #rangeHex' + id).addClass('hovered');
                        $('#arrow-svg #rangeHex' + id + 'short').attr('style', 'stroke:red;stroke-opacity:1;');
                    }, function () {
                        var id = $(this).attr('id');
                        $('#arrow-svg #rangeHex' + id).removeClass('hovered');
                        $('#arrow-svg #rangeHex' + id + 'short').attr('style', '');
                    });
                }


            }
            var img = $("#" + i + " img").attr("src");

            if (img) {
                if (mapUnits[i].isReduced) {
                    img = img.replace(/(.*[0-9])(\.png)/, "$1reduced.png");
                } else {
                    img = img.replace(/([0-9])reduced\.png/, "$1.png");
                }
            }
            var move = mapUnits[i].maxMove - mapUnits[i].moveAmountUsed;
            move = move.toFixed(2);
            move = move.replace(/\.00$/, '');
            move = move.replace(/(\.[1-9])0$/, '$1');
            var str = mapUnits[i].strength;
            var reduced = mapUnits[i].isReduced;
            var reduceDisp = "<span>";
            if (reduced) {
                reduceDisp = "<span class='reduced'>";
            }
            var symb = mapUnits[i].supplied !== false ? " - " : " <span class='reduced'>u</span> ";
//        symb = "-"+mapUnits[i].defStrength+"-";
            var html = reduceDisp + str + symb + move + "</span>";
            renderOuterUnit(i, mapUnits[i]);
            if(window.renderUnitNumbers){
                html = window.renderUnitNumbers(mapUnits[i]);
            }else{
                html = renderUnitNumbers(mapUnits[i]);
            }
            if (html) {
                $("#" + i + " .unit-numbers").html(html);
            }
            if(mapUnits[i].range > 1){
                $("#" + i + " .range").html(mapUnits[i].range);
            }else{
                $("#" + i + " .range").html('');
            }
            var len = $("#" + i + " .unit-numbers").text().length;
            $("#" + i + " div.unit-numbers span ").addClass("infoLen" + len);
            $("#" + i + " .counterWrapper .guard-unit ").addClass("infoLen" + len);
            $("#" + i).attr("src", img);
        }
        var dpBox = $("#deployBox").children().length;
        if (dpBox != beforeDeploy) {
            fixHeader();
            beforeDeploy = dpBox;

        }
        if ((dpBox == 0 || (phasingUnitsLeft === 0 && data.gameRules.mode !==  DEPLOY_MODE)) && $("#deployBox").is(":visible")) {
            $("#deployWrapper").hide({effect: "blind", direction: "up", complete: fixHeader});
        }


        svgRefresh();
        if(DR.showHexes){
            $("#showHexes").addClass('negative');
        }else{
            $("#showHexes").removeClass('negative');
        }
        if(DR.showHexes){
            $('.range-hex').addClass('hovering');
        }else{
            $('.range-hex').removeClass('hovering');
        }
    });

</script>
