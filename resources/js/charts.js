import Chart from 'chart.js/auto';

export function initializeChart(canvasId, chartConfig) {
    const ctx = document.getElementById(canvasId);
    if (!ctx) return null;

    return new Chart(ctx, chartConfig);
}

export const chartConfigs = {
    doughnut: (labels, data, colors) => ({
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { font: { size: 11 }, padding: 10 }
                }
            }
        }
    })
};
