<?php
// Start a session only if it hasn't been started yet
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>
