import Chart from 'chart.js/auto';

document.addEventListener('DOMContentLoaded', () => {
    const atividade = window.dadosAtividadeMensal || {};
    const distribuicao = window.dadosDistribuicao || { concluidos: 0, em_andamento: 0, nao_iniciados: 0 };

    const canvasAtividade = document.getElementById('graficoAtividade');
    if (canvasAtividade) {
        new Chart(canvasAtividade, {
            type: 'bar',
            data: {
                labels: Object.keys(atividade),
                datasets: [{
                    data: Object.values(atividade),
                    backgroundColor: '#2563eb',
                    borderRadius: 6,
                    maxBarThickness: 40,
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                scales: {
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                    x: { grid: { display: false } },
                },
            },
        });
    }

    const canvasDistribuicao = document.getElementById('graficoDistribuicao');
    if (canvasDistribuicao) {
        new Chart(canvasDistribuicao, {
            type: 'doughnut',
            data: {
                labels: ['Concluídos', 'Em andamento', 'Não iniciados'],
                datasets: [{
                    data: [distribuicao.concluidos, distribuicao.em_andamento, distribuicao.nao_iniciados],
                    backgroundColor: ['#10b981', '#3b82f6', '#e2e8f0'],
                    borderWidth: 0,
                }],
            },
            options: {
                plugins: { legend: { display: false } },
                cutout: '70%',
            },
        });
    }
});
