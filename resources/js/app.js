import './bootstrap';
import './chart-init';

import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';

window.Alpine = Alpine;
window.Chart = Chart;

// Define assessment summary Alpine component
window.assessmentSummary = function() {
    return {
        loading: true,
        
        init() {
            console.log('[AlpineJS] Assessment Summary initialized');
            // Automatically load if not cached
            const container = document.getElementById('aiSummaryContainer');
            if (container && container.querySelector('.bg-white.rounded.p-4.border-red-200')) {
                // Has error message, don't retry
                return;
            }
            if (container && !container.querySelector('.ai-insights')) {
                this.loadAISummary();
            }
        },

        loadAISummary() {
            const communityId = this.$el.dataset.communityId;
            if (!communityId) {
                console.warn('[AlpineJS] No community ID found');
                return;
            }
            
            console.log('[AlpineJS] Starting AI summary load for community:', communityId);

            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                             document.querySelector('[name="_token"]')?.value || '';

            const controller = new AbortController();
            const timeoutId = setTimeout(() => {
                controller.abort();
                console.error('[AlpineJS] Request timeout after 45 seconds');
            }, 45000);

            fetch(`/api/v1/communities/${communityId}/generate-ai-summary`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': csrfToken,
                    'Accept': 'application/json',
                },
                credentials: 'include',
                signal: controller.signal,
            })
            .then(response => {
                clearTimeout(timeoutId);
                console.log('[AlpineJS] Response received:', response.status);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                return response.json();
            })
            .then(data => {
                console.log('[AlpineJS] Data parsed:', data);
                
                if (data.status === 'success' && data.data.ai_summary) {
                    console.log('[AlpineJS] Summary loaded successfully');
                    const container = document.getElementById('aiSummaryContainer');
                    if (container) {
                        container.innerHTML = `
                            <div class="bg-white rounded p-3 border border-purple-100">
                                ${data.data.ai_summary}
                                <div class="text-xs text-gray-500 mt-3 pt-3 border-t border-gray-200">
                                    Generated: ${new Date(data.data.generated_at).toLocaleString()}
                                </div>
                            </div>
                        `;
                    }
                } else {
                    console.warn('[AlpineJS] Invalid response data:', data);
                    const container = document.getElementById('aiSummaryContainer');
                    if (container) {
                        container.innerHTML = `
                            <div class="bg-white rounded p-4 border border-red-200 text-center">
                                <p class="text-sm text-red-700">Failed to generate AI analysis</p>
                                <p class="text-xs text-gray-500 mt-1">Response: ${data.data?.ai_summary ? 'Invalid format' : 'No summary data'}</p>
                            </div>
                        `;
                    }
                }
            })
            .catch(error => {
                clearTimeout(timeoutId);
                console.error('[AlpineJS] Error loading:', error);
                
                let errorMessage = 'Error communicating with AI service';
                let errorDetails = 'Please refresh the page and try again';
                
                if (error.name === 'AbortError') {
                    errorMessage = 'AI analysis took too long to process';
                    errorDetails = 'The AI service is busy. Please try again in a moment.';
                }
                
                const container = document.getElementById('aiSummaryContainer');
                if (container) {
                    container.innerHTML = `
                        <div class="bg-white rounded p-4 border border-red-200 text-center">
                            <p class="text-sm text-red-700">${errorMessage}</p>
                            <p class="text-xs text-gray-500 mt-1">${errorDetails}</p>
                            <p class="text-xs text-gray-400 mt-2">Check browser console for details (F12)</p>
                        </div>
                    `;
                }
            });
        }
    };
};

Alpine.start();
