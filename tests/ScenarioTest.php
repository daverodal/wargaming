<?php
class ScenarioTest extends PHPUnit_Framework_TestCase
{



	/**
	 * @dataProvider provider
	 */
	public function testThis($game, $class=false){
		if($class === false){
			$class = $game;
		}
		$this->assertEquals(0,0);
		$scenario = new \stdClass();
		$scenario->stepReduction = true;
		$class = "\\Wargame\\Mollwitz\\$game\\$class";
        $k = new $class(null, 'main', $scenario);
		$this->assertObjectHasAttribute('force', $k);
		$this->assertObjectHasAttribute('scenario', $k);
		$this->assertTrue($k->scenario->stepReduction);
		$k->fake = true;
		$ret = $class::getPlayerData($scenario);
		$this->assertArrayHasKey('forceName', $ret);
		$this->assertArrayHasKey('deployName', $ret);
		$this->assertObjectHasAttribute('fake',$k);
		$doc = $k->save();
		$this->assertObjectNotHasAttribute('fake',$doc);
		$this->assertTrue($doc->scenario->stepReduction);

		$k->gameRules->selectNextPhase(1);

	}

	public function provider(){
		return [
			['Klissow1702'],
			['Mollwitz'],
			['Ferozesha'],
			['Fraustadt1706'],
			['Brandywine1777'],
			['Fontenoy1745'],
			['Ferozesha','FerozeshaDayTwo'],
			['Gadebusch1712'],
			['Golymin1806'],
			['Goojerat1849'],
			['hanau1813'],
			['hastenbeck'],
			['Helsingborg1710'],
			['Hohenfriedeberg'],
			['Jagersdorf'],
			['Kesselsdorf1745'],
			['Klissow1702'],
		];

	}
}
