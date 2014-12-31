<?php

define("GERMAN_FORCE", 1);
define("SOVIET_FORCE", 2);

global $force_name, $phase_name, $mode_name, $event_name, $status_name, $results_name, $combatRatio_name;
$force_name = array();
$force_name[0] = "Neutral Observer";
$force_name[1] = "German";
$force_name[2] = "Soviet";

require_once "constants.php";

require_once "ModernLandBattle.php";


class Moskow extends ModernLandBattle
{
    /* a comment */

    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>2];

    /* @var MapData $mapData */
    public $mapData;
    public $mapViewer;
    public $force;
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $prompt;
    public $display;
    public $victory;
    public $arg;
    public $scenario;

    public $players;

    static function getHeader($name, $playerData, $arg = false)
    {
        global $force_name;

        @include_once "globalHeader.php";
        @include_once "moskowHeader.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false)
    {
        global $force_name;
        $player = $force_name[$player];

        @include_once "view.php";
    }

    function terrainGen($mapDoc, $terrainDoc)
    {
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("town", "town", "t", 0, 0, 1, false);
        $this->terrain->addTerrainFeature("road", "road", "r", 1, 0, 0, false);
        $this->terrain->addNatAltEntranceCost('road','soviet','inf',.3);
        $this->terrain->addNatAltEntranceCost('road','soviet','mudinf',1./12.);
        $this->terrain->addNatAltEntranceCost('road','sovietguard','inf',.3);
        $this->terrain->addNatAltEntranceCost('road','sovietguard','mudinf',1./12.);

    }
    function save()
    {

        $data = parent::save();
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;
        return $data;
    }

    public function init()
    {

        $scenario = $this->scenario;

        for($i = 0; $i < 20;$i++){
            $this->force->addUnit("xxx", SOVIET_FORCE, "deployBox", "multiInf.png", 8, 4, 4, true, STATUS_CAN_DEPLOY, "B", 1, 1, "soviet", true, 'inf');
        }

        for($i = 0; $i < 4;$i++){
            $this->force->addUnit("xxx", SOVIET_FORCE, "deadpile", "multiInf.png", 8, 4, 4, true, STATUS_ELIMINATED, "B", 1, 1, "soviet", true, 'inf');
        }

        $this->force->addUnit("guard", SOVIET_FORCE, "gameTurn6", "multiInf.png", 10, 5, 4, true, STATUS_CAN_REINFORCE, "B",6, 1, "sovietguard", true, 'inf');


        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 12, 6, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 12, 6, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 12, 6, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 12, 6, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");

        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 11, 5, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 10, 5, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");

        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 9, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");

        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiArmor.png", 8, 4, 6, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "mech");

        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 8, 4, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 8, 4, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 8, 4, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");

        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 7, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 7, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 7, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 7, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 7, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");

        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 6, 3, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");

        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 5, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");

        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
        $this->force->addUnit("xx", GERMAN_FORCE, "deployBox", "multiInf.png", 4, 2, 4, false, STATUS_CAN_DEPLOY, "A", 1, 1, "german", true, "inf");
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {


        $this->mapData = MapData::getInstance();
        if ($data) {
            $this->arg = $data->arg;
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
            $this->scenario = $data->scenario;
            $this->terrainName = $data->terrainName;
            $this->victory = new Victory("TMCW/Moskow/moskowVictoryCore.php", $data);
            $this->display = new Display($data->display);
            $this->mapData->init($data->mapData);
            $this->mapViewer = array(new MapViewer($data->mapViewer[0]), new MapViewer($data->mapViewer[1]), new MapViewer($data->mapViewer[2]));
            $this->force = new Force($data->force);
            $this->terrain = new Terrain($data->terrain);
            $this->moveRules = new MoveRules($this->force, $this->terrain, $data->moveRules);
            $this->combatRules = new CombatRules($this->force, $this->terrain, $data->combatRules);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display, $data->gameRules);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);
            $this->players = $data->players;
        } else {
            $this->arg = $arg;
            $this->scenario = $scenario;

            $this->victory = new Victory("TMCW/Moskow/moskowVictoryCore.php");
            if ($scenario->supplyLen) {
                $this->victory->setSupplyLen($scenario->supplyLen);
            }
            $this->display = new Display();
            $this->mapViewer = array(new MapViewer(), new MapViewer(), new MapViewer());
            $this->force = new Force();
            $this->terrain = new Terrain();
            $this->moveRules = new MoveRules($this->force, $this->terrain);

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = 0;
            $this->moveRules->noZocZocOneHex = false;

            $this->combatRules = new CombatRules($this->force, $this->terrain);
            $this->gameRules = new GameRules($this->moveRules, $this->combatRules, $this->force, $this->display);
            $this->prompt = new Prompt($this->gameRules, $this->moveRules, $this->combatRules, $this->force, $this->terrain);

            // game data
            $this->gameRules->setMaxTurn(11);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);

            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, GERMAN_FORCE, SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, GERMAN_FORCE, SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_REPLACEMENT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, GERMAN_FORCE, SOVIET_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, GERMAN_FORCE, SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, BLUE_MECH_PHASE, MOVING_MODE, GERMAN_FORCE, SOVIET_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_MECH_PHASE, RED_REPLACEMENT_PHASE, REPLACING_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_REPLACEMENT_PHASE, RED_MOVE_PHASE, MOVING_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, RED_MECH_PHASE, MOVING_MODE, SOVIET_FORCE, GERMAN_FORCE, false);
            $this->gameRules->addPhaseChange(RED_MECH_PHASE, BLUE_REPLACEMENT_PHASE, REPLACING_MODE, GERMAN_FORCE, SOVIET_FORCE, true);
        }
    }
}