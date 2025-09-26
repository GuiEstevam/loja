/**
 * Dashboard Cliente - JavaScript Modular
 * Sistema de abas e gráficos interativos
 */

import Chart from 'chart.js/auto';

class DashboardCliente {
    constructor() {
        this.currentTab = 'overview';
        this.chart = null;
        this.init();
    }

    init() {
        this.initTabs();
        this.initChart();
        this.initTooltips();
        this.initResponsive();
    }

    /**
     * Inicializa o sistema de abas
     */
    initTabs() {
        const tabButtons = document.querySelectorAll('.dashboard-tab-btn');
        const tabPanes = document.querySelectorAll('.dashboard-tab-pane');

        tabButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const targetTab = button.getAttribute('data-tab');
                this.switchTab(targetTab, tabButtons, tabPanes);
            });
        });
    }

    /**
     * Troca entre as abas
     */
    switchTab(targetTab, tabButtons, tabPanes) {
        // Remove classe active de todos os botões
        tabButtons.forEach(btn => btn.classList.remove('active'));
        
        // Remove classe active de todos os painéis
        tabPanes.forEach(pane => pane.classList.remove('active'));
        
        // Adiciona classe active ao botão clicado
        const activeButton = document.querySelector(`[data-tab="${targetTab}"]`);
        if (activeButton) {
            activeButton.classList.add('active');
        }
        
        // Adiciona classe active ao painel correspondente
        const activePane = document.getElementById(targetTab);
        if (activePane) {
            activePane.classList.add('active');
        }

        this.currentTab = targetTab;
        
        // Redimensiona o gráfico se necessário
        if (this.chart && targetTab === 'overview') {
            setTimeout(() => {
                this.chart.resize();
            }, 100);
        }
    }

    /**
     * Inicializa o gráfico de gastos
     */
    initChart() {
        const canvas = document.getElementById('spendingChart');
        if (!canvas) return;

        const ctx = canvas.getContext('2d');
        
        // Dados do gráfico (vindos do controller)
        const chartData = window.dashboardData || {
            labels: ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'],
            data: [0, 0, 0, 0, 0, 0, 0]
        };

        this.chart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Gastos (€)',
                    data: chartData.data,
                    borderColor: '#8b5cf6',
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#8b5cf6',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: '#8b5cf6',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return `Gastos: €${context.parsed.y.toFixed(2)}`;
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(100, 116, 139, 0.1)'
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 12
                            },
                            callback: function(value) {
                                return '€' + value.toFixed(0);
                            }
                        }
                    }
                },
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                elements: {
                    point: {
                        hoverBackgroundColor: '#7c3aed'
                    }
                }
            }
        });
    }

    /**
     * Inicializa tooltips para elementos interativos
     */
    initTooltips() {
        // Tooltip para cards de navegação
        const navCards = document.querySelectorAll('.nav-card');
        navCards.forEach(card => {
            card.addEventListener('mouseenter', (e) => {
                this.showTooltip(e.target, 'Clique para acessar');
            });
            
            card.addEventListener('mouseleave', () => {
                this.hideTooltip();
            });
        });

        // Tooltip para botões de ação
        const actionButtons = document.querySelectorAll('.dashboard-action-btn');
        actionButtons.forEach(button => {
            button.addEventListener('mouseenter', (e) => {
                const text = e.target.textContent.trim();
                this.showTooltip(e.target, text);
            });
            
            button.addEventListener('mouseleave', () => {
                this.hideTooltip();
            });
        });
    }

    /**
     * Mostra tooltip personalizado
     */
    showTooltip(element, text) {
        const tooltip = document.createElement('div');
        tooltip.className = 'dashboard-tooltip';
        tooltip.textContent = text;
        tooltip.style.cssText = `
            position: absolute;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.5rem 0.75rem;
            border-radius: 0.375rem;
            font-size: 0.75rem;
            z-index: 1000;
            pointer-events: none;
            opacity: 0;
            transition: opacity 0.2s ease;
        `;
        
        document.body.appendChild(tooltip);
        
        const rect = element.getBoundingClientRect();
        tooltip.style.left = rect.left + (rect.width / 2) - (tooltip.offsetWidth / 2) + 'px';
        tooltip.style.top = rect.top - tooltip.offsetHeight - 8 + 'px';
        
        setTimeout(() => {
            tooltip.style.opacity = '1';
        }, 10);
    }

    /**
     * Esconde tooltip
     */
    hideTooltip() {
        const tooltip = document.querySelector('.dashboard-tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    }

    /**
     * Inicializa responsividade
     */
    initResponsive() {
        // Redimensiona gráfico quando a janela muda de tamanho
        window.addEventListener('resize', () => {
            if (this.chart) {
                this.chart.resize();
            }
        });

        // Detecta mudanças de orientação em dispositivos móveis
        window.addEventListener('orientationchange', () => {
            setTimeout(() => {
                if (this.chart) {
                    this.chart.resize();
                }
            }, 100);
        });
    }

    /**
     * Atualiza dados do gráfico
     */
    updateChartData(newData) {
        if (this.chart && newData) {
            this.chart.data.labels = newData.labels;
            this.chart.data.datasets[0].data = newData.data;
            this.chart.update('active');
        }
    }

    /**
     * Filtra dados por período
     */
    filterByPeriod(period) {
        // Implementar filtros por período (7 dias, 30 dias, etc.)
        console.log(`Filtrando por período: ${period}`);
        
        // Aqui você pode fazer uma requisição AJAX para buscar novos dados
        // ou filtrar os dados existentes no frontend
    }

    /**
     * Exporta dados do dashboard
     */
    exportData(format = 'json') {
        const data = {
            stats: this.getStatsData(),
            chart: this.getChartData(),
            timestamp: new Date().toISOString()
        };

        if (format === 'json') {
            this.downloadJSON(data, 'dashboard-data.json');
        } else if (format === 'csv') {
            this.downloadCSV(data, 'dashboard-data.csv');
        }
    }

    /**
     * Obtém dados das estatísticas
     */
    getStatsData() {
        const stats = {};
        const statCards = document.querySelectorAll('.dashboard-stat-card');
        
        statCards.forEach(card => {
            const label = card.querySelector('.dashboard-stat-label').textContent;
            const value = card.querySelector('.dashboard-stat-value').textContent;
            stats[label] = value;
        });
        
        return stats;
    }

    /**
     * Obtém dados do gráfico
     */
    getChartData() {
        if (this.chart) {
            return {
                labels: this.chart.data.labels,
                data: this.chart.data.datasets[0].data
            };
        }
        return null;
    }

    /**
     * Download JSON
     */
    downloadJSON(data, filename) {
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
    }

    /**
     * Download CSV
     */
    downloadCSV(data, filename) {
        let csv = 'Metric,Value\n';
        
        Object.entries(data.stats).forEach(([key, value]) => {
            csv += `"${key}","${value}"\n`;
        });
        
        const blob = new Blob([csv], { type: 'text/csv' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        a.click();
        URL.revokeObjectURL(url);
    }

    /**
     * Anima elementos ao entrar na viewport
     */
    initAnimations() {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, {
            threshold: 0.1
        });

        // Observa cards de estatísticas
        const statCards = document.querySelectorAll('.dashboard-stat-card');
        statCards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = `opacity 0.6s ease ${index * 0.1}s, transform 0.6s ease ${index * 0.1}s`;
            observer.observe(card);
        });

        // Observa seções
        const sections = document.querySelectorAll('.dashboard-section');
        sections.forEach(section => {
            section.style.opacity = '0';
            section.style.transform = 'translateY(30px)';
            section.style.transition = 'opacity 0.8s ease, transform 0.8s ease';
            observer.observe(section);
        });
    }
}

// Inicializa o dashboard quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', () => {
    // Verifica se Chart.js está disponível
    if (typeof Chart !== 'undefined') {
        window.dashboard = new DashboardCliente();
        
        // Inicializa animações se suportado
        if ('IntersectionObserver' in window) {
            window.dashboard.initAnimations();
        }
    } else {
        console.warn('Chart.js não está carregado. Gráficos não serão exibidos.');
        
        // Inicializa apenas as abas sem gráficos
        const dashboard = new DashboardCliente();
        dashboard.initTabs();
    }
});

// Exporta para uso global se necessário
window.DashboardCliente = DashboardCliente;
