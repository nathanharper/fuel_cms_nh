<form name="login" method="POST" action="/admin">
	<?php if(isset($msg)): ?>
		<p style="color:red;"><?php echo $msg; ?></p>
	<?php endif ?>
	<table>
		<tr><td>Username:</td><td><input type="text" name="username" /></td></tr>
		<tr><td>Password:</td><td><input type="password" name="password" /></td></tr>
		<tr><td colspan="2"><input type="submit" value="submit" name="submit_login" /></td></tr>
	</table>
</form>

