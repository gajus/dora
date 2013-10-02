<?php
$form = new \ay\thorax\Form(['foo' => 'Default foo value']);

if ($form->isSubmitted()) {
	// It is common to redirect user in case of an error. By default,
	// Thorax will cache input value and display it on the next page.
	if (!isset($_POST['action']['remember'])) {
		$form->clearFlash();
	}
	
	header('Location: ' . $_SERVER['REQUEST_URI']);
	
	exit;
}
?>
<form action="" method="post">
<?php
echo $form->input('foo');
echo $form->input('bar', ['value' => mt_rand(1000,9999)]);

echo $form->input('action[remember]', ['type' => 'submit', 'value' => 'Remember']);
echo $form->input('action[submit]', ['type' => 'submit', 'value' => 'Submit']);
?>
</form>