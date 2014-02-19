<?php
namespace demo;

$form = new \Gajus\Dora\Form();

// Using default template (dress):

$dress = new \Gajus\Dora\Dress($form);

echo $dress->input('foo');
echo $dress->input('bar');

// Define custom template:

class MyDress extends \Gajus\Dora\Dress\Manikin {
	public function toString () {
		$input = $this->getInput();

		ob_start();
		?>
		<div class="dora-input custom">
			<label for="<?=$input->getAttribute('id')?>"><?=$input->getProperty('name')?></label>
			<?=$input?>
		</div>
		<?php
		return ob_get_clean();
	}
}

$my_dress = new \Gajus\Dora\Dress($form, 'demo\MyDress');

echo $my_dress->input('baz', null, ['name' => 'Baz Custom Name']);
echo $my_dress->input('qux', null, ['name' => 'Qux Custom Name']);