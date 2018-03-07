<?php

// This is the database connection configuration.
return array(
	//'connectionString' => 'sqlite:'.dirname(__FILE__).'/../data/testdrive.db',
	// uncomment the following lines to use a MySQL database

	'connectionString' => 'mysql:host=localhost;dbname=deric',
	'tablePrefix'=>'ym_',
	'emulatePrepare' => true,
//	'username' => 'sisenapp_dcSK9c',
//	'password' => '4QDNmmq$@f5%Rtg',
    'username' => 'root',
	'password' => '',
	'charset' => 'utf8',
);