<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminTasks extends Controller
{
    public function list(){
        $dir = base_path() . '/database/migrations';

        $files = scandir($dir);
        foreach($files as $file){
            if($file == '.' || $file == '..'){
                continue;
            }
            echo $dir . DIRECTORY_SEPARATOR .  $file . "<br>";
        }
    }
}
