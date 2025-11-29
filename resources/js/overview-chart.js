import Chart from 'chart.js/auto';

function formatCounts(data) {
    return {
        labels: ['Teachers', 'Sections', 'Subjects'],
        values: [data.teachers || 0, data.sections || 0, data.subjects || 0]
    };
}

export default function initOverviewChart() {
    const canvas = document.getElementById('overviewChart');
    if (!canvas) return;

        const loadingEl = document.getElementById('overviewChartLoading');
        const errorEl = document.getElementById('overviewChartError');
        if (!canvas) return;

        function showLoading(show = true) {
            if (!loadingEl) return;
            if (show) {
                loadingEl.classList.remove('hidden');
                loadingEl.classList.add('flex', 'items-center', 'justify-center');
            } else {
                loadingEl.classList.add('hidden');
                loadingEl.classList.remove('flex', 'items-center', 'justify-center');
            }
        }

        function showError(show = false) {
            if (!errorEl) return;
            if (show) {
                errorEl.classList.remove('hidden');
            } else {
                errorEl.classList.add('hidden');
            }
            const wrapper = document.getElementById('overviewChartWrapper');
            if (wrapper) wrapper.classList.toggle('hidden', show);
        }

        showLoading(true);
        fetch('/admin/it/overview/data')
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok');
                return res.json();
            })
            .then(data => {
                showLoading(false);
                showError(false);

                const d = formatCounts(data);
                const ctx = canvas.getContext('2d');
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: d.labels,
                        datasets: [{
                            label: 'Counts',
                            data: d.values,
                            backgroundColor: ['#10B981', '#3B82F6', '#EC4899']
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: { display: false }
                        },
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });
            })
            .catch(err => {
                console.error('overview chart error', err);
                showLoading(false);
                showError(true);
            });
}
