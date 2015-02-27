<?php
require_once "constants.php";
global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;

require_once "Battle.php";
require_once "combatRules.php";
require_once "crt.php";
require_once "force.php";
require_once "gameRules.php";
require_once "hexagon.php";
require_once "hexpart.php";
require_once "los.php";
require_once "mapgrid.php";
require_once "moveRules.php";
require_once "display.php";
require_once "terrain.php";
require_once "victory.php";



class TroopersCore extends LandBattle{

    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>2];
    /* @var MapData $mapData */
    public $mapData;
    public $specialHexA;
    public $specialHexB;
    public $specialHexC;





    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->terrainName = $data->terrainName;
            $this->roadHex = $data->roadHex;
            $this->game = $data->game;

            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]), new MapViewer($data->mapViewer[1]), new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->victory = new Victory($data);

            $this->players = $data->players;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;
            $this->game = $game;

            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();

            $this->moveRules = new MoveRules($this->force, $this->terrain);
            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
        }
    }



    /*
     * terrainInit() gets called during game init, from unitInit(). It happens as a new game gets started.
     */
    function terrainInit($terrainDoc){


        $terrainInfo = $terrainDoc->terrain;

        $specialHexes = $terrainInfo->specialHexes ?  $terrainInfo->specialHexes : [];
        $mapHexes = new stdClass();
        foreach($specialHexes as $hexName => $specialHex){
            $mapHexes->$hexName = $this->specialHexesMap[$specialHex];
            $this->{lcfirst($specialHex)}[] = $hexName;
        }
        $this->mapData->setSpecialHexes($mapHexes);

        $this->players = array("", "", "");
        for ($player = 0; $player <= 2; $player++) {
            $this->mapViewer[$player]->setData($terrainInfo->originX , $terrainInfo->originY, // originX, originY
                $terrainInfo->b, $terrainInfo->b, // top hexagon height, bottom hexagon height
                $terrainInfo->a, $terrainInfo->c// hexagon edge width, hexagon center width
            );
        }

        $oldMapUrl = $this->mapData->mapUrl;
        if(!$oldMapUrl){
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
     */
    function terrainGen($mapDoc, $terrainDoc){

        $terrainArr = json_decode($terrainDoc->hexStr->hexEncodedStr);

        $map = $mapDoc->map;
        $this->terrain->mapUrl = $mapUrl = $map->mapUrl;
        $this->terrain->maxCol = $maxCol = $map->numX;
        $this->terrain->maxRow = $maxRow = $map->numY;
        $this->terrain->mapWidth = $map->mapWidth;
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
        $xOff = ($a + $c) * 2 - ($c/2 + $a);
        $this->terrain->originX = $xOff - $map->x;

        // code, name, displayName, letter, entranceCost, traverseCost, combatEffect, is Exclusive
        $this->terrain->addTerrainFeature("offmap", "offmap", "o", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("clear", "", "c", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("road", "road", "r", .5, 0, 0, false);
        $this->terrain->addTerrainFeature("town", "town", "t", 1, 0, 0, true, true);
        $this->terrain->addTerrainFeature("forest", "forest", "f", 2, 0, 1, true, true);
        $this->terrain->addTerrainFeature("hill", "hill", "h", 2, 0, 0, true, true);
        $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 0, false);
        $this->terrain->addAltEntranceCost('forest', 'cavalry', 4);
        $this->terrain->addTerrainFeature("trail", "trail", "r", 1, 0, 0, false);
        $this->terrain->addTerrainFeature("swamp", "swamp", "s", 9, 0, 1, true, false);
        $this->terrain->addTerrainFeature("blocked", "blocked", "b", 1, 0, 0, true);
        $this->terrain->addTerrainFeature("redoubt", "redoubt", "d", 0, 0, 0, false);
        $this->terrain->addTerrainFeature("redoubtfront", "redoubtfront", "d", 0, 2, 0, false);
        $this->terrain->addTerrainFeature("blocksnonroad", "blocksnonroad", "b", 1, 0, 0, false);
        $this->terrain->addAltEntranceCost('swamp','artillery','blocked');
        $this->terrain->addTerrainFeature("sunkenroad", "sunkenroad", "k", 0, 0, 1, false, false);
        $this->terrain->addTerrainFeature("slope","slope", "s", 0, 1, 0, false);
        $this->terrain->addTerrainFeature("elevation","elevation", "e", 0, 0, 0, false);
        $this->terrain->addTerrainFeature("elevation2","elevation2", "e", 0, 0, 0, false);



        for ($col = 100; $col <= $maxCol * 100; $col += 100) {
            for ($row = 1; $row <= $maxRow; $row++) {
                $this->terrain->addTerrain($row + $col, LOWER_LEFT_HEXSIDE, "clear");
                $this->terrain->addTerrain($row + $col, UPPER_LEFT_HEXSIDE, "clear");
                $this->terrain->addTerrain($row + $col, BOTTOM_HEXSIDE, "clear");
                $this->terrain->addTerrain($row + $col, HEXAGON_CENTER, "clear");

            }
        }
        foreach($terrainArr as $terrain){
            foreach($terrain->type as $terrainType){
                $name = $terrainType->name;
                $matches = [];
                if(preg_match("/SpecialHex/",$name)){
                    $this->terrain->addSpecialHex($terrain->number, $name);
                }else if(preg_match("/^ReinforceZone(.*)$/", $name,$matches)){
                    $this->terrain->addReinforceZone($terrain->number, $matches[1]);
                }else{
                    $tNum = sprintf("%04d",$terrain->number);
                    $this->terrain->addTerrain($tNum, $terrain->hexpartType, strtolower($name));
                }
            }
        }
    }
}