<?php
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
namespace Wargame\Mollwitz\Gemauerthof1705;
use \Wargame\Battle;

class Gemauerthof1705VictoryCore extends \Wargame\Mollwitz\victoryCore
{
    function __construct($data)
    {
        parent::__construct($data);
    }

    protected function checkVictory( $battle)
    {
        $gameRules = $battle->gameRules;
        $turn = $gameRules->turn;
        $swedishWin = $russianWin = $draw = false;

        if (!$this->gameOver) {
            $winScore = 24;

            if ($this->victoryPoints[Gemauerthof1705::SWEDISH_FORCE] >= $winScore) {
                    $swedishWin = true;
            }
            if ($this->victoryPoints[Gemauerthof1705::RUSSIAN_FORCE] >= $winScore) {
                $russianWin = true;
            }


            if ($swedishWin && !$russianWin) {
                $this->winner = Gemauerthof1705::SWEDISH_FORCE;
                $gameRules->flashMessages[] = "Swedish Win";
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }


            if (!$swedishWin && $russianWin) {
                $this->winner = Gemauerthof1705::RUSSIAN_FORCE;
                $gameRules->flashMessages[] = "Russian Win";
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }

            if ($russianWin && $swedishWin) {
                $this->winner = 0;
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = "Game Over";
                $this->gameOver = true;
                return true;
            }
            if ( $turn == ($gameRules->maxTurn + 1)) {
                $this->winner = 0;
                $gameRules->flashMessages[] = "Tie Game";
                $gameRules->flashMessages[] = "Game Over";
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
