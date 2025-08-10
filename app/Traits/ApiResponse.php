<?php

namespace App\Traits;

use Illuminate\Support\Facades\App;

trait ApiResponse
{
    protected function success($message = '', $data = [], $status = 200)
    {
        $res = [
            'success' => true,
        ];

        if(!empty($data)) {
            $res['data'] = $data;
        }

        if (!empty($message)) {
            $res['message'] = $message;
        }

        return response($res, $status);
    }

    protected function failure($message, $status = 422, $e = null)
    {
        $resp = [
            'success' => false,
            'message' => $message,
        ];

        if ($e && App::environment('local')) {
            if ($e instanceof \Exception) {
                $resp['exception'] = $e->getMessage();
            } else if (is_string($e)) {
                $resp['exception'] = $e;
            }
        }

        return response($resp, $status);
    }
}
