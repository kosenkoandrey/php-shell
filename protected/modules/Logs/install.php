<?
$data->extractTo(ROOT);

// Add triggers support
APP::Module('Triggers')->Register('remove_log_file', 'Logs', 'Remove log file');