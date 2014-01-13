<?php
set_include_path(__DIR__ . '/../src');

spl_autoload_register();

session_start();

ob_start();

$index = [];

$example = function ($name, $example_label, $display = ['code', 'demo', 'markup']) use (&$index) {
	$index[str_replace('/', '__', $name)] = $example_label;
	
	ob_start();
	require __DIR__ . '/examples/' . $name . '.php';
	$output = ob_get_clean();

	?>
	<div class="example" id="example-<?=str_replace('/', '__', $name)?>">
		<?php if (in_array('code', $display)):?>
		<div class="tab code">
			<div class="description">
				<h3><a href="#example-<?=str_replace('/', '__', $name)?>"><?=$example_label?></a></h3>
			</div>
		
			<div class="body">
				<pre><code class="language-php"><?=str_replace("\t", '    ', htmlspecialchars(file_get_contents(__DIR__ . '/examples/' . $name . '.php')))?></code></pre>
			</div>
		</div>
		<?php endif;?>
		
		
		<?php if (in_array('demo', $display)):?>
		<div class="tab demo">
			<div class="description"></div>
		
			<div class="body">
				<?=$output?>
			</div>
		</div>
		<?php endif;?>
		
		<?php if (in_array('markup', $display)):?>
		<div class="tab markup">
			<div class="description"></div>
		
			<div class="body">
				<pre><code class="language-markup"><?=htmlspecialchars($output)?></code></pre>
			</div>
		</div>
		<?php endif;?>
	</div>
	<?php
};
?>
<!DOCTYPE html>
<html>
<head>
	<script src="static/js/jquery-1.10.2.min.js"></script>
	<script src="static/js/frontend.js"></script>
	<script src="static/js/highlight/highlight.pack.js"></script>
	
	<script>
	hljs.initHighlightingOnLoad();
	</script>

	<link href="static/js/highlight/styles/default.css" rel="stylesheet">
	<link href="static/css/frontend.css" rel="stylesheet">
</head>
<body>
	<div id="examples">
		<?=$example('form_input', 'Form & input')?>
		<?=$example('input_name', 'Input name')?>
		<?=$example('attributes', 'Attributes')?>
		<?=$example('value_resolution', 'Value Resolution')?>
		<?=$example('templates', 'Templates')?>
		<?=$example('submit', 'Submit')?>
		<?php /*
		
		<?=$example('messages', 'Messages')?>*/?>
	</div>

	<div id="sidebar">
		<h1>Thorax</h1>
		<h2>Input validation – <br><a href="https://github.com/gajus/thorax" target="_blank">https://github.com/gajus/thorax</a></h2>

		<iframe src="http://ghbtns.com/github-btn.html?user=gajus&repo=thorax&type=watch&count=true" allowtransparency="true" frameborder="0" scrolling="0" width="110" height="20"></iframe>

		<ol class="nav">
			<?php foreach ($index as $id => $name):?>
			<li><?=$name?></li>
			<?php endforeach;?>
		</ol>
	</div>
</body>
</html>
<?php
echo ob_get_clean();