<?
APP::Module('Registry')->Delete([['item', 'IN', ['module_mail_charset', 'module_mail_x_mailer']]]);

APP::Module('DB')->Open(APP::Module('Mail')->conf['connection'])->query('DROP TABLE letters');
APP::Module('DB')->Open(APP::Module('Mail')->conf['connection'])->query('DROP TABLE letters_groups');
APP::Module('DB')->Open(APP::Module('Mail')->conf['connection'])->query('DROP TABLE senders');
APP::Module('DB')->Open(APP::Module('Mail')->conf['connection'])->query('DROP TABLE senders_groups');