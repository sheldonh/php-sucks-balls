<?php

require 'collection.php';

class CollectionTest extends PHPUnit_Framework_TestCase {

	public function testInstantiates() {
		$it = new Collection();
		$this->assertEquals(0, $it->size());
	}

	public function testInstantiatesFromArray() {
		$seed = array();
		$it = new Collection($seed);
		$this->assertEquals($seed, $it->to_a());
	}

	public function testIteratesOverElements() {
		$expected = array('foo', 'bar');
		$it = new Collection($expected);
		$received = array();
		$it->each(function($o) use (&$received) {
			$received[] = $o;
		});
		$this->assertEquals($expected, $received);
	}

	public function testMap() {
		$expected = new Collection(array('FOO', 'BAR'));
		$it = new Collection(array('foo', 'bar'));
		$returned = $it->map(function($o) {
			return strtoupper($o);
		});
		$this->assertEquals($expected, $returned);
	}

	public function testIncludes() {
		$it = new Collection(array('foo', '0'));
		$this->assertTrue($it->includes('foo'));
		$this->assertFalse($it->includes(0));
	}

	public function testSelect() {
		$expected = new Collection(array('bar', 'baz'));
		$it = new Collection(array('foo', 'bar', 'baz'));
		$returned = $it->select(function($o) {
			return strpos($o, 'b') !== false;
		});
		$this->assertEquals($expected, $returned);
	}

	public function testReject() {
		$expected = new Collection(array('foo'));
		$it = new Collection(array('foo', 'bar', 'baz'));
		$returned = $it->reject(function($o) {
			return strpos($o, 'b') !== false;
		});
		$this->assertEquals($expected, $returned);
	}

	public function testSize() {
		$it = new Collection(array('foo', 'bar'));
		$this->assertEquals(2, $it->size());
	}

	public function testFirst() {
		$it = new Collection(array('foo', 'bar'));
		$this->assertEquals('foo', $it->first());
	}

	public function testFirstIsNullIfEmpty() {
		$it = new Collection();
		$this->assertNull($it->first());
	}

	public function testLast() {
		$it = new Collection(array('foo', 'bar'));
		$this->assertEquals('bar', $it->last());
	}

	public function testLastIsNullIfEmpty() {
		$it = new Collection();
		$this->assertNull($it->last());
	}

	public function testExposesOnlyCloneOfInternalArray() {
		$it = new Collection(array('foo', 'bar'));
		$internal = $it->to_a();
		unset($internal[1]);
		$this->assertEquals('bar', $it->last());
	}

	public function testAt() {
		$it = new Collection(array('foo', 'bar'));
		$this->assertEquals('foo', $it->at(0));
		$this->assertEquals('bar', $it->at(1));
	}

	public function testAtTakesNegativeIndex() {
		$it = new Collection(array('foo', 'bar'));
		$this->assertEquals('foo', $it->at(-2));
	}

	public function testAtIsNullIfMissing() {
		$it = new Collection();
		$this->assertNull($it->at(-1));
	}

	public function testPush() {
		$it = new Collection(array('foo', 'bar'));
		$returned = $it->push('baz');
		$this->assertEquals('baz', $it->at(2));
		$this->assertEquals($it, $returned);
	}

	public function testUnshift() {
		$it = new Collection(array('foo', 'bar'));
		$returned = $it->unshift('baz');
		$this->assertEquals($it, $returned);
		$this->assertEquals('baz', $it->at(0));
	}

	public function testShift() {
		$it = new Collection(array('foo', 'bar'));
		$returned = $it->shift();
		$this->assertEquals('foo', $returned);
		$this->assertEquals(1, $it->size());
	}

	public function testPop() {
		$it = new Collection(array('foo', 'bar'));
		$returned = $it->pop();
		$this->assertEquals('bar', $returned);
		$this->assertEquals(1, $it->size());
	}

	public function testDeleteAt() {
		$it = new Collection(array('foo', 'bar', 'baz'));
		$returned = $it->delete_at(1);
		$this->assertEquals('bar', $returned);
		$this->assertEquals('baz', $it->at(1));
	}

	public function testDeleteAtIsNullIfMissing() {
		$it = new Collection();
		$this->assertEquals(null, $it->delete_at(0));
	}

	public function testDeleteAtnegativeIndex() {
		$it = new Collection(array('foo', 'bar', 'baz'));
		$returned = $it->delete_at(-1);
		$this->assertEquals('baz', $returned);
		$this->assertEquals('bar', $it->at(-1));
	}

	public function testInsertAt() {
		$it = new Collection(array('foo', 'baz'));
		$returned = $it->insert_at(1, 'bar');
		$this->assertEquals(new Collection(array('foo', 'bar', 'baz')), $it);
		$this->assertEquals($it, $returned);
	}

	public function testInsertBeyondEnd() {
		$it = new Collection(array('foo'));
		$returned = $it->insert_at(3, 'baz');
		$this->assertEquals(new Collection(array('foo', null, null, 'baz')), $it);
	}

	public function testSort() {
		$it = new Collection(array('foo', 'bar', 'baz'));
		$returned = $it->sort();
		$this->assertEquals(array('foo', 'bar', 'baz'), $it->to_a());
		$this->assertEquals(array('bar', 'baz', 'foo'), $returned->to_a());
	}

	public function testSortInPlace() {
		$it = new Collection(array('foo', 'bar', 'baz'));
		$returned = $it->sort($inplace = true);
		$this->assertEquals(array('bar', 'baz', 'foo'), $it->to_a());
		$this->assertEquals($it, $returned);
	}

	public function testSortBy() {
		$it = new Collection(array('foo', 'bar', 'baz'));
		$returned = $it->sort_by(function($a, $b) {
			return $a === $b ? 0 : ($b < $a ? -1 : 1);
		});
		$this->assertEquals(array('foo', 'bar', 'baz'), $it->to_a());
		$this->assertEquals(array('bar', 'baz', 'foo'), $returned->to_a());
	}

	public function testSortByInPlace() {
		$it = new Collection(array('foo', 'bar', 'baz'));
		$returned = $it->sort_by(function($a, $b) {
			return $a === $b ? 0 : ($b < $a ? -1 : 1);
		}, $inplace = true);
		$this->assertEquals(array('foo', 'baz', 'bar'), $it->to_a());
		$this->assertEquals($it, $returned);
	}

}
?>
