<?
$data->extractTo(ROOT);

// Add triggers support
APP::Module('Registry')->Add('module_trigger_type', '["add_trigger", "Triggers", "Add trigger"]');
APP::Module('Registry')->Add('module_trigger_type', '["remove_trigger", "Triggers", "Remove trigger"]');
APP::Module('Registry')->Add('module_trigger_type', '["update_trigger", "Triggers", "Update trigger"]');