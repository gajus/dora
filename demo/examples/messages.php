<?php
$form = new \Gajus\Dora\Form();

$label = new \Gajus\Dora\Label($form, function ($input) {
	
	// Retrieve all entries for "tag-list". Inbox is input specific.
	// $tags = $input->getInbox('tag-list');
	
	ob_start();
	?>
	<div class="dora-input">
		<label for="<?=$input->getAttribute('id')?>"><?=$input->getProperty('label')?></label>
		<?=$input?>
		<?php /*if ($tags):?>
		<ul class="demo-tags">
			<li><?=implode('</li><li>', $tags)?></li>
		</ul>
		<?php endif;*/?>
	</div>
	<?php
	return ob_get_clean();
});
?>
<form action="" method="post">
	<?=$label->input('tag_input')?>
	<?=$form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit'])?>
</form>
<?php
if ($form->isSubmitted()) {
	foreach (array_filter(explode(',', $_POST['tag_input'])) as $tag) {
		// Append new "tag-list" value to the input[name="tag_input"] dataset.
		$form->send('tag_input', 'tag-list', htmlspecialchars($tag));
	}
}