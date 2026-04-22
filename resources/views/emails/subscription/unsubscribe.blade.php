<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>退订确认 - {{ config('blog.name') }}</title>
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
        h1 {
            color: #e74c3c;
            font-size: 24px;
            margin: 0 0 20px 0;
        }
        .content {
            margin-bottom: 30px;
        }
        .warning {
            background-color: #fdf2f2;
            border: 1px solid #e74c3c;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .warning-title {
            color: #e74c3c;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .button {
            display: inline-block;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 10px 5px;
        }
        .button-danger {
            background-color: #e74c3c;
            color: #ffffff !important;
        }
        .button-danger:hover {
            background-color: #c0392b;
        }
        .button-secondary {
            background-color: #95a5a6;
            color: #ffffff !important;
        }
        .button-secondary:hover {
            background-color: #7f8c8d;
        }
        .button-group {
            text-align: center;
            margin: 30px 0;
        }
        .sad-icon {
            font-size: 48px;
            text-align: center;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="sad-icon">😢</div>
            <h1>确定要退订吗？</h1>
        </div>

        <div class="content">
            <p>亲爱的订阅者：</p>
            
            <p>我们收到了您的退订请求。如果您确定要取消订阅 <strong>{{ config('blog.name') }}</strong>，请点击下面的按钮：</p>
            
            <div class="warning">
                <div class="warning-title">⚠️ 退订后将不再收到以下内容：</div>
                <ul>
                    <li>📝 新文章发布通知</li>
                    <li>🔥 热门内容推荐</li>
                    <li>💌 博客更新动态</li>
                </ul>
            </div>
            
            <p>如果您改变主意，欢迎随时重新订阅！</p>
            
            <div class="button-group">
                <form action="{{ route('subscribe.unsubscribe') }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ $subscription->email }}">
                    <input type="hidden" name="token" value="{{ $subscription->token }}">
                    <button type="submit" class="button button-danger">确认退订</button>
                </form>
                
                <a href="{{ url('/') }}" class="button button-secondary">继续阅读</a>
            </div>
        </div>

        <div class="footer">
            <p>此邮件由 {{ config('blog.name') }} 自动发送</p>
            <p>&copy; {{ date('Y') }} {{ config('blog.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
