<?
APP::Module('DB')->Open(APP::Module('Mail')->conf['connection'])->query('DROP TABLE registry');