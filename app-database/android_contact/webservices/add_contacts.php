<?php
//ini_set('display_errors', 1);
//error_reporting(E_ALL);

// Database connection
$con = mysqli_connect('15.235.214.152', 'dpboss_net_api_user', 'qwertGfde#2', 'dpboss_net_api');

if (!$con) {
    http_response_code(500);
    echo json_encode([
        "status" => false,
        "msg" => "Database connection failed: " . mysqli_connect_error()
    ]);
    exit;
}

// Get raw JSON input
$rawData = file_get_contents("php://input");
$data = json_decode($rawData);

// Validate input
if (isset($data->phone_list) && !empty($data->user_id) && is_array($data->phone_list)) {

    $user_id = mysqli_real_escape_string($con, $data->user_id);
    $username = mysqli_real_escape_string($con, $data->username);
    $inserted_count = 0;

    foreach ($data->phone_list as $phone) {

        $name = mysqli_real_escape_string($con, $phone->name ?? '');
        $contact = mysqli_real_escape_string($con, $phone->contact ?? '');

        if (empty($name) || empty($contact)) {
            continue; // Skip if name or contact is empty
        }

        // Check for duplicate
        $ph_where = "SELECT COUNT(*) as count FROM android_contact_list 
                     WHERE contact = '$contact' AND user_id = '$user_id'";
        $result = mysqli_query($con, $ph_where);
        $row = mysqli_fetch_assoc($result);

        if ($row['count'] == 0) {
            // Insert contact
            $insert = "INSERT INTO `android_contact_list`(`contact`, `name`, `user_id`, `username`) 
                       VALUES ('$contact', '$name', '$user_id','$username')";
            if (mysqli_query($con, $insert)) {
                $inserted_count++;
            }
        }
    }
    echo json_encode([
        "status" => true,
        "msg" => "Contacts added successfully! ($inserted_count new contact(s))"
    ]);
} else {
    echo json_encode([
        "status" => false,
        "msg" => "Fill all required fields!"
    ]);
}

exit;
