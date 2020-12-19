<?php

namespace Wargame;

use \stdClass;

// gameRules.js

// Copyright (c) 2009-2011 Mark Butler
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

use \Wargame\Battle;

class AreaPhaseChange
{

    /* added line here */
    public $phase, $mode, $phaseWillIncrementTurn;

    function __construct($data = null)
    {
        if ($data) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    function set($phase, $mode, bool $phaseWillIncrementTurn)
    {
        $this->phase = $phase;
        $this->mode = $mode;
        $this->phaseWillIncrementTurn = $phaseWillIncrementTurn;
    }
}