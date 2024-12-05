<?php

if (session_status() == PHP_SESSION_NONE) {
    session_start(); // start the session if not already started
}

session_unset(); // removes all session variables
header('Location: '."/");
