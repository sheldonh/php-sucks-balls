<?php

/*
 * This class was an attempt to learn PHP by producing an object-oriented array wrapper that felt like Ruby's Array class.
 * As per the comments for the sort_by() method, I lost interest because PHP sucks balls in too many ways.
 */
class Collection {

	private $array;

	public function __construct($array = array()) {
		$this->array = $array;
	}

	public function at($index) {
		$size = sizeof($this->array);
		if ($index < 0) {
			$index += $size;
		}
		return ($size > 0 && $index < $size) ? $this->array[$index] : null;
	}

	public function delete_at($i) {
		$deleted = $this->at($i);
		array_splice($this->array, $i, 1);
		return $deleted;
	}

	public function each($block) {
		array_walk($this->array, $block);
	}

	public function first() {
		return $this->at(0);
	}

	public function includes($o) {
		return in_array($o, $this->array, $strict = true);
	}

	public function insert_at($i, $o) {
		$this->pad($i - $this->size(), null);
		array_splice($this->array, $i, 0, $o);
		return $this;
	}

	public function last() {
		# If values are unset() in $array without reindexing the array, this method of returning the last element will fail.
		return $this->at($this->size() - 1);
	}

	public function map($block) {
		$map = new Collection();
		$this->each(function($o) use (&$map, $block) {
			$map->push($block($o));
		});
		return $map;
	}

	public function pop() {
		return array_pop($this->array);
	}

	public function push($o) {
		$this->array[] = $o;
		return $this;
	}

	public function reject($block) {
		$selection = new Collection();
		$this->each(function($o) use (&$selection, $block) {
			if (!$block($o)) {
				$selection->push($o);
			}
		});
		return $selection;
	}

	public function select($block) {
		$selection = new Collection();
		$this->each(function($o) use (&$selection, $block) {
			if ($block($o)) {
				$selection->push($o);
			}
		});
		return $selection;
	}

	public function shift() {
		return array_shift($this->array);
	}

	public function size() {
		return sizeof($this->array);
	}

	public function sort($inplace = false) {
		if ($inplace) {
			sort($this->array);
			return $this;
		} else {
			$sorted = new Collection($this->array);
			return $sorted->sort(true);
		}
	}

	# This is the point at which I lost interest in learning PHP.  Not only is PHP ugly and inconsistent, but it's dog slow
	# and very hard to use efficiently.  When people ask "Why is Ruby so much slower than PHP?" they are asking the wrong
	# question. They should be asking "Why is Ruby so much slower than C?" for which there are reasonable and palatable
	# answers.  Many of PHP's C functions will be faster than Ruby's Ruby functions, but the speed of library functions
	# that don't do what I want means nothing to me.  What matters to me is how fast PHP is; when I want to write C code,
	# I'll write C code.
	#
	# I've learned enough about the language to dive into someone else's code if I need to. But I'd rather blow goats than
	# do this nonsense for money.
	#
	public function sort_by($block, $inplace = false) {
		if ($inplace) {
			usort($this->array, $block);
			return $this;
		} else {
			$sorted = new Collection($this->array);
			return $sorted->sort(true);
		}
	}

	public function to_a() {
		return $this->array;
	}

	public function unshift($o) {
		array_unshift($this->array, $o);
		return $this;
	}

	private function pad($count, $o = null) {
		for ($i = 0; $i < $count; $i++) {
			$this->array[] = null;
		}
	}

}

?>
