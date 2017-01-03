<?php
namespace Wargame\Mollwitz\Jagersdorf;
use \Wargame\Mollwitz\UnitFactory;
/*
Copyright 2012-2015 David Rodal

This program is fAe software; you can redistribute it
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

global $force_name;
$force_name[1] = "Prussian";
$force_name[2] = "Russian";

class Jagersdorf extends \Wargame\Mollwitz\JagCore {


    const PRUSSIAN_FORCE = 1;
    const RUSSIAN_FORCE = 2;

    /* @var Mapdata */
    public $mapData;
    public $mapViewer;
    public $force;
    /* @var Terrain */
    public $terrain;
    public $moveRules;
    public $combatRules;
    public $gameRules;
    public $victory;


    public $players;


    static function enterMulti(){
        @include_once "enterMulti.php";
    }


    static function getPlayerData($scenario){
        return \Wargame\Battle::register(["Observer", "Prussian", "Russian"],
            ["Observer", "Russian", "Prussian" ]);
    }

    function terrainGen($mapDoc, $terrainDoc){
        parent::terrainGen($mapDoc, $terrainDoc);
        $this->terrain->addAltEntranceCost('forest','artillery',3);
        $this->terrain->addAltEntranceCost('forest','cavalry',3);
    }

    function save()
    {
        $data = parent::save();

        return $data;
    }


    public function init(){
        UnitFactory::$injector = $this->force;

        $artRange = 3;
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",3, 3, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusInfBadge.png",2, 2, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Russian",false, 'infantry');

        UnitFactory::create("infantry-1", RED_FORCE, 807, "RusArtBadge.png",4, 4, 3, true, STATUS_READY, "B", 1, $artRange, "Russian",false,'artillery');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Russian",false,'artillery');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusArtBadge.png",4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Russian",false,'artillery');



        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",4, 4, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false,'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false,'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",2, 2, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false,'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false,'cavalry');

        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false,'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false,'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "RusCavBadge.png",1, 1, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Russian",false,'cavalry');

        if($this->scenario && !empty($this->scenario->prussianDeploy)){
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "C", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "C", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "C", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "C", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "C", 1, 1, "Prussian",false,'cavalry');

            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, $artRange, "Prussian",false,'artillery');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, $artRange, "Prussian",false,'artillery');
            if(!empty($this->scenario->extraArt)){
                UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, $artRange, "Prussian",false,'artillery');
            }

            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "D", 1, 1, "Prussian",false, 'infantry');

            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "C", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "C", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "C", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "C", 1, 1, "Prussian",false,'cavalry');
        }else{
            UnitFactory::create("infantry-1", BLUE_FORCE, 306, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 307, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 405, "PruCavBadge.png", 3, 3, 6, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 406, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 407, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');

            UnitFactory::create("infantry-1", BLUE_FORCE, 412, "PruArtBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, $artRange, "Prussian",false,'artillery');
            UnitFactory::create("infantry-1", BLUE_FORCE, 312, "PruArtBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, $artRange, "Prussian",false,'artillery');

            UnitFactory::create("infantry-1", BLUE_FORCE, 512, "PruInfBadge.png", 5, 5, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 513, "PruInfBadge.png", 5, 5, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 311, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 411, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 413, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 314, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 214, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 114, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 211, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 110, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 210, "PruInfBadge.png", 3, 3, 3, true, STATUS_READY, "B", 1, 1, "Prussian",false, 'infantry');

            UnitFactory::create("infantry-1", BLUE_FORCE, 115, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 215, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 316, "PruCavBadge.png", 3, 3, 6, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');
            UnitFactory::create("infantry-1", BLUE_FORCE, 416, "PruCavBadge.png", 2, 2, 5, true, STATUS_READY, "B", 1, 1, "Prussian",false,'cavalry');

        }
    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {

        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\Mollwitz\\Jagersdorf\\jagerVictoryCore");

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->stickyZoc = false;

            // game data
            $this->gameRules->setMaxTurn(12);

            $this->gameRules->setInitialPhaseMode(RED_DEPLOY_PHASE,DEPLOY_MODE);
            $this->gameRules->attackingForceId = RED_FORCE;/* object oriented! */
            $this->gameRules->defendingForceId = BLUE_FORCE;/* object oriented! */
            $this->force->setAttackingForceId($this->gameRules->attackingForceId); /* so object oriented */

            /**
             * not not prussian deploy phase for now
             */
            if(!empty($scenario->prussianDeploy)){
                $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_DEPLOY_PHASE, DEPLOY_MODE, BLUE_FORCE, RED_FORCE, false);
                $this->gameRules->addPhaseChange(BLUE_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            }else{
                $this->gameRules->addPhaseChange(RED_DEPLOY_PHASE, BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, false);
            }

            $this->gameRules->addPhaseChange(BLUE_MOVE_PHASE, BLUE_COMBAT_PHASE, COMBAT_SETUP_MODE, BLUE_FORCE, RED_FORCE, false);
            $this->gameRules->addPhaseChange(BLUE_COMBAT_PHASE, RED_MOVE_PHASE, MOVING_MODE, RED_FORCE, BLUE_FORCE, false);

            $this->gameRules->addPhaseChange(RED_MOVE_PHASE, RED_COMBAT_PHASE, COMBAT_SETUP_MODE, RED_FORCE, BLUE_FORCE, false);
            $this->gameRules->addPhaseChange(RED_COMBAT_PHASE,BLUE_MOVE_PHASE, MOVING_MODE, BLUE_FORCE, RED_FORCE, true);

            // end terrain data ----------------------------------------

        }
    }
}