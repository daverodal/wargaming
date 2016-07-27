<?php
namespace Wargame\Troops\ModernTactics;

use stdClass;
use Wargame\Battle;
use Wargame\Hexpart;

/**
 *
 * Copyright 2012-2015 David Rodal
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

/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */
class modernTacticsVictoryCore extends \Wargame\Troops\troopersVictoryCore
{

    public $attacked;

    function __construct($data)
    {
        parent::__construct($data);
        if ($data) {
            if (isset($data->victory->attacked)) {
                $this->attacked = $data->victory->attacked;

            } else {
                $this->attacked = new stdClass();

            }
        } else {
            $this->attacked = new stdClass();
        }

    }

    public function save()
    {
        $ret = parent::save();
        $ret->attacked = $this->attacked;
        return $ret;
    }

    public function reduceUnit($args)
    {
        $unit = $args[0];
        $hex = $unit->hexagon;
        $battle = Battle::getBattle();
        $playerOne = strtolower($battle->scenario->playerOne);
        $playerTwo = strtolower($battle->scenario->playerTwo);

        if ($unit->forceId == 1) {
            $victorId = 2;
            $this->victoryPoints[$victorId] += $unit->strength;
            if($hex->name) {
                if (isset($battle->mapData->specialHexesVictory->{$hex->name})) {

                    $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='$playerTwo'>DE</span>";
                } else {
                    $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerTwo'>DE</span>";

                }
            }
        } else {
            $victorId = 1;
            if($hex->name) {
                if (isset($battle->mapData->specialHexesVictory->{$hex->name})) {
                    $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='$playerOne'>DE</span>";
                } else {
                    $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerOne'>DE</span>";
                }
                $this->victoryPoints[$victorId] += $unit->strength;
            }
        }
    }

    public function disruptUnit($args)
    {
        list($unit, $combatResult) = $args;
        $disruptLevel = $unit->disruptionLevel($combatResult);
        $hex = $unit->hexagon;
        $battle = Battle::getBattle();
        $playerOne = strtolower($battle->scenario->playerOne);
        $playerTwo = strtolower($battle->scenario->playerTwo);

        if ($hex->name) {
            if ($unit->forceId == 1) {
                if (isset($battle->mapData->specialHexesVictory->{$hex->name})) {
                    $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='$playerTwo'>D$disruptLevel </span>";
                } else {
                    $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerTwo'>D$disruptLevel </span>";
                }
            } else {
                if (isset($battle->mapData->specialHexesVictory->{$hex->name})) {
                    $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='$playerOne'>D$disruptLevel </span>";
                } else {
                    $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerOne'>D$disruptLevel </span>";
                }
            }
        }
    }

    public function noEffectUnit($args)
    {
        list($unit) = $args;
        $hex = $unit->hexagon;
        $battle = Battle::getBattle();
        $playerOne = strtolower($battle->scenario->playerOne);
        $playerTwo = strtolower($battle->scenario->playerTwo);

        if ($hex->name) {
            if ($unit->forceId == 1) {
                if (isset($battle->mapData->specialHexesVictory->{$hex->name})) {
                    $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='$playerTwo'>NE</span>";
                } else {
                    $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerTwo'>NE</span>";
                }
            } else {
                if (isset($battle->mapData->specialHexesVictory->{$hex->name})) {
                    $battle->mapData->specialHexesVictory->{$hex->name} .= "<span class='$playerOne'>NE</span>";
                } else {
                    $battle->mapData->specialHexesVictory->{$hex->name} = "<span class='$playerOne'>NE</span>";
                }
            }
        }
    }

    public function specialHexChange($args)
    {

    }


    protected function checkVictory($attackingId, $battle)
    {
        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $sikhWin = $britishWin = false;

        return false;
    }

    public function postCombatResults($args)
    {
        list($defenderId, $attackers, $combatResults, $dieRoll) = $args;
        $b = Battle::getBattle();
        $cr = $b->combatRules;
        $f = $b->force;
        foreach ($attackers as $attackId => $v) {
            $unit = $f->units[$attackId];

            $hexagon = $unit->hexagon;
            $hexpart = new Hexpart();
            $hexpart->setXYwithNameAndType($hexagon->name, HEXAGON_CENTER);
            if ($b->terrain->terrainIs($hexpart, 'town') || $b->terrain->terrainIs($hexpart, 'forest')) {
                $cr->sighted($hexagon->name);
            }
        }
    }

    public function preRecoverUnit($arg)
    {
        list($unit) = $arg;
        if ($unit->status === STATUS_ATTACKED) {
            $this->attacked->{$unit->id} = true;
        }
    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();

        if ($b->gameRules->mode == COMBAT_SETUP_MODE) {
            if ($unit->isImproved !== true) {
            }
        }
        if ($b->gameRules->phase == BLUE_FIRST_COMBAT_PHASE && ($unit->isDisrupted === true || $unit->pinned)) {
            $unit->attemptUnDisrupt();
        }
        if ($unit->isDisrupted !== false) {
            $unit->status = STATUS_UNAVAIL_THIS_PHASE;
        }
        if (($b->gameRules->phase == BLUE_MOVE_PHASE || $b->gameRules->phase == RED_MOVE_PHASE)) {
            if (isset($this->attacked->{$unit->id})) {
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            }
            if ($unit->pinned === true) {
                $unit->status = STATUS_UNAVAIL_THIS_PHASE;
            }
        }
    }

    public function postRecoverUnits()
    {
        $b = Battle::getBattle();

        if ($b->gameRules->phase == BLUE_FIRST_COMBAT_PHASE) {
            $this->attacked = new stdClass();
        }
    }
}
