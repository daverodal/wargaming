<?php
namespace Wargame\Mollwitz\Mollwitz;
use \Wargame\Mollwitz\UnitFactory;
/**
 *
 * Copyright 2012-2015 David Rodal
 * User: David Markarian Rodal
 * Date: 3/8/15
 * Time: 5:48 PM
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

class Mollwitz extends \Wargame\Mollwitz\JagCore
{

    const PRUSSIAN_FORCE = 1;
    const AUSTRIAN_FORCE = 2;

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

    static function getPlayerData($scenario){
        return \Wargame\Battle::register(["Observer", "Prussian", "Austrian"],
            ["Observer", "Austrian", "Prussian" ]);
    }

    static function enterMulti()
    {
        @include_once "enterMulti.php";
    }

    function save()
    {
        $data = parent::save();

        return $data;
    }

    public function init(){


        $scenario = $this->scenario;
        $unitSets = $scenario->units;

        UnitFactory::$injector = $this->force;

        foreach ($unitSets as $unitSet) {
            if (empty($scenario->commandControl)) {
                if ($unitSet->class === 'hq'){
                    continue;
                }
            }
            for ($i = 0; $i < $unitSet->num; $i++) {
                UnitFactory::create("infantry-1", $unitSet->forceId, "deployBox", "", $unitSet->combat, $unitSet->combat, $unitSet->movement, true, STATUS_CAN_DEPLOY, $unitSet->reinforce, 1, $unitSet->range, $unitSet->nationality, false, $unitSet->class);
            }
        }
        return;
        $artRange = 3;
        $artStr = 2;
        UnitFactory::$injector = $this->force;

        if(!empty($this->scenario->artStr)){
            $artStr = 3;
        }
        if(!empty($this->scenario->commandControl)){
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'hq');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'hq');
            UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'hq');
        }

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 5, 5, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruInfBadge.png", 4, 4, 3, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'infantry');

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 2, 2, 5, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "B", 1, 1, "Prussian", false, 'cavalry');

        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');
        UnitFactory::create("infantry-1", BLUE_FORCE, "deployBox", "PruArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "B", 1, $artRange, "Prussian", false, 'artillery');

        if(!empty($this->scenario->commandControl)) {
            UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "HQBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'hq');
            UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "HQBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'hq');
            UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "HQBadge.png", 1, 1, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'hq');
        }
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusInfBadge.png", 3, 3, 3, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'infantry');

        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 4, 4, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 5, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');
        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusCavBadge.png", 3, 3, 6, true, STATUS_CAN_DEPLOY, "A", 1, 1, "Austrian", false, 'cavalry');

        UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        if(!empty($this->scenario->extraArt)){
            UnitFactory::create("infantry-1", RED_FORCE, "deployBox", "AusArtBadge.png", $artStr, $artStr, 2, true, STATUS_CAN_DEPLOY, "A", 1, $artRange, "Austrian", false, 'artillery');
        }


    }
    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {
        parent::__construct($data, $arg, $scenario, $game);
        if ($data) {

        } else {
            $this->victory = new \Wargame\Victory("\\Wargame\\Mollwitz\\Mollwitz\\mollwitzVictoryCore");

            $this->moveRules->enterZoc = "stop";
            $this->moveRules->exitZoc = "stop";
            $this->moveRules->noZocZoc = true;
            $this->moveRules->stickyZoc = false;

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