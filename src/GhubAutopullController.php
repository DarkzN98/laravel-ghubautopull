<?php

namespace Darkzn\Ghubautopull;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class GhubAutopullController extends Controller
{
    public function handlehook(Request $request)
    {
        // Check If Server .ENV file has GHUB_WEBHOOK_SECRET and GHUB_BRANCH_DEPLOY
        $webhookSecret = env('GHUB_WEBHOOK_SECRET');
        $webhookBranch = env('GHUB_BRANCH_DEPLOY');
        if (!isset($webhookSecret) || !isset($webhookBranch)) {
            return response()
                ->json(["code" => "500", "message" => "Webhook Secret Not Configured!"], 500);
        }

        // Get Headers
        header('Content-Type: application/json');
        $requestHeaders = $request->header();

        // Check if Github Signature Exists (Github Secret)
        if (!isset($requestHeaders['x-hub-signature-256'])) {
            Log::info('Received Unauthorized Webhook Request');
            return response()
                ->json(["code" => "403", "message" => "You are not authorized!"], 403);
        }

        // Check if Github Signature Matches Configuration
        $requestContent = $request->getContent();
        $serverHash = 'sha256=' . hash_hmac('sha256', $requestContent, $webhookSecret);
        if (!hash_equals($serverHash, $requestHeaders['x-hub-signature-256'][0])) {
            Log::info('Received Wrong Webhook Secret Request');
            return response()
                ->json(["code" => "403", "message" => "Wrong Webhook Secret!"], 403);
        }

        // Check Webhook Event Exists
        if (!isset($requestHeaders['x-github-event'])) {
            Log::info('Received Webhook Request Without Event');
            return response()
                ->json(["code" => "400", "message" => "Bad Request, Sorry!"], 400);
        }

        // Check Webhook Events
        $webhookEvent = $requestHeaders['x-github-event'][0];
        if ($webhookEvent == 'ping') {
            Log::info('Received Webhook Ping Request');
            return response()
                ->json(["code" => "200", "message" => "Pong! You're Good to Go!"], 200);
        } else if ($webhookEvent == 'push') {

            // handle push event
            $webhookPayload = json_decode($request->get('payload'));

            // Check branch / ref
            $webhookRef = explode('/', $webhookPayload->ref);
            if ($webhookBranch != end($webhookRef)) {
                return response()
                    ->json(["code" => "200", "message" => "No Action Taken, Because it's not for deployment!"], 200);
            }

            // PULL COMMAND SUCCESFULLY RECEIVED.
            // DO PULL
            $date = new \DateTime();
            shell_exec("php ".\realpath(__DIR__)."/GitPull.php >> ".base_path()."/storage/logs/webhook-".$date->format("Y-m-d")." 2>&1 &");
            return response()
                ->json(["code" => "202", "message" => "Autopull Command Received!"], 202);
        }

        Log::info('Received Unhandled Webhook Request');
        return response()
            ->json(["code" => "400", "message" => "Bad Request, Unable to Handle Webhook Event"], 400);
    }
}
