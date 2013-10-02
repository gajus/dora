<?php
$form = new \ay\thorax\Form();

$label = new \ay\thorax\Label($form, function ($input) {
	
	// Retrieve all entries for "tag-list". Inbox is input specific.
	$tags = $input->getInbox('tag-list');
	
	ob_start();
	?>
	<div class="thorax-row">
		<label for="<?=$input->getId()?>"><?=$input->getLabel()?></label>
		<?=$input?>
		<?php if ($tags):?>
		<ul class="demo-tags">
			<li><?=implode('</li><li>', $tags)?></li>
		</ul>
		<?php endif;?>
	</div>
	<?php
	return ob_get_clean();
});

if ($form->isSubmitted()) {
	foreach (array_filter(explode(',', $_POST['tag_input'])) as $tag) {
		// Append new "tag-list" value to the input[name="tag_input"] dataset.
		$form->send('tag_input', 'tag-list', htmlspecialchars($tag));
	}
}
?>
<form action="" method="post">
<?php
echo $label->input('tag_input');

echo $form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit']);
?>
</form>