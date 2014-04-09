<?php
namespace Gajus\Dora\Dress;

/**
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
class Naked extends Manikin {
	public function toString () {
		return $this->getInput()->toString();
	}
}