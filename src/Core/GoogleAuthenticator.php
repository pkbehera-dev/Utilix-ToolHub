<?php
namespace App\Core;

class GoogleAuthenticator {

    /**
     * Generate a random base32 string for the TOTP secret
     */
    public static function generateSecret(int $length = 16): string {
        $b32 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ234567";
        $s = "";
        for ($i = 0; $i < $length; $i++) {
            $s .= $b32[random_int(0, 31)];
        }
        return $s;
    }

    /**
     * Get the QR Code URL for Google Authenticator
     */
    public static function getQrCodeUrl(string $company, string $holder, string $secret): string {
        $url = sprintf("otpauth://totp/%s:%s?secret=%s&issuer=%s", rawurlencode($company), rawurlencode($holder), $secret, rawurlencode($company));
        // Using an external QR generation API to keep it vanilla PHP without GD barcode dependencies
        return "https://api.qrserver.com/v1/create-qr-code/?data=" . urlencode($url) . "&size=200x200&ecc=M";
    }

    /**
     * Verify a TOTP code against a secret
     */
    public static function verifyCode(string $secret, string $code, int $discrepancy = 1): bool {
        $currentTimeSlice = floor(time() / 30);
        
        for ($i = -$discrepancy; $i <= $discrepancy; $i++) {
            $calculatedCode = self::calculateCode($secret, $currentTimeSlice + $i);
            if (hash_equals($calculatedCode, $code)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Calculate the code, given the secret and point in time
     */
    private static function calculateCode(string $secret, int $timeSlice): string {
        $secretKey = self::base32Decode($secret);
        
        // Pack time into binary string
        $time = chr(0).chr(0).chr(0).chr(0).pack('N*', $timeSlice);
        
        // Hash it with SHA1
        $hm = hash_hmac('SHA1', $time, $secretKey, true);
        
        // Use last nibble of result as index/offset
        $offset = ord(substr($hm, -1)) & 0x0F;
        
        // Grab 4 bytes of the result
        $hashpart = substr($hm, $offset, 4);
        
        // Unpack binary value
        $value = unpack('N', $hashpart);
        $value = $value[1];
        
        // Only 32 bits
        $value = $value & 0x7FFFFFFF;
        
        $modulo = pow(10, 6);
        return str_pad((string)($value % $modulo), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Helper to decode Base32 strings
     */
    private static function base32Decode(string $secret): string {
        if (empty($secret)) return '';

        $base32chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ234567';
        $base32charsFlipped = array_flip(str_split($base32chars));

        $paddingCharCount = substr_count($secret, '=');
        $allowedValues = [6, 4, 3, 1, 0];
        if (!in_array($paddingCharCount, $allowedValues)) {
            return false;
        }
        for ($i = 0; $i < 4; $i++) {
            if ($paddingCharCount == $allowedValues[$i] &&
                substr($secret, -($allowedValues[$i])) != str_repeat('=', $allowedValues[$i])) {
                return false;
            }
        }
        $secret = str_replace('=', '', $secret);
        $secret = str_split($secret);
        $binaryString = '';
        for ($i = 0; $i < count($secret); $i = $i + 8) {
            $x = '';
            if (!in_array($secret[$i], str_split($base32chars))) {
                return false;
            }
            for ($j = 0; $j < 8; $j++) {
                if (isset($secret[$i + $j])) {
                    $x .= str_pad(base_convert((string)$base32charsFlipped[$secret[$i + $j]], 10, 2), 5, '0', STR_PAD_LEFT);
                }
            }
            $eightBits = str_split($x, 8);
            for ($z = 0; $z < count($eightBits); $z++) {
                if (strlen($eightBits[$z]) == 8) {
                    $binaryString .= (($y = chr((int)base_convert($eightBits[$z], 2, 10))) || ord($y) == 48) ? $y : '';
                }
            }
        }
        return $binaryString;
    }
}
