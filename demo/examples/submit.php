<?php
$form = new \gajus\dora\Form(['foo' => 'Default "foo" value']);

// Example temporarily removed

return;

if ($form->isSubmitted()) {
	header('Location: ' . $_SERVER['REQUEST_URI']);
	
	exit;
}
?>
<form action="#example-submit" method="post">
	<?=$form->input('foo')?>
	<?=$form->input('bar', ['value' => mt_rand(1000,9999)])?>
	
	<div class="dora-buttons">
		<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
	</div>
</form>