<?php
/**
 * Copyright 2016 David Rodal
 * User: David Markarian Rodal
 * Date: 2/21/16
 * Time: 11:46 AM
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Wargame;

class VictoryCore
{

    /*
     * Two public variables for analytics visible.
     * @var gameOver
     * @var winner
     */

    public $gameOver = false;
    public $winner = 0;
    public $saveDeploy = false;

    public function __construct($data){
        if($data) {
            $this->gameOver = $data->victory->gameOver;
            $this->winner = $data->victory->winner;
        }
        $saveDeploy = false;
    }

    public function save()
    {
        $ret = new \stdClass();
        $ret->gameOver = $this->gameOver;
        $ret->winner = $this->winner;
        return $ret;
    }
    public function nextPhase(){
        $b = Battle::getBattle();
        if($b->gameRules->mode == DEPLOY_MODE){
            $this->saveDeploy = true;
        }
    }
}