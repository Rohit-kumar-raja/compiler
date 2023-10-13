<?php

namespace App\Http\Controllers\Compiler\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompilerController extends Controller
{
    public function run(Request $request)
    {
        if ($request->code && $request->lang) {
            $unique_folder = uniqid("code");
            mkdir(env("COMPILER") . $unique_folder);
            $uniqu_file=uniqid('c').'.php';
            $complete_file=env("COMPILER") .$unique_folder. "/" .$uniqu_file;
            touch($complete_file);
            $file = fopen($complete_file, 'w');
            if ($request->lang == "php") {
                $text = "<?php " . $request->code . " ?>";
            } else {
                $text = $request->code;
            }

            fwrite($file, $text);
            $command = "cd ".env('COMPILER').$unique_folder.";". $request->lang . " " . $complete_file . " 2>&1";
            exec($command, $output, $exitStatus);

            if ($exitStatus !== 0) {
                foreach ($output as $line) {
                    echo "<p style='color:red'>$line<p>";
                }
            } else {
                foreach ($output as $line) {
                    echo "<p style='color:green'>$line</p>";
                }
            }
        }
    }
}
