<?php
$form = new \ay\thorax\Form();

$label = $form->addLabel();

$rule = $form->addRule('test');

$rule->add(['foo'])
?>
<form action="" method="post">
	<?=$label->input('foo')?>
	<?=$label->input('bar')?>
	
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
	
	<script>
	<?=$form->exportRules()?>
	</script>
</form>
<?php
#ay(  );

/*if ($form->isSubmitted()) {
	header('Location: ' . $_SERVER['REQUEST_URI']);
}*/