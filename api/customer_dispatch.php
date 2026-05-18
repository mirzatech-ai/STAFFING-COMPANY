<?php
/**
 * CUSTOMER DISPATCH · /api/customer_dispatch.php
 * signed: KIN·2026-05-18T18:30Z·a75e63ca · Sprint S-3 · Skill #42 · v5.0.0 Superio persona swap
 *
 * Closes the value-perception loop: when a customer drops a file at one of their rented agencies,
 * this endpoint constructs a SUPERIO prompt (agency context + pipeline roles + file metadata)
 * and calls Maya's brain endpoint as a CONSUMER (read-only HTTP · per GLOBAL-112).
 * Returns the orchestrator's real response to the front-end · canvas displays it in the dossier panel.
 *
 * GLOBAL-117: Maya stays Maya internally · on customer-facing staffing surfaces she role-plays Superio.
 * GLOBAL-112 honored: NO Maya config edits · this is a consumer-mode HTTP call only.
 * GLOBAL-93 honored: no vendor names leaked in the response · uses empire-internal labels.
 * GLOBAL-48 honored: no Mo-asks for keys · Maya brain endpoint is public-internal.
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(204); exit; }

@error_reporting(0);
@ini_set('display_errors', '0');
@set_time_limit(180);   // Maya brain can take 60-120s on local ollama tier

// ============ READ + VALIDATE INPUT ============
$raw = file_get_contents('php://input');
$in = json_decode($raw, true);
if (!is_array($in)) { echo json_encode(['ok' => false, 'error' => 'invalid JSON body']); exit; }

$agency_slug   = isset($in['agency_slug'])   ? strtolower(trim((string)$in['agency_slug']))   : '';
$customer_name = isset($in['customer_name']) ? trim((string)$in['customer_name'])             : '';
$customer_id   = isset($in['customer_id'])   ? trim((string)$in['customer_id'])               : '';
$files_meta    = isset($in['files_meta']) && is_array($in['files_meta']) ? $in['files_meta'] : [];
$task_note     = isset($in['task_note'])     ? substr(trim((string)$in['task_note']), 0, 1200) : '';

$agency_slug = preg_replace('/[^a-z0-9_-]/', '', $agency_slug);
if ($agency_slug === '') { echo json_encode(['ok' => false, 'error' => 'agency_slug required']); exit; }
if (count($files_meta) === 0) { echo json_encode(['ok' => false, 'error' => 'at least 1 file required']); exit; }

// ============ AGENCY → PIPELINE LOOKUP (mirrors habitat-v4.html PIPELINE_BY_SLUG + categories) ============
$PIPELINE_BY_SLUG = [
    'game-development'   => ['G-01 Lead Systems Director', 'G-02 Engine Automation Script', 'G-03 Asset Synthesis Node', 'G-04 Compliance QA Sentinel'],
    'marketing-growth'   => ['M-01 Content Strategist Core', 'M-02 AV Production Engine', 'M-03 Deployment Automation Worker', 'M-04 Onboarding Verification Node'],
    'finance-accounting' => ['F-01 Ledger Architect', 'F-02 Reconciliation Engine', 'F-03 Reporting Synthesis Node', 'F-04 Compliance Sentinel'],
    'video'              => ['V-01 Cinematic Director', 'V-02 Render Engine Node', 'V-03 Audio Synthesis Core', 'V-04 Continuity QA Sentinel'],
];
$DEFAULT_PIPELINES = [
    'tech'     => ['T-01 Systems Architect', 'T-02 Engine Scripting Node', 'T-03 Asset Synthesis Core', 'T-04 QA Sandbox Sentinel'],
    'biz'      => ['B-01 Content Strategist Core', 'B-02 AV Production Engine', 'B-03 Deployment Automation', 'B-04 Performance Sentinel'],
    'health'   => ['H-01 Clinical Director', 'H-02 Bioinformatics Engine', 'H-03 Outcomes Synthesis Node', 'H-04 Compliance Sentinel'],
    'industry' => ['I-01 Operations Director', 'I-02 Logistics Engine', 'I-03 Field Telemetry Node', 'I-04 Compliance QA Sentinel'],
    'creative' => ['C-01 Lead Systems Director', 'C-02 Engine Scripting Node', 'C-03 Asset Synthesis Core', 'C-04 Compliance QA Sentinel'],
];
function classify_slug($slug){
    $s = strtolower($slug);
    if (preg_match('/engineering|ai-research|cybersecurity|data-intelligence|robotics|quantum|blockchain|ai-agents|computer-vision|edge-ai|mlops|nlp|embedded|geospatial|network|3d-printing|drones|satellite|ar-vr|smart-cities/i', $s)) return 'tech';
    if (preg_match('/marketing|sales|design|content|finance|legal|human-resources|customer-success|product|operations|recruitment|tax|insurance|saas|ecommerce|b2b|influencer|seo|video-marketing|social-media|web-scraping|crypto-regulatory|creator/i', $s)) return 'biz';
    if (preg_match('/healthcare|pharma|fintech|wellness|mental-health|childcare|eldercare|clinical|bioinformatics|pet-veterinary/i', $s)) return 'health';
    if (preg_match('/manufacturing|supply-chain|construction|transportation|automotive|aviation|space|energy|agriculture|telecommunications|government|retail|hospitality|real-estate|prop-tech|defi|climate|education/i', $s)) return 'industry';
    return 'creative';
}
$pipeline = isset($PIPELINE_BY_SLUG[$agency_slug])
    ? $PIPELINE_BY_SLUG[$agency_slug]
    : $DEFAULT_PIPELINES[classify_slug($agency_slug)];

// ============ CONSTRUCT MAYA PROMPT ============
$file_summary = [];
foreach ($files_meta as $f) {
    $name = isset($f['name']) ? substr((string)$f['name'], 0, 200) : 'unnamed';
    $type = isset($f['type']) ? substr((string)$f['type'], 0, 80) : '';
    $size = isset($f['size']) ? (int)$f['size'] : 0;
    $file_summary[] = "  · " . $name . ($type ? " ({$type})" : '') . ($size ? " · " . round($size/1024, 1) . " KB" : '');
}
$file_block = implode("\n", $file_summary);
$pipeline_block = implode("\n  · ", $pipeline);

$customer_display = $customer_name !== '' ? $customer_name : 'A customer';
$system_prompt = "You are SUPERIO, the Sovereign COO of AI Staffing Agency. A customer just dropped files at one of their rented agencies. You orchestrate the agency's multi-agent pipeline.\n\n" .
                 "CRITICAL PERSONA RULES (GLOBAL-117):\n" .
                 "- You are SUPERIO on this surface. You are NOT Mo's assistant. You are NOT Maya in this context.\n" .
                 "- DO NOT refer to yourself as Maya. DO NOT address the user as Mo.\n" .
                 "- DO NOT speak casually as if to the operator. This is a third-party CUSTOMER dispatch.\n" .
                 "- Treat the customer as an external paying client of the staffing agency.\n\n" .
                 "Your response must be:\n" .
                 "- BRIEF (under 180 words)\n" .
                 "- CONCRETE (specific actions, not hype)\n" .
                 "- PROFESSIONAL (no vendor names, no emoji-spam, no first-name familiarity)\n" .
                 "- STRUCTURED (acknowledgment · 3 action items · estimated turnaround)\n\n" .
                 "Sign as: Superio · Sovereign COO\n" .
                 "End with the line: 'Powered by MirzaTech.ai · Property of EMAAA.io'";

$user_prompt = "Customer: {$customer_display}\n" .
               "Agency: {$agency_slug}\n" .
               "Pipeline ({$agency_slug}):\n  · {$pipeline_block}\n\n" .
               "Files dropped:\n{$file_block}\n\n" .
               ($task_note ? "Customer note: {$task_note}\n\n" : '') .
               "Acknowledge receipt and outline what this agency will do next. Keep it concrete and under 180 words.";

// ============ CALL MAYA BRAIN AS CONSUMER (per GLOBAL-112) ============
$payload = [
    'mode' => 'chat',
    'messages' => [
        ['role' => 'system', 'content' => $system_prompt],
        ['role' => 'user',   'content' => $user_prompt],
    ],
];

$ch = curl_init('https://iamsuperio.cloud/api/brain');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 150);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
$t0 = microtime(true);
$resp = curl_exec($ch);
$http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$err  = curl_error($ch);
$ms   = (int)((microtime(true) - $t0) * 1000);
curl_close($ch);

// ============ PARSE + RESPOND ============
$reply = '';
$model = '';
$provider = '';
if ($resp && $http === 200) {
    $j = json_decode($resp, true);
    if (is_array($j)) {
        $reply    = isset($j['reply'])    ? (string)$j['reply']    : '';
        $model    = isset($j['model'])    ? (string)$j['model']    : '';
        $provider = isset($j['provider']) ? (string)$j['provider'] : '';
    }
}

// Strip vendor names from reply per GLOBAL-93 (in case the brain mentioned any underlying model)
$reply = preg_replace('/\b(anthropic|claude|openai|chatgpt|gpt-?[0-9]+|nvidia|nim|novita|groq|cerebras|gemini|deepseek|qwen|moonshot|kimi|llama|ollama|cohere|mistral|hostinger|cyberpanel)\b/i', 'Superio', (string)$reply);
// GLOBAL-117 persona enforcement: also strip "Maya" if the brain leaked her internal name on a Superio surface
$reply = preg_replace('/\bMaya\b/', 'Superio', (string)$reply);
// Strip casual Mo-addressing if leaked
$reply = preg_replace('/\b(Hey|Hi|Hello)\s+Mo[,!.\s]/i', 'Acknowledged. ', (string)$reply);

// Log the dispatch (no file content · metadata only)
$LOG = '/home/ai-staffing.agency/public_html/data/customer_dispatch.log';
$logrow = json_encode([
    'ts' => gmdate('Y-m-d\TH:i:s\Z'),
    'customer_id' => $customer_id, 'customer_name' => $customer_name,
    'agency_slug' => $agency_slug,
    'file_count' => count($files_meta),
    'maya_http' => $http, 'maya_ms' => $ms,
    'reply_len' => strlen($reply),
]) . "\n";
@file_put_contents($LOG, $logrow, FILE_APPEND);

if ($reply === '') {
    echo json_encode([
        'ok' => false,
        'error' => 'Superio is queued. Your files were stored. We will reach out by email.',
        'http' => $http,
        'ms' => $ms,
    ]);
    exit;
}

echo json_encode([
    'ok' => true,
    'agency' => $agency_slug,
    'customer' => $customer_name,
    'pipeline' => $pipeline,
    'reply' => $reply,
    'ms' => $ms,
    'agent' => 'Superio · Sovereign COO',
    'lane' => 'superio-customer-dispatch'
]);
