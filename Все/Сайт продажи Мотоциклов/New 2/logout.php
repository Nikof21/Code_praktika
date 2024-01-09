<?php
session_start();

session_destroy();

header("Location: glav.html");
exit();
?>
