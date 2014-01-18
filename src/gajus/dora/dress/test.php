<?php
namespace gajus\dora\dress;

/**
 * This dress is used for unit testing.
 */
class Test extends Manikin {
	public function toString () {
		return $this->getInput()->getAttribute('id');
	}
}