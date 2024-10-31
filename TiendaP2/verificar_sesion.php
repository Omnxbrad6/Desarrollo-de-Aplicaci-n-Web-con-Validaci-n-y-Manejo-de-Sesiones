<?php
session_start();

if (!isset($_SESSION['USER'])) {
    http_response_code(403); // Prohibido
    exit();
}
