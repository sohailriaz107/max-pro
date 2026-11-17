<?php
// ================================================
// HS ONLINE BB 52 – AUTO RESULT CRON (EVERY MINUTE)
// ================================================
date_default_timezone_set('Asia/Kolkata'); // Set to Indian Standard Time (IST)
include('../config.php'); // adjust path if needed
// -------- CONFIG --------
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
// → Fills only empty slots for dates before TODAY (yesterday and earlier)
// ================================================
$today = date("Y-m-d");
// find last date in DB
$last_res = mysqli_query($con, "SELECT game_date FROM $TABLE ORDER BY game_date DESC LIMIT 1");
$last_date = mysqli_num_rows($last_res) ? mysqli_fetch_assoc($last_res)['game_date'] : null;
if ($last_date && $last_date < $today) {
// start from the day after last_date
$d = date("Y-m-d", strtotime($last_date . " +1 day"));
// stop BEFORE today (we will handle today separately)
while ($d < $today) {
// ensure row exists
$insert_stmt = mysqli_prepare($con, "INSERT IGNORE INTO $TABLE (game_date) VALUES (?)");
mysqli_stmt_bind_param($insert_stmt, "s", $d);
if (!mysqli_stmt_execute($insert_stmt)) {
echo "ERROR: Failed to insert row for $d\n";
        }
// load existing row for this day
$day_res = mysqli_query($con, "SELECT * FROM $TABLE WHERE game_date='$d' LIMIT 1");
if (mysqli_num_rows($day_res) == 0) {
echo "WARNING: Row for $d not found after insert\n";
$d = date("Y-m-d", strtotime($d . " +1 day"));
continue;
        }
$row_day = mysqli_fetch_assoc($day_res);
// build used_for_day from existing non-empty cols
$used_for_day = [];
foreach ($row_day as $k => $v) {
if (strpos($k, "col") === 0 && !empty(trim($v))) {
$used_for_day[] = trim($v);
            }
        }
// shuffle for randomness
shuffle($possible_results);
// fill only empty slots
$filled_count = 0;
for ($i = 0; $i < $TOTAL_SLOTS; $i++) {
$col = "col" . ($i + 1);
if (!empty(trim($row_day[$col]))) {
continue; // already set
            }
$available = array_values(array_diff($possible_results, $used_for_day));
if (empty($available)) {
echo "WARNING: No available results left for $d, slot " . ($i + 1) . "\n";
break;
            }
$res_val = $available[array_rand($available)];
$used_for_day[] = $res_val;
// prepared update
$stmt = mysqli_prepare($con, "UPDATE $TABLE SET `$col`=? WHERE game_date=?");
mysqli_stmt_bind_param($stmt, "ss", $res_val, $d);
if (!mysqli_stmt_execute($stmt)) {
echo "ERROR: Failed to update $col for $d\n";
            } else {
$filled_count++;
            }
        }
echo "Backfilled $filled_count slots for $d\n";
// next date
$d = date("Y-m-d", strtotime($d . " +1 day"));
    }
}
// ================================================
// STEP B: LOAD TODAY'S ROW
// ================================================
$res = mysqli_query($con, "SELECT * FROM $TABLE WHERE game_date='$today' LIMIT 1");
if (mysqli_num_rows($res) == 0) {
$insert_stmt = mysqli_prepare($con, "INSERT INTO $TABLE (game_date) VALUES (?)");
mysqli_stmt_bind_param($insert_stmt, "s", $today);
if (!mysqli_stmt_execute($insert_stmt)) {
echo "ERROR: Failed to insert today's row\n";
    }
$row = array_fill(0, $TOTAL_SLOTS + 1, ""); // rough pad; col0 doesn't exist
$row['game_date'] = $today;
} else {
$row = mysqli_fetch_assoc($res);
}
// ================================================
// STEP C: PREPARE & RESET TXT (TODAY ONLY) AT 09:15
// ================================================
$current_time = date("H:i");
// reset only once at 09:15
if ($current_time === "09:15") {
$blank = array_fill(0, $TOTAL_SLOTS, "");
if (!file_put_contents($txt_path, implode(PHP_EOL, $blank))) {
echo "ERROR: Failed to reset TXT file\n";
    } else {
echo "TXT reset at 09:15\n";
    }
}
// load today's txt (pad to 48)
$txt_lines = file_exists($txt_path) ? file($txt_path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) : array();
$txt_lines = array_pad($txt_lines, $TOTAL_SLOTS, "");
// ================================================
// STEP D: AUTO-FILL TODAY SLOT-BY-SLOT (ONLY PAST SLOTS)
// → Ensures unique per-day by maintaining $used_today while running
// ================================================
$now_ts = strtotime(date("Y-m-d") . " " . $current_time); // full timestamp for accuracy
// No initial used_today; build dynamically in loop
$used_today = [];
$needs_write = false;
// shuffle the master list for randomness for this run
shuffle($possible_results);
for ($i = 0; $i < $TOTAL_SLOTS; $i++) {
$slot_time = $slot_times[$i];
$slot_ts = strtotime(date("Y-m-d") . " " . $slot_time);
// only fill slots that have passed (1 min late: process only after the slot minute)
if ($now_ts <= $slot_ts) {
continue;
    }
$col = "col" . ($i + 1);
$txt_val = trim($txt_lines[$i] ?? "");
$db_val = trim($row[$col] ?? "");
$final_val = "";
if (!empty($db_val)) {
    // Check: If DB has value (manual update), skip auto-fill and only sync TXT if empty (do not change TXT if already set, even if desynced - warn instead)
    if (empty($txt_val)) {
        // Sync TXT from DB only if empty
        $txt_lines[$i] = $db_val;
        $needs_write = true;
        echo "Synced TXT from DB (Slot " . ($i + 1) . " - $slot_time): $db_val\n";
    } elseif ($txt_val !== $db_val) {
        // Warn on desync but do not change TXT per requirement
        echo "WARNING: Desync between DB and TXT for slot " . ($i + 1) . " ($slot_time) - DB: $db_val, TXT: $txt_val\n";
    }
    $final_val = $db_val;
    echo "Manual/DB result used (Slot " . ($i + 1) . " - $slot_time): $db_val\n";
} else {
    // DB empty: check TXT or auto-fill
    if (!empty($txt_val)) {
        // Sync DB from TXT (assuming manual TXT override)
        $sync_stmt = mysqli_prepare($con, "UPDATE $TABLE SET `$col`=? WHERE game_date=?");
        mysqli_stmt_bind_param($sync_stmt, "ss", $txt_val, $today);
        if (mysqli_stmt_execute($sync_stmt)) {
            $row[$col] = $txt_val;
            $final_val = $txt_val;
            echo "Synced DB from manual TXT (Slot " . ($i + 1) . " - $slot_time): $txt_val\n";
        } else {
            echo "ERROR: Failed to sync DB from TXT for slot " . ($i + 1) . "\n";
            $final_val = $txt_val; // fallback
        }
    } else {
        // Both empty: Auto-fill
        $available = array_values(array_diff($possible_results, $used_today));
        if (empty($available)) {
            echo "WARNING: No available results left for slot " . ($i + 1) . " ($slot_time)\n";
            continue;
        }
        $final_result = $available[array_rand($available)];
        // Update DB
        $stmt = mysqli_prepare($con, "UPDATE $TABLE SET `$col`=? WHERE game_date=?");
        mysqli_stmt_bind_param($stmt, "ss", $final_result, $today);
        if (mysqli_stmt_execute($stmt)) {
            $row[$col] = $final_result;
            $txt_lines[$i] = $final_result;
            $needs_write = true;
            $final_val = $final_result;
            echo "AUTO RESULT POSTED (Slot " . ($i + 1) . " - $slot_time): $final_result\n";
        } else {
            echo "ERROR: Failed to update DB for slot " . ($i + 1) . "\n";
            continue;
        }
    }
}
// Add final value to used_today if not already present
if (!empty($final_val) && !in_array($final_val, $used_today, true)) {
$used_today[] = $final_val;
}
}
// Write TXT if any changes to it occurred
if ($needs_write) {
if (!file_put_contents($txt_path, implode(PHP_EOL, $txt_lines) . PHP_EOL)) {
echo "ERROR: Failed to update TXT file\n";
}
}
?>