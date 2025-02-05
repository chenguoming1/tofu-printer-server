<?php
namespace App\Helpers;

use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;

class TerminalHelper
{
    // Example usage
    // $ecn = "000000005764";  // 12-byte ECN
    // $funcCode = "55";      // Function Code
    // $versionCode = "01";   // Version Code

    // $hexString = composeRequestHeader($ecn, $funcCode, $versionCode);
    // echo "Hex Request Header: " . $hexString . PHP_EOL;
    public static function composeRequestHeader($ecn, $funcCode, $versionCode) {
        $ecn = static::funcCodeToHex($ecn);

        // Convert code codes to 2 bytes hex
        $funcCode = static::funcCodeToHex($funcCode);
        $versionCode = static::funcCodeToHex($versionCode);

        // Validate ECN length (must be exactly 12 bytes)
        if (strlen($ecn) !== 24) {
            throw new Exception("ECN must be exactly 24-character hexadecimal string.");
        }

        // Validate Func Code (2 bytes in hex)
        if (strlen($funcCode) !== 4 || !ctype_xdigit($funcCode)) {
            throw new Exception("Function Code must be a 4-character hexadecimal string.");
        }

        // Validate Version Code (2 bytes in hex)
        if (strlen($versionCode) !== 4 || !ctype_xdigit($versionCode)) {
            throw new Exception("Version Code must be a 4-character hexadecimal string.");
        }

        // RFU and Separator (fixed values)
        $rfu = "\x30";  // Default Reserved Field
        $separator = "\x1C"; // Separator

        // Construct the header as a binary string
        $header = hex2bin($ecn) . hex2bin($funcCode) . hex2bin($versionCode) . $rfu . $separator;

        // Convert the full header to a hexadecimal string
        return strtoupper(bin2hex($header));
    }

    // // Example usage
    // $fieldCode = "000A"; // Example field code
    // $data = "Hello, World!"; // Example data
    // $hexMessage = composeMessageData($fieldCode, $data);
    // echo "Hex Message Data: " . $hexMessage . PHP_EOL;
    public static function composeMessageData($fieldCode, $data) {
        $fieldCode = static::funcCodeToHex($fieldCode);

        // Validate Field Code (2 bytes in hex)
        if (strlen($fieldCode) !== 4 || !ctype_xdigit($fieldCode)) {
            throw new Exception("Field Code must be a 4-character hexadecimal string.");
        }

        // Calculate data length
        $length = strlen($data);

        // Convert length to a 2-byte hex representation
        $lengthHex = str_pad(dechex($length), 4, "0", STR_PAD_LEFT);

        // Separator (fixed value)
        $separator = "\x1C";

        // Construct the full message
        $message = hex2bin($fieldCode) . hex2bin($lengthHex) . $data . $separator;

        // Convert to uppercase hex string
        return strtoupper(bin2hex($message));
    }

    // // Example usage
    // $hexMessage = "000A000D48656C6C6F2C20576F726C64211C"; // Example message
    // try {
    //     $parsedMessage = decomposeMessageData($hexMessage);
    //     print_r($parsedMessage);
    // } catch (Exception $e) {
    //     echo "Error: " . $e->getMessage() . PHP_EOL;
    // }
    public static function decomposeMessageData($hexString) {
        // Convert hex string to binary
        $binaryData = hex2bin($hexString);

        // Validate minimum length (5 bytes: 2 for field code, 2 for length, 1 for separator)
        if (strlen($binaryData) < 5) {
            throw new Exception("Invalid message data length.");
        }

        // Extract field code (first 2 bytes)
        $fieldCode = strtoupper(bin2hex(substr($binaryData, 0, 2)));

        // Extract length (next 2 bytes) and convert from hex to decimal
        $lengthHex = bin2hex(substr($binaryData, 2, 2));
        $length = hexdec($lengthHex);

        // Validate total length
        if (strlen($binaryData) !== (5 + $length - 1)) {
            throw new Exception("Mismatch between specified length and actual data size.");
        }

        // Extract data (next 'length' bytes)
        $data = substr($binaryData, 4, $length);

        // Extract separator (last byte)
        $separator = bin2hex(substr($binaryData, 4 + $length, 1));

        // Validate separator
        if ($separator !== "1C") {
            throw new Exception("Invalid Separator value. Expected '1C'.");
        }

        // Return decomposed values
        return [
            "Field Code" => $fieldCode,
            "Length" => $length,
            "Data" => $data,
            "Separator" => $separator
        ];
    }

    // // Example usage
    // $hexResponseHeader = "4142434445464748494A4B4C00010002301C"; // Example response header
    // try {
    //     $parsedHeader = decomposeResponseHeader($hexResponseHeader);
    //     print_r($parsedHeader);
    // } catch (Exception $e) {
    //     echo "Error: " . $e->getMessage() . PHP_EOL;
    // }
    public static function decomposeResponseHeader($hexString) {
        // Convert hex string to binary
        $binaryData = hex2bin($hexString);
    
        // Validate total length (18 bytes)
        if (strlen($binaryData) !== 18) {
            throw new Exception("Invalid response header length. Expected 18 bytes.");
        }
    
        // Extract fields
        $ecn = substr($binaryData, 0, 12); // First 12 bytes (ASCII)
        $funcCode = strtoupper(bin2hex(substr($binaryData, 12, 2))); // Next 2 bytes (Hex)
        $responseCode = strtoupper(bin2hex(substr($binaryData, 14, 2))); // Next 2 bytes (Hex)
        $rfu = bin2hex(substr($binaryData, 16, 1)); // 1 byte RFU
        $separator = bin2hex(substr($binaryData, 17, 1)); // 1 byte Separator
    
        // Validate RFU and Separator
        if ($rfu !== "30") {
            throw new Exception("Invalid RFU value. Expected '30'.");
        }
        if ($separator !== "1C") {
            throw new Exception("Invalid Separator value. Expected '1C'.");
        }
    
        // Return decomposed values as an associative array
        return [
            "ECN" => $ecn,
            "Function Code" => $funcCode,
            "Response Code" => $responseCode,
            "RFU" => $rfu,
            "Separator" => $separator
        ];
    }

    // // Example usage
    // $header = "4142434445464748494A4B4C00010001301C"; // Example header
    // $body1 = "000A000548656C6C6F1C"; // Example body (Field Code: 000A, Data: "Hello")
    // $body2 = "000B00066D6F746F721C"; // Example body (Field Code: 000B, Data: "motor")
    
    // $hexRequest = composeFinalRequest($header, [$body1, $body2]);
    // echo "Final Request Hex: " . $hexRequest . PHP_EOL;
    public static function composeFinalRequest($header, $bodies) {
        // Combine header and multiple message bodies
        $message = hex2bin($header);
        foreach ($bodies as $body) {
            $message .= hex2bin($body);
        }
    
        // Calculate total message length (including LRC)
        $length = '' . (strlen($message) + 1); // +1 lrc
        $lengthHex = static::intStringToBCD($length);
        
        // Compute LRC for error checking
        $lrc = static::computeLRC($message);
    
        // Construct final request: length + message + LRC
        $finalRequest = hex2bin($lengthHex) . $message . $lrc;
    
        // Return the full hex string
        return strtoupper(bin2hex($finalRequest));
    }

    // // Example usage
    // $hexRequest = "00244142434445464748494A4B4C00010001301C000A000548656C6C6F1C000B00066D6F746F721C5A"; // Example request
    // try {
    //     $parsedRequest = decomposeFinalRequest($hexRequest);
    //     print_r($parsedRequest);
    // } catch (Exception $e) {
    //     echo "Error: " . $e->getMessage() . PHP_EOL;
    // }
    public static function decomposeFinalRequest($hexString) {
        // Convert hex to binary
        $binaryData = hex2bin($hexString);
    
        // Extract length (first 2 bytes)
        $lengthHex = bin2hex(substr($binaryData, 0, 2));
        $length = (int) static::BCDToIntString($lengthHex);
    
        // Extract message (excluding LRC)
        $message = substr($binaryData, 2, $length - 1);
    
        // Extract LRC (last byte)
        $lrc = strtoupper(bin2hex(substr($binaryData, 2 + $length - 1, 1)));
    
        // Compute expected LRC
        $computedLRC = strtoupper(bin2hex(static::computeLRC($message)));
    
        // Validate LRC
        if ($lrc !== $computedLRC) {
            throw new Exception("LRC mismatch! Expected: $computedLRC, Found: $lrc");
        }
    
        return [
            "Length" => $length,
            "Message" => strtoupper(bin2hex($message)),
            "LRC" => $lrc
        ];
    }

    public static function computeLRC($data) {
        $lrc = 0;
        for ($i = 0; $i < strlen($data); $i++) {
            $lrc ^= ord($data[$i]); // XOR all bytes
        }
        return chr($lrc);
    }    

    public static function generateRandomECN() {
        $ecn = '' . time();

        $ecn = str_pad($ecn, 12, "0", STR_PAD_LEFT);

        return strtoupper(bin2hex($ecn));
    }

    // Function to convert a function code to hexstring
    public static function funcCodeToHex(string $code) {
        return bin2hex($code);
    }

    static function intStringToBCD($intString) {
        // Ensure the input is a valid numeric string
        if (!ctype_digit($intString)) {
            throw new InvalidArgumentException("Input must be a numeric string.");
        }
    
        // Pad the input to make it exactly 4 digits (2 bytes in BCD)
        $bcdString = str_pad($intString, 4, "0", STR_PAD_LEFT);
    
        // Convert to BCD format
        return strtoupper(bin2hex(pack("H*", $bcdString)));
    }

    static function BCDToIntString($bcdHex) {
        // Ensure the input is a valid 4-digit hexadecimal string
        if (!ctype_xdigit($bcdHex) || strlen($bcdHex) !== 4) {
            throw new InvalidArgumentException("Input must be a 4-character hexadecimal string.");
        }
    
        // Convert BCD hex to raw binary and then to a numeric string
        return ltrim(implode("", unpack("H*", hex2bin($bcdHex))), "0") ?: "0";
    }
}

// // Generate a random ECN
// $ecn = TerminalHelper::generateECN();
// $funcCode = "55"
// $versionCode = "01"
// // Step 1: Compose the request header with the random ECN, function code, and version code
// $requestHeader = TerminalHelper::composeRequestHeader($ecn, $funcCode, $versionCode);

// // Step 2: Optionally, you can add message data if needed
// // For example, let's assume we are adding some dummy data to the message
// $fieldCode = 10;  // Example field code
// $data = "Sample data";  // Example data to be included
// $messageData = TerminalHelper::composeMessageData($fieldCode, $data);

// // Step 3: Combine header and message data into the final request
// $finalRequest = TerminalHelper::composeFinalRequest($requestHeader, $messageData);

// // Output the final request (typically you would send this request over a network or similar)
// echo "Final Request: " . $finalRequest . "\n";
