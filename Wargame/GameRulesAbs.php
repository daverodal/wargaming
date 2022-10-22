<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 1/13/19
 * Time: 11:07 AM
 *
 * /*
 * Copyright 2012-2019 David Rodal
 * This program is free software; you can redistribute it
 * and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation;
 * either version 2 of the License, or (at your option) any later version
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Wargame;


abstract class GameRulesAbs
{

    abstract public function save();
    abstract public function inject(MoveRules $MoveRules,CombatRules $CombatRules, SimpleForce $Force);
    abstract public function setMaxTurn($max_Turn);
    abstract public function setInitialPhaseMode($phase, $mode);
    abstract public function addPhaseChange($currentPhase, $nextPhase, $nextMode, $nextAttackerId, $nextDefenderId, $phaseWillIncrementTurn);
    abstract public function processEvent($event, $id, $location, $click);

    abstract public function currentPhase($phase = false);
    abstract public function phaseJump($attackingId, $defendingId, $phase = false, $incTurn = false, $mode= false);
    abstract public function incrementTurn();
}