<?php
namespace dynoser\catshop;

require_once 'SetupDB.php';
require_once 'Config.php';

$conf = new Config(true);

// Random images generate:
//require_once 'imagesgen.php';

// create database-setup object
$setup = new SetupDB($conf);

// Inicialize database with parameters from config
$setup->init();

echo "Complete\n";
