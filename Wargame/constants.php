<?php
/* the following left behind for kitch factor */
// Battle for Allen Creek wargame
// constants.js

// Copyright (c) 2009 Mark Butler
/*
Copyright 2012-2015 David Rodal

This program is free software; you can redistribute it
and/or modify it under the terms of the GNU General Public License
as published by the Free Software Foundation;
either version 2 of the License, or (at your option) any later version->

This program is distributed in the hope that it will be useful,
   but WITHOUT ANY WARRANTY; without even the implied warranty of
   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
   GNU General Public License for more details.

You should have received a copy of the GNU General Public License
   along with this program.  If not, see <http://www.gnu.org/licenses/>.
   */
// force data
global $force_name,$phase_name,$mode_name, $event_name, $status_name, $results_name,$combatRatio_name;
define("NO_FORCE",0);
define("BLUE_FORCE",1);
define("RED_FORCE",2);
define("TEAL_FORCE", 3);
define("PURPLE_FORCE", 4);
global $force_name;

if(!isset($force_name)){
    $force_name = array();
    $force_name[0] = "Neutral Observer";
    $force_name[1] = "Rebel";
    $force_name[2] = "Loyalist";
    $force_name[3] = "outworlders";

}
// game phases
define("BLUE_MOVE_PHASE",1);
define("BLUE_COMBAT_PHASE",2);
define("BLUE_FIRE_COMBAT_PHASE",3);
define("RED_MOVE_PHASE",4);
define("RED_COMBAT_PHASE",5);
define("RED_FIRE_COMBAT_PHASE",6);
define("GAME_OVER_PHASE",7);
define("BLUE_DEPLOY_PHASE",8);
define("BLUE_MECH_PHASE",9);
define("BLUE_REPLACEMENT_PHASE",10);
define("RED_MECH_PHASE",11);
define("RED_REPLACEMENT_PHASE",12);
define("BLUE_DISPLAY_PHASE",13);
define("RED_DISPLAY_PHASE",14);
define("RED_DEPLOY_PHASE",15);
define("BLUE_SURPRISE_MOVE_PHASE",16);
define("BLUE_COMBAT_RES_PHASE",17);
define("RED_COMBAT_RES_PHASE",18);
define("RED_FIRST_COMBAT_PHASE", 19);
define("BLUE_FIRST_COMBAT_PHASE", 20);
define("RED_SECOND_COMBAT_PHASE", 21);
define("BLUE_SECOND_COMBAT_PHASE", 22);
define("TEAL_REPLACEMENT_PHASE", 23);
define("TEAL_MOVE_PHASE", 24);
define("TEAL_COMBAT_PHASE", 25);
define("PURPLE_REPLACEMENT_PHASE", 26);
define("PURPLE_MOVE_PHASE", 27);
define("PURPLE_COMBAT_PHASE", 28);
define("BLUE_AIR_COMBAT_PHASE",29);
define("RED_AIR_COMBAT_PHASE",30);
define("BLUE_TORP_COMBAT_PHASE", 31);
define("RED_TORP_COMBAT_PHASE", 32);
define("BLUE_SPEED_PHASE", 33);
define("RED_SPEED_PHASE",34);
define("RED_COMBINE_PHASE",35);
define("BLUE_COMBINE_PHASE",36);
define("RED_MECH_COMBINE_PHASE",37);
define("BLUE_MECH_COMBINE_PHASE",38);
define("BLUE_REBASE_PHASE", 39);
define("RED_REBASE_PHASE", 40);
define("BLUE_SUPPLY_PHASE", 41);
define("RED_SUPPLY_PHASE", 42);
define("BLUE_TRANSPORT_PHASE", 43);
define("RED_TRANSPORT_PHASE", 44);
define("BLUE_OPTION_PHASE", 45);
define("RED_OPTION_PHASE", 46);
define("RED_FIRE_COMBAT_PHASE_TWO", 47);
define("BLUE_FIRE_COMBAT_PHASE_TWO", 48);
define("COMMAND_PHASE", 49);
define("RESULTS_PHASE", 50);
define("PRODUCTION_PHASE", 51);

$phase_name = array();
$phase_name[0] = "";
$phase_name[1] = "<span class='playerOneFace'>fNameOne</span> Move";
$phase_name[2] = "<span class='playerOneFace'>fNameOne</span>";
$phase_name[3] = "<span class='playerOneFace'>fNameOne</span> Off Fire";
$phase_name[4] = "<span class='playerTwoFace'>fNameTwo</span> Move";
$phase_name[5] = "<span class='playerTwoFace'>fNameTwo</span>";
$phase_name[6] = "<span class='playerOneFace'>fNameTwo</span> Def Fire";
$phase_name[7] = "Victory";
$phase_name[8] = "<span class='playerOneFace'>fNameOne</span> Deploy";
$phase_name[9] = "<span class='playerOneFace'>fNameOne</span> Mech";
$phase_name[10] = "<span class='playerOneFace'>fNameOne</span> Replacement";
$phase_name[11] = "<span class='playerTwoFace'>fNameTwo</span> Mech";
$phase_name[12] = "<span class='playerTwoFace'>fNameTwo</span> Replacement";
$phase_name[13] = "";
$phase_name[14] = "";
$phase_name[15] = "<span class='playerTwoFace'>fNameTwo</span> Deploy";
$phase_name[16] = "<span class='playerTwoFace'>fNameOne</span> Surprise";
$phase_name[17] = "<span class='playerOneFace'>fNameOne</span> Fire";
$phase_name[18] = "<span class='playerTwoFace'>fNameTwo</span> Fire";
$phase_name[19] = "<span class='playerTwoFace'>fNameTwo</span> First ";
$phase_name[20] = "<span class='playerOneFace'>fNameOne</span> First ";
$phase_name[21] = "<span class='playerTwoFace'>fNameTwo</span> Second ";
$phase_name[22] = "<span class='playerOneFace'>fNameOne</span> Second ";

$phase_name[23] = "<span class='playerThreeFace'>fNameThree</span> Replacement";
$phase_name[24] = "<span class='playerThreeFace'>fNameThree</span> Move";
$phase_name[25] = "<span class='playerThreeFace'>fNameThree</span>";
$phase_name[26] = "<span class='playerFourFace'>fNameFour</span> Replacement";
$phase_name[27] = "<span class='playerFourFace'>fNameFour</span> Move";
$phase_name[28] = "<span class='playerFourFace'>fNameFour</span>";
$phase_name[29] = "<span class='playerOneFace'>fNameOne</span> Air ";
$phase_name[30] = "<span class='playerOneFace'>fNameTwo</span> Air ";
$phase_name[31] = "<span class='playerOneFace'>fNameOne</span> Torp ";
$phase_name[32] = "<span class='playerTwoFace'>fNameTwo</span> Torp ";
$phase_name[33] = "<span class='playerOneFace'>fNameOne</span> Speed ";
$phase_name[34] = "<span class='playerTwoFace'>fNameTwo</span> Speed ";
$phase_name[35] = "<span class='playerTwoFace'>fNameTwo</span> Combining ";
$phase_name[36] = "<span class='playerOneFace'>fNameOne</span> Combining ";
$phase_name[37] = "<span class='playerTwoFace'>fNameTwo</span> Mech Combining ";
$phase_name[38] = "<span class='playerOneFace'>fNameOne</span> Mech Combining ";
$phase_name[39] = "<span class='playerOneFace'>fNameOne</span> Rebase ";
$phase_name[40] = "<span class='playerTwoFace'>fNameTwo</span> Rebase ";
$phase_name[41] = "<span class='playerOneFace'>fNameOne</span> Supply ";
$phase_name[42] = "<span class='playerTwoFace'>fNameTwo</span> Supply ";
$phase_name[43] = "<span class='playerOneFace'>fNameOne</span> Air Transport ";
$phase_name[44] = "<span class='playerTwoFace'>fNameTwo</span> Air Transport ";
$phase_name[45] = "<span class='playerOneFace'>fNameOne</span> Option ";
$phase_name[46] = "<span class='playerTwoFace'>fNameTwo</span> Option ";
$phase_name[47] = "<span class='playerTwoFace'>fNameTwo</span> Off Fire ";
$phase_name[48] = "<span class='playerOneFace'>fNameOne</span> Def Fire ";

// game modes
define("SELECT_TO_MOVE_MODE",1);
define("MOVING_MODE",2);
define("COMBAT_SETUP_MODE",3);
define("COMBAT_RESOLUTION_MODE",4);
define("FIRE_COMBAT_SETUP_MODE",5);
define("FIRE_COMBAT_RESOLUTION_MODE",6);
define("SELECT_TO_RETREAT_MODE",7);
define("CHECK_RETREAT_MODE",8);
define("RETREATING_MODE",9);
define("STOP_RETREAT_MODE",10);
define("SELECT_TO_ADVANCE_MODE",11);
define("ADVANCING_MODE",12);
define("SELECT_TO_DELETE_MODE",13);
define("DELETING_MODE",14);
define("CHECK_FOR_COMBAT_MODE",15);
define("GAME_OVER_MODE",16);
define("DEPLOY_MODE",17);
define("EXCHANGING_MODE",18);
define("REPLACING_MODE",19);
define("ATTACKER_LOSING_MODE",20);
define("DISPLAY_MODE",21);
define("SPEED_MODE", 22);
define("COMBINING_MODE", 23);
define("REBASE_MODE", 24);
define("SUPPLY_MODE", 25);
define("DEFENDER_LOSING_MODE",26);
define("OPTION_MODE",27);
define("COMMAND_MODE", 28);
define("RESULTS_MODE",29);
define("PRODUCTION_MODE", 30);
define("FPF_MODE", 31);



// mode names
$mode_name = array();
$mode_name[ 1] = "moving mode";
$mode_name[ 2] = "moving mode";
$mode_name[ 3] = "combat setup mode";
$mode_name[ 4] = "combat resolution";
$mode_name[ 5] = "combat setup mode";
$mode_name[ 6] = "combat resolution";
$mode_name[ 7] = "retreating mode";
$mode_name[ 8] = "retreating mode";
$mode_name[ 9] = "retreating mode";
$mode_name[10] = "retreating mode";
$mode_name[11] = "advancing mode";
$mode_name[12] = "advancing mode";
$mode_name[13] = "select units to delete";
$mode_name[14] = "deleting unit";
$mode_name[15] = "checking combat";
$mode_name[16] = "game over";
$mode_name[17] = "phase ";
$mode_name[18] = "exchanging mode";
$mode_name[19] = "replacing mode";
$mode_name[20] = "attacker loss mode";
$mode_name[21] = "display mode";
$mode_name[22] = "speed mode";
$mode_name[23] = "combining mode";
$mode_name[24] = "rebase mode";
$mode_name[25] = "supply mode";
$mode_name[26] = "defender loss mode";
$mode_name[27] = "option mode";
$mode_name[28] = "command mode";
$mode_name[29] = "results mode";
$mode_name[30] = "production mode";
$mode_name[31] = "fpf mode";

// form event constants
define("OVER_MAP_EVENT",1);
define("SELECT_MAP_EVENT",2);
define("OVER_COUNTER_EVENT",3);
define("SELECT_COUNTER_EVENT",4);
define("OVER_BUTTON_EVENT",5);
define("SELECT_BUTTON_EVENT",6);
define("SELECT_SHIFT_COUNTER_EVENT",7);
define("KEYPRESS_EVENT",8);
define("COMBAT_PIN_EVENT",9);
define("SELECT_ALT_COUNTER_EVENT",10);
define("SAVE_GAME_EVENT", 11);
define("SELECT_ALT_MAP_EVENT", 12);
define("SURRENDER_EVENT", 13);


// event names
$event_name = array();
$event_name[1] = "over map";
$event_name[2] = "select map";
$event_name[3] = "over counter";
$event_name[4] = "select counter";
$event_name[5] = "over button";
$event_name[6] = "select button";


// form actions
define("NO_ACTION",0);
define("UPDATE_GAME_STATUS_DISPLAY",1);
define("MOVE_COUNTER",3);
define("DELETE_COUNTERS",4);
define("REMOVE_COUNTERS",5);
define("GAME_OVER",6);

// unit status
define("STATUS_NONE",0);
define("STATUS_READY",1);
define("STATUS_CAN_REINFORCE",2);
define("STATUS_REINFORCING",3);
define("STATUS_MOVING",4);
define("STATUS_STOPPED",6);
define("STATUS_DEFENDING",8);
define("STATUS_ATTACKING",9);
define("STATUS_NO_RESULT",10);
define("STATUS_DEFENDED",11);
define("STATUS_ATTACKED",12);
define("STATUS_CAN_RETREAT",13);
define("STATUS_RETREATING",14);
define("STATUS_RETREATED",15);
define("STATUS_CAN_ADVANCE",16);
define("STATUS_ADVANCING",17);
define("STATUS_ADVANCED",18);
define("STATUS_DELETING",19);
define("STATUS_ELIMINATING",21);
define("STATUS_ELIMINATED",22);
define("STATUS_EXITING",23);
define("STATUS_EXITED",24);
define("STATUS_CAN_EXCHANGE",27);
define("STATUS_EXCHANGED",28);
define("STATUS_REPLACED",29);
define("STATUS_CAN_REPLACE",30);
define("STATUS_CAN_UPGRADE",31);
define("STATUS_CAN_ATTACK_LOSE",32);
define("STATUS_BOMBARDING",33);
define("STATUS_UNAVAIL_THIS_PHASE",34);
define("STATUS_CAN_DEPLOY",35);
define("STATUS_DEPLOYING",36);
define("STATUS_REPLACING",37);
define("STATUS_COMBINED",38);
define("STATUS_CAN_COMBINE",39);
define("STATUS_CAN_DEFEND_LOSE",40);
define("STATUS_LOADING", 41);
define("STATUS_CAN_TRANSPORT", 42);
define("STATUS_UNLOADING", 43);
define("STATUS_CAN_UNLOAD", 44);
define("STATUS_CAN_LOAD", 45);
define("STATUS_MUST_ADVANCE", 46);
define("STATUS_FPF", 47);


// unit status names
$status_name = array();
$status_name[ 0] = " none";
$status_name[ 1] = " is ready";
$status_name[ 2] = " is ready to reinforce";
$status_name[ 3] = " is reinforcing";
$status_name[ 4] = " is moving";
$status_name[ 5] = " not moved";
$status_name[ 6] = " has stopped";
$status_name[ 7] = " has been removed";
$status_name[ 8] = " is defending";
$status_name[ 9] = " is attacking";
$status_name[10] = " no result";
$status_name[11] = " has defended";
$status_name[12] = " has attacked";
$status_name[13] = " can retreat";
$status_name[14] = " is retreating";
$status_name[15] = " has retreated";
$status_name[16] = " can advance";
$status_name[17] = " is advancing";
$status_name[18] = " has advanced";
$status_name[19] = " id being deleted";
$status_name[20] = " is deleted";
$status_name[21] = " is eliminating";
$status_name[22] = " has been eliminated";
$status_name[23] = " is exiting";
$status_name[24] = " has exited ";
$status_name[25] = " no more CRT to resolve";
$status_name[26] = " more CRT to resolve";

// Combat Results Table values
define("DE",0);
define("DRL",1);
define("DR",2);
define("EX",3);
define("NE",4);
define("AL",5);
define("AR",6);
define("AE",7);
define("DR2",8);
define("EX2",9);
define("DRL2",10);
define("DL",11);
define("EX0", 12);
define("EX02", 13);
define("EX03", 14);
define("DD",15);
define("P", 16);
define("W",17);
define("PW", 18);
define("MISS", 19);
define("H", 20);
define("HH", 21);
define("S", 22);
define("P2", 23);
define("S2", 24);
define("D", 25);
define("R", 26);
define("F", 27);
define("E", 28);
define("ALF", 29);
define("ALR", 30);
define("DLR", 31);
define("BL", 32);
define("BLDR", 33);
define("DLF", 34);
define("DEAL", 35);
define("AL2", 36);
define("DL2", 37);
define("DL2R", 38);
define("DL2F", 39);
define("AL2F", 40);
define("AL2R", 41);
define("L", 42);
define("L2", 43);
define("PIN", 44);
define("PANIC", 45);
define("D1", 46);
define("D2", 47);
define("D3", 48);
define("DL2AL", 49);
define("DL2AL2",50);
define("DLR2",51);
define("HALFE", 52);
define("DR1", 53);
define("DR3", 55);
define("DR4", 56);
define("AR1", 57);
define("AR2", 58);
define("AR3", 59);
define("BR", 60);
define("AX", 61);


$results_name = array();
//results_name[DE] = "Defender eliminated";
//results_name[DR] = "Defender retreat";
//results_name[NR] = "No result";
//results_name[AR] = "Attacker retreat";
//results_name[AE] = "Attacker eliminated";
$results_name[DE] = "DE";
$results_name[DRL] = "DRL";
$results_name[DR] = "DR";
$results_name[EX] = "EX";
$results_name[NE] = "NE";
$results_name[AL] = "AL";
$results_name[AR] = "AR";
$results_name[AE] = "AE";
$results_name[DR2] = "DR";
$results_name[EX2] = "EX";
$results_name[DRL2] = "DRL2";
$results_name[DL] = "DL";
$results_name[EX0] = "EX";
$results_name[EX02] = "EX2";
$results_name[EX03] = "EX3";
$results_name[DD] = "DD";
$results_name[P] = "P";
$results_name[W] = "W";
$results_name[PW] = "PW";
$results_name[MISS] = ".";
$results_name[H] = 'H';
$results_name[HH] = '2H';
$results_name[S] = 'S';
$results_name[P2] = '2P';
$results_name[D] = 'D';
$results_name[R] = 'R';
$results_name[F] = 'F';
$results_name[E] = 'E';
$results_name[ALF] = 'ALF';
$results_name[ALR] = 'ALR';
$results_name[DLR] = 'DLR';
$results_name[BL] = 'BL';
$results_name[BLDR] = 'BL/DR';
$results_name[DLF] = 'DLF';
$results_name[DEAL] = 'DE/AL';
$results_name[AL2] = "AL2";
$results_name[DL2] = "DL2";
$results_name[DL2R] = "DL2R";
$results_name[DL2F] = "DL2F";
$results_name[AL2F] = "AL2F";
$results_name[AL2R] = "AL2R";
$results_name[L] = "L";
$results_name[L2] = "L2";
$results_name[PIN] = "Pin";
$results_name[PANIC] = "(P)";
$results_name[D1] = "D-1";
$results_name[D2] = "D-2";
$results_name[D3] = "D-3";
$results_name[DL2AL] = "DL2AL";
$results_name[DL2AL2] = "DL2AL2";
$results_name[DLR2] = DLR2;
$results_name[HALFE] = "½ E";
$results_name[DR1] = "DR-1";
$results_name[DR2] = "DR-2";
$results_name[DR3] = "DR-3";
$results_name[DR4] = "DR-4";
$results_name[AR1] = "AR-1";
$results_name[AR2] = "AR-2";
$results_name[AR3] = "AR-3";
$results_name[BR] = "BR";
$results_name[AX] = "AX";


// combat ratio
$combatRatio_name = array();
$combatRatio_name[0] = "";
$combatRatio_name[1] = " 1 to 2 ";
$combatRatio_name[2] = " 1 to 1 ";
$combatRatio_name[3] = " 2 to 1 ";
$combatRatio_name[4] = " 3 to 1 ";
$combatRatio_name[5] = " 4 to 1 ";
$combatRatio_name[6] = " greater than 5 to 1 ";

define("MAP",false);
define("NONE",-1);

// hexpart types
define("HEXAGON_CENTER",1);
define("BOTTOM_HEXSIDE",2);
define("LOWER_LEFT_HEXSIDE",3);
define("UPPER_LEFT_HEXSIDE",4);

function exception_error_handler($errno, $errstr, $errfile, $errline ) {
    if($errno != E_NOTICE){
        throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
    }
}
ini_set('display_errors',1);
set_error_handler('exception_error_handler');