<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head> 
	<title><?php echo Config::get('project_name', ''); ?> Admin</title>
	<?php 
		echo Asset::css($css);
		include($admin_css);
		echo Asset::js($js);
		include($admin_js); 
	?>
</head>
<body>
	<?php if(Auth::instance('SimpleAuth')->check()): ?>
		<form action="/admin" method="get">
			<input type="submit" value="Logout" name="logout" />
		</form>
		<br />
		<div id="admin-tabs">
			<?php foreach($tabs as $key => $val): ?>
				<div class="tab <?php if ($key == $table) echo 'active'; ?>">
					<a href="/admin/list/<?php echo $key; ?>"><b><?php echo Inflector::camelize($key); ?></b></a>
				</div>
			<?php endforeach ?>
		</div>
		<hr class="clearfix" />
	<?php endif ?>
	<?php echo $body; ?>
</body>
</html>