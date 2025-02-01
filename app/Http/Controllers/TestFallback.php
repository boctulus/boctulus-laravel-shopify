<?php

namespace App\Http\Controllers;

use Boctulus\ApiClient\ApiClientFallback as ApiClientFallbackAlias;
use Boctulus\ApiClient\Helpers\VarDump;

class TestFallback extends Controller
{
    function test_apiclientfallback_package(){
        $client = new ApiClientFallbackAlias();

        $client->cache(3600);

        $client
        ->setHeaders([
            "Content-type" => "application/json"
        ])        
        ->get("https://jsonplaceholder.typicode.com/posts/1");

        $res = $client->getResponse();

        VarDump::dd($client->status(), 'STATUS');
        VarDump::dd($client->error(), 'ERROR');
        
        VarDump::dd($client
        ->decode()
        ->data(), 'DATA');
    }
}
