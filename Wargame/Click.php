<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 12/16/16
 * Time: 6:44 PM
 *
 * /*
 * Copyright 2012-2016 David Rodal
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


class Click
{
    public $event;
    public $id;
    public $x;
    public $y;
    public $user;
    public $click;
    public $playerId;
    public $dieRoll;

    public function __construct($data = false,  $event = false, $id = false, $x= false, $y= false, $user= false, $playerId = false, $click = false, $dieRoll = false){
        if($data){
            foreach($data as $k => $v){
                $this->$k = $v;
            }
        }else{
            $this->event = $event;
            $this->id = $id;
            $this->x = $x;
            $this->y = $y;
            $this->user = $user;
            $this->playerId = $playerId;
            $this->click = $click;
            $this->dieRoll = $dieRoll;
        }
    }
}