/**
 * 应用主 JavaScript 入口文件
 * 
 * 包含：Alpine.js、Livewire、主题切换等功能
 */

import Alpine from 'alpinejs';
import Livewire from 'livewire';
import './bootstrap.js';

/**
 * ===========================
 * Alpine.js 初始化
 * ===========================
 */

// 启动 Alpine
window.Alpine = Alpine;

// 注册全局数据存储
Alpine.store('theme', {
    dark: false,
    
    init() {
        // 从 localStorage 读取主题设置
        const stored = localStorage.getItem('theme');
        if (stored) {
            this.dark = stored === 'dark';
        } else {
            // 跟随系统设置
            this.dark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        }
        this.apply();
    },
    
    toggle() {
        this.dark = !this.dark;
        localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        this.apply();
    },
    
    apply() {
        if (this.dark) {
            document.documentElement.classList.add('dark');
            document.body.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
            document.body.classList.remove('dark');
        }
    }
});

// 注册移动端菜单组件
Alpine.data('mobileMenu', () => ({
    open: false,
    
    toggle() {
        this.open = !this.open;
    },
    
    close() {
        this.open = false;
    }
}));

// 注册搜索组件
Alpine.data('search', () => ({
    open: false,
    query: '',
    
    toggle() {
        this.open = !this.open;
        if (this.open) {
            this.$nextTick(() => {
                this.$refs.searchInput?.focus();
            });
        }
    },
    
    close() {
        this.open = false;
        this.query = '';
    }
}));

// 注册下拉菜单组件
Alpine.data('dropdown', () => ({
    open: false,
    
    toggle() {
        this.open = !this.open;
    },
    
    close() {
        this.open = false;
    }
}));

// 注册 Tabs 组件
Alpine.data('tabs', (initialTab = null) => ({
    activeTab: initialTab,
    
    init() {
        if (!this.activeTab) {
            const firstTab = this.$refs.tablist?.querySelector('[role="tab"]');
            this.activeTab = firstTab?.dataset.tab;
        }
    },
    
    switch(tab) {
        this.activeTab = tab;
        
        // 触发 tab 切换事件
        this.$dispatch('tab-changed', { tab });
    }
}));

// 注册折叠面板组件
Alpine.data('collapse', (initOpen = false) => ({
    open: initOpen,
    
    toggle() {
        this.open = !this.open;
    }
}));

// 注册 Toast 通知组件
Alpine.store('toast', {
    notifications: [],
    
    add(notification) {
        const id = Date.now();
        this.notifications.push({ id, ...notification });
        
        // 自动移除
        if (notification.duration !== 0) {
            setTimeout(() => {
                this.remove(id);
            }, notification.duration || 5000);
        }
    },
    
    remove(id) {
        this.notifications = this.notifications.filter(n => n.id !== id);
    }
}));

Alpine.start();

/**
 * ===========================
 * Livewire 初始化
 * ===========================
 */

// 配置 Livewire
Livewire.config = {
    // 调试模式（生产环境设为 false）
    debug: import.meta.env.DEV,
    
    // 确认窗口
    confirm: {
        // 关闭时自动重置
        resetOnConfirm: true,
    },
    
    // 通用配置
    generic: {
        // 加载延迟（防止闪烁）
        loadingDelay: 200,
    },
    
    // 表单特性
    formFeatures: {
        // 文件名上传
        fileUploadOnProgress: true,
    }
};

// 处理 Livewire 事件
Livewire.on('success', (event) => {
    // 显示成功提示
    Alpine.store('toast').add({
        type: 'success',
        message: event.message || '操作成功！',
    });
});

Livewire.on('error', (event) => {
    // 显示错误提示
    Alpine.store('toast').add({
        type: 'error',
        message: event.message || '发生错误，请重试。',
    });
});

Livewire.on('warning', (event) => {
    Alpine.store('toast').add({
        type: 'warning',
        message: event.message || '警告！',
    });
});

/**
 * ===========================
 * 全局工具函数
 * ===========================
 */

window.BlogUtils = {
    /**
     * 格式化日期
     */
    formatDate(date, format = 'YYYY-MM-DD') {
        const d = new Date(date);
        const year = d.getFullYear();
        const month = String(d.getMonth() + 1).padStart(2, '0');
        const day = String(d.getDate()).padStart(2, '0');
        const hour = String(d.getHours()).padStart(2, '0');
        const minute = String(d.getMinutes()).padStart(2, '0');
        
        return format
            .replace('YYYY', year)
            .replace('MM', month)
            .replace('DD', day)
            .replace('HH', hour)
            .replace('mm', minute);
    },
    
    /**
     * 相对时间（多少分钟/小时前）
     */
    timeAgo(date) {
        const now = new Date();
        const past = new Date(date);
        const diff = now - past;
        
        const seconds = Math.floor(diff / 1000);
        const minutes = Math.floor(seconds / 60);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        const months = Math.floor(days / 30);
        const years = Math.floor(days / 365);
        
        if (years > 0) return `${years}年前`;
        if (months > 0) return `${months}个月前`;
        if (days > 0) return `${days}天前`;
        if (hours > 0) return `${hours}小时前`;
        if (minutes > 0) return `${minutes}分钟前`;
        return '刚刚';
    },
    
    /**
     * 防抖函数
     */
    debounce(func, wait = 250) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    },
    
    /**
     * 节流函数
     */
    throttle(func, limit = 250) {
        let inThrottle;
        return function executedFunction(...args) {
            if (!inThrottle) {
                func(...args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },
    
    /**
     * 复制文本到剪贴板
     */
    async copyToClipboard(text) {
        try {
            await navigator.clipboard.writeText(text);
            Alpine.store('toast').add({
                type: 'success',
                message: '已复制到剪贴板',
                duration: 2000,
            });
            return true;
        } catch (err) {
            console.error('复制失败:', err);
            return false;
        }
    },
    
    /**
     * 平滑滚动到元素
     */
    scrollTo(element, offset = 0) {
        const target = typeof element === 'string' 
            ? document.querySelector(element) 
            : element;
            
        if (target) {
            const top = target.getBoundingClientRect().top + window.pageYOffset - offset;
            window.scrollTo({ top, behavior: 'smooth' });
        }
    },
    
    /**
     * 复制文章链接
     */
    async sharePost(title, url) {
        const shareData = {
            title,
            url: url || window.location.href,
        };
        
        // 尝试使用 Web Share API
        if (navigator.share && navigator.canShare && navigator.canShare(shareData)) {
            try {
                await navigator.share(shareData);
                return;
            } catch (err) {
                if (err.name === 'AbortError') return;
            }
        }
        
        // 降级到复制链接
        await this.copyToClipboard(shareData.url);
    }
};

/**
 * ===========================
 * 初始化
 * ===========================
 */

document.addEventListener('DOMContentLoaded', () => {
    // 初始化主题
    Alpine.store('theme').init();
    
    // 监听系统主题变化
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (e) => {
        if (!localStorage.getItem('theme')) {
            Alpine.store('theme').dark = e.matches;
            Alpine.store('theme').apply();
        }
    });
    
    // 平滑滚动锚点
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', (e) => {
            const href = anchor.getAttribute('href');
            if (href === '#') return;
            
            e.preventDefault();
            BlogUtils.scrollTo(href, 80);
        });
    });
});

// 页面可见性变化时暂停/恢复动画
document.addEventListener('visibilitychange', () => {
    if (document.hidden) {
        document.body.classList.add('paused');
    } else {
        document.body.classList.remove('paused');
    }
});
