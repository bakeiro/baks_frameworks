<?php

//Config
require('config.php');

//Engine
require(SYSTEM . 'engine/Settings.php');
require(SYSTEM . 'engine/Url.php');
require(SYSTEM . 'engine/EngController.php');
require(SYSTEM . 'engine/Session.php');
//require(SYSTEM . 'engine/Security.php');
require(SYSTEM . "engine/Output.php");
require(SYSTEM . 'engine/Connection.php');
require(SYSTEM . 'engine/Util.php');
require(SYSTEM . 'engine/Errors.php');
require(SYSTEM . 'engine/Loader.php');
require(SYSTEM . 'engine/Model.php');
require(SYSTEM . 'engine/Controller.php');

//Bootstrap
require("config_data.php");
require(SYSTEM. "Start.php");

//Cache
Settings::set('cache_version', '1.0');

//Errors
Settings::Set("debug", true);
set_error_handler( array(new Errors(),"my_error_handler") ,E_ALL);
error_reporting(E_ALL);

//escape $_POST,$_GET,$_COOKIE
Util::cleanInput();

//Autoload composer libraries
require(SYSTEM."libraries/vendor/autoload.php");

//Execute controller
$Controller = new EngController();
$Controller->execController();

//FIXME: Somehow make the controller and model not callable if the index was not executed
//In opencart inherits from the controller class

//DB
Connection::$CONN->close();