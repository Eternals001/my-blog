<?php

namespace App\Services;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\RateLimiter;

class SpamDetectionService
{
    /**
     * 敏感词列表（可配置化）
     */
    protected array $spamKeywords = [
        'xxx',
        'casino',
        'viagra',
        'cialis',
        'lottery',
        'winner',
        '点击此处',
        '了解更多',
        '代开发票',
        '赌博',
        '色情',
    ];

    /**
     * 高风险域名后缀
     */
    protected array $suspiciousTlds = [
        '.xyz',
        '.top',
        '.click',
        '.link',
        '.work',
        '.date',
        '.racing',
        '.win',
        '.review',
    ];

    /**
     * 检测评论是否为垃圾评论
     */
    public function isSpam(Request $request): bool
    {
        // 1. IP 频率限制检测
        if ($this->isRateLimited($request)) {
            return true;
        }

        // 2. 敏感词检测
        if ($this->containsSpamKeywords($request->input('content', ''))) {
            return true;
        }

        // 3. 外链数量检测
        if ($this->hasTooManyLinks($request->input('content', ''))) {
            return true;
        }

        // 4. 短评论拦截（低于3个字符可能是垃圾评论）
        if ($this->isTooShort($request->input('content', ''))) {
            return true;
        }

        // 5. 邮箱黑名单检测
        if ($this->isEmailBlacklisted($request->input('author_email', ''))) {
            return true;
        }

        // 6. URL 黑名单检测
        if ($this->containsBlacklistedUrl($request->input('author_url', ''))) {
            return true;
        }

        return false;
    }

    /**
     * IP 频率限制检测
     */
    public function isRateLimited(Request $request): bool
    {
        $ip = $request->ip();
        $key = 'spam_ip_' . md5($ip);

        // 限制：同一 IP 5 分钟内最多提交 3 条评论
        $maxAttempts = 3;
        $decayMinutes = 5;

        if (Cache::has($key . '_count')) {
            $count = Cache::get($key . '_count', 0);
            if ($count >= $maxAttempts) {
                return true;
            }
            Cache::increment($key . '_count');
        } else {
            Cache::put($key . '_count', 1, now()->addMinutes($decayMinutes));
        }

        // 同一 IP 24 小时内提交超过 20 条评论
        $dailyKey = 'spam_ip_daily_' . md5($ip . '_' . now()->format('Y-m-d'));

        if (Cache::has($dailyKey)) {
            $dailyCount = Cache::get($dailyKey, 0);
            if ($dailyCount >= 20) {
                return true;
            }
            Cache::increment($dailyKey);
        } else {
            Cache::put($dailyKey, 1, now()->addHours(24));
        }

        return false;
    }

    /**
     * 检测敏感词
     */
    public function containsSpamKeywords(string $content): bool
    {
        $content = mb_strtolower($content);

        foreach ($this->spamKeywords as $keyword) {
            if (str_contains($content, mb_strtolower($keyword))) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检测外链数量
     */
    public function hasTooManyLinks(string $content): bool
    {
        // 统计 Markdown 链接和 HTML 链接
        $linkPattern = '/\[([^\]]+)\]\(([^)]+)\)|<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>/i';
        preg_match_all($linkPattern, $content, $matches);

        // 超过 3 个链接认为是可疑
        $linkCount = count($matches[0]);

        return $linkCount > 3;
    }

    /**
     * 检测评论长度
     */
    public function isTooShort(string $content): bool
    {
        // 移除空白字符后少于 3 个字符
        $cleanContent = trim($content);

        return mb_strlen($cleanContent) < 3;
    }

    /**
     * 检测邮箱是否在黑名单
     */
    public function isEmailBlacklisted(string $email): bool
    {
        if (empty($email)) {
            return false;
        }

        $email = strtolower($email);

        // 常见垃圾邮箱模式
        $blacklistPatterns = [
            '/^test.*@.*\.xyz$/i',
            '/^.*@mail\.(ru|cn|xyz|top)$/i',
            '/^.*@(temp|fake|trash|disposable)/i',
        ];

        foreach ($blacklistPatterns as $pattern) {
            if (preg_match($pattern, $email)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 检测 URL 是否在黑名单
     */
    public function containsBlacklistedUrl(?string $url): bool
    {
        if (empty($url)) {
            return false;
        }

        $url = strtolower($url);

        // 检查可疑的 TLD
        foreach ($this->suspiciousTlds as $tld) {
            if (str_ends_with($url, $tld) || str_contains($url, $tld)) {
                return true;
            }
        }

        // 检查可疑域名关键词
        $suspiciousDomains = [
            'casino',
            'bet',
            'gambling',
            'porn',
            'xxx',
            'viagra',
            'cialis',
        ];

        foreach ($suspiciousDomains as $domain) {
            if (str_contains($url, $domain)) {
                return true;
            }
        }

        return false;
    }

    /**
     * 获取垃圾评分（0-100）
     */
    public function getSpamScore(Request $request): int
    {
        $score = 0;
        $content = $request->input('content', '');
        $email = $request->input('author_email', '');
        $url = $request->input('author_url', '');

        // 敏感词检测（每个 +20 分）
        $foundKeywords = 0;
        $contentLower = mb_strtolower($content);
        foreach ($this->spamKeywords as $keyword) {
            if (str_contains($contentLower, mb_strtolower($keyword))) {
                $foundKeywords++;
            }
        }
        $score += min($foundKeywords * 20, 60);

        // 外链检测（每个 +10 分）
        $linkPattern = '/\[([^\]]+)\]\(([^)]+)\)|<a\s+[^>]*href=["\']([^"\']+)["\'][^>]*>/i';
        preg_match_all($linkPattern, $content, $matches);
        $linkCount = count($matches[0]);
        $score += min($linkCount * 10, 30);

        // 短评论
        if ($this->isTooShort($content)) {
            $score += 20;
        }

        // 邮箱黑名单
        if ($this->isEmailBlacklisted($email)) {
            $score += 30;
        }

        // URL 黑名单
        if ($this->containsBlacklistedUrl($url)) {
            $score += 30;
        }

        return min($score, 100);
    }

    /**
     * 添加敏感词
     */
    public function addSpamKeyword(string $keyword): void
    {
        if (!in_array($keyword, $this->spamKeywords)) {
            $this->spamKeywords[] = $keyword;
        }
    }

    /**
     * 获取所有敏感词
     */
    public function getSpamKeywords(): array
    {
        return $this->spamKeywords;
    }

    /**
     * 记录评论者的 IP（用于后续分析）
     */
    public function recordIpAddress(string $ip): void
    {
        $key = 'ip_records_' . now()->format('Y-m-d');
        $records = Cache::get($key, []);

        if (!isset($records[$ip])) {
            $records[$ip] = [
                'count' => 0,
                'first_seen' => now()->toDateTimeString(),
            ];
        }

        $records[$ip]['count']++;
        $records[$ip]['last_seen'] = now()->toDateTimeString();

        Cache::put($key, $records, now()->addDays(7));
    }

    /**
     * 获取可疑 IP 列表
     */
    public function getSuspiciousIps(int $threshold = 10): array
    {
        $suspiciousIps = [];

        for ($i = 0; $i < 7; $i++) {
            $date = now()->subDays($i)->format('Y-m-d');
            $key = 'ip_records_' . $date;
            $records = Cache::get($key, []);

            foreach ($records as $ip => $data) {
                if ($data['count'] >= $threshold) {
                    if (!isset($suspiciousIps[$ip])) {
                        $suspiciousIps[$ip] = [
                            'ip' => $ip,
                            'total_count' => 0,
                            'first_seen' => $data['first_seen'],
                            'last_seen' => $data['last_seen'] ?? $data['first_seen'],
                        ];
                    }
                    $suspiciousIps[$ip]['total_count'] += $data['count'];
                }
            }
        }

        return array_values($suspiciousIps);
    }
}
