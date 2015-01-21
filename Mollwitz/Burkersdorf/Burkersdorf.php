<?php
define("AUSTRIAN_FORCE", 1);
define("PRUSSIAN_FORCE", 2);

global $force_name;
$force_name[PRUSSIAN_FORCE] = "Prussian";
$force_name[AUSTRIAN_FORCE] = "Austrian";
require_once "JagCore.php";

class Burkersdorf extends JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>1, 'SpecialHexC'=>2];

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $display;
    public $victory;
    public $cities;
    public $loc;


    public $players;

    static function playMulti($name, $wargame, $arg = false)
    {
        $deployOne = $playerOne = "Austrian";
        $deployTwo = $playerTwo = "Prussian";

        @include_once "playMulti.php";
    }

    static function getHeader($name, $playerData, $arg = false)
    {
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "BurkersdorfHeader.php";

    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployOne = $playerOne = "Austrian";
        $deployTwo = $playerTwo = "Prussian";
        @include_once "view.php";
    }


    public function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
        $this->cities = $this->specialHexA;
        $this->loc = $this->specialHexB;
    }


    function save()
    {
        $data = new stdClass();
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->display = $this->display;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;
        $data->cities = $this->cities;
        $data->loc = $this->loc;
        return $data;
    }


    public function init()
    {

        $artRange = 3;

        if($this->scenario->bigAustrian){
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'infantry');
            }
            for ($i = 0; $i < 25; $i++) {
                $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'infantry');
            }
        }else{
            for ($i = 0; $i < 31; $i++) {
                $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'infantry');
            }
        }

        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 2; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 1; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusCavBadge.png", 5, 5, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Austrian", false, 'cavalry');
        }
        for ($i = 0; $i < 8; $i++) {
            $this->force->addUnit("infantry-1", AUSTRIAN_FORCE, "deployBox", "AusArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Austrian", false, 'artillery');
        }


        for ($i = 0; $i < 14; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 10; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruInfBadge.png", 6, 6, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'infantry');
        }
        for ($i = 0; $i < 7; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 3; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruCavBadge.png", 5, 5, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Prussian", false, 'cavalry');
        }
        for ($i = 0; $i < 5; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 2, 2, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Prussian", false, 'artillery');
        }
         for ($i = 0; $i < 1; $i++) {
            $this->force->addUnit("infantry-1", PRUSSIAN_FORCE, "deployBox", "PruArtBadge.png", 4, 4, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Prussian", false, 'artillery');
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->cities = $data->cities;
            $this->loc = $data->loc;
        } else {
            $this->victory = new Victory("Mollwitz/Burkersdorf/burkersdorfVictoryCore.php");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;

            // game data
            $this->gameRules->setMaxTurn(12);
            $this->gameRules->setInitialPhaseMode(BLUE_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = BLUE_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = RED_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, RED_DEPLOY_PHASE, DEPLOY_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);


        }
    }
}