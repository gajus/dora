<?php
$form = new \ay\thorax\Form(['foo' => 'Default foo value']);
?>
<form action="" method="post">
	<?=$form->input('foo')?>
	<?=$form->input('bar', ['value' => mt_rand(1000,9999)])?>
	
	<div class="thorax-buttons">
		<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
	</div>
</form>
<?php
if ($form->isSubmitted()) {
	header('Location: ' . $_SERVER['REQUEST_URI']);
	
	exit;
}