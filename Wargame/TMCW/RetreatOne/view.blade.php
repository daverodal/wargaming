@include('wargame::global-header')
@include('wargame::TMCW.RetreatOne.retreatOneHeader')
<link rel="stylesheet" type="text/css" href="{{elixir('vendor/wargame/tmcw/css/retreatone.css')}}">
<style type="text/css">
    <?php
    include_once "Wargame/TMCW/RetreatOne/all.css";
    ?>
</style>
</head>

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
    {{--    @include('wargame::TMCW.Amph.victoryConditions')--}}
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::TMCW.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::TMCW.obc')
@endsection
<script type="text/javascript">
    x.register("mapUnits", function (mapUnits, data) {
        var str;
        var fudge;
        var x, y;
        var beforeDeploy = $("#deployBox").children().size();
        DR.stackModel = {};
        DR.stackModel.ids = {};

        var phasingForceId = data.gameRules.attackingForceId;

        var phasingUnitsLeft = 0;

        for (i in mapUnits) {
            if (typeof mapUnits[i].parent == "undefined") {
                $('#' + i).hide();
                continue;
            } else {
                $('#' + i).css("display", "");
            }
            if (mapUnits[i].forceId === phasingForceId && mapUnits[i].parent === "deployBox") {
                phasingUnitsLeft++;
            }
            width = $("#" + i).width();
            height = $("#" + i).height();
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

                if(mapUnits[i].class === "hq") {
                    DR.showHexes = true;
                    var hexSideLen = 35.2;
                    var b = hexSideLen * .866;
                    var unit = mapUnits[i];
                    unit.id = i;
                    drawHex(hexSideLen, unit, 'short');
                    var range = 8;
                    drawHex(b * (range * 2 + 1), unit);
                    $("#" + i).hover(function () {
                        var id = $(this).attr('id');
                        var curClass = $('#arrow-svg #rangeHex' + id).attr('class');
                        if (curClass) {
                            curClass = curClass.replace(/hovered/, '');
                            $('#arrow-svg #rangeHex' + id).attr('class', curClass + ' hovered');
                        }
                        $('#arrow-svg #rangeHex' + id + 'short').attr('style', 'stroke:red;stroke-opacity:1;');
                    }, function () {
                        var id = $(this).attr('id');
                        var curClass = $('#arrow-svg #rangeHex' + id).attr('class');
                        if (curClass) {
                            curClass = curClass.replace(/hovered/, '');
                            $('#arrow-svg #rangeHex' + id).attr('class', curClass);
                        }
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
            html = renderUnitNumbers(mapUnits[i]);
            if (html) {
                $("#" + i + " .unit-numbers").html(html);
            }
            var len = $("#" + i + " .unit-numbers").text().length;
            $("#" + i + " div.unit-numbers span ").addClass("infoLen" + len);
            $("#" + i + " .counterWrapper .guard-unit ").addClass("infoLen" + len);
            $("#" + i).attr("src", img);
        }
        var dpBox = $("#deployBox").children().size();
        if (dpBox != beforeDeploy) {
            fixHeader();
            beforeDeploy = dpBox;

        }
        if ((dpBox == 0 || (phasingUnitsLeft === 0 && data.gameRules.mode !== <?= DEPLOY_MODE?>)) && $("#deployBox").is(":visible")) {
            $("#deployWrapper").hide({effect: "blind", direction: "up", complete: fixHeader});
        }

    });
</script>
@include('wargame::stdIncludes.view' )
