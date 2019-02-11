<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 1/19/19
 * Time: 11:35 AM
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


class CRT
{
    public $header;
    public $next;
    public $combatIndexCount;
    public $maxCombatIndex;
    public $dieCount;
    public $table;
    public function __construct($header = false, $next = false, $combatIndexCount = 0, $minDieRoll = 1, $table = null){
        $this->header = $header;
        $this->next = $next;
        $this->combatIndexCount = $combatIndexCount;
        $this->maxCombatIndex = $this->combatIndexCount - 1;
        $this->dieOffsetHelper = 0 - $minDieRoll;
        $this->table = $table;
    }
}