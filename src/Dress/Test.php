<?php
namespace Gajus\Dora\Dress;

/**
 * This dress is used for unit testing.
 * @todo Move to tests.
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
class Test extends Manikin {
	public function toString () {
		return $this->getInput()->getAttribute('id');
	}
}