<?php
require '_bootstrap.php';
session_destroy();
echo json_encode(['success' => true]);