<?php
require __DIR__ . "/vendor/autoload.php";
$app = require_once __DIR__ . "/bootstrap/app.php";
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$email = "5000015@example.com";
$user = App\Models\User::where("email", $email)->first();
$token = $user->createToken("test")->plainTextToken;

function callApi($method, $uri, $data = [], $token) {
    $request = Illuminate\Http\Request::create($uri, $method, $data);
    $request->headers->set("Accept", "application/json");
    $request->headers->set("Authorization", "Bearer " . $token);
    $response = app()->handle($request);
    $content = json_decode($response->getContent(), true);
    return ["status" => $response->getStatusCode(), "data" => $content];
}

echo "1. Dashboard\n";
$res = callApi("GET", "/api/v1/new-movements/dashboard", [], $token);
echo json_encode($res["data"]) . "\n\n";

echo "2. Start Movement\n";
$res = callApi("POST", "/api/v1/new-movements/start", [
    "start_location" => "Head Office",
    "latitude" => "23.7808",
    "longitude" => "90.2792",
    "type" => "official"
], $token);
echo json_encode($res["data"]) . "\n\n";
$movement_id = $res["data"]["data"]["movement_id"] ?? null;

echo "3. Reached Destination\n";
$res = callApi("POST", "/api/v1/new-movements/reached-destination", [
    "current_location" => "Client Area",
    "latitude" => "23.8",
    "longitude" => "90.3",
    "is_office_return" => 0
], $token);
echo json_encode($res["data"]) . "\n\n";

echo "4. Start Meeting\n";
$res = callApi("POST", "/api/v1/new-movements/start-meeting", [
    "client_name" => "ABC Corp",
    "meeting_type" => "sales",
    "location" => "Client Area",
    "latitude" => "23.8",
    "longitude" => "90.3"
], $token);
echo json_encode($res["data"]) . "\n\n";

echo "5. End Meeting\n";
$res = callApi("POST", "/api/v1/new-movements/end-meeting", [], $token);
echo json_encode($res["data"]) . "\n\n";

echo "6. Submit Feedback\n";
$res = callApi("POST", "/api/v1/new-movements/submit-feedback", [
    "feedback" => "Great meeting",
    "movement_id" => $movement_id
], $token);
echo json_encode($res["data"]) . "\n\n";

echo "7. Decision (Return to Office)\n";
$res = callApi("POST", "/api/v1/new-movements/decision", [
    "choice" => "office",
    "current_location" => "Client Area",
    "latitude" => "23.8",
    "longitude" => "90.3"
], $token);
echo json_encode($res["data"]) . "\n\n";

echo "8. Reached Office (End Movement)\n";
$res = callApi("POST", "/api/v1/new-movements/reached-destination", [
    "current_location" => "Head Office",
    "latitude" => "23.7808",
    "longitude" => "90.2792",
    "is_office_return" => 1
], $token);
echo json_encode($res["data"]) . "\n\n";

echo "9. Apply TA\n";
$res = callApi("POST", "/api/v1/new-movements/apply-ta", [
    "movement_id" => $movement_id,
    "expenses" => [
        ["type" => "taxi", "amount" => 200, "note" => "Going"],
        ["type" => "taxi", "amount" => 250, "note" => "Returning"]
    ]
], $token);
echo json_encode($res["data"]) . "\n\n";
