<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;

trait Responser
{

    protected function successResponse($data = null, $message = 'OK', $code = 200, $xtraData = [])
    {
        $response = [
            //'code'		=>	200,
            'message'   => $message,
            'data'      => $data
        ];
        $response["deubug_data"] = $xtraData;
        return response()->json($response, $code);
    }

    protected function errorResponse($data = null, $message = 'Bad Request', $code = 400)
    {
        throw new HttpResponseException(response()->json([
            //'code'        =>    $code,
            'message'   => $message,
            'data'      => $data
        ], $code));
    }

    protected function failedAjaxResponse()
    {
        if (!env('AJAX_VALIDATION')) {
            return false;
        }
        if (!$this->ajax()) {
            $this->errorResponse(null, 'only ajax is accepted', 403);
        }
    }

    protected function queryGolbalFilter($Query, $request)
    {
        $this->FilterQuery($Query, $request);
        $paginate = $request->paginate ?? 60;
        if ($request->orderBy) {
            $request->orderBy == 'random' ?
                $Query->inRandomOrder() :
                $Query->orderby($request->orderByColumn, $request->orderBy);
        }
        $request->has('limit') ? $Query->limit($request->limit) : null;
        $result = $request->isPaginate ? $Query->paginate($paginate) : $Query->get();
        return $result;
    }
}
