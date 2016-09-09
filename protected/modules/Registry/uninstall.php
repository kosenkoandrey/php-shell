<?
APP::Module('DB')->Open(APP::Module('Registry')->conf['connection'])->query('DROP TABLE registry');