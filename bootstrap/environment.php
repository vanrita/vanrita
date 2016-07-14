<?php
/***************************************************************************
 * 
 * Copyright (c) 2015 Baidu.com, Inc. All Rights Reserved
 * 
 **************************************************************************/
 
 
 
/**
 * @file environment.php
 * @author zhangwei51(com@baidu.com)
 * @date 2015/12/29 18:54:44
 * @brief 
 *  
 **/
/*
|--------------------------------------------------------------------------
| Detect The Application Environment
|--------------------------------------------------------------------------
|
| Laravel takes a dead simple approach to your application environments
| so you can just specify a machine name for the host that matches a
| given environment, then we will automatically detect it for you.
|
*/

$env = $app->detectEnvironment(function(){
    $environmentPath = __DIR__.'/../.env';
    $setEnv = trim(file_get_contents($environmentPath));
    if (file_exists($environmentPath))
    {
        putenv($setEnv);
        if (getenv('APP_ENV') && file_exists(__DIR__.'/../.' .getenv('APP_ENV') .'.env')) {
            $dotenv = new \Dotenv\Dotenv(__DIR__ . '/../', '.' . getenv('APP_ENV') . '.env');
            $dotenv->load();
        }
    }
});

?>
