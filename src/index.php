<?php
require_once 'Challenge.php';


// Load environment variables
$securityToken = getenv('SECURITY_TOKEN');

// Validate security token
$headers = $headers = array_change_key_case(getallheaders(), CASE_LOWER);
if (!isset($headers['authorization']) || $headers['authorization'] !== "Bearer $securityToken") {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}


/*
 * --- POST request to create a new challenge ---
 */
// TODO: allow json requests
// Handle POST request to create a new challenge
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = [
        'id' => $_POST['id'] ?? 0,
        'challenger' => $_POST['challenger'] ?? 0,
        'challengerID' => $_POST['challengerID'] ?? 0,
        'lastMoverID' => $_POST['lastMoverID'] ?? 0,
        'board' => $_POST['board'] ?? '',
        'canPerformMove' => $_POST['canPerformMove'] ?? false,
        'habitId' => $_POST['habitId'] ?? 0,
        'habitName' => $_POST['habitName'] ?? '',
        'habitOccurrenceType' => $_POST['habitOccurrenceType'] ?? '',
        'habitOccurrenceNum' => $_POST['habitOccurrenceNum'] ?? 0
    ];
    $challenge = new Challenge($data);
    echo json_encode($challenge);

    if ($challenge->isValid()) {
        saveChallenge($challenge);
        echo "Challenge created successfully. ID: " . $data['id'] . ", Name: " . $data['habitName'];
    } else {
        echo "Invalid input. Please provide valid data for all fields.";
    }

    exit;
}

// Function to write a challenge to a JSON file
function saveChallenge($challenge) {
    $filePath = __DIR__ . "/data/{$challenge->getId()}.json";
    file_put_contents($filePath, json_encode($challenge, JSON_PRETTY_PRINT));
}


/*
 * --- GET request to retrieve a challenge ---
 */
// Get the ID from the query parameters
$id = $_GET['id'] ?? "0";
// Check if the challenge with the given ID exists
$challenge = getChallenge($id);
if ($challenge) {
    echo json_encode($challenge);
} else {
    echo "Challenge not found.";
}

// Function to read a challenge from a JSON file
function getChallenge($id) {
    $filePath = __DIR__ . "/data/{$id}.json";
    if (file_exists($filePath)) {
        $json = file_get_contents($filePath);
        return Challenge::fromJson($json);
    }
    // Return a JSON error instead of plain text
    echo json_encode(["error" => "Challenge not found."]);
    return null;
}
?>