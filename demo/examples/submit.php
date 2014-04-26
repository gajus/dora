<?php
$form = new \Gajus\Dora\Form($_POST);

// Note that "bar" input has a default value:
// $form->input('bar', ['value' => mt_rand(1000,9999)])
// However, when you submit the form, this value is overwritten.

// Form signature is used to generate UID and CSRF tokens.
// UID is used to identify instance of the form that's submitted.
// Form signature is generated using $form->sign().

if ($form->isSubmitted()) {
    header('Location: ' . $_SERVER['REQUEST_URI']);
    
    exit;
}
?>
<form action="" method="post">
    <?=$form->input('foo')?>
    <?=$form->input('bar', ['value' => mt_rand(1000,9999)])?>
    
    <div class="button-group">
        <?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'], null, null)?>
    </div>

    <?=$form->sign()?>
</form>