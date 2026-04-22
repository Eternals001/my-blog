# 部署指南

本指南面向运维人员，提供将博客项目部署到虚拟主机的完整步骤。

## 虚拟主机要求

### 服务器配置

| 项目 | 最低要求 | 推荐配置 |
|------|---------|---------|
| 操作系统 | Linux (CentOS 7+ / Ubuntu 20.04+) | Linux Ubuntu 22.04 LTS |
| PHP | >= 8.2 | PHP 8.2 |
| MySQL | >= 5.7 | MySQL 8.0 |
| 内存 | 512MB | 1GB+ |
| 磁盘空间 | 2GB | 10GB+ |
| PHP 扩展 | pdo, mbstring, xml, curl, json, gd, zip | pdo, mbstring, xml, curl, json, gd, zip, openssl |

### PHP 必需扩展

```bash
# 检查已安装的 PHP 扩展
php -m
```

确保包含以下扩展：
- pdo_mysql
- mbstring
- xml
- curl
- json
- gd
- zip
- openssl

### Web 服务器

- Apache 2.4+ (带 mod_rewrite)
- Nginx 1.18+

## 目录结构

### 部署目录结构

```
/var/www/blog/
├── public/           # Web 根目录（绑定到域名）
│   ├── index.php
│   ├── css/
│   ├── js/
│   └── images/
├── storage/          # 存储目录（需可写）
│   ├── app/
│   ├── framework/
│   └── logs/
├── vendor/           # 依赖目录
├── .env              # 环境配置
├── artisan          # Laravel 命令行
├── composer.json
└── bootstrap/
```

### 虚拟主机配置示例

#### Apache (.htaccess)

```apache
# public/.htaccess
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # 处理文件存在时的情况
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

#### Nginx

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/blog/public;
    index index.php;

    access_log /var/log/nginx/blog-access.log;
    error_log /var/log/nginx/blog-error.log;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    location ~* \.(jpg|jpeg|gif|png|webp|svg|woff|woff2|ttf|eot|ico|css|js)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

## 文件上传

### 方法 1：FTP 上传

```bash
# 连接 FTP 服务器
ftp your-server.com

# 上传所有文件（排除 vendor 和 node_modules）
 ftp> mput *

# 或者使用 lftp（支持并行传输）
lftp -e "mirror -R --parallel=5 ./ /var/www/blog" your-server.com
```

### 方法 2：SSH/SCP 上传

```bash
# 压缩项目文件
tar -czf blog.tar.gz \
    --exclude=vendor \
    --exclude=node_modules \
    --exclude=.git \
    --exclude=.env \
    --exclude=*.log \
    .

# 上传到服务器
scp blog.tar.gz user@your-server:/var/www/

# SSH 登录服务器并解压
ssh user@your-server
cd /var/www
tar -xzf blog.tar.gz
```

### 方法 3：Git 部署

```bash
# 在服务器上克隆仓库
cd /var/www
git clone <repository-url> blog
cd blog

# 安装依赖（生产环境）
composer install --optimize-autoloader --no-dev

# 如果需要前端资源
npm install
npm run build
```

### 文件权限设置

```bash
# 设置目录权限
chown -R www-data:www-data /var/www/blog
chmod -R 755 /var/www/blog

# storage 目录必须可写
chmod -R 775 /var/www/blog/storage
chmod -R 775 /var/www/blog/bootstrap/cache

# 创建日志文件并设置权限
touch /var/www/blog/storage/logs/laravel.log
chmod 644 /var/www/blog/storage/logs/laravel.log
```

## 数据库配置

### 创建数据库

```sql
-- 登录 MySQL
mysql -u root -p

-- 创建数据库
CREATE DATABASE blog CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- 创建用户并授权
CREATE USER 'blog'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON blog.* TO 'blog'@'localhost';
FLUSH PRIVILEGES;

-- 退出
EXIT;
```

### 导入数据

```bash
# 导出本地数据库（仅数据）
mysqldump -u root -p blog --no-tablespaces > blog.sql

# 导入到服务器
mysql -u blog -p blog < blog.sql

# 或者使用 BigDump（大数据文件）
# 参考：https://www.ozerov.de/bigdump/
```

### 修改 .env 配置

```env
# 应用配置
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# 数据库配置
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=blog
DB_USERNAME=blog
DB_PASSWORD=your_strong_password

# 缓存配置
CACHE_STORE=file

# 日志配置
LOG_CHANNEL=stack
LOG_LEVEL=warning
```

### 生成应用密钥

```bash
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 运行迁移

```bash
# 如果是新数据库
php artisan migrate

# 或者从备份恢复
php artisan migrate:fresh --seed
```

## 域名配置

### DNS 解析

在域名注册商处添加 DNS 记录：

| 记录类型 | 主机记录 | 值 |
|----------|---------|------|
| A | @ | 你的服务器 IP |
| A | www | 你的服务器 IP |
| CNAME | blog | @ |

### 虚拟主机绑定

#### cPanel

1. 登录 cPanel
2. 找到「域名」->「 Aliases 」或「子域名」
3. 添加域名并设置文档根目录为 `/public`

#### 宝塔面板

1. 登录宝塔
2. 添加网站
3. 设置域名和根目录

#### 手动配置

```bash
# Apache 虚拟主机配置
# /etc/apache2/sites-available/blog.conf
<VirtualHost *:80>
    ServerName yourdomain.com
    ServerAlias www.yourdomain.com
    DocumentRoot /var/www/blog/public

    <Directory /var/www/blog/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/blog-error.log
    CustomLog ${APACHE_LOG_DIR}/blog-access.log combined
</VirtualHost>

# 启用站点
a2ensite blog.conf
a2reload
```

## HTTPS 配置

### 方法 1：Let's Encrypt（免费）

```bash
# 安装 Certbot
sudo apt install certbot python3-certbot-apache

# 获取证书
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# 自动续期（Certbot 会配置）
sudo certbot renew --dry-run
```

### 方法 2：宝塔面板

1. 登录宝塔面板
2. 网站 -> 设置 -> HTTPS
3. 申请 Let's Encrypt 证书

### 配置 HTTPS

```nginx
# 强制 HTTPS 重定向
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name yourdomain.com;
    root /var/www/blog/public;

    # SSL 证书配置
    ssl_certificate /etc/letsencrypt/live/yourdomain.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/yourdomain.com/privkey.pem;

    # SSL 安全配置
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers on;

    # 其他配置...
}
```

### 强制 HTTPS

在 `.env` 中配置：

```env
# 强制 HTTPS（通过中间件或 web 服务器实现）
FORCE_HTTPS=true
```

在 `bootstrap/app.php` 中添加��

```php
// 强制 HTTPS
if (env('FORCE_HTTPS', false)) {
    \Illuminate\Support\Facades\URL::forceScheme('https');
}
```

## 定时任务

### Laravel Scheduler 配置

```bash
# 编辑 crontab
crontab -e

# 添加定时任务
* * * * * cd /var/www/blog && php artisan schedule:run >> /dev/null 2>&1
```

### 定时任务说明

| 命令 | 说明 | 推荐执行时间 |
|------|------|-------------|
| `schedule:run` | 执行定时任务 | 每分钟 |
| `backup:run` | 备份数据库 | 每天凌晨 2:00 |
| `posts:publish` | 发布定时文章 | 每分钟 |
| `cache:prune-stale-tags` | 清理过期缓存 | 每天凌晨 3:00 |

### 常用定时任务示例

在 `app/Console/Kernel.php` 中配置：

```php
<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        // 每天凌晨清理过期缓存
        $schedule->command('cache:prune-stale-tags')->dailyAt('03:00');

        // 每天凌晨备份数据库
        $schedule->command('backup:run')->dailyAt('02:00');

        // 每周日凌晨优化数据库
        $schedule->command('optimize:clear')->weeklyOn(0, '04:00');
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
    }
}
```

## 备份策略

### 数据库备份脚本

```bash
#!/bin/bash
# /var/www/blog/backup.sh

# 配置
DB_NAME="blog"
DB_USER="blog"
DB_PASS="your_password"
BACKUP_DIR="/var/backups/blog"
DATE=$(date +%Y%m%d_%H%M%S)

# 创建备份目录
mkdir -p $BACKUP_DIR

# 备份数据库
mysqldump -u $DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/db_$DATE.sql.gz

# 删除 7 天前的备份
find $BACKUP_DIR -name "*.sql.gz" -mtime +7 -delete

echo "Database backup completed: $DATE"
```

### 文件备份

```bash
#!/bin/bash
# /var/www/blog/backup-files.sh

BACKUP_DIR="/var/backups/blog/files"
DATE=$(date +%Y%m%d_%H%M%S)
SOURCE_DIR="/var/www/blog"

# 备份存储目录和公共资源
tar -czf $BACKUP_DIR/files_$DATE.tar.gz \
    -C $SOURCE_DIR storage/app public/uploads
```

### 自动备份 (crontab)

```bash
# 每天凌晨 2:00 备份数据库
0 2 * * * /var/www/blog/backup.sh >> /var/log/backup.log 2>&1

# 每天凌晨 3:00 备份文件
0 3 * * * /var/www/blog/backup-files.sh >> /var/log/backup-files.log 2>&1
```

### 备份到远程服务器

```bash
#!/bin/bash
# Rsync 备份到远程服务器

REMOTE_USER="backup"
REMOTE_HOST="backup-server.com"
REMOTE_DIR="/backups/blog"

# 同步备份文件
rsync -avz --delete /var/backups/blog/ \
    $REMOTE_USER@$REMOTE_HOST:$REMOTE_DIR
```

## 监控维护

### 日志监控

```bash
# 查看应用日志
tail -f /var/www/blog/storage/logs/laravel.log

# 查看 Nginx 错误日志
tail -f /var/log/nginx/blog-error.log

# 查看 PHP-FPM 日志
tail -f /var/log/php8.2-fpm.log
```

### 日志轮转配置 (logrotate)

```bash
# /etc/logrotate.d/blog
/var/www/blog/storage/logs/*.log {
    daily
    missingok
    rotate 30
    compress
    delaycompress
    notifempty
    create 644 www-data www-data
    postrotate
        [ -f /var/run/php/php8.2-fpm.pid ] && kill -USR1 $(cat /var/run/php/php8.2-fpm.pid)
    endscript
}
```

### 性能监控

```bash
# 监控 PHP-FPM 进程
pm2 status

# 监控 MySQL 连接
mysql -u blog -p -e "SHOW STATUS LIKE 'Threads_connected';"

# 监控磁盘使用
df -h

# 监控内存使用
free -h
```

### 定期维护任务

```bash
# 每周日凌晨优化数据库表
0 4 * * 0 mysqlcheck -u blog -p --optimize --all-databases

# 每天清理��志��件
0 0 * * * find /var/www/blog/storage/logs -name "*.log" -mtime +30 -delete
```

### 服务管理

```bash
# 重启 PHP-FPM
sudo systemctl restart php8.2-fpm

# 重启 Nginx
sudo systemctl restart nginx

# 重启 MySQL
sudo systemctl restart mysql

# 重启所有服务
sudo systemctl restart php8.2-fpm nginx mysql
```

### 常见问题排查

| 问题 | 可能原因 | 解决方案 |
|------|---------|---------|
| 500 错误 | .env 配置错误或权限问题 | 检查 `.env`、storage 权限 |
| 数据库连接失败 | 数据库配置错误 | 检查 `.env` 中的 DB 配置 |
| 静态资源无法加载 | public 目录配置错误 | 检查虚拟主机 DocumentRoot |
| 邮件无法发送 | 邮件配置错误 | 检查 MAIL 相关配置 |
| 上传文件失败 | storage 目录不可写 | `chmod -R 775 storage` |