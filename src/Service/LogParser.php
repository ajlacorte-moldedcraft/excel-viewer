<?php

namespace App\Service;

class LogParser
{
    public function parse(string $filePath, array $filter = []): array
    {
        $lines = file($filePath);
        $logs = [];

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            // Extract timestamp (between [ and ])
            $start = strpos($line, '[');
            $end = strpos($line, ']');
            if ($start === false || $end === false) {
                continue;
            }

            $timestamp = substr($line, $start + 1, $end - $start - 1);

            // After timestamp
            $remaining = trim(substr($line, $end + 1));

            // Find the first colon after channel.level
            $colonPos = strpos($remaining, ':');
            if ($colonPos === false) {
                continue;
            }

            // Extract "channel.level"
            $channelAndLevel = trim(substr($remaining, 0, $colonPos));

            // Break into channel and level (by last dot)
            $lastDot = strrpos($channelAndLevel, '.');
            if ($lastDot === false) {
                continue;
            }

            $channel = substr($channelAndLevel, 0, $lastDot);
            $level = substr($channelAndLevel, $lastDot + 1);

            // Message = everything after ": "
            $messageStart = $colonPos + 1;
            $message = trim(substr($remaining, $messageStart));

            // Optional filter
            if (
                (!isset($filter['level']) || stripos($level, $filter['level']) !== false)
            ) {
                $logs[] = [
                    'timestamp' => $timestamp,
                    'channel' => $channel,
                    'level' => $level,
                    'message' => $message,
                ];
            }
        }

        return $logs;
    }
}
