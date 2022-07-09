<?php
namespace Wargame;
/* the following left behind for kitch factor */
// Battle for Allen Creek wargame
// constants.js

// Copyright (c 2009 Mark Butler
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option any later version->

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */
// force data
interface Cnst {
    const NO_FORCE =  0;
    const BLUE_FORCE =  1;
    const RED_FORCE =  2;
    const TEAL_FORCE =  3;
    const PURPLE_FORCE =  4;

// game phases
    const BLUE_MOVE_PHASE =  1;
    const BLUE_COMBAT_PHASE =  2;
    const BLUE_FIRE_COMBAT_PHASE =  3;
    const RED_MOVE_PHASE =  4;
    const RED_COMBAT_PHASE =  5;
    const RED_FIRE_COMBAT_PHASE =  6;
    const GAME_OVER_PHASE =  7;
    const BLUE_DEPLOY_PHASE =  8;
    const BLUE_MECH_PHASE =  9;
    const BLUE_REPLACEMENT_PHASE =  10;
    const RED_MECH_PHASE =  11;
    const RED_REPLACEMENT_PHASE =  12;
    const BLUE_DISPLAY_PHASE =  13;
    const RED_DISPLAY_PHASE =  14;
    const RED_DEPLOY_PHASE =  15;
    const BLUE_SURPRISE_MOVE_PHASE =  16;
    const BLUE_COMBAT_RES_PHASE =  17;
    const RED_COMBAT_RES_PHASE =  18;
    const RED_FIRST_COMBAT_PHASE =  19;
    const BLUE_FIRST_COMBAT_PHASE =  20;
    const RED_SECOND_COMBAT_PHASE =  21;
    const BLUE_SECOND_COMBAT_PHASE =  22;
    const TEAL_REPLACEMENT_PHASE =  23;
    const TEAL_MOVE_PHASE =  24;
    const TEAL_COMBAT_PHASE =  25;
    const PURPLE_REPLACEMENT_PHASE =  26;
    const PURPLE_MOVE_PHASE =  27;
    const PURPLE_COMBAT_PHASE =  28;
    const BLUE_AIR_COMBAT_PHASE =  29;
    const RED_AIR_COMBAT_PHASE =  30;
    const BLUE_TORP_COMBAT_PHASE =  31;
    const RED_TORP_COMBAT_PHASE =  32;
    const BLUE_SPEED_PHASE =  33;
    const RED_SPEED_PHASE =  34;
    const RED_COMBINE_PHASE =  35;
    const BLUE_COMBINE_PHASE =  36;
    const RED_MECH_COMBINE_PHASE =  37;
    const BLUE_MECH_COMBINE_PHASE =  38;
    const BLUE_REBASE_PHASE =  39;
    const RED_REBASE_PHASE =  40;
    const BLUE_SUPPLY_PHASE =  41;
    const RED_SUPPLY_PHASE =  42;
    const BLUE_TRANSPORT_PHASE =  43;
    const RED_TRANSPORT_PHASE =  44;
    const BLUE_OPTION_PHASE =  45;
    const RED_OPTION_PHASE =  46;
    const RED_FIRE_COMBAT_PHASE_TWO =  47;
    const BLUE_FIRE_COMBAT_PHASE_TWO =  48;
    const COMMAND_PHASE = 49;
    const RESULTS_PHASE = 50;
    const PRODUCTION_PHASE = 51;

// game modes
    const SELECT_TO_MOVE_MODE =  1;
    const MOVING_MODE =  2;
    const COMBAT_SETUP_MODE =  3;
    const COMBAT_RESOLUTION_MODE =  4;
    const FIRE_COMBAT_SETUP_MODE =  5;
    const FIRE_COMBAT_RESOLUTION_MODE =  6;
    const SELECT_TO_RETREAT_MODE =  7;
    const CHECK_RETREAT_MODE =  8;
    const RETREATING_MODE =  9;
    const STOP_RETREAT_MODE =  10;
    const SELECT_TO_ADVANCE_MODE =  11;
    const ADVANCING_MODE =  12;
    const SELECT_TO_DELETE_MODE =  13;
    const DELETING_MODE =  14;
    const CHECK_FOR_COMBAT_MODE =  15;
    const GAME_OVER_MODE =  16;
    const DEPLOY_MODE =  17;
    const EXCHANGING_MODE =  18;
    const REPLACING_MODE =  19;
    const ATTACKER_LOSING_MODE =  20;
    const DISPLAY_MODE =  21;
    const SPEED_MODE =  22;
    const COMBINING_MODE =  23;
    const REBASE_MODE =  24;
    const SUPPLY_MODE =  25;
    const DEFENDER_LOSING_MODE =  26;
    const OPTION_MODE =  27;
    const COMMAND_MODE = 28;
    const RESULTS_MODE = 29;
    const PRODUCTION_MODE = 30;
    const FPF_MODE = 31;
    const DEFENDER_RETREATING_MODE =  32;
    const ATTACKER_RETREATING_MODE =  33;


// form event constants
    const OVER_MAP_EVENT =  1;
    const SELECT_MAP_EVENT =  2;
    const OVER_COUNTER_EVENT =  3;
    const SELECT_COUNTER_EVENT =  4;
    const OVER_BUTTON_EVENT =  5;
    const SELECT_BUTTON_EVENT =  6;
    const SELECT_SHIFT_COUNTER_EVENT =  7;
    const KEYPRESS_EVENT =  8;
    const COMBAT_PIN_EVENT =  9;
    const SELECT_ALT_COUNTER_EVENT =  10;
    const SAVE_GAME_EVENT =  11;
    const SELECT_ALT_MAP_EVENT = 12;
    const SURRENDER_EVENT = 13;


// unit status
    const STATUS_NONE =  0;
    const STATUS_READY =  1;
    const STATUS_CAN_REINFORCE =  2;
    const STATUS_REINFORCING =  3;
    const STATUS_MOVING =  4;
    const STATUS_STOPPED =  6;
    const STATUS_DEFENDING =  8;
    const STATUS_ATTACKING =  9;
    const STATUS_NO_RESULT =  10;
    const STATUS_DEFENDED =  11;
    const STATUS_ATTACKED =  12;
    const STATUS_CAN_RETREAT =  13;
    const STATUS_RETREATING =  14;
    const STATUS_RETREATED =  15;
    const STATUS_CAN_ADVANCE =  16;
    const STATUS_ADVANCING =  17;
    const STATUS_ADVANCED =  18;
    const STATUS_DELETING =  19;
    const STATUS_ELIMINATING =  21;
    const STATUS_ELIMINATED =  22;
    const STATUS_EXITING =  23;
    const STATUS_EXITED =  24;
    const STATUS_CAN_EXCHANGE =  27;
    const STATUS_EXCHANGED =  28;
    const STATUS_REPLACED =  29;
    const STATUS_CAN_REPLACE =  30;
    const STATUS_CAN_UPGRADE =  31;
    const STATUS_CAN_ATTACK_LOSE =  32;
    const STATUS_BOMBARDING =  33;
    const STATUS_UNAVAIL_THIS_PHASE =  34;
    const STATUS_CAN_DEPLOY =  35;
    const STATUS_DEPLOYING =  36;
    const STATUS_REPLACING =  37;
    const STATUS_COMBINED =  38;
    const STATUS_CAN_COMBINE =  39;
    const STATUS_CAN_DEFEND_LOSE = 40;
    const STATUS_LOADING = 41;
    const STATUS_CAN_TRANSPORT = 42;
    const STATUS_UNLOADING = 43;
    const STATUS_CAN_UNLOAD = 44;
    const STATUS_CAN_LOAD = 45;
    const STATUS_MUST_ADVANCE = 46;
    const STATUS_FPF= 47;
}
