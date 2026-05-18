<?php
/**
 * CUSTOMER REGISTER · /api/customer_register.php
 * signed: KIN·2026-05-18T12:00Z·a75e63ca · Sprint S-2 ship · Skill #41
 *
 * Mo verbatim 2026-05-17: "We need to expedite this process sooner and much faster. I'm broke."
 *
 * Accepts post-payment customer registration from /portal/welcome.html · stores a JSON record
 * per customer + generates a personalized canvas URL + emails them the link.
 *
 * Per GLOBAL-93 · NO vendor names on public surfaces · response uses empire-internal labels.
 * Per GLOBAL-112 · zero Maya config edits · this is a NEW file under ai-staffing.agency only.
 * Per GLOBAL-114 customer view doctrine · stored record drives the ?tier=customer URL params.
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

@error_reporting(0);
@ini_set('display_errors', '0');
@set_time_limit(30);

// ============ READ + VALIDATE INPUT ============
$raw = file_get_contents('php://input');
$in = json_decode($raw, true);
if (!is_array($in)) {
    echo json_encode(['ok' => false, 'error' => 'invalid JSON body']); exit;
}

$name = isset($in['customer_name']) ? trim((string)$in['customer_name']) : '';
$email = isset($in['customer_email']) ? trim((string)$in['customer_email']) : '';
$session = isset($in['stripe_session']) ? trim((string)$in['stripe_session']) : '';
$tier = isset($in['tier']) ? strtolower(trim((string)$in['tier'])) : 'starter';
$agencies = isset($in['agencies']) && is_array($in['agencies']) ? $in['agencies'] : [];

if ($name === '' || $email === '') { echo json_encode(['ok' => false, 'error' => 'name + email required']); exit; }
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { echo json_encode(['ok' => false, 'error' => 'invalid email']); exit; }
if (count($agencies) === 0) { echo json_encode(['ok' => false, 'error' => 'must pick at least 1 agency']); exit; }

// ============ TIER LIMITS · enforce server-side ============
$TIER_LIMITS = ['starter' => 1, 'team' => 3, 'scale' => 10, 'enterprise' => 100];
$validTiers = ['starter', 'team', 'scale', 'enterprise'];
if (!in_array($tier, $validTiers, true)) { echo json_encode(['ok' => false, 'error' => 'invalid tier']); exit; }
$limit = $TIER_LIMITS[$tier];
if (count($agencies) > $limit) {
    echo json_encode(['ok' => false, 'error' => "tier {$tier} allows max {$limit} agencies · you picked " . count($agencies)]); exit;
}

// ============ SANITIZE agency slugs ============
$sanitized_agencies = [];
foreach ($agencies as $slug) {
    $s = strtolower(trim((string)$slug));
    $s = preg_replace('/[^a-z0-9_-]/', '', $s);
    $s = substr($s, 0, 80);
    if ($s !== '' && $s !== '_council' && !str_starts_with($s, 'pad_')) {
        $sanitized_agencies[] = $s;
    }
}
if (count($sanitized_agencies) === 0) { echo json_encode(['ok' => false, 'error' => 'no valid agency slugs']); exit; }
$sanitized_agencies = array_values(array_unique($sanitized_agencies));

// ============ GENERATE CUSTOMER ID ============
$customer_id = 'cust_' . substr(bin2hex(random_bytes(8)), 0, 12);
$now = gmdate('Y-m-d\TH:i:s\Z');

// ============ STORE CUSTOMER RECORD ============
$CUSTOMERS_DIR = '/home/ai-staffing.agency/public_html/data/customers';
if (!is_dir($CUSTOMERS_DIR)) { @mkdir($CUSTOMERS_DIR, 0775, true); }

$record = [
    'customer_id' => $customer_id,
    'stripe_session' => $session,
    'name' => $name,
    'email' => $email,
    'tier' => $tier,
    'agency_limit' => $limit,
    'rented_agencies' => $sanitized_agencies,
    'started_at' => $now,
    'active' => true,
    'source' => '/portal/welcome.html',
    'agent_signature' => 'KIN·2026-05-18T12:00Z·a75e63ca'
];

$record_path = $CUSTOMERS_DIR . '/' . $customer_id . '.json';
@file_put_contents($record_path, json_encode($record, JSON_PRETTY_PRINT));

// ============ GENERATE CANVAS URL ============
$encoded_name = rawurlencode($name);
$encoded_agencies = implode(',', $sanitized_agencies);
$canvas_url = 'https://ai-staffing.agency/habitat-v4.html?tier=customer&agencies=' . $encoded_agencies . '&customer=' . $encoded_name . '&cid=' . $customer_id;

// ============ APPEND TO MO'S NOTIFICATION LOG ============
$LOG = '/home/ai-staffing.agency/public_html/data/customer_register.log';
$logrow = json_encode([
    'ts' => $now, 'customer_id' => $customer_id, 'name' => $name, 'email' => $email,
    'tier' => $tier, 'agencies' => $sanitized_agencies, 'canvas_url' => $canvas_url
]) . "\n";
@file_put_contents($LOG, $logrow, FILE_APPEND);

// ============ SEND EMAIL TO CUSTOMER (best-effort · uses PHP mail()) ============
$subject = 'Your AI Staffing Agency canvas is ready';
$body = "Hi {$name},\n\n" .
        "Your personalized AI Staffing Agency canvas is live.\n\n" .
        "Bookmark this link · this is your private portal:\n{$canvas_url}\n\n" .
        "Your tier: {$tier} (up to {$limit} agencies)\n" .
        "Your agencies: " . implode(', ', $sanitized_agencies) . "\n\n" .
        "Drop files into any of your agencies and Maya will dispatch the work to the right multi-agent pipeline. Cross-agency routing happens automatically.\n\n" .
        "Questions? Reply to this email or hit us at hello@ai-staffing.agency\n\n" .
        "Welcome aboard,\n" .
        "The AI Staffing Agency team\n" .
        "https://ai-staffing.agency";

$headers = "From: AI Staffing Agency <hello@ai-staffing.agency>\r\n" .
           "Reply-To: hello@ai-staffing.agency\r\n" .
           "X-Mailer: PHP/AIStaffing\r\n";
$mail_sent = @mail($email, $subject, $body, $headers);

// ============ NOTIFY MO TOO ============
$mo_subject = 'New customer · ' . $name . ' · ' . $tier;
$mo_body = "New customer registered:\n\n" .
           "Name: {$name}\nEmail: {$email}\nTier: {$tier}\nAgencies: " . implode(', ', $sanitized_agencies) . "\n" .
           "Stripe session: " . ($session ?: '(none)') . "\n" .
           "Customer ID: {$customer_id}\n" .
           "Canvas URL: {$canvas_url}\n\n" .
           "Record: {$record_path}\n";
@mail('hello@ai-staffing.agency', $mo_subject, $mo_body, $headers);

// ============ RESPONSE ============
echo json_encode([
    'ok' => true,
    'customer_id' => $customer_id,
    'canvas_url' => $canvas_url,
    'tier' => $tier,
    'agencies' => $sanitized_agencies,
    'email_sent' => (bool)$mail_sent,
    'message' => 'Your canvas is ready · check your email for the bookmarkable link · welcome aboard.'
]);
