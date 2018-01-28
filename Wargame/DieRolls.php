<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 1/28/18
 * Time: 1:06 PM
 *
 * /*
 * Copyright 2012-2018 David Rodal
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


class DieRolls implements RandEvents
{
    protected $usedPre = false;
    protected $preEvents = [];
    protected $events = [];
    function getEvent(int $dieSides, int $arg2 = null){
        if(count($this->preEvents) > 0){
            $this->usedPre = true;
            return array_shift($this->preEvents);
        }
        if($this->usedPre){
            dd('bad');
        }
        if($arg2 !== null){
            $Die = rand($dieSides, $arg2);
        }else{
            $Die = floor($dieSides * (rand() / getrandmax()));
        }
        $this->events[] = $Die;
        return $Die;
    }

    function getEvents(){
        return $this->events;
    }

    function setEvents(array $events){
        $this->preEvents = $events;
    }
}