<?php
namespace Gajus\Dora\Template;

/**
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
class Traditional extends \Gajus\Dora\Template {
	public function toString () {
		$input = $this->getInput();
		$input_id = $input->getAttribute('id');
		$description = $input->getProperty('description');

		$class = $input->getProperty('class');
		$class = $class ? ' ' . $class : '';

		ob_start();?>
		<div class="dora-input<?=$class?>">
			<label for="<?=$input_id?>"><?=$input->getProperty('name')?></label>
			<?=$input?>
			
			<?php if ($description):?>
			<div class="description">
				<p><?=$description?></p>
			</div>
			<?php endif;?>
		</div>
		<?php
		return ob_get_clean();
	}
}