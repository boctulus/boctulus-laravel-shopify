<?php

namespace App\Http\Controllers;

use Boctulus\ApiClient\ApiClientFallback as ApiClientFallbackAlias;

class TestFallback extends Controller
{
    function test_apiclientfallback_package(){
        $client = new ApiClientFallbackAlias();

        $client
        ->setHeaders([
            "Content-type" => "application/json"
        ])
        ->get("https://jsonplaceholder.typicode.com/posts/1");

        $res = $client->getResponse();

        var_dump($client->status());
        var_dump($client->error());

        var_dump($client
        ->decode()
        ->data());
    }
}
