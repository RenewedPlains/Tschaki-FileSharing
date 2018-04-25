<?php
session_start();
$upload_time = microtime(true) - $_SESSION['time'];
// You should of course probably also check if the upload was OK and all that ;)