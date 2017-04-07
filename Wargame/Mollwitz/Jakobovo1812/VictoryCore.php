<?php
namespace Wargame\Mollwitz\Jakobovo1812;
use \Wargame\Battle;
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
/**
 * Created by JetBrains PhpStorm.
 * User: markarianr
 * Date: 5/7/13
 * Time: 7:06 PM
 * To change this template use File | Settings | File Templates.
 */

class VictoryCore extends \Wargame\Mollwitz\victoryCore
{
    public $victoryPoints = [0,0,10];
    function __construct($data)
    {

        parent::__construct($data);
        if ($data) {
        }
    }

    public function save()
    {
        $ret = parent::save();
        return $ret;
    }

    public function specialHexChange($args)
    {
        list($mapHexName, $forceId) = $args;

        $battle = Battle::getBattle();
        $vHexes = [0,0,0];
        $mapData = $battle->mapData;

        list($hexB) = $battle->specialHexB;
        if ((int)$hexB === (int)$mapHexName) {

            $this->victoryPoints[0] = $forceId === Jakobovo1812::FRENCH_FORCE ? 0 : 1; /* Russian Bridge Hex control */

        }


        if(in_array($mapHexName, $battle->specialHexA)){
            if($forceId === Jakobovo1812::RUSSIAN_FORCE){
                $this->victoryPoints[Jakobovo1812::RUSSIAN_FORCE]++;
                $this->victoryPoints[Jakobovo1812::FRENCH_FORCE]--;
            }else{
                $this->victoryPoints[Jakobovo1812::RUSSIAN_FORCE]--;
                $this->victoryPoints[Jakobovo1812::FRENCH_FORCE]++;
            }
        }




    }

        protected function checkVictory( $battle)
    {
        $battle = Battle::getBattle();

        $gameRules = $battle->gameRules;
        $scenario = $battle->scenario;
        $turn = $gameRules->turn;
        $frenchWin = $AlliedWin = $draw = false;

        $victoryReason = "";

        if (!$this->gameOver) {
            $mapData = $battle->mapData;
            if ($turn > $gameRules->maxTurn) {
                list($hexB) = $battle->specialHexB;
                if ($mapData->getSpecialHex($hexB) == Jakobovo1812::RUSSIAN_FORCE) {
                    $this->winner = Jakobovo1812::RUSSIAN_FORCE;
                    $gameRules->flashMessages[] = "Russians Control Bridge and WIN";
                    $this->gameOver = true;
                    return true;
                }
            }

            if ($turn > $gameRules->maxTurn) {

                $vHexes = [0,0,0];

                /* French control bridge hex */
                foreach($battle->specialHexA as $hexA){
                    $vHexes[$mapData->getSpecialHex($hexA)]++;
                }
                if($vHexes[Jakobovo1812::FRENCH_FORCE] < $vHexes[Jakobovo1812::RUSSIAN_FORCE]){
                    $gameRules->flashMessages[] = "Russians Control More Hexes and WIN";
                    $gameRules->flashMessages[] = $vHexes[Jakobovo1812::RUSSIAN_FORCE]." vs ".$vHexes[Jakobovo1812::FRENCH_FORCE];
                    $this->winner = Jakobovo1812::RUSSIAN_FORCE;
                }elseif($vHexes[Jakobovo1812::FRENCH_FORCE] > $vHexes[Jakobovo1812::RUSSIAN_FORCE]){
                    $gameRules->flashMessages[] = "French Control More Hexes and WIN";
                    $gameRules->flashMessages[] = $vHexes[Jakobovo1812::FRENCH_FORCE]." vs ".$vHexes[Jakobovo1812::RUSSIAN_FORCE];

                    $this->winner = Jakobovo1812::FRENCH_FORCE;
                }else{
                    $gameRules->flashMessages[] = "TIE GAME";
                    $this->winner = 0;
                }

                $this->gameOver = true;
                return true;
            }


        }
        return false;
    }

    public function postRecoverUnit($args)
    {
        $unit = $args[0];
        $b = Battle::getBattle();
        $scenario = $b->scenario;
        $id = $unit->id;


        parent::postRecoverUnit($args);

    }
}
