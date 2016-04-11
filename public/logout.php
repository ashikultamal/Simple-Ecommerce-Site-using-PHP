<?php
require_once("../system/config.php");

session_start();
session_destroy();

redirect("index.php");