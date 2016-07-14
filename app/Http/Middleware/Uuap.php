<?php
/***************************************************************************
 *
 * Copyright (c) 2016 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/

namespace App\Http\Middleware;

/**
 * @file Uuap.php
 * @author zhangwei51(com@baidu.com)
 * @date 2016/05/17 14:51:46
 * @brief
 *
 **/

use Closure;
use UUAP_Index;

class Uuap
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $strUserId = UUAP_Index::checkLogin();
        view()->share('username', $strUserId);
        if($request->input('username') == null) {
            $request->request->add(['username' => $strUserId]);
        }
        return $next($request);
    }
}
