<?php

namespace Wargame;

class EastWestGameRules extends GameRules
{
    function selectNextPhase($click){
        $ret = $this->endPhase($click);

        $numCombines = 0;
        if (method_exists($this->force, 'getCombine')) {
            $numCombines = $this->force->getCombine();
        }

        do {
            $didOne = false;

            if ($ret === true &&
                (($this->mode === COMBAT_SETUP_MODE &&
                        ($this->phase === BLUE_COMBAT_PHASE || $this->phase === RED_COMBAT_PHASE))
                    || ($this->mode === FIRE_COMBAT_SETUP_MODE)
                ) &&
                $this->force->anyCombatsPossible === false) {
                $didOne = true;
                $this->flashMessages[] = "No Combats Possible.";
                $ret = $this->endPhase($click);
            }
        }while($didOne === true);
        return $ret;

    }

}