<?php
header('Content-Type: application/json');

// File paths
$main_file  = '../txtdb/free-game.txt';
$extra_file = '../txtdb/other-free-game.txt';

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

// Helper: convert hyphen-separated string to array
function explode_values($str) {
    return explode('-', $str);
}

// Helper: check if game is allowed
function is_game_allowed($game, $allowed_list) {
    return in_array(strtoupper(trim($game)), array_map('strtoupper', $allowed_list));
}

// Read main file (free-game.txt)
if (file_exists($main_file)) {
    $lines = file($main_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    for ($i = 0; $i < count($lines); $i++) {
        $line = trim($lines[$i]);

        // If it's a date
        if (preg_match('/^\d{2}\/\d{2}\/\d{4}$/', $line)) {
            $date = $line;
        } else {
            $game  = $line;
            if (!is_game_allowed($game, $free_game_market_names)) {
                $i += 3; // skip ank, patti, jodi lines
                continue;
            }

            $ank   = isset($lines[$i + 1]) ? explode_values($lines[$i + 1]) : [];
            $patti = isset($lines[$i + 2]) ? explode_values($lines[$i + 2]) : [];
            $jodi  = isset($lines[$i + 3]) ? explode_values($lines[$i + 3]) : [];

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

// Read other file (other-free-game.txt)
if (file_exists($extra_file)) {
    $lines = file($extra_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    foreach ($lines as $line) {
        $line = trim($line);
        if (!empty($line)) {
            $parts = explode('|', $line);
            if (count($parts) >= 4) {
                $game  = trim($parts[0]);
                if (!is_game_allowed($game, $free_game_market_names)) {
                    continue;
                }

                $ank   = explode_values($parts[1]);
                $patti = explode_values($parts[2]);
                $jodi  = explode_values($parts[3]);

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

// Output JSON
echo json_encode([
    'status'    => true,
    'date'      => $date,
    'free_game' => $free_game
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
?>
