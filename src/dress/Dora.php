<?php
namespace gajus\dora\dress;

class Dora extends Manikin {
	public function toString () {
		$input = $this->getInput();
		$input_id = $input->getAttribute('id');
		$input_string = $input->toString();
		$errors = [];

		ob_start();?>
		<div class="dora-input">
			<label for="<?=$input_id?>"><?=$input->getProperty('name')?></label>
			<?=$input_string?>
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