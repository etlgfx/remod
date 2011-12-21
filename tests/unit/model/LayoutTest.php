<?php

class LayoutTest extends PHPUnit_Framework_TestCase {

	public function testLayoutModule() {
		$this->assertNotNull($m = new LayoutModule(array()));
		$this->assertEquals(array(), $m->attrs);
		$this->assertNull($m->id);

		$this->assertNotNull($m = new LayoutModule(array('m:uuid' => 'value')));
		$this->assertEquals(array(), $m->attrs);
		$this->assertEquals($m->uuid, 'value');

		$this->assertNotNull($n = new LayoutModule(array('uuid' => 'value')));
		$this->assertEquals($n, $m);

		$this->assertNotNull($n = new LayoutModule(array('uuid' => 'bla', 'class' => 'className')));
		$this->assertEquals($n->attrs, array('class' => 'className'));
	}
}

?>
