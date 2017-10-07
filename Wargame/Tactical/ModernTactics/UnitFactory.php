<?php
namespace Wargame\Tactical\ModernTactics;

class UnitFactory {
    public static $id = 0;
    public static $injector;
    public static function build($data = false){

        $sU =  new ModernTacticalUnit($data);
        if($data === false){
            $sU->id = self::$id++;
        }
        return $sU;
    }
    public static function create(  $unitForceId, $unitHexagon,  $attackStrength, $range,  $defense, $unitMaxMove, $weapons, $target,  $unitStatus, $unitReinforceZone, $unitReinforceTurn, $nationality = "neutral", $class, $unitDesig = "", $canTransport = false){
        $unit = self::build();
        $unit->set($unitForceId, $unitHexagon,  $attackStrength,$range, $defense, $unitMaxMove, $weapons, $target, $unitStatus, $unitReinforceZone, $unitReinforceTurn,  $nationality, $class, $unitDesig, $canTransport);
        self::$injector->injectUnit($unit);
    }

}