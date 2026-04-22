<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>订阅成功 - {{ config('blog.name') }}</title>
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
            color: #27ae60;
            font-size: 24px;
            margin: 0 0 20px 0;
        }
        .success-icon {
            font-size: 64px;
            text-align: center;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .features {
            background-color: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .feature {
            display: flex;
            align-items: center;
            margin: 15px 0;
        }
        .feature-icon {
            font-size: 24px;
            margin-right: 15px;
        }
        .cta-button {
            display: inline-block;
            background-color: #27ae60;
            color: #ffffff !important;
            padding: 14px 28px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            margin: 20px 0;
        }
        .cta-button:hover {
            background-color: #219a52;
        }
        .footer {
            text-align: center;
            color: #7f8c8d;
            font-size: 12px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        .unsubscribe-link {
            color: #7f8c8d;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">🎉</div>
            <h1>订阅成功！</h1>
        </div>

        <div class="content">
            <p>亲爱的订阅者：</p>
            
            <p>恭喜您！您已成功订阅 <strong>{{ config('blog.name') }}</strong>。</p>
            
            <div class="features">
                <h3 style="margin-top: 0;">📬 订阅福利</h3>
                
                <div class="feature">
                    <span class="feature-icon">📝</span>
                    <span><strong>第一时间获取</strong> - 新文章发布时自动通知</span>
                </div>
                
                <div class="feature">
                    <span class="feature-icon">🔥</span>
                    <span><strong>精选内容推荐</strong> - 每周热门文章推送</span>
                </div>
                
                <div class="feature">
                    <span class="feature-icon">💌</span>
                    <span><strong>无广告打扰</strong> - 纯净阅读体验</span>
                </div>
            </div>
            
            <p style="text-align: center;">
                <a href="{{ url('/') }}" class="cta-button">开始阅读 →</a>
            </p>
            
            <p>感谢您的支持！如果您有任何问题或建议，欢迎随时联系我们。</p>
        </div>

        <div class="footer">
            <p>此邮件由 {{ config('blog.name') }} 自动发送</p>
            <p>
                <a href="{{ route('subscribe.unsubscribe.show', ['email' => $email, 'token' => $token]) }}" class="unsubscribe-link">
                    取消订阅
                </a>
            </p>
            <p>&copy; {{ date('Y') }} {{ config('blog.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
