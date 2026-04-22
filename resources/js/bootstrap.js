/**
 * Bootstrap 工具模块
 * 
 * 提供辅助函数和 Polyfill
 */

/**
 * 检查浏览器是否支持某个特性
 */
export function supports(feature) {
    return feature in document.createElement('input');
}

/**
 * 检测是否为触摸设备
 */
export function isTouchDevice() {
    return 'ontouchstart' in window || 
           navigator.maxTouchPoints > 0 || 
           navigator.msMaxTouchPoints > 0;
}

/**
 * 检测是否为移动设备
 */
export function isMobile() {
    return /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(
        navigator.userAgent
    );
}

/**
 * 检测是否为暗色模式
 */
export function prefersDarkMode() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches;
}

/**
 * 获取 URL 参数
 */
export function getUrlParam(name) {
    const params = new URLSearchParams(window.location.search);
    return params.get(name);
}

/**
 * 设置 URL 参数
 */
export function setUrlParam(key, value) {
    const url = new URL(window.location);
    url.searchParams.set(key, value);
    window.history.pushState({}, '', url);
}

/**
 * 移除 URL 参数
 */
export function removeUrlParam(key) {
    const url = new URL(window.location);
    url.searchParams.delete(key);
    window.history.pushState({}, '', url);
}

/**
 * 获取视口尺寸
 */
export function getViewport() {
    return {
        width: Math.max(document.documentElement.clientWidth || 0, window.innerWidth || 0),
        height: Math.max(document.documentElement.clientHeight || 0, window.innerHeight || 0)
    };
}

/**
 * 检测元素是否在视口内
 */
export function isInViewport(element, partial = true) {
    const rect = element.getBoundingClientRect();
    const windowHeight = window.innerHeight || document.documentElement.clientHeight;
    const windowWidth = window.innerWidth || document.documentElement.clientWidth;
    
    const vertInView = partial 
        ? (rect.top <= windowHeight && (rect.top + rect.height) >= 0)
        : (rect.top >= 0 && (rect.top + rect.height) <= windowHeight);
        
    const horInView = partial
        ? (rect.left <= windowWidth && (rect.left + rect.width) >= 0)
        : (rect.left >= 0 && (rect.left + rect.width) <= windowWidth);
    
    return vertInView && horInView;
}

/**
 * 防抖装饰器
 */
export function debounce(func, wait, immediate = false) {
    let timeout;
    return function executedFunction(...args) {
        const context = this;
        const later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        const callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
}

/**
 * 节流装饰器
 */
export function throttle(func, limit) {
    let inThrottle;
    let lastFunc;
    let lastRan;
    return function executedFunction(...args) {
        const context = this;
        if (!inThrottle) {
            func.apply(context, args);
            lastRan = Date.now();
            inThrottle = true;
        } else {
            clearTimeout(lastFunc);
            lastFunc = setTimeout(() => {
                if (Date.now() - lastRan >= limit) {
                    func.apply(context, args);
                    lastRan = Date.now();
                }
            }, Math.max(limit - (Date.now() - lastRan), 0));
        }
    };
}

/**
 * 生成随机 ID
 */
export function generateId(prefix = 'id') {
    return `${prefix}-${Math.random().toString(36).substr(2, 9)}`;
}

/**
 * 深拷贝
 */
export function deepClone(obj) {
    if (obj === null || typeof obj !== 'object') return obj;
    if (obj instanceof Date) return new Date(obj.getTime());
    if (obj instanceof Array) return obj.map(item => deepClone(item));
    if (obj instanceof Object) {
        const copy = {};
        Object.keys(obj).forEach(key => {
            copy[key] = deepClone(obj[key]);
        });
        return copy;
    }
}

/**
 * 等待指定时间
 */
export function wait(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

/**
 * 带超时的 Promise
 */
export function withTimeout(promise, timeoutMs, errorMessage = '操作超时') {
    return Promise.race([
        promise,
        new Promise((_, reject) => 
            setTimeout(() => reject(new Error(errorMessage)), timeoutMs)
        )
    ]);
}

/**
 * LocalStorage 封装（带 JSON 解析）
 */
export const storage = {
    get(key, defaultValue = null) {
        try {
            const item = localStorage.getItem(key);
            return item ? JSON.parse(item) : defaultValue;
        } catch {
            return defaultValue;
        }
    },
    
    set(key, value) {
        try {
            localStorage.setItem(key, JSON.stringify(value));
            return true;
        } catch {
            return false;
        }
    },
    
    remove(key) {
        try {
            localStorage.removeItem(key);
            return true;
        } catch {
            return false;
        }
    },
    
    clear() {
        try {
            localStorage.clear();
            return true;
        } catch {
            return false;
        }
    }
};

/**
 * 事件委托
 */
export function delegate(element, eventType, selector, handler) {
    element.addEventListener(eventType, (event) => {
        const target = event.target.closest(selector);
        if (target && element.contains(target)) {
            handler.call(target, event, target);
        }
    });
}

/**
 * 媒体查询监听器
 */
export function mediaQuery(query, callback) {
    const mediaQuery = window.matchMedia(query);
    
    // 立即执行一次
    callback(mediaQuery.matches);
    
    // 监听变化
    const handler = (e) => callback(e.matches);
    mediaQuery.addEventListener('change', handler);
    
    // 返回清理函数
    return () => mediaQuery.removeEventListener('change', handler);
}
