# 前端性能优化指南

本文档说明了博客系统中实现的前端性能优化策略。

## 1. 图片优化

### 1.1 懒加载
所有非首屏图片都使用 `loading="lazy"` 和 `decoding="async"` 属性：

```html
<img src="image.jpg" loading="lazy" decoding="async" alt="描述">
```

### 1.2 响应式图片
根据视口大小提供合适尺寸的图片：

```html
<img 
    srcset="small.jpg 480w, medium.jpg 800w, large.jpg 1200w"
    sizes="(max-width: 480px) 100vw, (max-width: 800px) 50vw, 33vw"
    src="medium.jpg"
    alt="描述"
>
```

### 1.3 图片占位符
使用低质量占位符 (LQIP) 或纯色占位符：

```html
<img 
    src="high-quality.jpg"
    data-src="lazy-load-image.jpg"
    class="lazy"
    alt="描述"
>
```

## 2. JavaScript 优化

### 2.1 脚本延迟加载
使用 `defer` 或 `async` 属性：

```html
<!-- defer: HTML 解析完成后执行 -->
<script defer src="analytics.js"></script>

<!-- async: 下载完成后立即执行 -->
<script async src="chat-widget.js"></script>
```

### 2.2 按需加载组件
使用 `x-lazy-component` 指令延迟加载非首屏组件：

```html
<x-lazy-component component="comments-section" min-height="200px">
    <div>加载中...</div>
</x-lazy-component>
```

### 2.3 事件委托
使用事件委托减少事件监听器数量：

```javascript
// 替代为
document.addEventListener('click', (e) => {
    if (e.target.matches('.delete-btn')) {
        handleDelete(e.target.dataset.id);
    }
});
```

## 3. CSS 优化

### 3.1 关键 CSS 内联
将首屏渲染所需的 CSS 直接内联到 HTML 中：

```html
<head>
    <style>
        /* 关键 CSS */
        body { margin: 0; font-family: system-ui; }
        .header { background: #fff; }
    </style>
</head>
```

### 3.2 异步加载非关键 CSS
```html
<link rel="preload" href="styles.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
```

## 4. 字体优化

### 4.1 字体子集化
仅加载需要的字符集：

```css
@font-face {
    font-family: 'Noto Sans SC';
    src: url('fonts/noto-sans-sc-latin.woff2') format('woff2');
    unicode-range: U+0000-00FF; /* 拉丁字符 */
}
```

### 4.2 字体显示策略
```css
font-display: swap; /* 或 optional */
```

### 4.3 预加载关键字体
```html
<link rel="preload" href="font.woff2" as="font" type="font/woff2" crossorigin>
```

## 5. 缓存策略

### 5.1 Service Worker 缓存
使用 Workbox 进行资源缓存：

```javascript
// 缓存优先策略
workbox.routing.registerRoute(
    /\.(?:png|jpg|jpeg|svg|gif|webp)$/,
    new workbox.strategies.CacheFirst({
        cacheName: 'images',
        plugins: [
            new workbox.expiration.Plugin({
                maxEntries: 60,
                maxAgeSeconds: 30 * 24 * 60 * 60 // 30 天
            })
        ]
    })
);
```

### 5.2 HTTP 缓存头
```
Cache-Control: public, max-age=31536000, immutable
```

## 6. 网络优化

### 6.1 预连接
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="dns-prefetch" href="//analytics.example.com">
```

### 6.2 资源提示
```html
<link rel="prefetch" href="next-page.html">
<link rel="prerender" href="likely-next-page.html">
```

## 7. 渲染优化

### 7.1 避免布局抖动
为图片和媒体元素指定尺寸：

```html
<img src="photo.jpg" width="800" height="600" alt="描述">
```

### 7.2 内容可见性
```css
.content-below-fold {
    content-visibility: auto;
    contain-intrinsic-size: 1px 500px;
}
```

### 7.3 GPU 加速
```css
.animated-element {
    will-change: transform;
    transform: translateZ(0);
}
```

## 8. Core Web Vitals 优化

### 8.1 LCP (Largest Contentful Paint)
- 优化服务器响应时间
- 使用 CDN
- 预加载首屏图片

### 8.2 FID (First Input Delay)
- 减少主线程阻塞
- 代码分割
- 优化长任务

### 8.3 CLS (Cumulative Layout Shift)
- 始终为图片和视频指定尺寸
- 避免动态插入内容
- 使用 `font-display: optional` 避免字体加载导致的布局偏移

## 9. 监控工具

- **Lighthouse**: 页面性能审计
- **WebPageTest**: 详细性能测试
- **Chrome DevTools**: 实时性能分析
- **GTmetrix**: 性能评分和优化建议

## 10. 持续优化

1. 定期运行 Lighthouse CI
2. 设置性能预算
3. 监控真实用户性能 (RUM)
4. A/B 测试优化方案
