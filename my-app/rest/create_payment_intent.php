<?php
/**
 * Stripe Payment Intent endpoint
 * Called via fetch() from Checkout.php to create a PaymentIntent.
 * Returns the client_secret needed by Stripe Elements on the frontend.
 */

require_once __DIR__ . '/../../vendor/autoload.php';

// Load environment variables
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../../');
$dotenv->load();

header('Content-Type: application/json');

// Only allow POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

// Read and decode the JSON body sent from the frontend
$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['amount']) || !is_numeric($data['amount'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid amount']);
    exit;
}

// Amount arrives as SEK (e.g. 1299.00). Stripe wants the smallest unit (öre = cents).
$amountInOre = (int) round((float)$data['amount'] * 100);

if ($amountInOre < 100) { // Stripe minimum is 1.00 SEK
    http_response_code(400);
    echo json_encode(['error' => 'Amount too small']);
    exit;
}

$secretKey = $_ENV['STRIPE_SECRET_KEY'] ?? '';

if (empty($secretKey) || str_starts_with($secretKey, 'sk_test_REPLACE')) {
    http_response_code(500);
    echo json_encode(['error' => 'Stripe secret key not configured. Add STRIPE_SECRET_KEY to your .env file.']);
    exit;
}

\Stripe\Stripe::setApiKey($secretKey);

try {
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount'   => $amountInOre,
        'currency' => 'sek',
        'automatic_payment_methods' => ['enabled' => true],
    ]);

    echo json_encode(['clientSecret' => $paymentIntent->client_secret]);

} catch (\Stripe\Exception\ApiErrorException $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
