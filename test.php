<?php
/**
 * ================================================================
 *  END-TO-END TESTER
 *  Project → 6 × HttpTask (all HTTP methods) → HttpRequestBuilder
 *  → HttpRequest → HttpRequestRunner → HttpResponse
 * ================================================================
 */

$base = __DIR__ . "/src/Domain";

// ── ValueObjects ─────────────────────────────────────────────
require_once $base . "/ValueObject/DataType.php";
require_once $base . "/ValueObject/HttpRequestMethod.php";
require_once $base . "/ValueObject/HttpHeaderCategory.php";
require_once $base . "/ValueObject/Http/HttpHeaderCategory.php";
require_once $base . "/ValueObject/Http/HttpBodyType.php";
require_once $base . "/ValueObject/Http/HttpRequestMethod.php";
require_once $base . "/ValueObject/Transformer/EncodeType.php";
require_once $base . "/ValueObject/Transformer/HashType.php";
require_once $base . "/ValueObject/Transformer/EncryptType.php";

// ── Transformers ─────────────────────────────────────────────
require_once $base . "/Transformer/Transformer.php";
require_once $base . "/Transformer/TransformStep.php";
require_once $base . "/Transformer/Encoder/AbstractEncoder.php";
require_once $base . "/Transformer/Encoder/EncoderFactory.php";
require_once $base . "/Transformer/Hasher/AbstractHasher.php";
require_once $base . "/Transformer/Hasher/HasherFactory.php";
require_once $base . "/Transformer/Encryptor/AbstractEncryptor.php";
require_once $base . "/Transformer/Encryptor/EncryptorFactory.php";

// ── Parameters ───────────────────────────────────────────────
require_once $base . "/Parameter/Parameter.php";
require_once $base . "/Parameter/HttpHolder/HttpParameterHolder.php";
require_once $base . "/Parameter/HttpHolder/HttpHeaderHolder.php";
require_once $base . "/Parameter/HttpHolder/HttpQueryHolder.php";
require_once $base . "/Parameter/HttpHolder/HttpBodyHolder.php";

// ── Domain ───────────────────────────────────────────────────
require_once $base . "/Task/Task.php";
require_once $base . "/Task/HttpTask.php";
require_once $base . "/Project/Project.php";

// ── Infrastructure ───────────────────────────────────────────
require_once $base . "/Request/HttpRequest.php";
require_once $base . "/Request/Builder/HttpRequestBuilder.php";
require_once $base . "/Request/Runner/HttpRequestRunner.php";
require_once $base . "/Response/HttpResponse.php";

use Project\Project;
use Task\HttpTask;
use Parameter\Parameter;
use Transformer\TransformStep;
use ValueObject\DataType;
use ValueObject\HttpRequestMethod;
use ValueObject\HttpHeaderCategory;
use ValueObject\Http\HttpBodyType;
use ValueObject\Transformer\EncodeType;
use ValueObject\Transformer\HashType;
use ValueObject\Transformer\EncryptType;
use Request\Builder\HttpRequestBuilder;
use Request\Runner\HttpRequestRunner;

// ── Console helpers ───────────────────────────────────────────
$NC  = "\033[0m";
$CYN = "\033[1;36m";
$YLW = "\033[1;33m";
$GRN = "\033[1;32m";
$RED = "\033[1;31m";
$MAG = "\033[1;35m";
$WHT = "\033[0;37m";

function sec(string $t): void {
    global $CYN, $YLW, $NC;
    $bar = str_repeat("═", 62);
    echo "\n{$CYN}{$bar}{$NC}\n";
    echo "{$YLW}  {$t}{$NC}\n";
    echo "{$CYN}{$bar}{$NC}\n";
}
function kv(string $label, mixed $val): void {
    global $GRN, $NC, $WHT;
    $s = is_array($val)
        ? json_encode($val, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE)
        : (string) $val;
    echo "  {$GRN}{$label}:{$NC} {$WHT}{$s}{$NC}\n";
}
function badge(string $method): string {
    $colors = ['GET'=>"\033[42;30m",'POST'=>"\033[44;37m",'PUT'=>"\033[43;30m",
               'PATCH'=>"\033[45;37m",'DELETE'=>"\033[41;37m",'HEAD'=>"\033[46;30m"];
    $c = $colors[$method] ?? "\033[47;30m";
    return "{$c} {$method} \033[0m";
}

function runTask(HttpTask $task, HttpRequestRunner $runner, HttpRequestBuilder $builder): void {
    global $GRN, $RED, $NC, $MAG, $WHT;

    $method = $task->getMethod()->value;
    echo "\n  " . badge($method) . "  \033[1;37m{$task->getName()}\033[0m\n";
    echo "  {$WHT}─────────────────────────────────────────────────────────{$NC}\n";
    kv("URL",         $task->getAddress());
    kv("Slug",        $task->getSlug());
    kv("Description", $task->getDescription());

    // Show transformed parameter values before sending
    foreach ($task->getBodies()->getParameters() as $p) {
        if (!empty($p->getSteps())) {
            echo "  {$MAG}  ↳ body[{$p->getKey()}] raw='{$p->getValue()}' → transformed='{$p->getModifiedValue()}'{$NC}\n";
        }
    }
    foreach ($task->getQueries()->getParameters() as $p) {
        if (!empty($p->getSteps())) {
            echo "  {$MAG}  ↳ query[{$p->getKey()}] raw='{$p->getValue()}' → transformed='{$p->getModifiedValue()}'{$NC}\n";
        }
    }

    $request = $builder->build($task);
    kv("Full URL",    $request->getFullAddress());
    kv("Headers",     $request->getHeaders());

    $body = $request->getBody();
    if (!empty($body)) kv("Body sent", is_array($body) ? $body : substr($body, 0, 200));

    echo "\n  \033[0;33m⟶  Sending…{$NC}\n";

    try {
        $response = $runner->run($request);
        $code = $response->getHttpStatusCode();
        $codeColor = $code < 300 ? $GRN : ($code < 400 ? "\033[1;33m" : $RED);
        echo "  {$codeColor}✔  HTTP {$code}{$NC}\n";
        kv("Response Headers", $response->getHeader());

        $bodyStr = $response->getBody();
        if ($bodyStr !== '') {
            // Parse JSON for pretty print if possible
            $decoded = json_decode($bodyStr, true);
            kv("Response Body", $decoded ?? substr($bodyStr, 0, 400));
        }
    } catch (\Exception $e) {
        echo "  {$RED}✘  ERROR: " . $e->getMessage() . "{$NC}\n";
    }
}

// ══════════════════════════════════════════════════════════════
//  1. CREATE PROJECT
// ══════════════════════════════════════════════════════════════
sec("① CREATE PROJECT");

$project = new Project(
    "HTTP Tester Suite",
    "Full end-to-end test of all HTTP methods with transformed parameters"
);

kv("Name",        $project->getName());
kv("Slug",        $project->getSlug());
kv("Description", $project->getDescription());
kv("Created At",  $project->getCreatedAt()->format('Y-m-d H:i:s'));

// ── Shared infra ─────────────────────────────────────────────
$builder = new HttpRequestBuilder();
$runner  = (new HttpRequestRunner())
    ->withTimeout(20)
    ->withConnectTimeout(8)
    ->withSslVerification(false)
    ->withFollowRedirects(true, 3);

$host = "https://httpbin.org";

// ══════════════════════════════════════════════════════════════
//  2. DEFINE 6 TASKS
// ══════════════════════════════════════════════════════════════
sec("② DEFINE 6 TASKS");

// ─── TASK 1 · GET ────────────────────────────────────────────
$t1 = new HttpTask(
    "Fetch User Info",
    "{$host}/get",
    "GET with query params; token is Base64-encoded before sending",
    HttpRequestMethod::GET
);
$t1->addHeader(new Parameter(HttpHeaderCategory::ACCEPT, "application/json", DataType::TEXT));
$t1->addHeader(new Parameter(HttpHeaderCategory::USER_AGENT, "HttpTaskSuite/1.0", DataType::TEXT));
$t1->addQuery(new Parameter("user_id", "42", DataType::INTEGER));
$t1->addQuery(new Parameter(
    "token", "secret-api-token", DataType::TEXT,
    [new TransformStep(EncodeType::BASE64)]          // secret-api-token → base64
));

// ─── TASK 2 · POST ───────────────────────────────────────────
$t2 = new HttpTask(
    "Create User",
    "{$host}/post",
    "POST JSON body; password SHA-256 hashed, username ROT-13 encoded",
    HttpRequestMethod::POST
);
$t2->getHeaders()->setBodyType(new Parameter(
    HttpHeaderCategory::CONTENT_TYPE, HttpBodyType::JSON, DataType::TEXT
));
$t2->addHeader(new Parameter(HttpHeaderCategory::ACCEPT, "application/json", DataType::TEXT));
$t2->addBody(new Parameter(
    "username", "jox_developer", DataType::TEXT,
    [new TransformStep(EncodeType::ROT13)]           // rot-13 obfuscation demo
));
$t2->addBody(new Parameter(
    "password", "my-super-secret-pass", DataType::TEXT,
    [new TransformStep(HashType::SHA256)]            // sha256 hash
));
$t2->addBody(new Parameter("email", "jox@example.com", DataType::TEXT));
$t2->addBody(new Parameter("role", "admin", DataType::TEXT));

// ─── TASK 3 · PUT ────────────────────────────────────────────
$t3 = new HttpTask(
    "Replace Profile",
    "{$host}/put",
    "PUT form-urlencoded; bio is URL-encoded",
    HttpRequestMethod::PUT
);
$t3->getHeaders()->setBodyType(new Parameter(
    HttpHeaderCategory::CONTENT_TYPE, HttpBodyType::FORM_URLENCODED, DataType::TEXT
));
$t3->addHeader(new Parameter(HttpHeaderCategory::AUTHORIZATION, "Bearer test-token-xyz", DataType::TEXT));
$t3->addBody(new Parameter("display_name", "Jox Developer", DataType::TEXT));
$t3->addBody(new Parameter(
    "bio", "PHP OOP & Clean Architecture lover / builder",
    DataType::TEXT,
    [new TransformStep(EncodeType::URL)]             // url-encode special chars
));
$t3->addBody(new Parameter("location", "Surabaya, ID", DataType::TEXT));

// ─── TASK 4 · PATCH ──────────────────────────────────────────
$t4 = new HttpTask(
    "Patch User Status",
    "{$host}/patch",
    "PATCH JSON; status hex-encoded, score SHA3-256 hashed",
    HttpRequestMethod::PATCH
);
$t4->getHeaders()->setBodyType(new Parameter(
    HttpHeaderCategory::CONTENT_TYPE, HttpBodyType::JSON, DataType::TEXT
));
$t4->addHeader(new Parameter(HttpHeaderCategory::X_API_KEY, "patch-key-00001", DataType::TEXT));
$t4->addBody(new Parameter("user_id", "42", DataType::INTEGER));
$t4->addBody(new Parameter(
    "status", "active", DataType::TEXT,
    [new TransformStep(EncodeType::HEX)]             // hex encode
));
$t4->addBody(new Parameter(
    "score_checksum", "9999", DataType::TEXT,
    [new TransformStep(HashType::SHA3_256)]          // sha3-256 hash
));

// ─── TASK 5 · DELETE ─────────────────────────────────────────
$t5 = new HttpTask(
    "Delete Resource",
    "{$host}/delete",
    "DELETE with MD5-hashed resource ID in query string",
    HttpRequestMethod::DELETE
);
$t5->addHeader(new Parameter(HttpHeaderCategory::X_API_KEY, "delete-key-99999", DataType::TEXT));
$t5->addQuery(new Parameter("resource_type", "user", DataType::TEXT));
$t5->addQuery(new Parameter(
    "resource_id", "resource-object-999", DataType::TEXT,
    [new TransformStep(HashType::MD5)]               // md5 hashed ID
));

// ─── TASK 6 · HEAD ───────────────────────────────────────────
$t6 = new HttpTask(
    "Health Check",
    "{$host}/get",
    "HEAD — inspect response headers without downloading a body",
    HttpRequestMethod::HEAD
);
$t6->addHeader(new Parameter(HttpHeaderCategory::USER_AGENT, "HttpTaskSuite/1.0 (Health)", DataType::TEXT));
$t6->addQuery(new Parameter("probe", "1", DataType::INTEGER));

// Register all tasks
foreach ([$t1, $t2, $t3, $t4, $t5, $t6] as $t) {
    $project->addTask($t);
}

echo "  Tasks registered: " . count($project->getTasks()) . "\n";

// ══════════════════════════════════════════════════════════════
//  3. EXECUTE TASKS
// ══════════════════════════════════════════════════════════════
sec("③ EXECUTE — sending requests to httpbin.org");

foreach ($project->getTasks() as $task) {
    runTask($task, $runner, $builder);
}

// ══════════════════════════════════════════════════════════════
//  4. PROJECT SUMMARY
// ══════════════════════════════════════════════════════════════
sec("④ PROJECT SUMMARY");
echo "\n";
printf("  %-4s  %-26s  %-7s  %s\n", "#", "Task", "Method", "URL");
echo "  " . str_repeat("─", 70) . "\n";
foreach ($project->getTasks() as $i => $task) {
    /** @var HttpTask $task */
    printf(
        "  %-4s  %-26s  %s  %s\n",
        "[" . ($i + 1) . "]",
        $task->getName(),
        badge($task->getMethod()->value),
        $task->getAddress()
    );
}

echo "\n\033[1;32m  ✔  Done — {$project->getName()} · " . count($project->getTasks()) . " tasks executed.\033[0m\n\n";