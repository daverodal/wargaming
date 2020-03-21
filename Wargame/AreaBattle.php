<?php
namespace Wargame;
use \stdClass;
// Copyright 2012-2015 David Rodal

// This program is free software; you can redistribute it
// and/or modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation;
// either version 2 of the License, or (at your option) any later version->

// This program is distributed in the hope that it will be useful,
//    but WITHOUT ANY WARRANTY; without even the implied warranty of
//    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//    GNU General Public License for more details.
//
//You should have received a copy of the GNU General Public License
//    along with this program.  If not, see <http://www.gnu.org/licenses/>.


class AreaBattle extends \Wargame\Battle{

    public $players = [];
    static function playAs($name, $wargame, $arg = false)
    {
        @include_once "playAs.php";
    }

    static function playMulti($name, $wargame, $arg = false)
    {
        @include_once "playMulti.php";
    }

    public static function buildUnit($data = false){
        return UnitFactory::build($data);
    }

    public $clickHistory = [];
    public $dieRolls;

    public function __construct($data = false){
        $this->dieRolls = new DieRolls();
        if($data){

        }
    }

    static function transformChanges($doc, $last_seq, $user){


        global $mode_name, $phase_name;
        $battle = Battle::battleFromDoc($doc);

        $transform =  compact( "doc", "last_seq");
        $transform = $battle->postTransform($battle, $transform);
        return $transform;

    }

    public function postTransform($battle, $transform){
        return $transform;
    }

    public function save()
    {
        $data = new stdClass();
        $data->arg = $this->arg;
        $data->scenario = $this->scenario;
        $data->mapData = $this->mapData;
        $data->mapViewer = $this->mapViewer;
        $data->moveRules = $this->moveRules->save();
        $data->force = $this->force;
        $data->gameRules = $this->gameRules->save();
        $data->combatRules = $this->combatRules->save();
        $data->players = $this->players;
        $data->victory = $this->victory->save();
        $data->terrainName = $this->terrainName;
        return $data;
    }

    function poke($event, $id, $commands,  $x, $y, $user, $click)
    {
        /* @var $gameRules \Wargame\AreaGameRules */

        $retVal = $this->gameRules->processEvent($event, $commands, $user, $id, $click);
        return $retVal;
    }
}
