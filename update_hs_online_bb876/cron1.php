<?php
declare(strict_types=1);

// ================================================
// HS ONLINE BB 52 – AUTO RESULT CRON (EVERY MINUTE)
// ================================================
date_default_timezone_set('Asia/Kolkata');
require_once '../config.php';

$TABLE = "hs_online_bb_52";
$txt_path = '../txtdb/hs_online_bb_52_results.txt';
$TOTAL_SLOTS = 48;
$slot_times = [
    "09:16","09:31","09:46",
    "10:01","10:16","10:31","10:46",
    "11:01","11:16","11:31","11:46",
    "12:01","12:16","12:31","12:46",
    "13:01","13:16","13:31","13:46",
    "14:01","14:16","14:31","14:46",
    "15:01","15:16","15:31","15:46",
    "16:01","16:16","16:31","16:46",
    "17:01","17:16","17:31","17:46",
    "18:01","18:16","18:31","18:46",
    "19:01","19:16","19:31","19:46",
    "20:01","20:16","20:31","20:46",
    "21:01"
];

// =====================
// ALL POSSIBLE RESULTS
// =====================
$possible_results = [
    // Base 10 results
    "000-0","111-3","222-6","333-9","444-2","555-5","666-8","777-1","888-4","999-7",
    // Block 1
    "128-1","137-1","146-1","236-1","245-1","290-1","380-1","470-1","489-1","560-1","579-1","678-1",
    // Block 2
    "129-2","138-2","147-2","156-2","237-2","246-2","345-2","390-2","480-2","570-2","589-2","679-2",
    // Block 3
    "120-3","139-3","148-3","157-3","238-3","247-3","256-3","346-3","490-3","580-3","670-3","689-3",
    // Block 4
    "130-4","149-4","158-4","167-4","239-4","248-4","257-4","347-4","356-4","590-4","680-4","789-4",
    // Block 5
    "140-5","159-5","168-5","230-5","249-5","258-5","267-5","348-5","357-5","456-5","690-5","780-5",
    // Block 6
    "123-6","150-6","169-6","178-6","240-6","259-6","268-6","349-6","358-6","367-6","457-6","790-6",
    // Block 7
    "124-7","160-7","179-7","250-7","269-7","278-7","340-7","359-7","368-7","458-7","467-7","890-7",
    // Block 8
    "125-8","134-8","170-8","189-8","260-8","279-8","350-8","369-8","378-8","459-8","468-8","567-8",
    // Block 9
    "126-9","135-9","180-9","234-9","270-9","289-9","360-9","379-9","450-9","469-9","478-9","568-9",
    // Block 0
    "127-0","136-0","145-0","190-0","235-0","280-0","370-0","389-0","460-0","479-0","569-0","578-0",
    // Second set
    "100-1","119-1","155-1","227-1","335-1","344-1","399-1","588-1","669-1",
    "110-2","200-2","228-2","255-2","336-2","499-2","660-2","688-2","778-2",
    "166-3","229-3","300-3","337-3","355-3","445-3","599-3","779-3","788-3",
    "112-4","220-4","266-4","338-4","400-4","446-4","455-4","699-4","770-4",
    "113-5","122-5","177-5","339-5","366-5","447-5","500-5","799-5","889-5",
    "114-6","277-6","330-6","448-6","466-6","556-6","600-6","880-6","899-6",
    "115-7","133-7","188-7","223-7","377-7","449-7","557-7","566-7","700-7",
    "116-8","224-8","233-8","288-8","440-8","477-8","558-8","800-8","990-8",
    "117-9","144-9","199-9","225-9","388-9","559-9","577-9","667-9","900-9",
    "118-0","226-0","244-0","299-0","334-0","488-0","550-0","668-0","677-0"
];

// ================================================
// STEP A: AUTO-FILL MISSING DATES (DB ONLY)
// ================================================
$today = date("Y-m-d");

$last_res = mysqli_query($con, "SELECT game_date FROM hs_online_bb_52 ORDER BY game_date DESC LIMIT 1");
$last_date = ($last_res && mysqli_num_rows($last_res) > 0) ? (string)mysqli_fetch_assoc($last_res)['game_date'] : null;
if ($last_res) {
    mysqli_free_result($last_res);
}

if ($last_date !== null && $last_date !== '' && $last_date < $today) {
    $d = date("Y-m-d", (int)strtotime($last_date . " +1 day"));
    while ($d < $today) {
        $insert_stmt = mysqli_prepare($con, "INSERT IGNORE INTO hs_online_bb_52 (game_date) VALUES (?)");
        if ($insert_stmt) {
            mysqli_stmt_bind_param($insert_stmt, "s", $d);
            if (!mysqli_stmt_execute($insert_stmt)) {
                echo "ERROR: Failed to insert row for " . htmlspecialchars($d, ENT_QUOTES, 'UTF-8') . "\n";
            }
            mysqli_stmt_close($insert_stmt);
        }

        $select_day_stmt = mysqli_prepare($con, "SELECT * FROM hs_online_bb_52 WHERE game_date=? LIMIT 1");
        $row_day = null;
        if ($select_day_stmt) {
            mysqli_stmt_bind_param($select_day_stmt, "s", $d);
            mysqli_stmt_execute($select_day_stmt);
            $day_res = mysqli_stmt_get_result($select_day_stmt);
            if ($day_res && mysqli_num_rows($day_res) > 0) {
                $row_day = mysqli_fetch_assoc($day_res);
            }
            mysqli_stmt_close($select_day_stmt);
        }

        if (!$row_day) {
            echo "WARNING: Row for " . htmlspecialchars($d, ENT_QUOTES, 'UTF-8') . " not found after insert\n";
            $d = date("Y-m-d", (int)strtotime($d . " +1 day"));
            continue;
        }

        $used_for_day = [];
        foreach ($row_day as $k => $v) {
            if (is_string($k) && str_starts_with($k, "col") && !empty(trim((string)$v))) {
                $used_for_day[] = trim((string)$v);
            }
        }

        shuffle($possible_results);
        $filled_count = 0;

        for ($i = 0; $i < $TOTAL_SLOTS; $i++) {
            $col = "col" . ($i + 1);
            if (!empty(trim((string)($row_day[$col] ?? '')))) {
                continue;
            }

            $available = array_values(array_diff($possible_results, $used_for_day));
            if (empty($available)) {
                echo "WARNING: No available results left for $d, slot " . ($i + 1) . "\n";
                break;
            }

            $res_val = $available[array_rand($available)];
            $used_for_day[] = $res_val;

            $stmt = mysqli_prepare($con, "UPDATE hs_online_bb_52 SET `{$col}`=? WHERE game_date=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $res_val, $d);
                if (!mysqli_stmt_execute($stmt)) {
                    echo "ERROR: Failed to update $col for $d\n";
                } else {
                    $filled_count++;
                }
                mysqli_stmt_close($stmt);
            }
        }

        echo "Backfilled $filled_count slots for $d\n";
        $d = date("Y-m-d", (int)strtotime($d . " +1 day"));
    }
}

// ================================================
// STEP B: LOAD TODAY'S ROW
// ================================================
$sel_today_stmt = mysqli_prepare($con, "SELECT * FROM hs_online_bb_52 WHERE game_date=? LIMIT 1");
$row = null;
if ($sel_today_stmt) {
    mysqli_stmt_bind_param($sel_today_stmt, "s", $today);
    mysqli_stmt_execute($sel_today_stmt);
    $res = mysqli_stmt_get_result($sel_today_stmt);
    if ($res && mysqli_num_rows($res) > 0) {
        $row = mysqli_fetch_assoc($res);
    }
    mysqli_stmt_close($sel_today_stmt);
}

if (!$row) {
    $insert_stmt = mysqli_prepare($con, "INSERT INTO hs_online_bb_52 (game_date) VALUES (?)");
    if ($insert_stmt) {
        mysqli_stmt_bind_param($insert_stmt, "s", $today);
        if (!mysqli_stmt_execute($insert_stmt)) {
            echo "ERROR: Failed to insert today's row\n";
        }
        mysqli_stmt_close($insert_stmt);
    }
    $row = array_fill(0, $TOTAL_SLOTS + 1, "");
    $row['game_date'] = $today;
}

// ================================================
// STEP C: PREPARE & RESET TXT (TODAY ONLY) AT 09:15
// ================================================
$current_time = date("H:i");
if ($current_time === "09:15") {
    $blank = array_fill(0, $TOTAL_SLOTS, "");
    if (file_put_contents($txt_path, implode(PHP_EOL, $blank) . PHP_EOL, LOCK_EX) === false) {
        echo "ERROR: Failed to reset TXT file\n";
    } else {
        echo "TXT reset at 09:15\n";
    }
}

// Load today's txt - preserve empty lines
$txt_lines = file_exists($txt_path) ? (file($txt_path, FILE_IGNORE_NEW_LINES) ?: []) : [];
$txt_lines = array_pad($txt_lines, $TOTAL_SLOTS, "");

// ================================================
// STEP D: AUTO-FILL TODAY SLOT-BY-SLOT (ONLY PAST SLOTS)
// ================================================
$now_ts = (int)strtotime(date("Y-m-d") . " " . $current_time);
$used_today = [];
$needs_write = false;

shuffle($possible_results);

for ($i = 0; $i < $TOTAL_SLOTS; $i++) {
    $slot_time = $slot_times[$i];
    $slot_ts = (int)strtotime(date("Y-m-d") . " " . $slot_time);

    if ($now_ts <= $slot_ts) {
        continue;
    }

    $col = "col" . ($i + 1);
    $txt_val = trim((string)($txt_lines[$i] ?? ""));
    $db_val = trim((string)($row[$col] ?? ""));
    $final_val = "";

    if (!empty($db_val)) {
        if (empty($txt_val)) {
            $txt_lines[$i] = $db_val;
            $needs_write = true;
            echo "Synced TXT from DB (Slot " . ($i + 1) . " - $slot_time): $db_val\n";
        } elseif ($txt_val !== $db_val) {
            echo "WARNING: Desync between DB and TXT for slot " . ($i + 1) . " ($slot_time) - DB: $db_val, TXT: $txt_val\n";
        }
        $final_val = $db_val;
        echo "Manual/DB result used (Slot " . ($i + 1) . " - $slot_time): $db_val\n";
    } else {
        if (!empty($txt_val)) {
            $sync_stmt = mysqli_prepare($con, "UPDATE hs_online_bb_52 SET `{$col}`=? WHERE game_date=?");
            if ($sync_stmt) {
                mysqli_stmt_bind_param($sync_stmt, "ss", $txt_val, $today);
                if (mysqli_stmt_execute($sync_stmt)) {
                    $row[$col] = $txt_val;
                    $final_val = $txt_val;
                    echo "Synced DB from manual TXT (Slot " . ($i + 1) . " - $slot_time): $txt_val\n";
                } else {
                    echo "ERROR: Failed to sync DB from TXT for slot " . ($i + 1) . "\n";
                    $final_val = $txt_val;
                }
                mysqli_stmt_close($sync_stmt);
            }
        } else {
            $available = array_values(array_diff($possible_results, $used_today));
            if (empty($available)) {
                echo "WARNING: No available results left for slot " . ($i + 1) . " ($slot_time)\n";
                continue;
            }
            $final_result = $available[array_rand($available)];
            $stmt = mysqli_prepare($con, "UPDATE hs_online_bb_52 SET `{$col}`=? WHERE game_date=?");
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, "ss", $final_result, $today);
                if (mysqli_stmt_execute($stmt)) {
                    $row[$col] = $final_result;
                    $txt_lines[$i] = $final_result;
                    $needs_write = true;
                    $final_val = $final_result;
                    echo "AUTO RESULT POSTED (Slot " . ($i + 1) . " - $slot_time): $final_result\n";
                } else {
                    echo "ERROR: Failed to update DB for slot " . ($i + 1) . "\n";
                }
                mysqli_stmt_close($stmt);
            }
        }
    }

    if (!empty($final_val) && !in_array($final_val, $used_today, true)) {
        $used_today[] = $final_val;
    }
}

if ($needs_write) {
    if (file_put_contents($txt_path, implode(PHP_EOL, $txt_lines) . PHP_EOL, LOCK_EX) === false) {
        echo "ERROR: Failed to update TXT file\n";
    }
}
