<?php
/**
 * Created by PhpStorm.
 * User: david
 * Date: 3/8/17
 * Time: 9:30 AM
 *
 * /*
 * Copyright 2012-2017 David Rodal
 * This program is free software; you can redistribute it
 * and/or modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation;
 * either version 2 of the License, or (at your option) any later version
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Wargame;


interface TransportMoveRules
{
    public function loadUnit();
    function stopUnloading(TransportableUnit $unit);
    function stopLoading(TransportableUnit $unit);
    function transport(TransportableUnit $unit);
    function unload(TransportableUnit $movingUnit, $hexagon);

}