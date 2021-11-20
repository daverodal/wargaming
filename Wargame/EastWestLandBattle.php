<?php
namespace Wargame;
use stdClass;
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */

class EastWestLandBattle extends LandBattle
{
    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    public $specialHexA;
    public $specialHexB;
    public $specialHexC;


    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
    /* @var EastWestForce $force */
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $victory;
    public $arg;
    public $scenario;

    public $players;


    public function getMapData(): MapData{
        return $this->mapData;
    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false){
        $this->mapData = MapData::getInstance();
        parent::__construct($data, $arg, $scenario, $game);


        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->terrainName = $data->terrainName;
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]), new MapViewer($data->mapViewer[1]), new MapViewer($data->mapViewer[2]));
            $units = $data->force->units;
            unset($data->force->units);
            $this->force = new EastWestForce($data->force);
            foreach($units as $unit){
                $this->force->injectUnit(static::buildUnit($unit));
            }
            if(isset($data->terrain)){
                $this->terrain = new Terrain($data->terrain);

            }else{
                $this->terrain = new Terrain();
            }
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $data->gameRules);
            $this->victory = new Victory($data);

            $this->players = $data->players;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;

            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new EastWestForce();
            $this->terrain = new Terrain();
            $this->moveRules = new MoveRules($this->force, $this->terrain);

            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force);
        }
        static::getPlayerData($scenario);
    }


    /*
     * Called if a unit finds units in the scenario.
     */
    public function scenInit(){


        $scenario = $this->scenario;
        $unitSets = $scenario->units;

        foreach($unitSets as $unitSet) {
//            dd($unitSet);
            if($unitSet->forceId !== MartianCivilWar::LOYALIST_FORCE){
                continue;
            }
            for ($i = 0; $i < $unitSet->num; $i++) {
                UnitFactory::create("xx", $unitSet->forceId, "deployBox", $unitSet->image, $unitSet->combat, $unitSet->combat/2, $unitSet->movement, false, STATUS_CAN_REINFORCE,  $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, true, $unitSet->class);

            }
        }
        foreach($unitSets as $unitSet) {
//            dd($unitSet);
            if($unitSet->forceId !== MartianCivilWar::REBEL_FORCE){
                continue;
            }
            for ($i = 0; $i < $unitSet->num; $i++) {
                UnitFactory::create("xx", $unitSet->forceId, "deployBox", $unitSet->image, $unitSet->combat, $unitSet->combat/2, $unitSet->movement, false, STATUS_CAN_REINFORCE,  $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, true, $unitSet->class);
            }
        }
    }



    /*
     * terrainInit() gets called during game init, from unitInit(). It happens as a new game gets started.
     */
    function terrainInit($terrainDoc)
    {
        $terrainInfo = $terrainDoc->terrain;

        $specialHexes = $terrainInfo->specialHexes ? $terrainInfo->specialHexes : [];
        $mapHexes = new stdClass();
        foreach ($specialHexes as $hexName => $specialHex) {
            $mapHexes->$hexName = $this->specialHexesMap[$specialHex];
            $this->{lcfirst($specialHex)}[] = $hexName;
        }
        $this->mapData->setSpecialHexes($mapHexes);

        $this->players = array("", "", "");
        for ($player = 0; $player <= 2; $player++) {
            $this->mapViewer[$player]->setData($terrainInfo->originX, $terrainInfo->originY, // originX, originY
                $terrainInfo->b, $terrainInfo->b, // top hexagon height, bottom hexagon height
                $terrainInfo->a, $terrainInfo->c,// hexagon edge width, hexagon center width
            $terrainInfo->mapWidth, $terrainInfo->mapHeight ?? false);
        }

        $oldMapUrl = $this->mapData->mapUrl;
        if (!$oldMapUrl) {
            $maxCol = $terrainInfo->maxCol;
            $maxRow = $terrainInfo->maxRow;
            $mapUrl = $terrainInfo->mapUrl;
            $this->mapData->setData($maxCol, $maxRow, $mapUrl);

            Hexagon::setMinMax();
            $this->terrain->setMaxHex();
        }
        return;
    }

    /*
     * terrainGen() gets called when a map is "published" from the map editor. It's not
     * related to a game start or a game file. It just generates the terrain info that gets saved to the
     * file terrain-Gamename
     *
     * TerrainFeatures that aren't declared here MUST be declared in the subclass BEFORE it calls it's parent::
     * TerrainFeatures that DO exist here but need to be modified MUST be declared AFTER the subclass calls it's parent::
     */
    function terrainGen($mapDoc, $terrainDoc)
    {
        // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("clear", "clear", "c", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
        $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
        $this->terrain->addTerrainFeature("fortified", "fortified", "h", 1, 0, 1, true);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 0, false);
        $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true);
        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 3, 0, 1, true);
        $this->terrain->addTerrainFeature("mountain", "mountain", "g", 3, 0, 2, true);
        $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 1, true);
        $this->terrain->addTerrainFeature("roughone", "roughone", "g", 2, 0, 2, true);
        $this->terrain->addTerrainFeature("roughtwo", "roughtwo", "g", 4, 0, 2, true);


        /* handle fort's in crtTraits */
        $this->terrain->addTerrainFeature("forta", "forta", "f", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("fortb", "fortb", "f", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("mine", "mine", "m", 0, 0, 0, false);


        $terrainArr = json_decode($terrainDoc->hexStr->hexEncodedStr);
        $mapId = $terrainDoc->hexStr->map;
        $map = $mapDoc->map;
        $this->terrain->mapUrl = $mapUrl = $map->mapUrl;
        $this->terrain->maxCol = $maxCol = $map->numX;
        $this->terrain->maxRow = $maxRow = $map->numY;
        $this->terrain->mapWidth = $map->mapWidth;
        $this->terrain->mapHeight = $map->mapHeight;
        $this->mapData->setData($maxCol, $maxRow, $mapUrl);

        Hexagon::setMinMax();
        $this->terrain->setMaxHex();
        $a = $map->a;
        $b = $map->b;
        $c = $map->c;
        $this->terrain->a = $a;
        $this->terrain->b = $b;
        $this->terrain->c = $c;
        $this->terrain->originY = $b * 3 - $map->y;
        $xOff = ($a + $c) * 2 - ($c / 2 + $a);
        $this->terrain->originX = $xOff - $map->x;


        for ($col = 100; $col <= $maxCol * 100; $col += 100) {
            for ($row = 1; $row <= $maxRow; $row++) {
                $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

            }
        }
        foreach ($terrainArr as $terrain) {
            foreach ($terrain->type as $terrainType) {
                $name = $terrainType->name;
                $matches = [];
                if (preg_match("/SpecialHex/", $name)) {
                    $this->terrain->addSpecialHex($terrain->number, $name);
                } else if (preg_match("/^ReinforceZone(.*)$/", $name, $matches)) {
                    $this->terrain->addReinforceZone($terrain->number, $matches[1]);
                } else {
                    $tNum = sprintf("%04d", $terrain->number);
                    $this->terrain->addTerrain($tNum, $terrain->hexpartType, strtolower($name));
                }
            }
        }
    }
}