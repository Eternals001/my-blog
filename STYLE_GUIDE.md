# 个人博客前端设计规范

## 概述

本文档定义了博客项目的前端设计规范，确保界面的一致性和可维护性。

## 技术栈

| 技术 | 版本 | 用途 |
|------|------|------|
| TailwindCSS | 3.x | CSS 框架 |
| Alpine.js | 3.x | 前端交互 |
| Livewire | 3.x | 前后端通信 |
| Vite | 5.x | 构建工具 |

## 设计系统

### 配色方案

#### 主色调 (Primary - 靛蓝色)
```
primary-50:  #eef2ff
primary-100: #e0e7ff
primary-200: #c7d2fe
primary-300: #a5b4fc
primary-400: #818cf8
primary-500: #6366f1  (默认)
primary-600: #4f46e5
primary-700: #4338ca
primary-800: #3730a3
primary-900: #312e81
primary-950: #1e1b4b
```

#### 强调色 (Accent - 琥珀色)
```
accent-50:  #fffbeb
accent-100: #fef3c7
accent-200: #fde68a
accent-300: #fcd34d
accent-400: #fbbf24
accent-500: #f59e0b  (默认)
accent-600: #d97706
accent-700: #b45309
accent-800: #92400e
accent-900: #78350f
accent-950: #451a03
```

#### 中性色 (Gray)
```
gray-50:  #f9fafb  (默认背景)
gray-100: #f3f4f6
gray-200: #e5e7eb
gray-300: #d1d5db
gray-400: #9ca3af
gray-500: #6b7280
gray-600: #4b5563
gray-700: #374151
gray-800: #1f2937  (默认深色背景)
gray-900: #111827
gray-950: #030712
```

### 字体系统

```css
/* 字体栈 */
font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, 
             "Helvetica Neue", Arial, sans-serif, "Apple Color Emoji", 
             "Segoe UI Emoji", "Segoe UI Symbol", "Noto Sans SC", 
             "PingFang SC", "Microsoft YaHei";

/* 字号 */
text-xs:   0.75rem  (12px) - 辅助文字
text-sm:   0.875rem (14px) - 次要信息
text-base: 1rem     (16px) - 正文
text-lg:   1.125rem (18px) - 副标题
text-xl:   1.25rem  (20px) - 小标题
text-2xl:  1.5rem   (24px) - 区块标题
text-3xl:  1.875rem (30px) - 页面标题
text-4xl:  2.25rem  (36px) - Hero 标题
```

### 间距系统

基于 0.25rem (4px) 网格：

| 名称 | 值 | 用途 |
|------|-----|------|
| 1 | 0.25rem | 紧凑间距 |
| 2 | 0.5rem | 元素内间距 |
| 3 | 0.75rem | 小间距 |
| 4 | 1rem | 默认间距 |
| 6 | 1.5rem | 组件间距 |
| 8 | 2rem | 区块间距 |
| 12 | 3rem | 大区块间距 |
| 16 | 4rem | 页面分区 |

### 圆角系统

| 名称 | 值 | 用途 |
|------|-----|------|
| rounded | 0.25rem | 小元素 |
| rounded-lg | 0.5rem | 按钮、输入框 |
| rounded-xl | 0.75rem | 卡片 |
| rounded-2xl | 1rem | 大卡片 |
| rounded-full | 9999px | 圆形、标签 |

### 阴影系统

```css
/* 卡片阴影 */
shadow-sm    - 子元素悬停
shadow-md    - 默认卡片
shadow-lg    - 模态框
shadow-xl    - 侧边栏

/* 自定义阴影 */
shadow-soft  - 柔和阴影（Light模式）
shadow-glow  - 发光效果（Primary）
```

## 组件规范

### 按钮

```blade
{{-- 主要按钮 --}}
<button class="btn-primary">主要操作</button>

{{-- 次要按钮 --}}
<button class="btn-secondary">次要操作</button>

{{-- 轮廓按钮 --}}
<button class="btn-outline">轮廓样式</button>

{{-- 幽灵按钮 --}}
<button class="btn-ghost">无背景</button>
```

状态：
- Hover: 颜色加深 10%
- Active: 颜色加深 15%
- Disabled: opacity-50, cursor-not-allowed
- Loading: 显示旋转图标

### 卡片

```blade
{{-- 默认卡片 --}}
<div class="card">
    <div class="p-6">
        <!-- 内容 -->
    </div>
</div>

{{-- 文章卡片 --}}
<x-post-card :post="$post" variant="featured" />
```

### 输入框

```blade
<input type="text" class="input" placeholder="请输入...">
```

### 徽章

```blade
<span class="badge-primary">主要</span>
<span class="badge-accent">强调</span>
<span class="badge-gray">灰色</span>
```

## 响应式断点

| 名称 | 最小宽度 | 用途 |
|------|----------|------|
| sm | 640px | 手机横屏 |
| md | 768px | 平板竖屏 |
| lg | 1024px | 平板横屏/小笔记本 |
| xl | 1280px | 桌面 |
| 2xl | 1536px | 大屏桌面 |

## 暗黑模式

### 启用方式

1. **Class 策略**: `darkMode: 'class'`
2. **切换方式**: 
   - 手动切换（点击按钮）
   - 系统跟随（prefers-color-scheme）

### 切换脚本

```html
<script>
    (function() {
        const stored = localStorage.getItem('theme');
        let shouldBeDark = stored === 'dark' || 
            (!stored && window.matchMedia('(prefers-color-scheme: dark)').matches);
        if (shouldBeDark) {
            document.documentElement.classList.add('dark');
        }
    })();
</script>
```

### 样式约定

```css
/* 浅色模式 */
.bg-white { }

/* 深色模式 */
.dark .dark\:bg-gray-900 { }

/* 通用（自动适配） */
.text-gray-900 { }
```

## 无障碍规范

### ARIA 属性

- 所有交互元素必须有 `aria-label`
- 模态框必须有 `role="dialog"` 和 `aria-modal="true"`
- 分页必须有 `role="navigation"` 和 `aria-label`
- 图片必须有 `alt` 属性

### 键盘导航

- 所有交互元素可通过 Tab 聚焦
- 焦点样式: `outline-2 outline-offset-2 outline-primary-500`
- ESC 关闭模态框/下拉菜单
- 方向键导航选项列表

### 屏幕阅读器

- 隐藏元素但保留可访问性: `sr-only`
- 跳过链接: Skip to content
- 动态内容更新使用 `aria-live`

## 文件结构

```
resources/
├── css/
│   └── app.css          # 主样式文件
├── js/
│   ├── app.js           # 主入口文件
│   └── bootstrap.js     # 工具函数
└── views/
    ├── components/
    │   ├── layout/      # 布局组件
    │   │   ├── app.blade.php
    │   │   ├── header.blade.php
    │   │   └── footer.blade.php
    │   ├── post-card.blade.php
    │   ├── tag-cloud.blade.php
    │   ├── pagination.blade.php
    │   └── comment-item.blade.php
    ├── frontend/        # 前台页面
    │   ├── home.blade.php
    │   ├── post.blade.php
    │   ├── category.blade.php
    │   └── tag.blade.php
    ├── backend/         # 后台页面
    │   ├── layouts/
    │   └── dashboard.blade.php
    └── auth/            # 认证页面
        ├── login.blade.php
        └── register.blade.php
```

## 常用类名速查

### 布局
- `container-blog` - 主容器
- `flex` - 弹性盒子
- `grid` - 网格布局
- `gap-{n}` - 间距

### 响应式
- `hidden` - 全部隐藏
- `block` - 块级显示
- `inline-block` - 行内块
- `sm:hidden` - sm以上隐藏
- `lg:block` - lg以上显示

### 文本
- `text-{size}` - 字号
- `font-{weight}` - 字重
- `text-{color}` - 颜色
- `line-clamp-{n}` - 行数限制

### 背景
- `bg-{color}` - 背景色
- `dark:bg-{color}` - 深色背景
- `bg-gradient-to-r` - 渐变背景

## 动画规范

### 过渡时间
- 快速: `duration-150` (150ms)
- 默认: `duration-200` (200ms)
- 慢速: `duration-300` (300ms)

### 常用动画
- `transition-colors` - 颜色过渡
- `transition-transform` - 变换过渡
- `animate-spin` - 旋转
- `animate-pulse` - 脉冲
- `animate-bounce` - 弹跳

### Alpine.js 过渡
```html
<div x-show="open"
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100">
</div>
```

## 性能优化

### 图片
- 使用 WebP 格式
- 指定 width/height 防止布局偏移
- 使用 `loading="lazy"` 延迟加载
- 响应式图片: `srcset`

### CSS
- 使用 TailwindCSS JIT 模式
- 避免不必要的 dark: 前缀
- 使用 `group` 减少重复选择器

### JavaScript
- 使用 Alpine.js 替代 jQuery
- 事件委托减少监听器
- 防抖/节流频繁操作

## 浏览器兼容

| 浏览器 | 最低版本 |
|--------|----------|
| Chrome | 88+ |
| Firefox | 85+ |
| Safari | 14+ |
| Edge | 88+ |

## 贡献指南

1. 遵循本规范的命名约定
2. 使用现有的组件而非重复代码
3. 确保暗黑模式正常工作
4. 测试响应式布局
5. 验证无障碍功能
