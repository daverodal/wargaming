<?php
/**
 *
 * Copyright 2012-2015 David Rodal
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
?><style type="text/css">
<?php include "all.css";?>
</style>
<script type="text/javascript">
    x.register("mapUnits", function(mapUnits) {
        var str;
        var fudge;
        var x,y;
        var beforeDeploy = $("#deployBox").children().size();
    DR.stackModel = {};
    DR.stackModel.ids = {};
    clearHexes();

    for (i in mapUnits) {
        width = $("#"+i).width();
        height = $("#"+i).height();
        x =  mapUnits[i].x;
        y = mapUnits[i].y;
    if(DR.stackModel[x] === undefined){
    DR.stackModel[x] = {};
    }
    if(DR.stackModel[x][y] === undefined){
    DR.stackModel[x][y] = {count:0,ids: {}};
    }
    fudge = 0;
    if(DR.stackModel[x][y].count){
        fudge = DR.stackModel[x][y].count * 4;
    }
    DR.stackModel[x][y].count++;
    var zIndex = DR.stackModel[x][y].count;
    /* really looking at the keys so the value can be the same */
    DR.stackModel[x][y].ids[i] = i;
    DR.stackModel.ids[i] = {x: x, y: y};

    if(mapUnits[i].parent != $("#"+i).parent().attr("id")){
        $("#"+i).appendTo($("#"+mapUnits[i].parent));
    if(mapUnits[i].parent != "gameImages"){
    $("#"+ i).css({top:"0"});
    $("#"+ i).css({left:"0"});
    if(!mapUnits[i].parent.match(/^gameTurn/)){
    $("#"+ i).css({float:"left"});
    }
    $("#"+ i).css({position:"relative"});
    }  else{
    $("#"+ i).css({float:"none"});
    $("#"+ i).css({position:"absolute"});

    }
    }
    width += 6;
    height += 6;
    if(mapUnits[i].parent == "gameImages"){

    $("#"+i).css({left:mapUnits[i].x-width/2-fudge+"px",top:mapUnits[i].y-height/2-fudge+"px", zIndex: zIndex});
    var hexSideLen = 32.0;
    var b = hexSideLen * .866;
    var unit = mapUnits[i];
    unit.id = i;
    drawHex(hexSideLen, unit, 'short');
    var range = mapUnits[i].range;
    drawHex(b * (range * 2 + 1), unit);
    $("#"+i).hover(function(){
        var id = $(this).attr('id');
        var curClass = $('#arrow-svg #rangeHex'+id).attr('class');
    if(curClass){
        curClass = curClass.replace(/hovered/,'');
        $('#arrow-svg #rangeHex'+id).attr('class', curClass+' hovered');
    }
    $('#arrow-svg #rangeHex'+id+'short').attr('style','stroke:red;stroke-opacity:1;');
    }, function(){
        var id = $(this).attr('id');
        var curClass = $('#arrow-svg #rangeHex'+id).attr('class');
    if(curClass){
        curClass = curClass.replace(/hovered/,'');
        $('#arrow-svg #rangeHex'+id).attr('class', curClass);
    }
    $('#arrow-svg #rangeHex'+id+'short').attr('style','');
    });
    }
    var img = $("#"+i+" img").attr("src");

    if(mapUnits[i].isReduced){
        img = img.replace(/(.*[0-9])(\.png)/,"$1reduced.png");
    }else{
         img = img.replace(/([0-9])reduced\.png/,"$1.png");
     }
    var  move = mapUnits[i].maxMove - mapUnits[i].moveAmountUsed;
    move = move.toFixed(2);
    move = move.replace(/\.00$/,'');
    move = move.replace(/(\.[1-9])0$/,'$1');
    var str = mapUnits[i].strength;
        var dStr = mapUnits[i].defenseStrength;
    var reduced = mapUnits[i].isReduced;
    var reduceDisp = "<span>";
    if(reduced){
        reduceDisp = "<span class='reduced'>";
    }
    var symb = mapUnits[i].supplied !== false ? " - " : " <span class='reduced'>u</span> ";
    var html = reduceDisp + str + symb + move + "</span>";
    $("#"+i+" .unitNumbers.attack").html(str);
        if(mapUnits[i].target === <?=\Wargame\Troops\ModernTactics\ModernTacticalUnit::HARD_TARGET?>){
            dStr = '[' + dStr + ']';
        }
    $("#"+i+" .unitNumbers.defense").html(dStr);

        var len  = $("#"+i+" .unitNumbers.attack").text().length;
    $("#"+i+" unitNumbers.attack").addClass("infoLen"+len);
    $("#"+i+" .unitNumbers.movement").html(move);
    var len  = $("#"+i+" .unitNumbers.movement").text().length;
    $("#"+i+" unitNumbers.movement").addClass("infoLen"+len);
    if(mapUnits[i].isImproved){
        $("#"+i).css('border-style','dotted');
    }
    }
    var dpBox = $("#deployBox").children().size();
    if(dpBox != beforeDeploy){
    fixHeader();
        beforeDeploy = dpBox;

    }
    if(dpBox == 0 && $("#deployBox").is(":visible")){
    $("#deployWrapper").hide({effect:"blind",direction:"up",complete:fixHeader});
    }

    });


    function renderCrtDetails(combat){
        var atk = combat.attackStrength;
        var def = combat.defenseStrength;
        var div = combat.index+'';
        var combatCol = combat.index + 1;

        var html = "<div id='crtDetails'>"+combat.combatLog+"</div><div>"+ div +  "</div>";
        /*+ atk + " - Defender " + def + " = " + diff + "</div>";*/
        return html;
    }


</script>