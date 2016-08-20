@include('wargame::global-header')
@include('wargame::TMCW.Airborne.airborneHeader')
<link rel="stylesheet" type="text/css" href="{{asset('vendor/wargame/tmcw/airborne/css/all.css')}}">
</head>

<script type="text/javascript">
    @section('combat-ruxles-controller')
            x.register("combatRules", function (combatRules, data) {
        for (var arrowUnits in $scope.mapUnits) {
            $scope.mapUnits[arrowUnits].arrows = {};
            $scope.mapUnits[arrowUnits].oddsDisp = null;
        }

        for (var i in $scope.topCrt.crts) {
            $scope.topCrt.crts[i].selected = null;
            $scope.topCrt.crts[i].pinned = null;
            $scope.topCrt.crts[i].combatRoll = null;
        }

        $scope.crtOdds = null;
        if (data.gameRules.phase == <?= BLUE_FIRE_COMBAT_PHASE?> || data.gameRules.phase == <?= RED_FIRE_COMBAT_PHASE?>) {
            $scope.curCrt = 'missile';
            crtName = 'missile';
        }

        $scope.$apply();

        var title = "Combat Results ";
        var cdLine = "";
        var activeCombat = false;
        var activeCombatLine = "<div></div>";
        var crtName = $scope.curCrt;
        var str = "";


        if (combatRules) {
            cD = combatRules.currentDefender;

            if (combatRules.combats && Object.keys(combatRules.combats).length > 0) {
                if (cD !== false) {
                    var defenders = combatRules.combats[cD].defenders;
                    if (combatRules.combats[cD].useAlt) {
//                                showCrtTable($('#cavalryTable'));
                    } else {
                        if (combatRules.combats[cD].useDetermined) {
//                                    showCrtTable($('#determinedTable'));
                        } else {
//                                    showCrtTable($('#normalTable'));
                        }
                    }


                    if (data.gameRules.phase == <?= BLUE_FIRE_COMBAT_PHASE?> || data.gameRules.phase == <?= RED_FIRE_COMBAT_PHASE?>) {
                        $scope.curCrt = 'missile';
                        crtName = 'missile';
                    }

                    for (var loop in defenders) {
                        $scope.mapUnits[loop].style.borderColor = 'yellow';
                    }

                    if (!chattyCrt) {
                        $("#crt").show({effect: "blind", direction: "up"});
                        $("#crtWrapper").css('overflow', 'visible');
                        chattyCrt = true;
                    }
                    if (Object.keys(combatRules.combats[cD].attackers).length != 0) {
                        if (combatRules.combats[cD].pinCRT !== false) {
                            combatCol = combatRules.combats[cD].pinCRT;
                            $scope.topCrt.crts[crtName].pinned = combatCol;
                        }
                        combatCol = combatRules.combats[cD].index;
                        $scope.topCrt.crts[crtName].selected = combatCol;
                    }
                }

                cdLine = "";
                var combatIndex = 0;

                for (i in combatRules.combats) {
                    if (combatRules.combats[i].index !== null) {


                        attackers = combatRules.combats[i].attackers;
                        defenders = combatRules.combats[i].defenders;
                        thetas = combatRules.combats[i].thetas;

                        var theta = 0;
                        for (var j in attackers) {

                            var numDef = Object.keys(defenders).length;
                            for (k in defenders) {


                                theta = thetas[j][k];
                                theta *= 15;
                                theta += 180;
                                if ($scope.mapUnits[j].facing !== undefined) {
                                    theta -= $scope.mapUnits[j].facing * 60;
                                }

                                $scope.mapUnits[j].arrows[k] = {};
                                $scope.mapUnits[j].arrows[k].style = {transform: ' scale(.55,.55) rotate(' + theta + "deg) translateY(45px)"};
                            }
                        }

                        var useAltColor = combatRules.combats[i].useAlt ? " altColor" : "";

                        if (combatRules.combats[i].useDetermined) {
                            useAltColor = " determinedColor";
                        }
                        var currentCombatCol = combatRules.combats[i].index;
                        if (combatRules.combats[i].pinCRT !== false) {
                            currentCombatCol = combatRules.combats[i].pinCRT;
                            useAltColor = " pinnedColor";
                        }
                        var currentOddsDisp = $scope.topCrt.crts[crtName].header[currentCombatCol];
                        $scope.mapUnits[i].oddsDisp = currentOddsDisp;
                        $scope.mapUnits[i].oddsColor = useAltColor;
                        $scope.$apply();


                        if (cD !== false && cD == i) {
                            var details = renderCrtDetails(combatRules.combats[i]);
                            $scope.crtOdds = "odds = " + currentOddsDisp;
                            activeCombat = combatIndex;
                            activeCombatLine = details;
                        }
                        combatIndex++;
                    }

                }
                str += "There are " + combatIndex + " Combats";
                if (cD !== false) {
                    attackers = combatRules.combats[cD].attackers;
                }
                str += "";
                $scope.topCrt.crts[crtName].crtOddsExp = $sce.trustAsHtml(activeCombatLine);
                $("#status").html(cdLine + str);
                if (DR.crtDetails) {
                    $("#crtDetails").toggle();
                }
                $("#status").show();
                $scope.$apply();

            } else {
                chattyCrt = false;
            }


            var lastCombat = "";
            if (combatRules.combatsToResolve) {
                if (combatRules.lastResolvedCombat) {
                    var finalRoll = combatRules.lastResolvedCombat.Die;

                    title += "<strong style='font-size:150%'>" + finalRoll + " " + combatRules.lastResolvedCombat.combatResult + "</strong>";
                    combatCol = combatRules.lastResolvedCombat.index + 1;

                    combatRoll = combatRules.lastResolvedCombat.Die;
                    $scope.dieOffset = combatRules.lastResolvedCombat.dieOffset;
                    $scope.$apply();

//                                $(".col" + combatCol).css('background-color', "rgba(255,255,1,.6)");
                    $scope.topCrt.crts[crtName].selected = combatCol - 1;

                    $scope.$apply();

                    var pin = combatRules.lastResolvedCombat.pinCRT;
                    if (pin !== false) {
                        pin++;
                        if (pin < combatCol) {
                            combatCol = pin;
                            $(".col" + combatCol).css('background-color', "rgba(255, 0, 255, .6)");
                            $scope.topCrts.crts[crtName].pinned = combatCol;
                        }
                    }

                    $scope.topCrt.crts[crtName].combatRoll = combatRoll - 1;

                    if (combatRules.lastResolvedCombat.useAlt) {
//                                showCrtTable($('#cavalryTable'));
                    } else {
                        if (combatRules.lastResolvedCombat.useDetermined) {
//                                    showCrtTable($('#determinedTable'));
                        } else {
//                                    showCrtTable($('#normalTable'));
                        }
                    }


                    var currentCombatCol = combatRules.lastResolvedCombat.index;
                    if (combatRules.lastResolvedCombat.pinCRT !== false) {
                        currentCombatCol = combatRules.lastResolvedCombat.pinCRT;
                        useAltColor = " pinnedColor";
                    }
                    var oddsDisp = $scope.topCrt.crts[crtName].header[currentCombatCol];

                    var details = renderCrtDetails(combatRules.lastResolvedCombat);

                    $scope.crtOdds = "odds = " + oddsDisp;
                    newLine = details;


                    $scope.topCrt.crts[crtName].crtOddsExp = $sce.trustAsHtml(newLine);

                }
                str += "";
                var noCombats = false;
                if (Object.keys(combatRules.combatsToResolve) == 0) {
                    noCombats = true;
                    str += "0 combats to resolve";
                }
                var combatsToResolve = 0;
                for (i in combatRules.combatsToResolve) {
                    combatsToResolve++;
                    if (combatRules.combatsToResolve[i].index !== null) {
                        attackers = combatRules.combatsToResolve[i].attackers;
                        defenders = combatRules.combatsToResolve[i].defenders;
                        thetas = combatRules.combatsToResolve[i].thetas;

                        var theta = 0;
                        for (var j in attackers) {
                            var numDef = Object.keys(defenders).length;
                            for (k in defenders) {
//                                        $("#"+j+ " .arrow").clone().addClass('arrowClone').addClass('arrow'+k).insertAfter("#"+j+ " .arrow").removeClass('arrow');
//                                        theta = thetas[j][k];
//                                        theta *= 15;
//                                        theta += 180;
//                                        $("#"+j+ " .arrow"+k).css({opacity: "1.0"});
//                                        $("#"+j+ " .arrow"+k).css({webkitTransform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
//                                        $("#"+j+ " .arrow"+k).css({transform: ' scale(.55,.55) rotate('+theta+"deg) translateY(45px)"});
                            }
                        }

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
                        var useAltColor = combatRules.combatsToResolve[i].useAlt ? " altColor" : "";
                        if (combatRules.combatsToResolve[i].useDetermined) {
                            useAltColor = " determinedColor";
                        }
                        if (combatRules.combatsToResolve[i].pinCRT !== false) {
                            useAltColor = " pinnedColor";
                        }

                        var currentCombatCol = combatRules.combatsToResolve[i].index;
                        if (combatRules.combatsToResolve[i].pinCRT !== false) {
                            currentCombatCol = combatRules.combatsToResolve[i].pinCRT;
                            useAltColor = " pinnedColor";
                        }
                        var oddsDisp = $scope.topCrt.crts[crtName].header[currentCombatCol];

                        $scope.mapUnits[i].oddsDisp = oddsDisp;
                        $scope.mapUnits[i].oddsColor = useAltColor;
                        $scope.$apply();
//                                $("#"+i).attr('title',oddsDisp).prepend('<div class="unitOdds'+useAltColor+'">'+oddsDisp+'</div>');;
                        var details = renderCrtDetails(combatRules.combatsToResolve[i]);

                        $scope.crtOdds = "odds = " + oddsDisp;
                        newLine = details;
                    }

                }
                if (combatsToResolve) {
//                str += "Combats To Resolve: " + combatsToResolve;
                }
                var resolvedCombats = 0;
                for (i in combatRules.resolvedCombats) {
                    resolvedCombats++;
                    if (combatRules.resolvedCombats[i].index !== null) {
                        atk = combatRules.resolvedCombats[i].attackStrength;
                        atkDisp = atk;
                        ;
                        if (combatRules.storm) {
                            atkDisp = atk * 2 + " halved for storm " + atk;
                        }
                        def = combatRules.resolvedCombats[i].defenseStrength;
                        ter = combatRules.resolvedCombats[i].terrainCombatEffect;
                        idx = combatRules.resolvedCombats[i].index + 1;
                        newLine = "";
                        if (combatRules.resolvedCombats[i].Die) {
//                                    var x = $("#" + cD).css('left').replace(/px/, "");
//                                    var mapWidth = $("body").css('width').replace(/px/, "");
                        }
                        var oddsDisp = $(".col" + combatCol).html()
                        newLine += " Attack = " + atkDisp + " / Defender " + def + atk / def + "<br>odds = " + Math.floor(atk / def) + " : 1<br>Coooooombined Arms Shift " + ter + " = " + oddsDisp + "<br>";
                        newLine += "Roll: " + combatRules.resolvedCombats[i].Die + " result: " + combatRules.resolvedCombats[i].combatResult + "<br><br>";
                        if (cD === i) {
                            newLine = "";
                        }
                    }

                }
                if (!noCombats) {
                    str += "Combats: " + resolvedCombats + " of " + (resolvedCombats + combatsToResolve);
                }
                $("#status").html(lastCombat + str);
                $("#status").show();

            }
        }
        $("#crt h3").html(title);

        $scope.$apply();

    });
    @endsection
</script>

@section('inner-crt')
    @include('wargame::TMCW.Airborne.airborne-inner-crt', ['topCrt'=> $top_crt = new \Wargame\TMCW\KievCorps\CombatResultsTable(REBEL_FORCE)])
@endsection

@section('unitRules')
    @parent
    <li class="exclusive">No units may be receive replacements in this game.
    </li>
@endsection

@section('victoryConditions')
    @include('wargame::TMCW.Amph.victoryConditions')
@endsection

@section('commonRules')
    @include('wargame::TMCW.commonRules')
@endsection

@section('exclusiveRules')
    @include('wargame::TMCW.exclusiveRules')
@endsection

@section('obc')
    @include('wargame::TMCW.Airborne.obc')
@endsection

@section('unitzs')
    {{--
      -- First stabe at svg to replace images,
      -- considered a fail, but important gains
      --}}
    @foreach ($units as $unit)
        <div class="unit {{$unit['nationality']}}" id="{{$unit['id']}}" alt="0">
            <div class="shadow-mask"></div>
            <div class="unitSize">{{$unit['unitSize']}}</div>
            <img class="arrow" src="{{asset('js/short-red-arrow-md.png')}}" class="counter">
            <div class="counterWrapper">
                @if($unit['image'] == 'multiInf.png')
                    <svg class="counter-symbol" width="18" height="12" viewBox="0 0 20 10">
                        <line x1="1" x2="1" y1="0" y2="12" stroke-width="1.2"></line>
                        <line x1="1" x2="19.5" y1="11" y2="11" stroke-width="1.2"></line>
                        <line x1="19" x2="19" y1="0" y2="11" stroke-width="1.2"></line>
                        <line x1="0.5" x2="19.5" y1="0" y2="0" stroke-width="1.2"></line>
                        <line x1="1" x2="19" y1="0" y2="12" stroke-width="1.2"></line>
                        <line x1="1" x2="19" y1="12" y2="0" stroke-width="1.2"></line>
                    </svg>
                @endif

                @if($unit['image'] == 'multiGlider.png')
                    <svg class="counter-symbol" width="18" height="9" viewBox="0 0 20 10">
                        <line x1="1" x2="1" y1="0" y2="10" stroke-width="1.5"></line>
                        <line x1="0" x2="20" y1="9" y2="9" stroke-width="1.5"></line>
                        <line x1="19" x2="19" y1="0" y2="10" stroke-width="1.5"></line>
                        <line x1="0" x2="20" y1="1" y2="1" stroke-width="1.5"></line>
                        <line x1="3" x2="17" y1="4.5" y2="4.5" stroke-width="1.5"></line>
                        \
                        <line x1="1" x2="19" y1="1" y2="9" stroke-width="1.5"></line>
                        <line x1="1" x2="19" y1="9" y2="1" stroke-width="1.5"></line>
                    </svg>
                @endif

                <span class="unit-desig"><?=$unit['unitDesig']?></span>
            </div>
            <div class="unit-numbers">5 - 4</div>
        </div>
    @endforeach
@endsection

@section('nosec')
    <div class="unit {{$unit['nationality']}}" id="{{$unit['id']}}" alt="0">
        <div class="shadow-mask"></div>
        <div class="unitSize">{{$unit['unitSize']}}</div>
        <img class="arrow" src="{{asset('js/short-red-arrow-md.png')}}" class="counter">
        <div class="counterWrapper">
            <img src="{{asset("js/".$unit['image'])}}" class="counter"><span
                    class="unit-desig"><?=$unit['unitDesig']?></span>
        </div>
        <div class="unit-numbers">5 - 4</div>
    </div>
@endsection



@section('outer-deploy-box')
    <div class="clear"></div>
    <div id="deployBox">

        <div class="deploy-zone-wrapper">
            Rebel Zone B
            <div class="a-unit-wrapper" ng-repeat="unit in deployUnits | filter:{reinforceZone: 'B'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>

        <div class="deploy-zone-wrapper">
            Airdrop Rebel Zone A
            <div class="a-unit-wrapper" ng-repeat="unit in deployUnits | filter:{reinforceZone: 'A'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div class="deploy-zone-wrapper">
            Loyalist Zone C
            <div class="a-unit-wrapper" ng-repeat="unit in deployUnits | filter:{reinforceZone: 'C'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div class="deploy-zone-wrapper">
            Loyalist Zone D
            <div class="a-unit-wrapper" ng-repeat="unit in deployUnits | filter:{reinforceZone: 'D'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
        <div class="deploy-zone-wrapper">
            Loyalist Zone E
            <div class="a-unit-wrapper" ng-repeat="unit in deployUnits | filter:{reinforceZone: 'E'}"
                 ng-style="unit.wrapperstyle">
                <offmap-unit unit="unit"></offmap-unit>
            </div>
            <div class="clear"></div>
        </div>
    </div>
@endsection



@section('units')
    <div class="a-unit-wrapper" ng-repeat="unit in mapUnits" ng-style="unit.wrapperstyle">
        <unit unit="unit"></unit>
    </div>

    <div ng-mouseover="hoverThis(unit)" ng-mouseleave="unHoverThis(unit)" ng-click="clickMe(unit.id, $event)"
         ng-style="unit.style" ng-repeat="unit in moveUnits track by $index" class="unit"
         ng-class="[unit.nationality, unit.class]">
        <ghost-unit unit="unit"></ghost-unit>
    </div>
@endsection

@include('wargame::Medieval.angular-view',['topCrt'=> new \Wargame\TMCW\KievCorps\CombatResultsTable(REBEL_FORCE)] )

<script type="text/ng-template" id="offmap-unit.html">

    <div id="@{{unit.id}}" ng-mouseUp="clickMe(unit.id, $event)" ng-style="unit.style" class="unit rel-unit"
         ng-class="[unit.nationality, unit.class]">
        <div ng-show="unit.oddsDisp" class="unitOdds" ng-class="unit.oddsColor">@{{ unit.oddsDisp }}</div>
        <div class="shadow-mask" ng-class="unit.shadow"></div>
        <div class="unitSize">{{$unit['unitSize']}}</div>
        <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
             src="{{asset('js/short-red-arrow-md.png')}}" class="counter">
        <div class="counterWrapper">
            <img src="{{asset("js/")}}/@{{ unit.image }}" class="counter"><span
                    class="unit-desig">@{{ unit.unitDesig }}</span>
        </div>
        <div class="unit-numbers">@{{ unit.strength }} - @{{ unit.maxMove - unit.moveAmountUsed }}</div>
        <div class="unit-steps">@{{ "...".slice(0, unit.steps) }}</div>
    </div>
</script>


<script type="text/ng-template" id="unit.html">
    <div id="@{{unit.id}}" ng-mouseDown="mouseDown(unit.id, $event)" ng-mouseUp="clickMe(unit.id, $event)"
         ng-right-click="rightClickMe(unit.id, $event)" ng-style="unit.style" class="unit rel-unit"
         ng-class="[unit.nationality, unit.class]">
        <div ng-show="unit.oddsDisp" class="unitOdds" ng-class="unit.oddsColor">@{{ unit.oddsDisp }}</div>
        <div class="shadow-mask" ng-class="unit.shadow"></div>
        <div class="unitSize">{{$unit['unitSize']}}</div>
        <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
             src="{{asset('js/short-red-arrow-md.png')}}" class="counter">
        <div class="counterWrapper">
            <img src="{{asset("js/")}}/@{{ unit.image }}" class="counter"><span
                    class="unit-desig">@{{ unit.unitDesig }}</span>
        </div>
        <div ng-class="unit.infoLen" class="unit-numbers">@{{ unit.strength }}
            - @{{ unit.maxMove - unit.moveAmountUsed }}</div>
        <div class="unit-steps">@{{ "...".slice(0, unit.steps) }}</div>
    </div>
</script>

<script type="text/ng-template" id="ghost-unit.html">
    <div class="unitSize">{{$unit['unitSize']}}</div>
    <img ng-repeat="arrow in unit.arrows" ng-style="arrow.style" class="arrow"
         src="{{asset('js/short-red-arrow-md.png')}}" class="counter">
    <div class="counterWrapper">
        <img src="{{asset("js/")}}/@{{ unit.image }}" class="counter"><span
                class="unit-desig">@{{ unit.unitDesig }}</span>
    </div>
    <div class="range">@{{ unit.armorClass }}</div>
    <div class="unit-numbers">@{{ unit.strength }} - @{{ unit.pointsLeft }}</div>
    <div class="unit-steps">@{{ "...".slice(0, unit.steps) }}</div>
</script>