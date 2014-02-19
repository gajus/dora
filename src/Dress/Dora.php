<?php
namespace Gajus\Dora\Dress;

/**
 * @link https://github.com/gajus/dora for the canonical source repository
 * @license https://github.com/gajus/dora/blob/master/LICENSE BSD 3-Clause
 */
class Dora extends Manikin {
	public function toString () {
		$input = $this->getInput();
		$input_id = $input->getAttribute('id');
		$input_string = $input->toString();
		$errors = [];
		$description = $input->getProperty('description');		

		ob_start();?>
		<div class="dora-input">
			<label for="<?=$input_id?>"><?=$input->getProperty('name')?></label>
			<div class="input">
				<?=$input_string?>
			</div>
			<?php if ($description):?>
			<div class="description">
				<p><?=$description?></p>
			</div>
			<?php endif;?>
			<?php if ($errors):?>
			<ul class="dora-error">
				<li><?=implode('</li><li>', $errors)?></li>
			</ul>
			<?php endif;?>
		</div>
		<?php
		return ob_get_clean();
	}
}