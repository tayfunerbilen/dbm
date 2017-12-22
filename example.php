<?php

# Sınıfı dahil et
require __DIR__ . '/dbm.class.php';

# Sınıfı başlat
$dbm = new Dbm('db_username', 'db_pass');

# Veritabanı oluştur
$dbm->database('test_db')
    ->create();
  
# Oluşturulan Veritabanını kullan
$dbm->use('test_db');

# Tablo oluştur
$dbm->table('users')
    ->columns([
        $dbm->column('id')->int()->ai()->add(),
        $dbm->column('name')->varchar(255)->add(),
        $dbm->column('about')->text()->add()
    ])
    ->create();
