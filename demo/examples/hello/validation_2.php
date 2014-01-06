<?php
$form = new \ay\thorax\Form();

$rule = $form->addRule(['first_name'], 'ay\thorax\rule\Is_Eq_A');

$label = $form->addLabel();
?>
<form action="" method="post">
	<?=$label->input('first_name', null, ['label' => 'Custom Label'])?>
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
</form>
<?php
if ($form->isSubmitted()) {
	ay( $form->isError() );
}