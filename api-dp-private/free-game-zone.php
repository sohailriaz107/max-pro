<?php
declare(strict_types=1);

header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

// File paths
$main_file  = __DIR__ . '/../txtdb/free-game.txt';
$extra_file = __DIR__ . '/../txtdb/other-free-game.txt';

// Allowed game names
$free_game_market_names = [
    "MILAN MORNING",
    "KALYAN MORNING",
    "TIME BAZAR",
    "MILAN DAY",
    "KALYAN",
    "MILAN NIGHT",
    "KALYAN NIGHT",
    "RAJDHANI NIGHT",
    "MAIN BAZAR",
    "PUNA BAZAR",
    "SRIDEVI NIGHT",
    "MAIN BAZAR MORNING",
    "PADMAVATI",
    "KARNATAKA DAY",
    "SRIDEVI",
    "PRABHAT",
    "NEW TIME BAZAR"
];

$free_game = [];
$date = '';

function explode_values(string $str): array {
    $trimmed = trim($str);
    if ($trimmed === '') {
        return [];
    }
    return explode('-', $trimmed);
}

function is_game_allowed(string $game, array $allowed_list): bool {
    return in_array(strtoupper(trim($game)), array_map('strtoupper', $allowed_list), true);
}

// Read main file (free-game.txt)
if (file_exists($main_file) && is_readable($main_file)) {
    $lines = file($main_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        $total = count($lines);
        for ($i = 0; $i < $total; $i++) {
            $line = trim((string)$lines[$i]);
            if ($line === '') {
                continue;
            }

            // If it's a date
            if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $line)) {
                $date = $line;
            } else {
                $game = $line;
                if (!is_game_allowed($game, $free_game_market_names)) {
                    $i += 3;
                    continue;
                }

                $ank   = isset($lines[$i + 1]) ? explode_values((string)$lines[$i + 1]) : [];
                $patti = isset($lines[$i + 2]) ? explode_values((string)$lines[$i + 2]) : [];
                $jodi  = isset($lines[$i + 3]) ? explode_values((string)$lines[$i + 3]) : [];

                $free_game[] = [
                    'game'  => $game,
                    'ank'   => $ank,
                    'patti' => $patti,
                    'jodi'  => $jodi
                ];

                $i += 3;
            }
        }
    }
}

// Read other file (other-free-game.txt)
if (file_exists($extra_file) && is_readable($extra_file)) {
    $lines = file($extra_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if (is_array($lines)) {
        foreach ($lines as $line) {
            $lineStr = trim((string)$line);
            if ($lineStr === '') {
                continue;
            }

            $parts = explode('|', $lineStr);
            if (count($parts) >= 4) {
                $game = trim((string)$parts[0]);
                if (!is_game_allowed($game, $free_game_market_names)) {
                    continue;
                }

                $ank   = explode_values((string)$parts[1]);
                $patti = explode_values((string)$parts[2]);
                $jodi  = explode_values((string)$parts[3]);

                $free_game[] = [
                    'game'  => $game,
                    'ank'   => $ank,
                    'patti' => $patti,
                    'jodi'  => $jodi
                ];
            }
        }
    }
}

echo json_encode([
    'status'    => true,
    'date'      => $date,
    'free_game' => $free_game
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
