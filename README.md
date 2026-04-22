# 个人博客系统

基于 Laravel 11 + Livewire 3 + FluxUI + TailwindCSS 构建的现代化个人博客系统。

## 项目状态

- ✅ **Phase 1: 基础架构搭建** - 已完成
- ✅ **安全修复** - 已完成
- ⏳ **Phase 2: 功能开发** - 待开始
- ⏳ **Phase 3: 前端开发** - 待开始
- ⏳ **Phase 4: 测试与部署** - 待开始

## 项目简介

这是一个功能完善的个人博客系统，支持 Markdown 文章编辑、分类管理、标签管理、评论系统、邮件订阅等功能。适用于技术博主、作家和个人品牌网站。

## 技术栈

| 类别 | 技术 |
|------|------|
| 后端框架 | Laravel 11 |
| 前端框架 | Livewire 3 + TailwindCSS + FluxUI |
| 数据库 | MySQL 8.0 |
| 图片处理 | Intervention Image |
| Markdown | Spatie Laravel Markdown |
| 权限管理 | Spatie Laravel Permission |
| API 认证 | Laravel Sanctum |

## 功能特性

### 文章管理

- Markdown 文章编辑
- 文章分类（支持多级分类）
- 文章标签
- 文章置顶
- 定时发布
- 私有文章
- SEO 设置

### 评论系统

- 嵌套评论
- 评论审核
- 匿名评论
- Gravatar 头像

### 订阅功能

- 邮件订阅
- 退订功能
- 发送邮件

### 用户权限

- 管理员：完全控制
- 编辑：管理文章、评论、分类、标签
- 订阅者：订阅博客、评论

### 安全特性

- 密码重置 Token 时效验证
- 搜索 SQL 注入防护
- 路由速率限制
- 邮箱验证 Token 安全哈希
- 用户删除数据处理
- 敏感操作日志

## 环境要求

| 软件 | 版本要求 | 说明 |
|------|---------|------|
| PHP | >= 8.2 | 必须支持 Laravel 11 |
| Composer | >= 2.0 | PHP 依赖管理 |
| Node.js | >= 18.0 | 前端资源构建 |
| npm | >= 9.0 | Node.js 包管理 |
| MySQL | >= 8.0 | 数据库 |

## 安装步骤

### 1. 克隆项目

```bash
git clone <repository-url> blog
cd blog
```

### 2. 安装 PHP 依赖

```bash
composer install
```

### 3. 复制环境配置文件

```bash
cp .env.example .env
```

### 4. 生成应用密钥

```bash
php artisan key:generate
```

### 5. 配置数据库

编辑 `.env` 文件：

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=
```

### 6. 创建数据库

```sql
CREATE DATABASE blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 7. 运行数据库迁移

```bash
php artisan migrate
```

### 8. 创建管理员账户

```bash
php artisan make:admin
```

按提示输入用户名、邮箱和密码。

### 9. 安装前端依赖

```bash
npm install
```

### 10. 启动开发服务器

```bash
# 终端 1 - 后端服务
php artisan serve

# 终端 2 - 前端热更新
npm run dev
```

访问 `http://localhost:8000` 验证安装。

## 配置说明

### .env 配置项

```env
# 应用配置
APP_NAME="Personal Blog"
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_TIMEZONE=Asia/Shanghai
APP_URL=http://localhost

# 本地化配置
APP_LOCALE=zh_CN
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=zh_CN

# 数据库配置
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=root
DB_PASSWORD=

# 会话配置
SESSION_DRIVER=database
SESSION_LIFETIME=120

# 缓存配置
CACHE_STORE=database
CACHE_PREFIX=blog_

# 邮件配置
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# 博客配置
BLOG_NAME="我的博客"
BLOG_DESCRIPTION="一个使用 Laravel 构建的个人博客"
BLOG_PER_PAGE=10

# 评论设置
BLOG_COMMENTS_ENABLED=true
BLOG_COMMENTS_REQUIRE_APPROVAL=true
BLOG_COMMENTS_ALLOW_ANONYMOUS=true
BLOG_COMMENTS_MAX_DEPTH=3

# 订阅设置
BLOG_SUBSCRIPTION_ENABLED=true
BLOG_SUBSCRIPTION_REQUIRE_CONFIRMATION=true
```

## 数据库

### 数据库迁移

```bash
# 运行所有迁移
php artisan migrate

# 创建新迁移
php artisan make:migration create_posts_table

# 回滚迁移
php artisan migrate:rollback

# 重置并重新运行
php artisan migrate:fresh

# 重置并重新运行（带种子）
php artisan migrate:fresh --seed
```

### 数据库填充

```bash
# 运行填充
php artisan db:seed

# 指定填充类
php artisan db:seed --class=PostsTableSeeder

# 迁移后立即填充
php artisan migrate:fresh --seed
```

## 部署指南

### 服务器要求

| 软件 | 最低要求 |
|------|---------|
| PHP | >= 8.2 |
| MySQL | >= 5.7 |
| 内存 | 512MB |
| 磁盘空间 | 2GB |

### 部署步骤

1. **上传文件**：通过 FTP、SSH 或 Git 上传到服务器
2. **安装依赖**：`composer install --optimize-autoloader --no-dev`
3. **配置数据库**：创建数据库并导入数据
4. **配置 .env**：设置生产环境配置
5. **运行迁移**：`php artisan migrate`
6. **优化配置**：

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### HTTPS 配置

推荐使用 Let's Encrypt 免费证书：

```bash
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com
```

更多详细部署步骤请参考 [部署指南](./docs/DEPLOYMENT.md)。

## API 文档

### 认证接口

| 接口路径 | 方法 | 说明 |
|----------|------|------|
| `/api/auth/login` | POST | 用户登录 |
| `/api/auth/register` | POST | 用户注册 |
| `/api/auth/logout` | POST | 退出登录 |
| `/api/user` | GET | 获取当前用户 |

### 文章接口

| 接口路径 | 方法 | 说明 |
|----------|------|------|
| `/api/posts` | GET | 文章列表 |
| `/api/posts/{slug}` | GET | 文章详情 |
| `/api/posts` | POST | 创建文章 |
| `/api/posts/{id}` | PUT | 更新文章 |
| `/api/posts/{id}` | DELETE | 删除文章 |

### 分类和标签

| 接口路径 | 方法 | 说明 |
|----------|------|------|
| `/api/categories` | GET | 分类列表 |
| `/api/tags` | GET | 标签列表 |

### 评论

| 接口路径 | 方法 | 说明 |
|----------|------|------|
| `/api/posts/{slug}/comments` | GET | 评论列表 |
| `/api/posts/{slug}/comments` | POST | 添加评论 |

### 订阅

| 接口路径 | 方法 | 说明 |
|----------|------|------|
| `/api/subscribe` | POST | 订阅博客 |
| `/api/unsubscribe` | POST | 取消订阅 |

更多 API 文档请参考 [API 文档](./docs/API.md)。

## 开发指南

### 开发命令

```bash
# 启动开发服务器
php artisan serve

# 清除缓存
php artisan cache:clear
php artisan config:clear
php artisan view:clear

# 路由缓存（生产环境）
php artisan route:cache
php artisan config:cache

# 运行测试
php artisan test

# 数据库填充
php artisan db:seed

# 创建模型
php artisan make:model Post

# 创建控制器
php artisan make:controller Admin/PostController

# 创建迁移
php artisan make:migration create_posts_table
```

### 代码规范

- 遵循 PSR-12 编码规范
- 使用中文注释
- 提交信息使用英文

### 测试

```bash
# 运行所有测试
php artisan test

# 运行单元测试
./vendor/bin/pest tests/Unit

# 运行功能测试
./vendor/bin/pest tests/Feature

# 运行测试并显示覆盖率
./vendor/bin/pest --coverage
```

更多开发指南请参考 [开发文档](./docs/DEVELOPMENT.md)。

## 目录结构

```
blog/
├── app/
���   ├── Console/              # Artisan 命令
│   ├── Enums/                # 枚举类
│   │   ├── UserRole.php      # 用户角色
│   │   └── PostStatus.php   # 文章状态
│   ├── Exceptions/          # 异常处理
│   ├── Http/
│   │   ├── Controllers/     # 控制器
│   │   │   ├── Admin/       # 后台管理
│   │   │   ├── Auth/        # 认证
│   │   │   └── Blog/        # 博客前端
│   │   └── Middleware/      # 中间件
│   ├── Models/              # Eloquent 模型
│   ├── Providers/           # 服务提供者
│   ├── Services/            # 服务类
│   └── Traits/              # Traits
├── bootstrap/               # Laravel 引导
├── config/                  # 配置文件
├── database/
│   ├── migrations/         # 数据库迁移
│   └── seeders/           # 数据填充
├── docs/                   # 文档
├── public/                  # 公共资源
├── resources/
│   └── views/             # 视图文件
├── routes/                 # 路由文件
├── storage/                # 存储目录
├── tests/                  # 测试文件
└── vendor/                # Composer 依赖
```

## 常见问题

### 安装失败

- 确认 PHP 版本 >= 8.2
- 确认已安装所有必需 PHP 扩展
- 运行 `composer install` 查看具体错误

### 数据库连接失败

- 检查 `.env` 中的数据库配置
- 确认数据库已创建
- 确认用户有权限访问数据库

### 静态资源无法加载

- 运行 `npm install && npm run dev`
- 检查 `public` 目录权限

### 邮件发送失败

- 检查 `.env` 中的 SMTP 配置
- 确认发件邮箱开启了 SMTP

## 相关文档

- [开发指南](./docs/DEVELOPMENT.md)
- [部署指南](./docs/DEPLOYMENT.md)
- [API 文档](./docs/API.md)
- [用户手册](./docs/USER_GUIDE.md)
- [性能优化](./docs/performance-optimization.md)

## License

MIT License