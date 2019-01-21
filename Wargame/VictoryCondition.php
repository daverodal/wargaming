<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 1/12/19
 * Time: 9:23 AM
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


class VictoryCondition
{
    public $player = false;
    public $score = false;
    public $lead = false;
    public $turn = false;
    public $canTie = false;

    public function __construct($player = false, $score = false, $lead = false, $turn = false)
    {
        if($player !== false){
            $this->player = $player;
        }
        if($score !== false){
            $this->score = $score;
        }
        if($lead !== false){
            $this->lead = $lead;
        }
        if($turn !== false){
            $this->turn = $turn;
        }
        
    }

    public function checkVictory($player, $playerScore, $enemyScore = false, $turn = false) {
        if ($player === $this->player) {
            if ($playerScore >= $this->score) {
                if ($this->lead !== false) {
                    if ($playerScore - $enemyScore > $this->lead) {
                        if($this->turn !== false){
                            if($turn <= $this->turn){
                                return true;
                            }
                        }else{
                            return true;
                        }
                    }
                } else {
                    return true;
                }
            }
        }
        return false;
    }
}