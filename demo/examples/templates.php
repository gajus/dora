<?php
namespace demo;

$form = new \gajus\dora\Form();

// Using default template (dress):

$dress = new \gajus\dora\Dress($form);

echo $dress->input('foo');
echo $dress->input('bar');

// Define custom template:

class MyDress extends \gajus\dora\dress\Manikin {
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

$my_dress = new \gajus\dora\Dress($form, 'demo\MyDress');

echo $my_dress->input('baz', null, ['name' => 'Baz Custom Name']);
echo $my_dress->input('qux', null, ['name' => 'Qux Custom Name']);