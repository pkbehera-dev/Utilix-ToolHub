<?php
namespace App\Controllers;

class SpeedTestController {

    /**
     * Streams dummy binary data to measure download speed.
     */
    public function download(): void {
        // Prevent buffering
        if (ob_get_level()) {
            ob_end_clean();
        }

        // Disable compression if possible
        if (function_exists('apache_setenv')) {
            @apache_setenv('no-gzip', '1');
        }
        @ini_set('zlib.output_compression', '0');

        // Headers to prevent caching and define content
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Encoding: identity');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
        header('Cache-Control: post-check=0, pre-check=0', false);
        header('Pragma: no-cache');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header('Content-Transfer-Encoding: binary');

        // Continuous streaming loop for up to 10 seconds
        $startTime = microtime(true);
        $chunkSize = 256 * 1024; // 256KB chunks

        // Output headers immediately
        flush();

        while (true) {
            // Stop if client disconnected or 10-second limit reached
            if (connection_aborted() || (microtime(true) - $startTime) > 10.0) {
                break;
            }

            try {
                echo random_bytes($chunkSize);
            } catch (\Exception $e) {
                echo str_repeat('A', $chunkSize);
            }

            flush();
        }
        exit;
    }

    /**
     * Receives and discards upload data to measure upload speed.
     */
    public function upload(): void {
        // Prevent buffering
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: application/json');
        header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');

        $input = fopen('php://input', 'r');
        $bytesRead = 0;

        if ($input) {
            $blockSize = 64 * 1024; // 64KB chunk size
            while (!feof($input)) {
                $data = fread($input, $blockSize);
                if ($data === false || connection_aborted()) {
                    break;
                }
                $bytesRead += strlen($data);
            }
            fclose($input);
        }

        echo json_encode([
            'success' => true,
            'bytes_uploaded' => $bytesRead
        ]);
        exit;
    }
}
