<?
$data->extractTo(ROOT);

// Add triggers support
APP::Module('Triggers')->Register('import_locale_module', 'Admin', 'Import locale module');
APP::Module('Triggers')->Register('remove_imported_module', 'Admin', 'Remove imported module');
APP::Module('Triggers')->Register('export_module', 'Admin', 'Export module');
APP::Module('Triggers')->Register('uninstall_module', 'Admin', 'Uninstall module');