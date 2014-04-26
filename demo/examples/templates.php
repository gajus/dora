<?php
// Custom template must extend Gajus\Dora\Template

class MyTemplate extends \Gajus\Dora\Template {
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

$form = new \Gajus\Dora\Form(null, 'MyTemplate');

echo $form->input('baz', null, ['name' => 'Baz Custom Name']);
echo $form->input('qux', null, ['name' => 'Qux Custom Name']);

// You can change individual input template too.

echo $form->input('qux', null, ['name' => 'Qux Custom Name'], 'Gajus\Dora\Template\Traditional');