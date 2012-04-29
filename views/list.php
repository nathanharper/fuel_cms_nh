<div id="list-search">
	<form action="/admin/list/<?php echo $table; ?>" method="POST">
		<table border="1px solid black" cellspacing="5" cellpadding="5" >
			<?php foreach($admin_config as $key => $props): ?>
				<?php if(!empty($props['search'])): ?>
					<?php if (!empty($search[$key])) $search_value = $search[$key]; else $search_value = false; ?>
					<tr>
						<td><?php echo $props['desc']; ?>: </td>
						<td>
							<?php call_user_func_array($props['type'].'::get_search', array($class,$key,$search_value)); ?>
						</td>
					</tr>
				<?php endif ?>
			<?php endforeach ?>
			<tr>
				<td><input type="submit" value="Search" /></td>
				<td>
					<input type="button" value="Clear" onclick="window.location = '/admin/list/<?php echo $table; ?>';" />
				</td>
			</tr>
		</table>
	</form>
</div>
<hr />
<div id="item-list">
	<a href="/admin/item/<?php echo $table; ?>?new=1" style="text-decoration:none;">
		<button>Create New <?php echo Inflector::camelize(Inflector::singularize($table)); ?></button>
	</a>
	<br />
	<?php if($msg): ?>
		<br />
		<span style="color:red;"><?php echo $msg; ?></span>
	<?php endif ?>
	<br />
	<table cellpadding="5" cellspacing="0" class="bordered">
		<tr class="table-header">
			<th>ID</th>
			<?php foreach($admin_config as $field_props): ?>
				<?php if(!empty($field_props['list'])): ?>
					<th><?php echo $field_props['desc']; ?></th>
				<?php endif ?>
			<?php endforeach ?>
			<th></th>
		</tr>
		<?php foreach($models as $model): ?>
			<tr>
				<td><?php echo $model->id; ?></td>
				<?php foreach($admin_config as $field_name => $field_props): ?>
					<?php if(!empty($field_props['list'])): ?>
						<td><?php echo $admin_fields[$model->id][$field_name]->list_view(); ?></td>
					<?php endif ?>
				<?php endforeach ?>
				<td><a href="/admin/item/<?php echo $table; ?>?id=<?php echo $model->id; ?>">edit</a></td>
			</tr>
		<?php endforeach ?>
	</table>
</div>
<br /><br /><br />