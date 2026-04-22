<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>确认订阅 - {{ config('blog.name') }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            padding: 40px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .logo {
            max-width: 150px;
            height: auto;
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
            margin: 0 0 20px 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            background-color: #3498db;
            color: #ffffff !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .button:hover {
            background-color: #2980b9;
        }
        .footer {
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ config('blog.name') }}</h1>
        </div>

        <div class="content">
            <h2>📧 确认您的订阅</h2>
            
            <p>您好！</p>
            
            <p>您收到这封邮件是因为有人在 {{ config('blog.name') }} 订阅了博客更新。请点击下面的按钮确认您的订阅：</p>
            
            <p style="text-align: center;">
                <a href="{{ route('subscribe.confirm', $token) }}" class="button">确认订阅</a>
            </p>
            
            <p>或者复制以下链接到浏览器打开：</p>
            <p style="word-break: break-all; color: #3498db;">{{ route('subscribe.confirm', $token) }}</p>
            
            <div class="warning">
                <strong>⚠️ 注意：</strong>如果您没有在 {{ config('blog.name') }} 订阅过博客，请忽略这封邮件。
            </div>
            
            <p>确认订阅后，您将收到：</p>
            <ul>
                <li>📝 新文章发布通知</li>
                <li>🔥 热门内容推荐</li>
                <li>💌 博客更新动态</li>
            </ul>
        </div>

        <div class="footer">
            <p>此邮件由 {{ config('blog.name') }} 自动发送</p>
            <p>如果您不想再收到邮件，可以 <a href="{{ route('subscribe.unsubscribe.show', ['email' => $email, 'token' => $token]) }}">取消订阅</a></p>
            <p>&copy; {{ date('Y') }} {{ config('blog.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
