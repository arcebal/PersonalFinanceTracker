import './bootstrap';

import Alpine from 'alpinejs';
import Swal from 'sweetalert2';
import { Chart, ArcElement, BarElement, BarController, PieController, CategoryScale, LinearScale, Tooltip, Legend } from 'chart.js';

Chart.register(ArcElement, BarElement, BarController, PieController, CategoryScale, LinearScale, Tooltip, Legend);

window.Alpine = Alpine;
window.Swal = Swal;

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    const root = document.documentElement;
    const applyThemePreference = (themePreference) => {
        const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
        const shouldUseDark = themePreference === 'dark' || (themePreference === 'system' && prefersDark);
        root.classList.toggle('dark', shouldUseDark);
        root.dataset.themePreference = themePreference;
    };

    const applyFontSizePreference = (fontSizePreference) => {
        root.dataset.fontSize = fontSizePreference;
    };

    applyThemePreference(root.dataset.themePreference || 'system');
    applyFontSizePreference(root.dataset.fontSize || 'default');

    const mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
    const syncSystemTheme = () => {
        if ((root.dataset.themePreference || 'system') === 'system') {
            applyThemePreference('system');
        }
    };

    if (typeof mediaQuery.addEventListener === 'function') {
        mediaQuery.addEventListener('change', syncSystemTheme);
    } else if (typeof mediaQuery.addListener === 'function') {
        mediaQuery.addListener(syncSystemTheme);
    }

    document.querySelectorAll('input[name="theme_preference"]').forEach((input) => {
        input.addEventListener('change', (event) => {
            if (event.target.checked) {
                applyThemePreference(event.target.value);
            }
        });
    });

    document.querySelectorAll('input[name="font_size_preference"]').forEach((input) => {
        input.addEventListener('change', (event) => {
            if (event.target.checked) {
                applyFontSizePreference(event.target.value);
            }
        });
    });

    const computedStyles = getComputedStyle(document.documentElement);
    const cssVar = (name, fallback) => computedStyles.getPropertyValue(name).trim() || fallback;
    const chartPalette = {
        brand: cssVar('--brand', '#138AF2'),
        success: cssVar('--success', '#17B890'),
        danger: cssVar('--danger', '#F36B7F'),
        warning: cssVar('--warning', '#F1B561'),
        grid: cssVar('--chart-grid', 'rgba(148, 163, 184, 0.22)'),
        text: cssVar('--chart-text', 'rgba(18, 35, 61, 0.72)'),
    };

    Chart.defaults.color = chartPalette.text;
    Chart.defaults.borderColor = chartPalette.grid;
    Chart.defaults.font.family = "'Manrope', ui-sans-serif, system-ui, sans-serif";
    Chart.defaults.plugins.legend.labels.usePointStyle = true;

    const flash = document.getElementById('flash');
    if (flash && flash.dataset.success) {
        const undoUrl = flash.dataset.undo || null;
        if (undoUrl) {
            Swal.fire({
                title: flash.dataset.success,
                icon: 'success',
                showCancelButton: true,
                confirmButtonText: 'Undo',
                cancelButtonText: 'Close'
            }).then(res => {
                if (res.isConfirmed) {
                    axios.post(undoUrl).then(() => {
                        Swal.fire({ icon: 'success', title: 'Restored', timer: 1200, showConfirmButton: false });
                        setTimeout(() => location.reload(), 900);
                    }).catch(() => {
                        Swal.fire({ icon: 'error', title: 'Failed to restore' });
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: flash.dataset.success,
                timer: 1800,
                showConfirmButton: false
            });
        }
    }

    document.querySelectorAll('.swal-delete-form').forEach(form => {
        const btn = form.querySelector('.delete-btn');
        if (!btn) return;
        btn.addEventListener('click', (e) => {
            e.stopPropagation();
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: 'This action will delete the item.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: chartPalette.danger,
                confirmButtonText: 'Yes, delete it!'
            }).then(result => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });

    // Category filtering for transaction create/edit forms
    document.querySelectorAll('form').forEach(form => {
        const typeSelect = form.querySelector('#tx-type');
        const categorySelect = form.querySelector('#tx-category');
        if (!typeSelect || !categorySelect) return;
        function filterCategories() {
            const selected = typeSelect.value;
            Array.from(categorySelect.options).forEach(opt => {
                opt.disabled = opt.dataset.type !== selected;
            });
            if (categorySelect.selectedOptions[0] && categorySelect.selectedOptions[0].disabled) {
                const firstEnabled = Array.from(categorySelect.options).find(o => !o.disabled);
                if (firstEnabled) categorySelect.value = firstEnabled.value;
            }
        }
        typeSelect.addEventListener('change', filterCategories);
        filterCategories();
    });

    // Quick-add from account cards: redirect to the full create page for consistency
    document.querySelectorAll('.account-card').forEach(card => {
        card.addEventListener('click', () => {
            const accountId = card.dataset.accountId;
            const url = `/transactions/create?account_id=${accountId}`;
            window.location.href = url;
        });
    });

    // Charts
    if (window.chartData) {
        const pieEl = document.getElementById('pieChart');
        if (pieEl) {
            new Chart(pieEl, {
                type: 'pie',
                data: {
                    labels: window.chartData.pieLabels,
                    datasets: [{
                        data: window.chartData.pieData,
                        backgroundColor: window.chartData.pieColors || [
                            chartPalette.brand,
                            chartPalette.success,
                            chartPalette.warning,
                            '#4f46e5',
                            '#8b5cf6'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                padding: 18,
                                color: chartPalette.text
                            }
                        }
                    }
                }
            });
        }

        const barEl = document.getElementById('barChart');
        if (barEl) {
            new Chart(barEl, {
                type: 'bar',
                data: {
                    labels: window.chartData.months,
                    datasets: [
                        { label: 'Income', backgroundColor: chartPalette.success, borderRadius: 12, data: window.chartData.income },
                        { label: 'Expense', backgroundColor: chartPalette.danger, borderRadius: 12, data: window.chartData.expense }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true, grid: { color: chartPalette.grid } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Budget chart
        const budgetEl = document.getElementById('budgetChart');
        if (budgetEl && window.budgetData) {
            new Chart(budgetEl, {
                type: 'bar',
                data: {
                    labels: window.budgetData.labels,
                    datasets: [
                        { label: 'Budget', backgroundColor: chartPalette.brand, borderRadius: 12, data: window.budgetData.budgets },
                        { label: 'Spent', backgroundColor: chartPalette.danger, borderRadius: 12, data: window.budgetData.spent }
                    ]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true, grid: { color: chartPalette.grid } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // Export PDF button handler
        const exportBtn = document.getElementById('exportPdfBtn');
        if (exportBtn) {
            exportBtn.addEventListener('click', async () => {
                try {
                    const pie = document.getElementById('pieChart');
                    const bar = document.getElementById('barChart');
                    const budget = document.getElementById('budgetChart');
                    const pieImage = pie ? pie.toDataURL('image/png') : null;
                    const barImage = bar ? bar.toDataURL('image/png') : null;
                    const budgetImage = budget ? budget.toDataURL('image/png') : null;
                    const payload = {
                        pieImage,
                        barImage,
                        budgetImage,
                        pieLabels: window.chartData?.pieLabels || [],
                        months: window.chartData?.months || [],
                        income: window.chartData?.income || [],
                        expense: window.chartData?.expense || [],
                        budgetLabels: window.budgetData?.labels || [],
                        budgetAmounts: window.budgetData?.budgets || [],
                        spentAmounts: window.budgetData?.spent || []
                    };

                    document.getElementById('exportStatus').textContent = 'Generating PDF...';

                    const resp = await axios.post('/reports/export-pdf', payload, { responseType: 'blob' });
                    const blob = new Blob([resp.data], { type: 'application/pdf' });
                    const url = window.URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    const filename = 'finance-report-' + new Date().toISOString().slice(0,19).replace(/[:T]/g, '-') + '.pdf';
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    a.remove();
                    window.URL.revokeObjectURL(url);

                    document.getElementById('exportStatus').textContent = 'PDF downloaded.';
                    setTimeout(() => { document.getElementById('exportStatus').textContent = ''; }, 3000);
                } catch (e) {
                    console.error(e);
                    Swal.fire({ icon: 'error', title: 'Export failed', text: e?.response?.data?.message || '' });
                    document.getElementById('exportStatus').textContent = '';
                }
            });
        }
    }
});
