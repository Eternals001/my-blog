<?php

/**
 * 安全修复验证测试
 * 
 * 此文件用于验证代码审查中发现的安全问题是否已正确修复
 * 运行: php tests/Unit/SecurityFixesTest.php
 */

require_once __DIR__ . '/../../vendor/autoload.php';

// 简单的测试运行器
class SecurityTestRunner
{
    private int $passed = 0;
    private int $failed = 0;
    private array $results = [];

    public function run(string $name, callable $test): void
    {
        try {
            $test();
            $this->passed++;
            $this->results[] = "✅ PASS: $name";
        } catch (Throwable $e) {
            $this->failed++;
            $this->results[] = "❌ FAIL: $name - " . $e->getMessage();
        }
    }

    public function summary(): void
    {
        echo "\n" . str_repeat('=', 50) . "\n";
        echo "安全修复验证结果\n";
        echo str_repeat('=', 50) . "\n\n";
        
        foreach ($this->results as $result) {
            echo $result . "\n";
        }
        
        echo "\n" . str_repeat('=', 50) . "\n";
        echo "总计: {$this->passed} 通过, {$this->failed} 失败\n";
        echo str_repeat('=', 50) . "\n";
    }
}

// ==================== 测试用例 ====================

$runner = new SecurityTestRunner();

// 测试 1: 搜索功能 SQL 注入防护
$runner->run('搜索功能使用参数绑定', function() {
    // 验证 scopeSearch 方法使用了参数绑定而非直接字符串拼接
    $refl = new ReflectionClass(\App\Models\Post::class);
    $method = $refl->getMethod('scopeSearch');
    
    $source = file_get_contents(__DIR__ . '/../../app/Models/Post.php');
    
    // 确保不使用直接字符串插入
    if (strpos($source, '"%$keyword%"') !== false) {
        throw new Exception('仍存在直接字符串插入的 SQL 注入风险');
    }
    
    // 确保使用了 $wildcard 变量
    if (strpos($source, '$wildcard') === false) {
        throw new Exception('未使用参数绑定变量 $wildcard');
    }
    
    echo "  - 已验证使用参数绑定防止 SQL 注入\n";
});

// 测试 2: 密码重置 Token 过期检查
$runner->run('密码重置 Token 过期检查', function() {
    $source = file_get_contents(__DIR__ . '/../../app/Services/AuthService.php');
    
    // 确保有过期检查逻辑
    if (strpos($source, 'addMinutes') === false) {
        throw new Exception('缺少 token 过期时间检查');
    }
    
    if (strpos($source, 'expiryMinutes') === false) {
        throw new Exception('未使用配置的过期时间');
    }
    
    echo "  - 已验证 token 过期检查逻辑\n";
});

// 测试 3: 速率限制中间件
$runner->run('Web 路由速率限制', function() {
    $source = file_get_contents(__DIR__ . '/../../routes/web.php');
    
    // 确保评论路由有 throttling
    if (strpos($source, "->middleware('throttle:3,5')") === false) {
        throw new Exception('评论路由缺少速率限制');
    }
    
    // 确保订阅路由有 throttling
    if (substr_count($source, "->middleware('throttle") < 2) {
        throw new Exception('部分路由缺少速率限制');
    }
    
    echo "  - 已验证路由速率限制\n";
});

// 测试 4: 邮箱验证 Token 哈希存储
$runner->run('邮箱验证 Token 哈希存储', function() {
    $source = file_get_contents(__DIR__ . '/../../app/Models/User.php');
    
    // 确保生成时哈希
    if (strpos($source, "hash('sha256'") === false) {
        throw new Exception('Token 生成时未使用哈希');
    }
    
    // 确保验证时使用时间安全比较
    if (strpos($source, 'hash_equals') === false) {
        throw new Exception('Token 验证时未使用 hash_equals');
    }
    
    echo "  - 已验证 Token 哈希存储和时间安全比较\n";
});

// 测试 5: 用户删除数据处理
$runner->run('用户删除时数据转移', function() {
    $source = file_get_contents(__DIR__ . '/../../app/Http/Controllers/Admin/UserController.php');
    
    // 确保有文章转移逻辑
    if (strpos($source, 'posts()->update') === false) {
        throw new Exception('删除用户时未处理文章转移');
    }
    
    // 确保有评论处理逻辑
    if (strpos($source, '[已删除用户]') === false) {
        throw new Exception('删除用户时未标记评论');
    }
    
    echo "  - 已验证用户删除数据处理\n";
});

// 测试 6: AdminLog 模型存在
$runner->run('AdminLog 模型创建', function() {
    $file = __DIR__ . '/../../app/Models/AdminLog.php';
    
    if (!file_exists($file)) {
        throw new Exception('AdminLog 模型文件不存在');
    }
    
    $source = file_get_contents($file);
    
    if (strpos($source, 'class AdminLog') === false) {
        throw new Exception('AdminLog 类定义不正确');
    }
    
    echo "  - 已验证 AdminLog 模型存在\n";
});

// 测试 7: AdminLog Migration 存在
$runner->run('AdminLog Migration 创建', function() {
    $files = glob(__DIR__ . '/../../database/migrations/*admin_logs*');
    
    if (empty($files)) {
        throw new Exception('admin_logs migration 不存在');
    }
    
    echo "  - 已验证 AdminLog Migration 存在\n";
});

// 测试 8: Auth 文件速率限制
$runner->run('Auth 路由速率限制', function() {
    $source = file_get_contents(__DIR__ . '/../../routes/auth.php');
    
    // 确保登录有速率限制
    if (strpos($source, "throttle:5,5") === false) {
        throw new Exception('登录路由缺少速率限制');
    }
    
    // 确保注册有速率限制
    if (strpos($source, "throttle:3,60") === false) {
        throw new Exception('注册路由缺少速率限制');
    }
    
    echo "  - 已验证 Auth 路由速率限制\n";
});

// 输出结果
$runner->summary();