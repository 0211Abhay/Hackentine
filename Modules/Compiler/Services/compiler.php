<?php
header("Access-Control-Allow-Origin: *"); // This allows all origins
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

    $language = strtolower($_POST['language']);
    $code = $_POST['code'];

    $random = substr(md5(mt_rand()), 0, 7);
    $filePath = "../Submissions/" . $random . "." . $language;
    $programFile = fopen($filePath, "w");
    fwrite($programFile, $code);
    fclose($programFile);

    $output = "" ;

    if ($language == "java") {
        $className = pathinfo($filePath, PATHINFO_FILENAME);
        $compileOutput = shell_exec("javac $filePath 2>&1");
        if (!$compileOutput) {
            $output = shell_exec("java -cp temp $className 2>&1");
        } else {
            $output = $compileOutput;
        }
    }

    if ($language == "py") {
        $output = shell_exec("C:\Python313\python.exe $filePath 2>&1");
    }

    if ($language == "javascript") {
        rename($filePath, $filePath . ".js");
        $output = shell_exec("node $filePath.js 2>&1");
    }

    if ($language == "c") {
        $outputExe = "temp/" . $random . ".exe";
        $compileOutput = shell_exec("gcc $filePath -o $outputExe 2>&1");
        if (!$compileOutput) {
            $output = shell_exec(__DIR__ . "/$outputExe 2>&1");
        } else {
            $output = $compileOutput;
        }
    }

    if ($language == "cpp") {
        $outputExe = "temp/" . $random . ".exe";
        $compileOutput = shell_exec("g++ $filePath -o $outputExe 2>&1");
        if (!$compileOutput) {
            $output = shell_exec(__DIR__ . "/$outputExe 2>&1");
        } else {
            $output = $compileOutput;
        }
    }

    echo "$output";

?>
