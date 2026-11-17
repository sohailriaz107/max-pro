<?php
declare(strict_types=1);

header("Content-Type: application/json; charset=UTF-8");
header("X-Content-Type-Options: nosniff");
http_response_code(403);

echo json_encode([
    'status' => false,
    'msg' => 'Access Denied'
], JSON_UNESCAPED_UNICODE);
