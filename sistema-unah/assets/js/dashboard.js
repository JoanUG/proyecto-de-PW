// Funcionalidades del dashboard

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips de Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Inicializar popovers
    const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    const popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });
    
    // Manejar notificaciones
    const notificationButtons = document.querySelectorAll('.notification-btn');
    notificationButtons.forEach(button => {
        button.addEventListener('click', function() {
            const notificationId = this.getAttribute('data-notification-id');
            markNotificationAsRead(notificationId);
        });
    });
    
    // Funcionalidad de búsqueda en tiempo real
    const searchInput = document.getElementById('globalSearch');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                performSearch(this.value);
            }, 500);
        });
    }
    
    // Actualizar estadísticas en tiempo real (simulación)
    updateRealTimeStats();
    setInterval(updateRealTimeStats, 30000); // Cada 30 segundos
    
    // Gráficos (si existen)
    initCharts();
    
    // Sidebar toggle para móviles
    const sidebarToggle = document.getElementById('sidebarToggle');
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            document.querySelector('.sidebar').classList.toggle('show');
        });
    }
    
    // Cerrar sidebar al hacer clic fuera en móviles
    document.addEventListener('click', function(event) {
        const sidebar = document.querySelector('.sidebar');
        const sidebarToggle = document.getElementById('sidebarToggle');
        
        if (window.innerWidth <= 768 && 
            sidebar && sidebar.classList.contains('show') &&
            !sidebar.contains(event.target) &&
            (!sidebarToggle || !sidebarToggle.contains(event.target))) {
            sidebar.classList.remove('show');
        }
    });
});

// Función para marcar notificación como leída
function markNotificationAsRead(notificationId) {
    // Simulación de AJAX
    console.log(`Marcando notificación ${notificationId} como leída`);
    
    // Aquí iría una petición AJAX real
    // fetch(`api/mark_notification_read.php?id=${notificationId}`)
    //   .then(response => response.json())
    //   .then(data => {
    //       if (data.success) {
    //           // Actualizar interfaz
    //           const notificationElement = document.querySelector(`[data-notification-id="${notificationId}"]`);
    //           if (notificationElement) {
    //               notificationElement.closest('.notification-item').classList.add('read');
    //           }
    //       }
    //   });
}

// Función de búsqueda
function performSearch(query) {
    if (query.length < 2) return;
    
    console.log(`Buscando: ${query}`);
    
    // Aquí iría una petición AJAX real
    // fetch(`api/search.php?q=${encodeURIComponent(query)}`)
    //   .then(response => response.json())
    //   .then(data => {
    //       displaySearchResults(data);
    //   });
}

// Actualizar estadísticas en tiempo real
function updateRealTimeStats() {
    // Simulación de actualización de estadísticas
    const statCards = document.querySelectorAll('.stat-card .stat-number');
    statCards.forEach(card => {
        const currentValue = parseInt(card.textContent);
        if (!isNaN(currentValue)) {
            const change = Math.floor(Math.random() * 5) - 2; // -2 a +2
            const newValue = Math.max(0, currentValue + change);
            
            // Animación suave
            animateCounter(card, currentValue, newValue, 1000);
        }
    });
}

// Animación de contador
function animateCounter(element, start, end, duration) {
    if (start === end) return;
    
    const range = end - start;
    const increment = range > 0 ? 1 : -1;
    const stepTime = Math.abs(Math.floor(duration / range));
    let current = start;
    
    const timer = setInterval(() => {
        current += increment;
        element.textContent = current;
        
        if (current === end) {
            clearInterval(timer);
        }
    }, stepTime);
}

// Inicializar gráficos
function initCharts() {
    // Verificar si Chart.js está disponible
    if (typeof Chart === 'undefined') {
        // Cargar Chart.js dinámicamente si es necesario
        loadChartJS();
        return;
    }
    
    // Inicializar gráficos existentes
    const chartElements = document.querySelectorAll('canvas');
    chartElements.forEach(canvas => {
        const ctx = canvas.getContext('2d');
        const chartType = canvas.getAttribute('data-chart-type') || 'bar';
        
        switch(chartType) {
            case 'doughnut':
                initDoughnutChart(ctx, canvas);
                break;
            case 'bar':
                initBarChart(ctx, canvas);
                break;
            case 'line':
                initLineChart(ctx, canvas);
                break;
        }
    });
}

// Cargar Chart.js dinámicamente
function loadChartJS() {
    const script = document.createElement('script');
    script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    script.onload = initCharts;
    document.head.appendChild(script);
}

// Inicializar gráfico de dona
function initDoughnutChart(ctx, canvas) {
    const data = {
        labels: ['Pendiente', 'En Proceso', 'Finalizada', 'Aprobada'],
        datasets: [{
            data: [25, 40, 20, 15],
            backgroundColor: [
                '#ffc107',
                '#0d6efd',
                '#198754',
                '#0dcaf0'
            ],
            borderWidth: 2
        }]
    };
    
    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Inicializar gráfico de barras
function initBarChart(ctx, canvas) {
    const data = {
        labels: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun'],
        datasets: [{
            label: 'Tesis Registradas',
            data: [12, 19, 8, 15, 12, 18],
            backgroundColor: 'rgba(44, 94, 26, 0.7)',
            borderColor: 'rgba(44, 94, 26, 1)',
            borderWidth: 1
        }]
    };
    
    new Chart(ctx, {
        type: 'bar',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Inicializar gráfico de línea
function initLineChart(ctx, canvas) {
    const data = {
        labels: ['Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb', 'Dom'],
        datasets: [{
            label: 'Actividad',
            data: [12, 19, 8, 15, 12, 18, 10],
            borderColor: 'rgba(139, 195, 74, 1)',
            backgroundColor: 'rgba(139, 195, 74, 0.2)',
            tension: 0.4,
            fill: true
        }]
    };
    
    new Chart(ctx, {
        type: 'line',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
}

// Función para exportar datos
function exportData(format) {
    const exportUrl = `api/export.php?format=${format}`;
    
    // Simulación de descarga
    const link = document.createElement('a');
    link.href = exportUrl;
    link.download = `reporte_${format}_${new Date().toISOString().slice(0,10)}.${format}`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Función para refrescar datos del dashboard
function refreshDashboard() {
    const refreshBtn = document.querySelector('.refresh-btn');
    if (refreshBtn) {
        refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
        refreshBtn.disabled = true;
    }
    
    // Simulación de recarga
    setTimeout(() => {
        location.reload();
    }, 1000);
}

// Event listeners globales
document.addEventListener('keydown', function(e) {
    // Atajos de teclado
    if (e.ctrlKey || e.metaKey) {
        switch(e.key.toLowerCase()) {
            case 'k':
                e.preventDefault();
                document.getElementById('globalSearch')?.focus();
                break;
            case 'r':
                if (!e.shiftKey) {
                    e.preventDefault();
                    refreshDashboard();
                }
                break;
            case 'd':
                e.preventDefault();
                window.location.href = 'dashboard.php';
                break;
        }
    }
});