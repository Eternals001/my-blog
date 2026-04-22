# 开发指南

本指南面向博客项目的开发人员，提供本地开发环境搭建、代码规范、开发流程等说明。

## 开发环境搭建

### 环境要求

| 软件 | 版本要求 | 说明 |
|------|---------|------|
| PHP | >= 8.2 | 必须支持 Laravel 11 |
| Composer | >= 2.0 | PHP 依赖管理 |
| Node.js | >= 18.0 | 前端资源构建 |
| npm | >= 9.0 | Node.js 包管理 |
| MySQL | >= 8.0 | 数据库 |
| Git | >= 2.0 | 版本控制 |

### 推荐开发环境

- **Linux/macOS**: Laravel Homestead (官方预配置的 Vagrant box)
- **Windows**: Laravel Sail (Docker) 或 XAMPP/WAMP
- **跨平台**: Docker Desktop + Laravel Sail

### 步骤 1：克隆项目

```bash
git clone <repository-url> blog
cd blog
```

### 步骤 2：安装 PHP 依赖

```bash
composer install
```

### 步骤 3：配置环境变量

```bash
cp .env.example .env
```

### 步骤 4：生成应用密钥

```bash
php artisan key:generate
```

### 步骤 5：创建数据库

```sql
-- 登录 MySQL
mysql -u root -p

-- 创建数据库
CREATE DATABASE blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 步骤 6：配置 .env

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=your_password
```

### 步骤 7：运行迁移

```bash
php artisan migrate
```

### 步骤 8：创建管理员账户

```bash
php artisan make:admin
```

按照提示输入用户名、邮箱和密码。

### 步骤 9：安装前端依赖

```bash
npm install
```

### 步骤 10：启动开发服务器

```bash
# 终端 1 - 后端服务
php artisan serve

# 终端 2 - 前端热更新
npm run dev
```

访问 `http://localhost:8000` 验证安装。

### 可选：使用 Docker (Laravel Sail)

```bash
# 创建 .env 文件
cp .env.example .env

# 启动容器
./vendor/bin/sail up -d

# 运行迁移
./vendor/bin/sail artisan migrate

# 创建管理员
./vendor/bin/sail artisan make:admin
```

## 代码规范

### PSR-12 代码规范

项目遵循 [PSR-12](https://www.php-fig.org/psr/psr-12/) 编码规范。

```bash
# 使用 PHP CS Fixer 自动修复代码格式
composer require --dev friendsofphp/php-cs-fixer

# 检查代码格式
./vendor/bin/php-cs-fixer fix --dry-run --diff

# 自动修复
./vendor/bin/php-cs-fixer fix
```

### Laravel 编码规范

1. **控制器**：保持简洁，只负责接收请求和返回响应
2. **模型**：使用 Eloquent ORM，负责数据关系和业务逻辑
3. **服务类**：封装复杂业务逻辑
4. **表单请求**：分离验证逻辑
5. **资源类**：API 数据格式化

### 代码示例

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * 文章列表
     */
    public function index(Request $request)
    {
        $posts = Post::with(['author', 'category'])
            ->when($request->status, fn($query) => $query->where('status', $request->status))
            ->when($request->category, fn($query) => $query->where('category_id', $request->category))
            ->orderByDesc('is_sticky')
            ->orderByDesc('published_at')
            ->paginate($request->per_page ?? 15));

        return view('admin.posts.index', compact('posts'));
    }

    /**
     * 创建文章表单
     */
    public function create()
    {
        return view('admin.posts.create');
    }

    /**
     * 保存文��
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts'],
            'content' => ['required', 'string'],
            'category_id' => ['required', 'exists:categories,id'],
            'status' => ['required', 'in:draft,published,scheduled,private'],
        ]);

        $post = auth()->user()->posts()->create($validated);

        return redirect()
            ->route('admin.posts.edit', $post)
            ->with('success', '文章创建成功');
    }
}
```

## Git 提交规范

### 提交信息格式

```
<type>(<scope>): <description>

[optional body]

[optional footer]
```

### Type 类型

| 类型 | 说明 |
|------|------|
| feat | 新功能 |
| fix | Bug 修复 |
| docs | 文档更新 |
| style | 代码格式（不影响功能）|
| refactor | 重构 |
| test | 测试相关 |
| chore | 构建/工具相关 |

### 提交示例

```bash
# 功能提交
git commit -m "feat(post): 添加文章置顶功能"

# 修复提交
git commit -m "fix(comment): 修复评论分页计算错误"

# 带描述的提交
git commit -m "fix(search): 修复搜索结果排序问题

问题的原因是 sort() 方法对中文排序支持不好，
改为使用 Collator 进行中文排序。
"
```

### 分支策略 (Git Flow)

```
main (发布分支)
  │
  ├── develop (开发分支)
  │     │
  │     ├── feature/xxx (功能分支)
  │     ├── fix/xxx (修复分支)
  │     └── docs/xxx (文档分支)
  │
  └── release/xxx (发布准备分支)
```

### 分支操作

```bash
# 创建功能分支
git checkout -b feature/new-feature

# 切换到开发分支
git checkout develop

# 合并功能分支
git merge --no-ff feature/new-feature

# 删除功能分支
git branch -d feature/new-feature
```

## 数据库设计

### ER 图

```
┌─────────────┐       ┌─────────────┐
│   users     │       │  categories │
├─────────────┤       ├─────────────┤
│ id         │◀─────│ parent_id  │
│ name       │       │ id         │
│ email      │       │ name       │
│ password   │       │ slug       │
│ role      │       │ order      │
│ avatar    │       └─────────────┘
│ bio       │             ▲
└───────────┘             │
      │                  │
      │ 1:N              │ 1:N
      ▼                  ▼
┌─────────────┐       ┌─────────────┐
│    posts    │       │    tags     │
├─────────────┤       ├─────────────┤
│ id          │       │ id          │
│ user_id     │       │ name        │
│ category_id │       │ slug        │
│ title       └───────┘
│ slug        │        ┌─────────────┐
│ content    │        │  post_tag   │
│ html_content│       ├─────────────┤
│ excerpt    │        │ post_id     │
│ status    │        │ tag_id      │
│ is_sticky │        └─────────────┘
│ view_count │
│ published_at│      ┌─────────────┐
└─────────────┘      │  comments  │
      │             ├─────────────┤
      │ 1:N        │ id         │
      ▼            │ post_id    │
┌────────────��┐     │ user_id   │
│subscriptions│     │ parent_id│
├─────────────┤     │ content  │
│ id          │     │ is_approved│
│ email       │     └─────────────┘
│ token      │
│ is_active  │
└─────────────┘
```

### 表结构说明

#### users 表

| 字段 | 类型 | 说明 |
|------|------|------|
| id | bigint | 主键，自增 |
| name | varchar(100) | 用户名 |
| email | varchar(255) | 邮箱（唯一） |
| password | varchar(255) | 密码（ bcrypt 加密）|
| role | enum | 角色：admin/editor/subscriber |
| avatar | varchar(500) | 头像 URL |
| bio | text | 个人简介 |
| email_token | varchar(64) | 邮箱验证 token |
| email_verified_at | timestamp | 邮箱验证时间 |
| last_login_at | timestamp | 最后登录时间 |
| created_at | timestamp | 创建时间 |
| updated_at | timestamp | 更新时间 |
| deleted_at | timestamp | 软删除时间 |

#### categories 表

| 字段 | 类型 | 说明 |
|------|------|------|
| id | bigint | 主键，自增 |
| parent_id | bigint | 父分类 ID（0 表示顶级）|
| name | varchar(100) | 分类名称 |
| slug | varchar(100) | URL 别名（唯一）|
| description | text | 分类描述 |
| order | smallint | 排序（越小越靠前）|
| created_at | timestamp | 创建时间 |
| updated_at | timestamp | 更新时间 |

#### posts 表

| 字段 | 类型 | 说明 |
|------|------|------|
| id | bigint | 主键，自增 |
| user_id | bigint | 作者 ID |
| category_id | bigint | 分类 ID |
| title | varchar(255) | 文章标题 |
| slug | varchar(255) | URL 别名（唯一）|
| content | longtext | Markdown 源码 |
| html_content | longtext | 渲染后的 HTML |
| excerpt | text | 文章摘要 |
| cover_image | varchar(500) | 封面图 URL |
| status | enum | 状态：draft/published/scheduled/private |
| is_sticky | boolean | 是否置顶 |
| view_count | int | 浏览量 |
| published_at | timestamp | 发布时间 |
| seo_title | varchar(255) | SEO 标题 |
| seo_description | varchar(500) | SEO 描述 |
| seo_keywords | varchar(500) | SEO 关键词 |
| created_at | timestamp | 创建时间 |
| updated_at | timestamp | 更新时间 |

#### tags 表

| 字段 | 类型 | 说明 |
|------|------|------|
| id | bigint | 主键，自增 |
| name | varchar(50) | 标签名称 |
| slug | varchar(50) | URL 别名（唯一）|
| created_at | timestamp | 创建时间 |
| updated_at | timestamp | 更新时间 |

#### comments 表

| 字段 | 类型 | 说明 |
|------|------|------|
| id | bigint | 主键，自增 |
| post_id | bigint | 文章 ID |
| user_id | bigint | 用户 ID（可为空）|
| parent_id | bigint | 父评论 ID |
| author_name | varchar(100) | 评论者名称 |
| author_email | varchar(255) | 评论者邮箱 |
| author_url | varchar(500) | 评论者网站 |
| content | text | 评论内容 |
| is_approved | boolean | 是否批准显示 |
| ip_address | varchar(45) | IP 地址 |
| user_agent | varchar(500) | 用户代理 |
| created_at | timestamp | 创建时间 |
| updated_at | timestamp | 更新时间 |

#### subscriptions 表

| 字段 | 类型 | 说明 |
|------|------|------|
| id | bigint | 主键，自增 |
| email | varchar(255) | 邮箱（唯一）|
| token | varchar(64) | 验证 token |
| is_active | boolean | 是否激活 |
| subscribed_at | timestamp | 订阅时间 |
| unsubscribed_at | timestamp | 取消订阅时间 |
| created_at | timestamp | 创建时间 |
| updated_at | timestamp | 更新时间 |

## 服务层说明

### 服务类列表

| 服务类 | 路径 | 职责 |
|--------|------|------|
| AuthService | app/Services/AuthService.php | 认证相关：登录、注册、密码重置 |
| CacheService | app/Services/CacheService.php | 缓存管理 |
| PostService | app/Services/PostService.php | 文章业务逻辑 |
| MarkdownService | app/Services/MarkdownService.php | Markdown 解析 |

### AuthService 示例

```php
<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class AuthService
{
    /**
     * 用户登录
     */
    public function login(string $email, string $password): ?User
    {
        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($password, $user->password)) {
            return null;
        }

        $user->update(['last_login_at' => now()]);

        return $user;
    }

    /**
     * 验证密码重置 Token
     */
    public function verifyResetToken(User $user, string $token): bool
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $user->email)
            ->first();

        if (!$record) {
            return false;
        }

        // 检查 token 是否过期（默认 60 分钟）
        if ($record->created_at->addMinutes(60)->isPast()) {
            DB::table('password_reset_tokens')
                ->where('email', $user->email)
                ->delete();
            return false;
        }

        return hash_equals($record->token, $token);
    }
}
```

### PostService 示例

```php
<?php

namespace App\Services;

use App\Models\Post;
use Illuminate\Support\Facades\Cache;

class PostService
{
    /**
     * 获取文章列表
     */
    public function getPosts(array $filters = [], int $perPage = 15)
    {
        return Post::with(['author', 'category', 'tags'])
            ->filter($filters)
            ->orderByDesc('is_sticky')
            ->orderByDesc('published_at')
            ->paginate($perPage);
    }

    /**
     * 获取热门文章
     */
    public function getPopularPosts(int $limit = 10): array
    {
        $cacheKey = 'popular_posts_' . $limit;

        return Cache::remember($cacheKey, 900, function () use ($limit) {
            return Post::published()
                ->orderByDesc('view_count')
                ->limit($limit)
                ->get()
                ->toArray();
        });
    }

    /**
     * 增加浏览量
     */
    public function incrementViewCount(Post $post): void
    {
        $post->increment('view_count');
    }
}
```

## 中间件说明

| 中间件 | 路径 | 职责 |
|--------|------|------|
| AdminMiddleware | app/Http/Middleware/AdminMiddleware.php | 检查用户是否为管理员 |
| EditorMiddleware | app/Http/Middleware/EditorMiddleware.php | 检查用户是否为编辑及以上角色 |
| EnsureEmailIsVerified | app/Http/Middleware/EnsureEmailIsVerified.php | 检查邮箱是否已验证 |
| TrackVisitorMiddleware | app/Http/Middleware/TrackVisitorMiddleware.php | 访客统计、防刷 |
| RedirectIfAuthenticated | app/Http/Middleware/RedirectIfAuthenticated.php | 已登录用户重定向 |

### AdminMiddleware

```php
<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasRole(UserRole::ADMIN)) {
            abort(403, '只有管理员可以访问此页面');
        }

        return $next($request);
    }
}
```

### TrackVisitorMiddleware

```php
<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitorMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        // 记录访客信息
        $this->trackVisitor($request);

        // 检查是否为爬虫/机器人
        if ($this->isBot($request)) {
            return $next($request);
        }

        // 简单的访问频率限制
        $this->checkRateLimit($request);

        return $next($request);
    }

    private function trackVisitor(Request $request): void
    {
        // 记录访问日志到数据库或日志文件
    }

    private function isBot(Request $request): bool
    {
        $userAgent = $request->userAgent();
        $bots = ['bot', 'spider', 'crawler', 'googlebot'];

        foreach ($bots as $bot) {
            if (stripos($userAgent, $bot) !== false) {
                return true;
            }
        }

        return false;
    }

    private function checkRateLimit(Request $request): void
    {
        // 实现访问频率限制逻辑
        // 可结合 Redis 实现分布式限流
    }
}
```

## 测试指南

### 测试环境配置

```bash
# 使用 SQLite 内存数据库进行测试
# .env.testing 配置
APP_ENV=testing
DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# 或者使用专门的测试数据库
DB_CONNECTION=mysql
DB_DATABASE=blog_test
```

### 运行测试

```bash
# 运行所有测试
php artisan test

# 运行单元测试
./vendor/bin/pest tests/Unit

# 运行功能测试
./vendor/bin/pest tests/Feature

# 运行测试并显示覆盖率
./vendor/bin/pest --coverage

# 运行特定测试
./vendor/bin/pest tests/Feature/PostTest.php
```

### 测试示例

```php
<?php

use App\Models\Post;
use App\Models\User;

test('文章列表显示已发布文章', function () {
    $publishedPost = Post::factory()->published()->create();
    $draftPost = Post::factory()->draft()->create();

    $response = $this->get(route('blog.posts.index'));

    $response->assertStatus(200);
    $response->assertSee($publishedPost->title);
    $response->assertDontSee($draftPost->title);
});

test('未登录用户不能访问后台', function () {
    $response = $this->get(route('admin.dashboard'));

    $response->assertRedirect(route('login'));
});

test('文章浏览量递增', function () {
    $post = Post::factory()->published()->create(['view_count' => 10]);

    $post->increment('view_count');

    expect($post->fresh()->view_count)->toBe(11);
});
```

## 性能优化

### 查询优化

1. **使用 eager loading 避免 N+1 问题**

```php
// ❌ 错误：N+1 查询
$posts = Post::all();
foreach ($posts as $post) {
    echo $post->author->name;
}

// ✅ 正确：预加载
$posts = Post::with('author')->get();
foreach ($posts as $post) {
    echo $post->author->name;
}
```

2. **使用索引**

```php
// 数据库迁移中添加索引
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->foreignId('category_id')->constrained()->onDelete('cascade');
    $table->string('slug')->unique();
    $table->enum('status', ['draft', 'published', 'scheduled', 'private']);
    $table->boolean('is_sticky')->default(false);
    $table->integer('view_count')->default(0);
    $table->timestamp('published_at');
    $table->index(['status', 'published_at']);
    $table->index(['is_sticky', 'published_at']);
});
```

### 缓存优化

1. **使用 Redis**

```env
CACHE_STORE=redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379
```

2. **缓存常用数据**

```php
// 缓存最近的博客文章
$posts = Cache::remember('recent_posts', 900, function () {
    return Post::published()
        ->orderByDesc('published_at')
        ->limit(10)
        ->get();
});
```

### 前端优化

```bash
# 生产环境构建
npm run build

# 启用构建缓��
npm run build -- --cache
```

更多性能优化建议请参考 [性能优化文档](./performance-optimization.md)。