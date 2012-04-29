<p>welcome to item view.</p>
<a href="/admin/list/<?php echo $table; ?>">back to list</a>
<br /><br />
<span style="color:green;"><?php if (isset($success)) echo $success; ?></span>

<form action="/admin/item/<?php echo $table.'?'; if ($item->id) echo 'id='.$item->id; else echo 'new=1'; ?>" method="POST" name="save_item" enctype="multipart/form-data">
<table border="0" cellspacing="5" cellpadding="5">
	<?php foreach($item_config as $field): ?>
		<tr>
			<td valign="top"><?php echo $field['desc']; ?>: </td>
			<td><?php echo $field['type']->item_view(); ?></td>
		</tr>
	<?php endforeach ?>
	<tr>
		<td colspan="2">
			<input type="submit" value="save" name="save_item" />
			<input type="button" value="cancel" onclick="window.location = '/admin/list/<?php echo $table; ?>';" />
			<?php if($item->id): ?>
				<input type="submit" value="delete" name="delete" onclick="alert('Are you sure you want to delete this record?');"/>
			<?php endif ?>
		</td>
	</tr>
</table>
</form>
<br /><br /><br />