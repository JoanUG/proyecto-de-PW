<?php if (isset($_SESSION['user_id'])): ?>
            </div> <!-- Cierre de main-content -->
        </div> <!-- Cierre de row -->
    </div> <!-- Cierre de container-fluid -->
<?php else: ?>
    </div> <!-- Cierre de container para login -->
<?php endif; ?>

    <!-- Modal de timeout -->
    <div id="timeoutModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-white">
                    <h5 class="modal-title">
                        <i class="fas fa-clock me-2"></i> Advertencia de Inactividad
                    </h5>
                </div>
                <div class="modal-body text-center">
                    <div class="mb-3">
                        <i class="fas fa-exclamation-triangle fa-3x text-warning"></i>
                    </div>
                    <p class="lead">Su sesión se cerrará por inactividad en</p>
                    <h2 class="text-danger" id="countdown">60</h2>
                    <p class="text-muted">segundos</p>
                    <p>¿Desea continuar con su sesión?</p>
                </div>
                <div class="modal-footer justify-content-center">
                    <button type="button" class="btn btn-success btn-lg" id="continueSession">
                        <i class="fas fa-play-circle me-2"></i> Continuar Sesión
                    </button>
                    <button type="button" class="btn btn-outline-secondary btn-lg" id="logoutNow">
                        <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Scripts personalizados -->
    <script src="assets/js/dashboard.js"></script>
    
    <?php if (isset($custom_scripts)): ?>
        <?php echo $custom_scripts; ?>
    <?php endif; ?>
    
    <script>
        // Inicializar tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Inicializar popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
        
        // Manejar notificaciones
        <?php if (isset($_SESSION['message'])): ?>
            showNotification('<?php echo $_SESSION['message']['text']; ?>', '<?php echo $_SESSION['message']['type']; ?>');
            <?php unset($_SESSION['message']); ?>
        <?php endif; ?>
        
        function showNotification(message, type = 'info') {
            const alertClass = {
                'success': 'alert-success',
                'error': 'alert-danger',
                'warning': 'alert-warning',
                'info': 'alert-info'
            }[type] || 'alert-info';
            
            const alertDiv = document.createElement('div');
            alertDiv.className = `alert ${alertClass} alert-dismissible fade show`;
            alertDiv.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            
            const container = document.querySelector('.main-content') || document.body;
            container.prepend(alertDiv);
            
            setTimeout(() => {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 300);
            }, 5000);
        }
    </script>
</body>
</html>