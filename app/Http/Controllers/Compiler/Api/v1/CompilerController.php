<?php

namespace App\Http\Controllers\Compiler\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CompilerController extends Controller
{
    public function run(Request $request)
    {
        if ($request->code && $request->lang) {
            $file = fopen(env('COMPILER'), 'w');
            if ($request->lang == "php") {
                $text = "<?php " . $request->code . " ?>";
            } else {
                $text = $request->code;
            }

            fwrite($file, $text);
            $command = $request->lang . " " . env('COMPILER') . " 2>&1";
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
