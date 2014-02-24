<?php
namespace Gajus\Dora;

/**
 * Container for DI.
 * 
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
class Former {
	public function form (array $default_data = null, array $input = null) {
		$form = new Form($default_data, $input);

		return new Dress($form);
	}
}