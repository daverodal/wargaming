<?php
namespace Wargame\Mollwitz\Monmouth1778;
use \Wargame\Mollwitz\UnitFactory;
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */

class Monmouth1778 extends \Wargame\Mollwitz\JagCore
{

    const AMERICAN_FORCE = 1;
    const REBEL_FORCE = self::AMERICAN_FORCE;
    const BRITISH_FORCE = 2;
    const LOYALIST_FORCE = self::BRITISH_FORCE;


    public $specialHexesMap = ['SpecialHexA'=>self::BRITISH_FORCE, 'SpecialHexB'=>self::AMERICAN_FORCE, 'SpecialHexC'=>0];

    static function getPlayerData($scenario){
        $forceName = ['Observer', "American", "British"];
        return \Wargame\Battle::register($forceName,$forceName);
    }

    function save()
    {
        $data = parent::save();
        return $data;
    }


    function terrainGen($mapDoc, $terrainDoc){
        parent::terrainGen($mapDoc, $terrainDoc);
    }

    public function terrainInit($terrainDoc){
        parent::terrainInit($terrainDoc);
    }

    public function init()
    {
        $scenario = $this->scenario;
        $unitSets = $scenario->units;
        UnitFactory::$injector = $this->force;

        foreach($unitSets as $unitSet) {
            $reinforceTurn = 1;
            $unitHex = "deployBox";
            $status = STATUS_CAN_DEPLOY;
            $name = isset($unitSet->name) ? $unitSet->name : "infantry-1";

            if(isset($unitSet->reinforceTurn)){
                $reinforceTurn = $unitSet->reinforceTurn;
                $unitHex = "gameTurn$reinforceTurn";
                $status = STATUS_CAN_REINFORCE;
            }
            for ($i = 0; $i < $unitSet->num; $i++) {
                UnitFactory::create($name, $unitSet->forceId, $unitHex, "", $unitSet->combat, $unitSet->combat, $unitSet->movement, true, $status, $unitSet->reinforce, $reinforceTurn, $unitSet->range, $unitSet->nationality, false, $unitSet->class);
            }
        }
    }

    function __construct($data = null, $arg = false, $scenario = false, $game = false)
    {

        parent::__construct($data, $arg, $scenario, $game);

        if ($data) {
            $this->specialHexA = $data->specialHexA;
            $this->specialHexB = $data->specialHexB;
        } else {
            $this->victory = new \Wargame\Victory('\Wargame\Mollwitz\Monmouth1778\VictoryCore');
            $this->gameRules->setMaxTurn(8);
            $this->deployFirstMoveFirst();
        }

        $this->moveRules->stacking = function($mapHex, $forceId, $unit){

            if($unit->name === "smallunit"){
                $nUnits = 0;
                foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                    $nUnits++;
                }
                return $nUnits >= 2;
            }

            $nUnits = 0;
            $smallUnit = false;
            foreach($mapHex->forces[$forceId] as $mKey => $mVal){
                if($this->force->units[$mKey]->name == "smallunit"){
                    $smallUnit = true;
                }
                $nUnits++;
            }
            if($smallUnit){
                return $nUnits >= 2;
            }
            return $nUnits >= 1;
        };

    }
}
