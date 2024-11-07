<?php
/**
 * php-sharp - A PHP wrapper for sharp js
 *
 * @link        https://github.com/kiatng/php-sharp
 * @copyright   2024 Ng Kiat Siong
 * @license     MIT
 */

namespace Kiatng\Sharp;

use Symfony\Component\Process\Process;
use Exception;

class Sharp
{
    /**
     * @param array $io {input: {is_raw: bool, data: string, ?ext: string}, output: {is_raw: bool, ?file: string}}
     * @param array $params {toFormat: {format: string, options: object}, {resize: {width: int, height: int, options: object}}}
     * @return string
     * @throws Exception
     */
    public static function run(array $io, array $params)
    {
        try {
            if ($io['input']['is_raw']) {
                $data = $io['input']['data'];
                $ext = $io['input']['ext'];
                $file = sys_get_temp_dir() . '/' . uniqid('sharp') . '.' . $ext;
                if (file_put_contents($file, $data) === false) {
                    throw new Exception('Failed to write temporary input file');
                }
                $io['input']['tmp_file'] = $file;
            } else {
                if (!file_exists($io['input']['data'])) {
                    throw new Exception('Input file not found: ' . $io['input']['data']);
                }
                $file = $io['input']['data'];
            }

            $process = new Process(['node', __DIR__ . '/sharp.js', $file, json_encode($params)]);
            $process->run();

            // Cleanup temporary file
            if ($io['input']['is_raw'] && isset($io['input']['tmp_file'])) {
                @unlink($io['input']['tmp_file']);
            }

            // Check for Sharp.js errors
            $errorOutput = $process->getErrorOutput();
            if ($errorOutput) {
                // Strip the SHARP_ERROR prefix if present
                $errorMessage = preg_replace('/^SHARP_ERROR:\s*/', '', $errorOutput);
                throw new Exception($errorMessage);
            }

            $output = $process->getOutput();
            if (empty($output)) {
                throw new Exception('Sharp.js produced no output');
            }

            if ($io['output']['is_raw']) {
                return $output;
            }

            if (file_put_contents($io['output']['file'], $output) === false) {
                throw new Exception('Failed to write output file');
            }
            return $io['output']['file'];
        } catch (Exception $e) {
            // Cleanup temporary file in case of errors
            if ($io['input']['is_raw'] && isset($io['input']['tmp_file'])) {
                @unlink($io['input']['tmp_file']);
            }
            throw new Exception($e->getMessage());
        }
    }
}
