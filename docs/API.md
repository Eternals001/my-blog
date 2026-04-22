# API 文档

本文档提供博客系统的 RESTful API 参考。

## 接口说明

### 通用格式

#### 请求头

```
Content-Type: application/json
Accept: application/json
Authorization: Bearer {token}
```

#### 响应格式

```json
{
    "success": true,
    "message": "操作成功",
    "data": {
        "id": 1,
        "title": "文章标题"
    },
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 100
    }
}
```

#### 错误响应

```json
{
    "success": false,
    "message": "错误信息",
    "error": {
        "code": "VALIDATION_ERROR",
        "details": [
            {
                "field": "email",
                "message": "邮箱格式不正确"
            }
        ]
    }
}
```

## 认证说明

### 认证方式

本系统使用 Laravel Sanctum 进行 API 认证。

### 获取 Token

```bash
# 登录获取 Token
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "admin@example.com", "password": "password"}'
```

响应：

```json
{
    "success": true,
    "message": "登录成功",
    "data": {
        "token": "1|xXxxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx",
        "token_type": "Bearer",
        "expires_in": 259200
    }
}
```

### 使用 Token

```bash
# 带 Token 的请求
curl -X GET http://localhost:8000/api/user \
  -H "Authorization: Bearer 1|xXxxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx" \
  -H "Accept: application/json"
```

### Token 刷新

Laravel Sanctum 的令牌在 259200 秒（30 天）后过期。

```bash
# 刷新 Token
curl -X POST http://localhost:8000/api/auth/refresh \
  -H "Authorization: Bearer {old_token}" \
  -H "Accept: application/json"
```

## 接口列表

### 认证接口

#### 1. 用户登录

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/auth/login` |
| 请求方法 | `POST` |
| 说明 | 用户登录，获取访问令牌 |

**请求参数**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| email | string | 是 | 用户邮箱 |
| password | string | 是 | 用户密码 |

**请求示例**

```json
{
    "email": "admin@example.com",
    "password": "your_password"
}
```

**返回示例**

```json
{
    "success": true,
    "message": "登录成功",
    "data": {
        "token": "1|xXxxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx",
        "token_type": "Bearer",
        "expires_in": 259200
    }
}
```

---

#### 2. 用户注册

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/auth/register` |
| 请求方法 | `POST` |
| 说明 | 用户注册 |

**请求参数**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| name | string | 是 | 用户名（2-100 字符）|
| email | string | 是 | 用户邮箱（唯一）|
| password | string | 是 | 密码（至少 8 字符）|
| password_confirmation | string | 是 | 确认密码 |

**返回示例**

```json
{
    "success": true,
    "message": "注册成功",
    "data": {
        "id": 2,
        "name": "新用户",
        "email": "new@example.com",
        "created_at": "2024-01-01T00:00:00+08:00"
    }
}
```

---

#### 3. 退出登录

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/auth/logout` |
| 请求方法 | `POST` |
| 说明 | 退出登录，销毁令牌 |

**请求头**

```
Authorization: Bearer {token}
```

**返回示例**

```json
{
    "success": true,
    "message": "退出成功"
}
```

---

#### 4. 获取当前用户信息

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/user` |
| 请求方法 | `GET` |
| 说明 | 获取已登录用户信息 |

**请求头**

```
Authorization: Bearer {token}
```

**返回示例**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "name": "管理员",
        "email": "admin@example.com",
        "role": "admin",
        "avatar": "https://www.gravatar.com/avatar/xxx",
        "bio": "热爱技术，热爱生活",
        "created_at": "2024-01-01T00:00:00+08:00"
    }
}
```

---

#### 5. 更新用户信息

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/user` |
| 请求方法 | `PUT` |
| 说明 | 更新当前用户信息 |

**请求参数**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| name | string | 否 | 用户名 |
| bio | string | 否 | 个人简介 |
| avatar | file | 否 | 头像图片 |

**返回示例**

```json
{
    "success": true,
    "message": "更新成功",
    "data": {
        "id": 1,
        "name": "新名字",
        "bio": "新的个人简介"
    }
}
```

### 文章接口

#### 6. 文章列表

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/posts` |
| 请求方法 | `GET` |
| 说明 | 获取文章列表 |

**请求参数**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| page | int | 否 | 页码（默认 1）|
| per_page | int | 否 | 每页数量（默认 15，最大 100）|
| category | string | 否 | 分类 slug |
| tag | string | 否 | 标签 slug |
| status | string | 否 | 状态：published, draft |
| search | string | 否 | 搜索关键词 |

**返回示例**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "第一篇文章",
            "slug": "first-post",
            "excerpt": "文章摘要...",
            "cover_image": "/storage/posts/cover.jpg",
            "status": "published",
            "is_sticky": false,
            "view_count": 100,
            "published_at": "2024-01-01T00:00:00+08:00",
            "author": {
                "id": 1,
                "name": "管理员",
                "avatar": "https://..."
            },
            "category": {
                "id": 1,
                "name": "技术",
                "slug": "tech"
            },
            "tags": [
                {"id": 1, "name": "PHP", "slug": "php"}
            ]
        }
    ],
    "meta": {
        "current_page": 1,
        "per_page": 15,
        "total": 50,
        "last_page": 4
    }
}
```

---

#### 7. 文章详情

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/posts/{slug}` |
| 请求方法 | `GET` |
| 说明 | 获取文章详情 |

**返回示例**

```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "第一篇文章",
        "slug": "first-post",
        "content": "# Markdown 内容",
        "html_content": "<h1>HTML 内容</h1>",
        "excerpt": "文章摘要...",
        "cover_image": "/storage/posts/cover.jpg",
        "status": "published",
        "is_sticky": false,
        "view_count": 101,
        "published_at": "2024-01-01T00:00:00+08:00",
        "seo": {
            "title": "SEO 标题",
            "description": "SEO 描述",
            "keywords": "关键词"
        },
        "author": {
            "id": 1,
            "name": "管理员",
            "avatar": "https://..."
        },
        "category": {
            "id": 1,
            "name": "技术",
            "slug": "tech"
        },
        "tags": [
            {"id": 1, "name": "PHP", "slug": "php"}
        ],
        "created_at": "2024-01-01T00:00:00+08:00",
        "updated_at": "2024-01-01T00:00:00+08:00"
    }
}
```

---

#### 8. 创建文章

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/posts` |
| 请求方法 | `POST` |
| 说明 | 创建新文章（需登录）|

**请求头**

```
Authorization: Bearer {token}
```

**请求参数**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| title | string | 是 | 文章标题 |
| slug | string | 否 | URL 别名（默认从标题生成）|
| content | string | 是 | Markdown 内容 |
| category_id | int | 是 | 分类 ID |
| tag_ids | array | 否 | 标签 ID 数组 |
| status | string | 否 | 状态：draft/published/scheduled/private |
| published_at | datetime | 否 | 发布时间 |
| excerpt | string | 否 | 摘要 |
| cover_image | file | 否 | 封面图片 |
| seo[title] | string | 否 | SEO 标题 |
| seo[description] | string | 否 | SEO 描述 |
| seo[keywords] | string | 否 | SEO 关键词 |

---

#### 9. 更新文章

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/posts/{id}` |
| 请求方法 | `PUT` |
| 说明 | 更新文章（需登录）|

**请求头**

```
Authorization: Bearer {token}
```

---

#### 10. 删除文章

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/posts/{id}` |
| 请求方法 | `DELETE` |
| 说明 | 删除文章（需登录）|

### 分类接口

#### 11. 分类列表

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/categories` |
| 请求方法 | `GET` |
| 说明 | 获取分类树形列表 |

**返回示例**

```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "name": "技术",
            "slug": "tech",
            "description": "技术相关文章",
            "order": 1,
            "children": [
                {
                    "id": 2,
                    "name": "PHP",
                    "slug": "php",
                    "parent_id": 1
                }
            ]
        }
    ]
}
```

---

### 标签接口

#### 12. 标签列表

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/tags` |
| 请求方法 | `GET` |
| 说明 | 获取标签列表 |

### 评论接口

#### 13. 文章评论列表

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/posts/{slug}/comments` |
| 请求方法 | `GET` |
| 说明 | 获取文章的评论列表 |

**请求参数**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| page | int | 否 | 页码 |
| per_page | int | 否 | 每页数量 |

---

#### 14. 添加评论

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/posts/{slug}/comments` |
| 请求方法 | `POST` |
| 说明 | 添加评论 |

**请求参数**

| 参数 | 类��� | ���填 | 说明 |
|------|------|------|------|
| content | string | 是 | 评论内容 |
| parent_id | int | 否 | 父评论 ID |
| author_name | string | 否 | 评论者名称（游客）|
| author_email | string | 否 | 评论者邮箱（游客）|
| author_url | string | 否 | 评论者网站（游客）|

**返回示例**

```json
{
    "success": true,
    "message": "评论已提交，等待审核"
}
```

### 订阅接口

#### 15. 订阅博客

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/subscribe` |
| 请求方法 | `POST` |
| 说明 | 订阅博客邮件通知 |

**请求参数**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| email | string | 是 | 订阅邮箱 |

**返回示例**

```json
{
    "success": true,
    "message": "订阅成功，请查收确认邮件"
}
```

---

#### 16. 取消订阅

| 项目 | 说明 |
|------|------|
| 接口路径 | `/api/unsubscribe` |
| 请求方法 | `POST` |
| 说明 | 取消订阅 |

**请求参数**

| 参数 | 类型 | 必填 | 说明 |
|------|------|------|------|
| email | string | 是 | 订阅邮箱 |
| token | string | 是 | 验证 token |

## 错误码说明

### 通用错误码

| 错误码 | 说明 | HTTP 状态码 |
|--------|------|-------------|
| SUCCESS | 操作成功 | 200 |
| VALIDATION_ERROR | 参数验证错误 | 422 |
| NOT_FOUND | 资源不存在 | 404 |
| UNAUTHORIZED | 未登录或 Token 无效 | 401 |
| FORBIDDEN | 无权限访问 | 403 |
| METHOD_NOT_ALLOWED | 请求方法不允许 | 405 |
| SERVER_ERROR | 服务器内部错误 | 500 |
| RATE_LIMIT_EXCEED | 请求频率超限 | 429 |

### 业务错误码

| 错误码 | 说明 |
|--------|------|
| EMAIL_EXISTS | 邮箱已被注册 |
| INVALID_CREDENTIALS | 邮箱或密码错误 |
| TOKEN_EXPIRED | Token 已过期 |
| POST_NOT_PUBLISHED | 文章未发布 |
| COMMENT_REQUIRE_APPROVAL | 评论待审核 |

## 示例请求

### cURL 示例

#### 登录

```bash
curl -X POST http://localhost:8000/api/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email": "admin@example.com", "password": "password"}'
```

#### 获取文章列表

```bash
curl -X GET "http://localhost:8000/api/posts?per_page=10&status=published" \
  -H "Accept: application/json"
```

#### 创建文章

```bash
curl -X POST http://localhost:8000/api/posts \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer 1|xXxxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXxXx" \
  -d '{
    "title": "新文章",
    "content": "# 内容",
    "category_id": 1,
    "status": "draft"
  }'
```

### JavaScript (Fetch) 示例

```javascript
// 登录
const login = async () => {
  const response = await fetch('/api/auth/login', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'Accept': 'application/json',
    },
    body: JSON.stringify({
      email: 'admin@example.com',
      password: 'password',
    }),
  });

  const data = await response.json();
  return data.data.token;
};

// 获取文章列表
const getPosts = async (token) => {
  const response = await fetch('/api/posts', {
    headers: {
      'Accept': 'application/json',
      'Authorization': `Bearer ${token}`,
    },
  });

  return response.json();
};
```

### PHP 示例 (Guzzle)

```php
use GuzzleHttp\Client;

$client = new Client(['base_uri' => 'http://localhost:8000']);

// 登录
$response = $client->post('/api/auth/login', [
    'json' => [
        'email' => 'admin@example.com',
        'password' => 'password',
    ]
]);

$data = json_decode($response->getBody(), true);
$token = $data['data']['token'];

// 获取文章列表
$response = $client->get('/api/posts', [
    'headers' => [
        'Authorization' => "Bearer {$token}",
        'Accept' => 'application/json',
    ]
]);

$posts = json_decode($response->getBody(), true);
```