<?
$data->extractTo(ROOT);

// Add triggers support
APP::Module('Triggers')->Register('add_ssh_connection', 'SSH', 'Add connection');
APP::Module('Triggers')->Register('remove_ssh_connection', 'SSH', 'Remove connection');
APP::Module('Triggers')->Register('update_ssh_connection', 'SSH', 'Update connection');