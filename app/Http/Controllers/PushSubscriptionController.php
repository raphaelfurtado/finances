<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PushSubscriptionController extends Controller
{
    public function update(Request $request)
    {
        $this->validate($request, [
            'endpoint' => 'required',
            'keys.auth' => 'required',
            'keys.p256dh' => 'required'
        ]);

        $endpoint = $request->endpoint;
        $key = $request->keys['p256dh'];
        $token = $request->keys['auth'];
        $contentEncoding = $request->content_encoding ?? 'aesgcm';

        $user = auth()->user();
        if ($user) {
            $user->updatePushSubscription($endpoint, $key, $token, $contentEncoding);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 401);
    }

    public function destroy(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);

        $user = auth()->user();
        if ($user) {
            $user->deletePushSubscription($request->endpoint);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false], 401);
    }
}
