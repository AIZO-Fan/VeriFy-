<?php

namespace App\Services;

use Exception;

class StoryValidator
{
    public static function validate(array $story)
    {
        if (!isset($story['schema_version']) || $story['schema_version'] !== 1) {
            throw new Exception("Schema version tidak valid atau tidak didukung.");
        }

        $startNode = $story['start'] ?? null;
        $nodes = $story['nodes'] ?? [];

        if (!$startNode || !isset($nodes[$startNode])) {
            throw new Exception("Node start tidak ditemukan di dalam nodes.");
        }

        self::validateNodesStructure($nodes);
        self::validateGraphReachability($nodes, $startNode);
    }

    private static function validateNodesStructure(array $nodes)
    {
        $allowedTypes = ['conversation', 'payment', 'outcome'];
        $allowedStages = ['situation', 'decision', 'consequence'];
        $allowedSenders = ['contact', 'user', 'system'];
        $allowedTones = ['positive', 'warning', 'neutral'];

        foreach ($nodes as $key => $node) {
            if (!in_array($node['type'] ?? '', $allowedTypes)) throw new Exception("Tipe node '$key' tidak valid.");
            if (!in_array($node['stage'] ?? '', $allowedStages)) throw new Exception("Stage node '$key' tidak valid.");

            // Validasi html/script sederhana
            $nodeJson = json_encode($node);
            if (preg_match('/<[^>]*>/', $nodeJson)) {
                throw new Exception("Node '$key' mengandung tag HTML yang dilarang.");
            }

            if ($node['type'] === 'outcome') {
                if (!in_array($node['tone'] ?? '', $allowedTones)) throw new Exception("Tone outcome '$key' tidak valid.");
                if (!isset($node['score'], $node['max_score'], $node['scoring_version'])) {
                    throw new Exception("Outcome '$key' kehilangan field scoring wajib.");
                }
            }

            if ($node['type'] === 'payment') {
                if (!isset($node['amount'], $node['currency']) || $node['currency'] !== 'IDR' || !is_int($node['amount']) || $node['amount'] <= 0) {
                    throw new Exception("Data payment '$key' tidak valid.");
                }
            }

            if ($node['type'] === 'conversation') {
                if (empty($node['choices'])) throw new Exception("Conversation '$key' harus memiliki minimal satu choice.");
                foreach ($node['messages'] as $msg) {
                    if (!in_array($msg['sender'] ?? '', $allowedSenders)) throw new Exception("Sender pesan di '$key' tidak valid.");
                }
            }
        }
    }

    private static function validateGraphReachability(array $nodes, string $startNode)
    {
        $visited = [];
        $recursionStack = [];
        $hasOutcome = false;

        $dfs = function ($currentNode) use (&$dfs, &$visited, &$recursionStack, $nodes, &$hasOutcome) {
            $visited[$currentNode] = true;
            $recursionStack[$currentNode] = true;

            $node = $nodes[$currentNode];

            if ($node['type'] === 'outcome') {
                $hasOutcome = true;
            }

            $nextNodes = [];
            if (isset($node['choices'])) {
                foreach ($node['choices'] as $choice) {
                    if (isset($choice['next'])) $nextNodes[] = $choice['next'];
                }
            }
            if (isset($node['confirm']['next'])) $nextNodes[] = $node['confirm']['next'];
            if (isset($node['cancel']['next'])) $nextNodes[] = $node['cancel']['next'];

            foreach ($nextNodes as $nextNode) {
                if (!isset($nodes[$nextNode])) {
                    throw new Exception("Node tujuan '$nextNode' tidak tersedia.");
                }

                if (!isset($visited[$nextNode])) {
                    $dfs($nextNode);
                } elseif (isset($recursionStack[$nextNode]) && $recursionStack[$nextNode]) {
                    throw new Exception("Ditemukan alur melingkar (cycle) pada node '$nextNode'.");
                }
            }

            $recursionStack[$currentNode] = false;
        };

        $dfs($startNode);

        if (!$hasOutcome) {
            throw new Exception("Skenario tidak memiliki jalur yang berujung pada outcome.");
        }
    }
}
