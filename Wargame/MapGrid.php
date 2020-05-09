<?php
namespace Wargame;
//  MapGrid
//
// Copyright (c) 1998-2011 Mark Butler
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

// MapGrid Constructor
class MapGrid
{

    public $originX;
    public $originY;
    public $topHeight;
    public $bottomHeight;
    public $hexsideWidth;
    public $centerWidth;
    public $hexagonWidth;
    public $hexagonHeight;
    public $halfHexagonHeight;
    public $halfHexagonWidth;
    public $oneFourthHexagonHeight;
    public $leftMapEdge;
    public $trueRows;
    public $mirror = false;

    // pixel info from screen
    public $mapGridX, $mapGridY;
    public $distanceFromLeftEdgeOfHexagon;
    public $distanceFromTopEdgeOfHexagon;
    public $column, $row;

    // hexagon and it's hexpart
    public $hexagon;
    public $hexpart;

    /* @param MapViewer $mapData */
    function __construct($mapData)
    {
        $this->originX = $mapData->originX;
        $this->originY = $mapData->originY;
        $this->topHeight = $mapData->topHeight;
        $this->bottomHeight = $mapData->bottomHeight;
        $this->hexsideWidth = $mapData->hexsideWidth;
        $this->centerWidth = $mapData->centerWidth;
        $this->mapWidth = $mapData->mapWidth;
        $this->mapHeight = $mapData->mapHeight ?? false;
        $this->trueRows = $mapData->trueRows;
        $this->mirror = $mapData->mirror ?? false;

        $this->hexagonHeight = $this->topHeight + $this->bottomHeight;
        $this->hexagonWidth = $this->hexsideWidth + $this->centerWidth;
        $this->halfHexagonHeight = $this->hexagonHeight / 2;
        $this->halfHexagonWidth = $this->hexagonWidth / 2;
        $this->oneFourthHexagonHeight = $this->hexagonHeight / 4;
        $this->leftMapEdge = -($this->hexsideWidth + ($this->centerWidth / 2));

        $this->hexagon = new Hexagon();
        $this->hexpart = new Hexpart();
    }

    function setPixels($pixelX, $pixelY)
    {

        $this->calculateHexpartFromPixels($pixelX, $pixelY);
        $this->calculateHexagonFromPixels();
    }

    function setHexagonXY($x, $y)
    {

        $this->setHexpartXY($x, $y);
    }

    function setHexpartXY($x, $y)
    {

        $this->mapGridX = ($this->halfHexagonWidth * $x) - $this->originX;
        $this->mapGridY = ($this->oneFourthHexagonHeight * $y) - $this->originY;
        $this->hexpart->setXY($x, $y);
    }

    function calculateHexpartFromPixels($pixelX, $pixelY)
    {

        //  var hexpart, hexpartY;

        // adjust for hexagonGrid origin
        $this->mapGridX = $pixelX + $this->originX;
        $this->mapGridY = $pixelY + $this->originY;

        $this->column = floor(($this->mapGridX - $this->leftMapEdge) / $this->hexagonWidth);
        $this->distanceFromLeftEdgeOfHexagon = ($this->mapGridX - $this->leftMapEdge) - ($this->column * $this->hexagonWidth);

        if ($this->distanceFromLeftEdgeOfHexagon < $this->hexsideWidth) {

            //  it's a / or \ hexside
            $hexpartX = (2 * $this->column) - 1;
            $this->row = floor($this->mapGridY / $this->halfHexagonHeight);
            $hexpartY = (2 * $this->row) + 1;
            $this->distanceFromTopEdgeOfHexagon = $this->mapGridY - ($this->row * $this->topHeight);
        } else {

            // it's a center or lower hexside
            $hexpartX = 2 * ($this->column);
            $this->mapGridY = $this->mapGridY + $this->oneFourthHexagonHeight;
            $this->row = floor($this->mapGridY / $this->halfHexagonHeight);
            $hexpartY = (2 * $this->row);
            $this->distanceFromTopEdgeOfHexagon = $this->mapGridY - ($this->row * $this->topHeight);
        }
        $this->hexpart->setXY($hexpartX, $hexpartY);
    }

    function calculateHexagonFromPixels()
    {

        //    var hexpartX, hexpartY, hexpartType;

        $hexpartX = $this->hexpart->getX();
        $hexpartY = $this->hexpart->getY();
        $hexpartType = $this->hexpart->getHexpartType();

        switch ($hexpartType) {
            case 1:
                $this->hexagon->setXY($hexpartX, $hexpartY);
                break;

            case 2:
                if ($this->distanceFromTopEdgeOfHexagon < $this->oneFourthHexagonHeight) {
                    $this->hexagon->setXY($hexpartX, $hexpartY - 2);
                } else {
                    $this->hexagon->setXY($hexpartX, $hexpartY + 2);
                }
                break;

            case 3:
                // check the tangent of the hexside line with tangent of the mappoint
                //
                // the hexside line tangent is opposite / adjacent = $this->hexsideWidth / $this->topHeight
                // the mappoint tangent is opposite / adjacent =  $this->distanceFromLeftEdgeOfHexagon / $this->distanceFromTopEdgeOfHexagon
                //
                // is mappoint tangent <  line tangent ?
                // ($this->distanceFromLeftEdgeOfHexagon / $this->distanceFromTopEdgeOfHexagon) < ($this->hexsideWidth / $this->topHeight)
                //
                // multiply both sides by $this->topHeight
                // ($this->distanceFromLeftEdgeOfHexagon / $this->distanceFromTopEdgeOfHexagon) * $this->topHeight  < ($this->hexsideWidth )
                //
                // multiply both sides by $this->distanceFromTopEdgeOfHexagon
                // ($this->distanceFromLeftEdgeOfHexagon * $this->topHeight ) < ($this->distanceFromTopEdgeOfHexagon * $this->hexsideWidth)
                //

                if ($this->distanceFromLeftEdgeOfHexagon * $this->topHeight < $this->distanceFromTopEdgeOfHexagon * $this->hexsideWidth) {
                    //  ______
                    //  |\ |  |
                    //  | \|  |
                    //  |* |\ |
                    //  |__|_\|
                    //
                    $this->hexagon->setXY($hexpartX - 1, $hexpartY + 1);
                } else {
                    //  ______
                    //  |\ |  |
                    //  | \|* |
                    //  |  |\ |
                    //  |__|_\|
                    //
                    $this->hexagon->setXY($hexpartX + 1, $hexpartY - 1);
                }
                break;

            case 4:
                // check the tangent of the hexside line with tangent of the mappoint
                //
                // see above
                //

                if ($this->distanceFromLeftEdgeOfHexagon * $this->topHeight < $this->distanceFromTopEdgeOfHexagon * $this->hexsideWidth) {
                    //  ______
                    //  |  | /|
                    //  |* |/ |
                    //  | /|  |
                    //  |/_|_ |
                    //
                    $this->hexagon->setXY($hexpartX - 1, $hexpartY - 1);
                } else {
                    //  ______
                    //  |  | /|
                    //  |  |/ |
                    //  | /|* |
                    //  |/_|_ |
                    //
                    $this->hexagon->setXY($hexpartX + 1, $hexpartY + 1);
                }
                break;
        }
    }

    function getHexpart()
    {
        return $this->hexpart;
    }

    function getHexagon()
    {
        return $this->hexagon;
    }

    function getPixelX()
    {
        if($this->trueRows){
            if($this->mirror){
                return $this->mapHeight - $this->mapGridY;

            }
            return $this->mapGridY;
        }else{
            if($this->mirror){
                return $this->mapWidth - $this->mapGridX;

            }
            return $this->mapGridX;

        }
    }

    function getPixelY()
    {
        if($this->trueRows) {
            if($this->mirror){
                return $this->mapGridX;
            }
            return $this->mapWidth - $this->mapGridX;
        }else{
            if($this->mirror){
                return $this->mapHeight - $this->mapGridY;

            }
            return $this->mapGridY;

        }
    }
}