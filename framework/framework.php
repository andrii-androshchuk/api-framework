<?php

/**
 * 	This file makes include all of kernel files that is need to run the application.
 * 	You do not need edit it, but just include it in your index file.
 * 
 *  @author Androschuk Andriy <androschuk.andriy@gmail.com> 
 * 	@copyright 2013 (c) Androschuk Andriy
 *
 *  @version 0.1
 */

require_once "kernel/apiframework.class.php";
require_once "log/log.class.php";
require_once "configuration/configuration.class.php";
require_once "plugins/plugins.class.php";
require_once "database/database.connections.class.php";
require_once "database/database.connection.class.php";
require_once "database/database.query.class.php";
require_once "database/connection.class.php";
require_once "registry/registry.class.php";
require_once "controller/available.controllers.class.php";
require_once "controller/controller.class.php";
require_once "view/view.class.php";
require_once "model/use.model.class.php";
require_once "model/model.class.php";

require_once "register.php";