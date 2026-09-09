<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;

class VersionService
{
    /**
     * The cache key for storing the version.
     */
    protected string $cacheKey = 'app_version';

    /**
     * The cache duration in seconds (1 day).
     */
    protected int $cacheDuration = 86400; // 24 hours

    /**
     * Get the application version information.
     */
    public function getVersionInfo(): array
    {
        return Cache::remember($this->cacheKey, $this->cacheDuration, function () {
            return $this->extractVersionInfoFromGit();
        });
    }

    /**
     * Get the application version string.
     */
    public function getVersion(): string
    {
        $versionInfo = $this->getVersionInfo();

        return $versionInfo['display_version'] ?? Config::get('custom.version', '0.0.0');
    }

    /**
     * Get the current commit message.
     */
    public function getCommitMessage(): string
    {
        return trim(shell_exec('git log -1 --pretty=format:"%B"'));
    }

    /**
     * Get the version metadata for hover information.
     */
    public function getVersionMeta(): string
    {
        $versionInfo = $this->getVersionInfo();

        return $versionInfo['meta'] ?? '';
    }

    /**
     * Extract version information from git history.
     */
    protected function extractVersionInfoFromGit(): array
    {
        try {
            // Get current commit hash
            $currentHash = trim(shell_exec('git rev-parse --short HEAD'));

            // Get the current commit message
            $currentCommitMessage = trim(shell_exec('git log -1 --pretty=format:"%B"'));

            // Try to extract version from current commit
            $currentVersionMatch = [];

            // Only match at the beginning of the first line
            $hasCurrentVersion = preg_match('/^v(\d+\.\d+\.\d+(?:-\w+)?)/i', $currentCommitMessage, $currentVersionMatch);

            if ($hasCurrentVersion) {
                // Current commit has a version, use it directly
                return [
                    'display_version' => $currentVersionMatch[0],
                    'raw_version' => $currentVersionMatch[0],
                    'is_exact' => true,
                    'commit_hash' => $currentHash,
                    'meta' => "Commit: {$currentHash}"
                ];
            }

            // Current commit doesn't have a version, search back in history
            $gitLogOutput = shell_exec('git log --pretty="%h|%B" -n 50');
            $gitLog = $gitLogOutput ? explode("\n", $gitLogOutput) : [];

            foreach ($gitLog as $logEntry) {
                $parts = explode('|', $logEntry, 2);
                if (count($parts) !== 2) continue;

                [$commitHash, $commitMessage] = $parts;

                // Only match at the beginning of the commit message
                if (preg_match('/^v(\d+\.\d+\.\d+(?:-\w+)?)/i', $commitMessage, $matches)) {
                    // Found a version in history, use it with a "+" suffix
                    $commitHashShort = trim($commitHash);
                    $commitsAhead = $this->getCommitsAheadCount($matches[0]);

                    return [
                        'display_version' => $matches[0] . '+',
                        'raw_version' => $matches[0],
                        'is_exact' => false,
                        'commit_hash' => $currentHash,
                        'version_commit_hash' => $commitHashShort,
                        'commits_ahead' => $commitsAhead,
                        'meta' => "Last versioned commit: {$commitHashShort} ({$matches[0]})\nCurrent commit: {$currentHash}\n{$commitsAhead} commits ahead"
                    ];
                }
            }

            // No version found in recent history, fall back to config
            return [
                'display_version' => Config::get('custom.version', '0.0.0'),
                'is_exact' => false,
                'commit_hash' => $currentHash,
                'meta' => "No version found in recent history\nCurrent commit: {$currentHash}"
            ];
        } catch (\Exception $e) {
            // If anything goes wrong, return an array with just the fallback version
            return [
                'display_version' => Config::get('custom.version', '0.0.0'),
                'is_exact' => false,
                'meta' => "Error retrieving version from git: {$e->getMessage()}"
            ];
        }
    }

    /**
     * Get the number of commits ahead of a specific version.
     */
    protected function getCommitsAheadCount(string $version): int
    {
        try {
            // Find the commit with this version at the beginning of the commit message
            $command = "git log --pretty=%H --grep=\"^{$version}\"";
            $versionCommit = trim(shell_exec($command));

            if (empty($versionCommit)) {
                return 0;
            }

            // Count commits between that commit and HEAD
            $command = "git rev-list --count {$versionCommit}..HEAD";
            return (int) trim(shell_exec($command));
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Clear the version cache.
     */
    public function clearCache(): void
    {
        Cache::forget($this->cacheKey);
    }

    /**
     * Set the application version in the config.
     */
    public function setVersionInConfig(): void
    {
        Config::set('custom.version', $this->getVersion());
    }
}
