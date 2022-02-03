<?php
use \Wargame\Mollwitz\Klissow1702\Klissow1702;
use \Wargame\MapViewer;
use \Wargame\Battle;
class GameRulesTest extends PHPUnit\Framework\TestCase
{
    public $mapViewer;
	public function testThis(){

		$this->assertEquals(0,0);
        $klissow = new Klissow1702();
        $this->assertNotEmpty($klissow);
        $this->assertNotEmpty($klissow->gameRules);
        $this->assertNotEmpty($klissow->force);
        $this->assertNotEmpty($klissow->combatRules);
        $this->assertNotEmpty($klissow->moveRules);
        $mapViewer =  new MapViewer();
//        Battle::$class = $klissow;
//        $klissow->gameRules->phase = BLUE_MOVE_PHASE;
//        $klissow->gameRules->turn = 1;
//        $klissow->victory->victoryPoints[1] = 35;
//        $klissow->victory->victoryPoints[2] = 40;
//        $klissow->victory->playerTurnChange(2);
//        $this->assertEquals(is_object($mapViewer), true);
////        $this->assertEquals(is_object($klissow), true);
//        $mapViewer->setData(1,2,3,4,5,6);
//        $this->assertEquals($mapViewer->originX, 1);
//        $this->assertEquals($mapViewer->originY, 2);
//        $this->assertEquals($mapViewer->topHeight, 3);
//        $this->assertEquals($mapViewer->bottomHeight, 4);
//        $this->assertEquals($mapViewer->hexsideWidth, 5);
//        $this->assertEquals($mapViewer->centerWidth, 6);
    }
}
