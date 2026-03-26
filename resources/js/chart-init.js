// Store chart configs globally
window.chartConfigs = window.chartConfigs || {};

// Initialize all charts on page load
function initializeAllCharts() {
    const canvases = document.querySelectorAll('canvas[data-chart-config]');
    console.log(`[Chart Init] Found ${canvases.length} canvases with chart config`);
    
    if (typeof Chart === 'undefined') {
        console.error('[Chart Init] Chart.js is not available!');
        return;
    }
    
    canvases.forEach((canvas, index) => {
        if (canvas.dataset.initialized) {
            console.log(`[Chart Init] Canvas ${index} already initialized, skipping`);
            return;
        }
        
        try {
            const canvasId = canvas.id;
            console.log(`[Chart Init] Initializing canvas ${index}: ${canvasId}`);
            
            // Decode base64 config
            const configStr = atob(canvas.dataset.chartConfig);
            console.log(`[Chart Init] Decoded config string length: ${configStr.length}`);
            
            const config = JSON.parse(configStr);
            console.log(`[Chart Init] Parsed config type: ${config.type}, labels: ${config.data?.labels?.length || 0}`);
            
            // Check canvas visibility and parent visibility
            const parent = canvas.closest('[style*="position: relative"]');
            const isVisible = canvas.offsetParent !== null || (parent && parent.offsetParent !== null);
            console.log(`[Chart Init] Canvas visible check: ${isVisible}, offsetParent: ${canvas.offsetParent}`);
            
            // Ensure canvas has dimensions
            const rect = canvas.getBoundingClientRect();
            console.log(`[Chart Init] Canvas dimensions - width: ${rect.width}, height: ${rect.height}`);
            
            // If canvas has no dimensions, use parent dimensions or defaults
            if (rect.width === 0 && parent) {
                const parentRect = parent.getBoundingClientRect();
                console.log(`[Chart Init] Canvas has 0 width, parent dimensions: ${parentRect.width}x${parentRect.height}`);
            }
            
            const chartInstance = new Chart(canvas, config);
            console.log(`[Chart Init] Chart created successfully for ${canvasId}`);
            canvas.dataset.initialized = 'true';
        } catch (error) {
            console.error(`[Chart Init] Error initializing chart at index ${index}:`, error);
            console.error(`[Chart Init] Error stack:`, error.stack);
        }
    });
}

// Initialize charts when Chart is available
function checkAndInitCharts() {
    console.log(`[Chart Init] Checking for Chart availability...`);
    if (typeof Chart !== 'undefined') {
        console.log(`[Chart Init] Chart is available, initializing charts`);
        initializeAllCharts();
    } else {
        console.log(`[Chart Init] Chart not ready, retrying in 100ms`);
        setTimeout(checkAndInitCharts, 100);
    }
}

// Run when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    console.log('[Chart Init] DOMContentLoaded event fired');
    checkAndInitCharts();
});

// Also try when window loads (backup)
window.addEventListener('load', () => {
    console.log('[Chart Init] Window load event fired');
    checkAndInitCharts();
});

// Re-initialize when modals open (for dynamically shown content)
if (typeof document !== 'undefined') {
    const observer = new MutationObserver(() => {
        // Re-check for uninitialized charts
        const uninitializedCanvases = document.querySelectorAll('canvas[data-chart-config]:not([data-initialized="true"])');
        if (uninitializedCanvases.length > 0) {
            console.log(`[Chart Init] Found ${uninitializedCanvases.length} uninitialized canvases, retrying...`);
            setTimeout(initializeAllCharts, 100);
        }
    });
    
    observer.observe(document.body, {
        childList: true,
        subtree: true,
        attributes: true,
        attributeFilter: ['style', 'class']
    });
}

export { initializeAllCharts };
