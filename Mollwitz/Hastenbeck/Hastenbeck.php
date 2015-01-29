<?php

define("FRENCH_FORCE", 1);
 define("ALLIED_FORCE", 2);

global $force_name;
$force_name[FRENCH_FORCE] = "French";
$force_name[ALLIED_FORCE] = "Allied";

require_once "JagCore.php";

class Hastenbeck extends JagCore
{
    public $specialHexesMap = ['SpecialHexA'=>1, 'SpecialHexB'=>2, 'SpecialHexC'=>0];

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

    static function getHeader($name, $playerData, $arg = false)
    {
        @include_once "globalHeader.php";
        @include_once "header.php";
        @include_once "HastenbeckHeader.php";

    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    static function getView($name, $mapUrl, $player = 0, $arg = false, $scenario = false, $game = false)
    {
        global $force_name;
        $youAre = $force_name[$player];
        $deployTwo = $playerOne = "French";
        $deployOne = $playerTwo = "Allied";
        @include_once "view.php";
    }

    function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
    }

    function terrainGen($mapDoc, $terrainDoc){
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addTerrainFeature("forta","forta","f",0,0,0,false);
        $this->terrain->addNatAltEntranceCost('forta','French','artillery','blocked');
        $this->terrain->addNatAltEntranceCost('forta','French','cavalry','blocked');
        $this->terrain->addNatAltEntranceCost('forta','French','infantry','blocked');
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
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->game = $this->game;
        $data->terrainName = $this->terrainName;
        $data->specialHexA = $this->specialHexA;
        $data->specialHexB = $this->specialHexB;
        $data->specialHexC = $this->specialHexC;
        return $data;
    }


    public function init()
    {

        $artRange = 3;
        $coinFlip = floor(2 * (rand() / getrandmax()));

        if($this->scenario->hastenbeck2){
            $frenchDeploy = "C";
        }else{
            $frenchDeploy = $coinFlip == 1 ? "B": "C";
        }

        if($this->scenario->redux){
            $frenchDeploy = "B";
            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'infantry');
            }
            for ($i = 0; $i < 20; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'infantry');
            }

            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 7; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, $artRange, "French", false, 'artillery');
            }



            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'infantry');
            }
            for ($i = 0; $i < 17; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'infantry');
            }
            for ($i = 0; $i < 3; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'cavalry');
            }
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'cavalry');
            }
            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Allied", false, 'artillery');
            }

        }else {


            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'infantry');
            }
            for ($i = 0; $i < 25; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'infantry');
            }
            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 7; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 1; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, 1, "French", false, 'cavalry');
            }
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", FRENCH_FORCE, "deployBox", "FrenchArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, $frenchDeploy, 1, $artRange, "French", false, 'artillery');
            }


            for ($i = 0; $i < 4; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'infantry');
            }
            for ($i = 0; $i < 17; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'infantry');
            }
            for ($i = 0; $i < 3; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'cavalry');
            }
            for ($i = 0; $i < 6; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Allied", false, 'cavalry');
            }
            for ($i = 0; $i < 3; $i++) {
                $this->force->addUnit("infantry-1", ALLIED_FORCE, "deployBox", "AngArtBadge.png", 3, 3, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Allied", false, 'artillery');
            }
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {
            $this->cities = $data->cities;
            $this->loc = $data->loc;
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
            $this->specialHexC = $data->specialHexC;
        } else {
            $this->victory = new Victory("Mollwitz/Hastenbeck/hastenbeckVictoryCore.php");

            $this->mapData->blocksZoc->blocked = true;
            $this->mapData->blocksZoc->blocksnonroad = true;

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;

            // game data
            $this->gameRules->setMaxTurn(12);
            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE, DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE; /* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE; /* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */


            $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

        }
    }
}