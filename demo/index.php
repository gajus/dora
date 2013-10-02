<?php
set_include_path(__DIR__ . '/../../../../vendor');

spl_autoload_register();

session_start();

ob_start();

require __DIR__ . '/demo.php';

$example = function ($name, $example_label, $display = ['code', 'demo', 'markup']) {
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
				<pre><code class="language-php"><?=htmlspecialchars(file_get_contents(__DIR__ . '/examples/' . $name . '.php'))?></code></pre>
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
				<pre><code class="language-markup"><?=htmlspecialchars(clean_html_code($output))?></code></pre>
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
	
	<link href="static/js/prism/prism.css" rel="stylesheet">
	<link href="static/css/frontend.css" rel="stylesheet">
</head>
<body>
	<div id="header">
		<h1>Thorax (alpha)</h1>
		
		<a href="https://github.com/gajus/thorax" target="_blank" class="github">GitHub</a>
	</div>

	<div id="examples">
		<?=$example('hello/form_input', 'Form & input')?>
		<?=$example('hello/form_input_2', 'Form & input #2')?>
		<?=$example('hello/attributes', 'Manipulating attributes')?>
		<?=$example('hello/dynamic_attributes', 'Dynamic attributes')?>
		<?=$example('hello/arrays', 'Arrays')?>
		<?=$example('hello/templates', 'Templates')?>
		<?=$example('hello/templates_2', 'Templates #2')?>
		<?=$example('hello/submit', 'Submit')?>
		<?=$example('hello/validation', 'Validation')?>
		<?=$example('hello/validation_2', 'Validation #2')?>
		
		<?php /*
		
		
		
		
		<?=$example('hello/messages', 'Messages')?>
		
		<?=$example('hello/validation_2', 'Validation #2')?>
		<?=$example('hello/validation_3', 'Validation #3')?>*/?>
	</div>
	
	<script src="static/js/prism/prism.js"></script>
</body>
</html>
<?php
echo ob_get_clean();