<?php
require_once 'Challenge.php';


// Load environment variables
$securityToken = getenv('SECURITY_TOKEN');

// Function to read a challenge from a JSON file
function getChallenge($id) {
    $filePath = __DIR__ . "/data/{$id}.json";
    if (file_exists($filePath)) {
        $json = file_get_contents($filePath);
        return Challenge::fromJson($json);
    }
    return null;
}

// Function to write a challenge to a JSON file
function saveChallenge($challenge) {
    $filePath = __DIR__ . "/data/{$challenge->getId()}.json";
    file_put_contents($filePath, json_encode($challenge, JSON_PRETTY_PRINT));
}

// Validate security token
$headers = $headers = array_change_key_case(getallheaders(), CASE_LOWER);
if (!isset($headers['authorization']) || $headers['authorization'] !== "Bearer $securityToken") {
    http_response_code(401);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// TODO: allow json requests
// Handle POST request to create a new challenge
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? $_POST['id'] : 0;
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $occurrence = isset($_POST['occurrence']) ? $_POST['occurrence'] : '';
    $num = isset($_POST['num']) ? (int)$_POST['num'] : 0;
    $board = isset($_POST['board']) ? $_POST['board'] : '';
    $challenger = isset($_POST['challenger']) ? (int)$_POST['challenger'] : 0;
    $score = isset($_POST['score']) ? (int)$_POST['score'] : 0;

    if ($id > 0 && !empty($name) && !empty($occurrence) && $num > 0 && !empty($board) && $challenger > 0 && $score >= 0) {
        $challenge = new Challenge($id, $name, $occurrence, $num, $board, $challenger, $score);
        saveChallenge($challenge);
        echo "Challenge created successfully. ID: " . $id . ", Name: " . $name;
    } else {
        echo "Invalid input. Please provide valid data for all fields.";
    }
    exit;
}

// Get the ID from the query parameters
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Check if the challenge with the given ID exists
$challenge = getChallenge($id);
if ($challenge) {
    header('Content-Type: application/json');
    echo json_encode($challenge);
} else {
    echo "Challenge not found.";
}
?>