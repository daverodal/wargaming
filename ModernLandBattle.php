<?php
require_once "Battle.php";
require_once "crtTraits.php";
require_once "combatRules.php";
require_once "crt.php";
require_once "force.php";
require_once "gameRules.php";
require_once "hexagon.php";
require_once "hexpart.php";
require_once "los.php";
require_once "mapgrid.php";
require_once "moveRules.php";
require_once "terrain.php";
require_once "display.php";
require_once "victory.php";
require_once "victoryCore.php";

class ModernLandBattle extends LandBattle
{
    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    public $specialHexA;
    public $specialHexB;
    public $specialHexC;


    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $display;
    public $victory;
    public $arg;
    public $scenario;

    public $players;


    function __construct($data = null, $arg = false, $scenario = false, $game = false){
        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->scenario = $data->scenario;
            $this->terrainName = $data->terrainName;
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
                $terrainInfo->a, $terrainInfo->c// hexagon edge width, hexagon center width
            );
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
        $this->terrain->addTerrainFeature("mountain", "mountain", "g", 3, 0, 2, true);
        $this->terrain->addTerrainFeature("river", "river", "v", 0, 1, 1, true);

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