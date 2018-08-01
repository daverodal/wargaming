<?php
namespace Wargame\TMCW\NorthVsSouth;
/**
 * Copyright 2015 David Rodal
 * User: David Markarian Rodal
 * Date: 6/14/15
 * Time: 5:37 PM
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


class UnitFactory {
    public static $id = 0;
    public static $injector;
    private static $randomInjector = [];

    public static function build($data = false) : SimpleUnit{
        $nUnit =  new SimpleUnit($data);
        if ($data === false) {
            $nUnit->id = self::$id++;
        }
        return $nUnit;
    }

    public static function create($unitName,
                                  $unitForceId,
                                  $unitHexagon,
                                  $unitImage,
                                  $unitStrength,
                                  $untriedStrength,
                                  $unitMaxMove,
                                  $unitStatus,
                                  $unitReinforceZoneName,
                                  $unitReinforceTurn,
                                  $nationality = "neutral",
                                  $class = false,
                                  $unitDesig = false,
                                    $range = 1){
        /* @var $unit \Wargame\TMCW\NorthVsSouth\SimpleUnit */
        $unit = static::build();
        $unit->set($unitName, $unitForceId, $unitHexagon, $unitImage, $unitStrength, $untriedStrength,
            $unitMaxMove, $unitStatus, $unitReinforceZoneName, $unitReinforceTurn, $range, $nationality, $class, $unitDesig);
        static::$randomInjector[] = $unit;
    }

    public static function flush(){
        shuffle(static::$randomInjector);
        foreach(static::$randomInjector as $unit){
            self::$injector->injectUnit($unit);
        }
        static::$randomInjector = [];
    }
}