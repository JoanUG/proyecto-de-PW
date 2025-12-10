// Funcionalidades específicas para la gestión de tesis

document.addEventListener('DOMContentLoaded', function() {
    // Inicializar tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Validación de formulario de tesis
    const formTesis = document.getElementById('formTesis');
    if (formTesis) {
        formTesis.addEventListener('submit', function(e) {
            const titulo = document.getElementById('titulo').value.trim();
            const estudiante = document.getElementById('estudiante_id').value;
            
            if (titulo.length < 10) {
                e.preventDefault();
                showAlert('El título debe tener al menos 10 caracteres', 'error');
                return false;
            }
            
            if (!estudiante) {
                e.preventDefault();
                showAlert('Debe seleccionar un estudiante', 'error');
                return false;
            }
            
            // Verificar duplicidad via AJAX (opcional)
            // Puedes implementar una llamada AJAX aquí
        });
    }
    
    // Búsqueda en tiempo real
    const searchInput = document.getElementById('searchInput');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(function() {
                // Aquí podrías implementar búsqueda en tiempo real
                console.log('Buscando:', searchInput.value);
            }, 500);
        });
    }
    
    // Confirmación de eliminación
    const deleteButtons = document.querySelectorAll('.btn-delete-tesis');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!confirm('¿Está seguro que desea eliminar esta tesis? Esta acción no se puede deshacer.')) {
                e.preventDefault();
                return false;
            }
        });
    });
    
    // Cargar temas similares al escribir título
    const tituloInput = document.getElementById('titulo');
    if (tituloInput) {
        tituloInput.addEventListener('input', function() {
            const titulo = this.value;
            if (titulo.length > 3) {
                // Aquí podrías hacer una petición AJAX para buscar temas similares
                // y mostrar una advertencia si hay duplicidad
            }
        });
    }
    
    // Función para exportar a PDF
    window.exportToPDF = function() {
        // Implementar exportación a PDF
        alert('Exportando a PDF... (funcionalidad en desarrollo)');
    };
    
    // Función para exportar a Excel
    window.exportToExcel = function() {
        // Implementar exportación a Excel
        alert('Exportando a Excel... (funcionalidad en desarrollo)');
    };
    
    // Inicializar gráficos si existen
    const chartElements = document.querySelectorAll('.chart-container');
    if (chartElements.length > 0) {
        // Cargar Chart.js dinámicamente si no está cargado
        if (typeof Chart === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = initCharts;
            document.head.appendChild(script);
        } else {
            initCharts();
        }
    }
    
    // Funcionalidad de filtros avanzados
    const advancedFilters = document.getElementById('advancedFilters');
    const toggleFiltersBtn = document.getElementById('toggleFilters');
    if (toggleFiltersBtn && advancedFilters) {
        toggleFiltersBtn.addEventListener('click', function() {
            advancedFilters.classList.toggle('d-none');
            this.innerHTML = advancedFilters.classList.contains('d-none') 
                ? '<i class="fas fa-filter me-2"></i> Mostrar Filtros Avanzados'
                : '<i class="fas fa-times me-2"></i> Ocultar Filtros';
        });
    }
    
    // Ordenar tabla
    const sortableHeaders = document.querySelectorAll('th[data-sort]');
    sortableHeaders.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', function() {
            const sortField = this.getAttribute('data-sort');
            const currentOrder = this.getAttribute('data-order') || 'asc';
            const newOrder = currentOrder === 'asc' ? 'desc' : 'asc';
            
            // Actualizar icono
            this.setAttribute('data-order', newOrder);
            
            // Limpiar iconos de otros headers
            sortableHeaders.forEach(h => {
                if (h !== this) {
                    h.innerHTML = h.innerHTML.replace(/<i class="fas fa-sort-(up|down)"><\/i>/, '');
                    h.removeAttribute('data-order');
                }
            });
            
            // Agregar icono
            const icon = document.createElement('i');
            icon.className = `fas fa-sort-${newOrder === 'asc' ? 'up' : 'down'} ms-1`;
            this.appendChild(icon);
            
            // Ordenar tabla (simulación)
            sortTable(sortField, newOrder);
        });
    });
    
    // Funcionalidad de selección múltiple
    const selectAllCheckbox = document.getElementById('selectAll');
    const tesisCheckboxes = document.querySelectorAll('.tesis-checkbox');
    
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            tesisCheckboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            updateBulkActions();
        });
    }
    
    tesisCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateBulkActions();
            updateSelectAllCheckbox();
        });
    });
    
    // Acciones masivas
    const bulkActions = document.getElementById('bulkActions');
    const bulkActionSelect = document.getElementById('bulkAction');
    const applyBulkActionBtn = document.getElementById('applyBulkAction');
    
    if (applyBulkActionBtn) {
        applyBulkActionBtn.addEventListener('click', function() {
            const action = bulkActionSelect.value;
            const selectedTesis = getSelectedTesis();
            
            if (selectedTesis.length === 0) {
                showAlert('Seleccione al menos una tesis', 'warning');
                return;
            }
            
            if (confirm(`¿Está seguro de aplicar "${bulkActionSelect.options[bulkActionSelect.selectedIndex].text}" a ${selectedTesis.length} tesis?`)) {
                applyBulkAction(action, selectedTesis);
            }
        });
    }
});

// Función para mostrar alertas
function showAlert(message, type = 'info') {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    // Insertar al principio del contenido
    const mainContent = document.querySelector('.main-content') || document.querySelector('.container-fluid');
    if (mainContent) {
        mainContent.prepend(alertDiv);
    }
    
    // Auto-remover después de 5 segundos
    setTimeout(() => {
        if (alertDiv.parentNode) {
            alertDiv.classList.remove('show');
            setTimeout(() => alertDiv.remove(), 300);
        }
    }, 5000);
}

// Función para ordenar tabla
function sortTable(field, order) {
    const table = document.querySelector('table.table-unah');
    if (!table) return;
    
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    
    rows.sort((a, b) => {
        let aValue, bValue;
        
        // Obtener valores según el campo
        switch(field) {
            case 'titulo':
                aValue = a.querySelector('td:nth-child(2)').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(2)').textContent.toLowerCase();
                break;
            case 'estudiante':
                aValue = a.querySelector('td:nth-child(3)').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(3)').textContent.toLowerCase();
                break;
            case 'estado':
                aValue = a.querySelector('td:nth-child(6) .badge').textContent.toLowerCase();
                bValue = b.querySelector('td:nth-child(6) .badge').textContent.toLowerCase();
                break;
            default:
                aValue = a.querySelector(`td[data-${field}]`)?.getAttribute(`data-${field}`) || '';
                bValue = b.querySelector(`td[data-${field}]`)?.getAttribute(`data-${field}`) || '';
        }
        
        // Comparar
        if (aValue < bValue) return order === 'asc' ? -1 : 1;
        if (aValue > bValue) return order === 'asc' ? 1 : -1;
        return 0;
    });
    
    // Reordenar filas
    rows.forEach(row => tbody.appendChild(row));
}

// Obtener tesis seleccionadas
function getSelectedTesis() {
    const selected = [];
    document.querySelectorAll('.tesis-checkbox:checked').forEach(checkbox => {
        selected.push(checkbox.value);
    });
    return selected;
}

// Actualizar acciones masivas
function updateBulkActions() {
    const selectedCount = getSelectedTesis().length;
    const bulkActions = document.getElementById('bulkActions');
    
    if (bulkActions) {
        if (selectedCount > 0) {
            bulkActions.classList.remove('d-none');
            document.getElementById('selectedCount').textContent = selectedCount;
        } else {
            bulkActions.classList.add('d-none');
        }
    }
}

// Actualizar checkbox "Seleccionar todos"
function updateSelectAllCheckbox() {
    const selectAllCheckbox = document.getElementById('selectAll');
    if (!selectAllCheckbox) return;
    
    const tesisCheckboxes = document.querySelectorAll('.tesis-checkbox');
    const checkedCount = getSelectedTesis().length;
    
    if (checkedCount === 0) {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = false;
    } else if (checkedCount === tesisCheckboxes.length) {
        selectAllCheckbox.checked = true;
        selectAllCheckbox.indeterminate = false;
    } else {
        selectAllCheckbox.checked = false;
        selectAllCheckbox.indeterminate = true;
    }
}

// Aplicar acción masiva
function applyBulkAction(action, tesisIds) {
    console.log(`Aplicando acción "${action}" a tesis:`, tesisIds);
    
    // Aquí iría una petición AJAX real
    // fetch('api/bulk_action_tesis.php', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //     },
    //     body: JSON.stringify({
    //         action: action,
    //         tesis_ids: tesisIds
    //     })
    // })
    // .then(response => response.json())
    // .then(data => {
    //     if (data.success) {
    //         showAlert(data.message, 'success');
    //         setTimeout(() => location.reload(), 1000);
    //     } else {
    //         showAlert(data.message, 'error');
    //     }
    // });
    
    // Simulación
    showAlert(`Acción "${action}" aplicada a ${tesisIds.length} tesis`, 'success');
    setTimeout(() => {
        // Recargar para ver cambios
        location.reload();
    }, 1500);
}

// Inicializar gráficos de tesis
function initCharts() {
    // Inicializar gráficos específicos de tesis aquí
    const tesisChartCanvas = document.getElementById('tesisChart');
    if (tesisChartCanvas && typeof Chart !== 'undefined') {
        const ctx = tesisChartCanvas.getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pendiente', 'En Proceso', 'Finalizada', 'Aprobada'],
                datasets: [{
                    label: 'Cantidad de Tesis',
                    data: [15, 25, 10, 5],
                    backgroundColor: [
                        '#ffc107',
                        '#0d6efd',
                        '#198754',
                        '#0dcaf0'
                    ]
                }]
            },
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
}

// Función para previsualizar archivos
function previewFile(input) {
    const file = input.files[0];
    if (!file) return;
    
    const preview = document.getElementById('filePreview');
    if (!preview) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        preview.innerHTML = `
            <div class="alert alert-info">
                <i class="fas fa-file me-2"></i>
                Archivo seleccionado: <strong>${file.name}</strong> (${(file.size / 1024).toFixed(2)} KB)
            </div>
        `;
    };
    reader.readAsDataURL(file);
}