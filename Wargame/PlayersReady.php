<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 12/7/19
 * Time: 2:53 PM
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
use JsonSerializable;

class PlayerReady{
    public $id;
    public $ready;
    public function __construct($id, $data = false)
    {
        if($data){
            $this->id = $data->id;
            $this->ready = $data->ready;
        }else{
            $this->id = $id;
            $this->ready = false;
        }

    }
}
class PlayersReady implements JsonSerializable
{

    public $players = [];
    public function __construct($data = false)
    {
        if($data){
            foreach($data->playersReady as $k => $playerRready){
                $this->players[] = new PlayerReady($k, $playerRready);
            }
        }
    }

    function setReady(int $playerNumber){
        foreach($this->players as $player){
            if($playerNumber == $player->id){
                $player->ready = true;
            }
        }
    }
    function addPlayer(int $id){
        $this->players[] = new PlayerReady($id);
    }

    function jsonSerialize(){
        return $this->players;

    }
    function allReady(){
        foreach($this->players as $player){
            if($player->ready !== true){
                return false;
            }
        }
        return true;
    }
}