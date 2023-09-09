<?php

namespace FennecTech\LaravelSingleSignOn\Http\Responses;

use FennecTech\LaravelSingleSignOn\Http\Controllers\SingleSignOnController;
use Illuminate\Http\JsonResponse;
use Laravel\Fortify\Contracts\LogoutResponse as LogoutResponseContract;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

class LogoutResponse implements LogoutResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        SingleSignOnController::logout($request);
        return $request->wantsJson()
            ? new JsonResponse('', 204)
            : redirect(LaravelLocalization::getCurrentLocale() . '/');
    }
}
