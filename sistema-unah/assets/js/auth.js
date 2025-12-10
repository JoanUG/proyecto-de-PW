// Funcionalidades de autenticación

document.addEventListener('DOMContentLoaded', function() {
    // Toggle para mostrar/ocultar contraseña
    const togglePassword = document.getElementById('showPassword');
    const passwordField = document.getElementById('password');
    
    if (togglePassword && passwordField) {
        togglePassword.addEventListener('change', function() {
            passwordField.type = this.checked ? 'text' : 'password';
        });
    }
    
    // Validación del formulario de login
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value;
            
            if (username.length < 3) {
                e.preventDefault();
                showAlert('El usuario debe tener al menos 3 caracteres', 'error');
                return false;
            }
            
            if (password.length < 6) {
                e.preventDefault();
                showAlert('La contraseña debe tener al menos 6 caracteres', 'error');
                return false;
            }
            
            // Simulación de carga
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Verificando...';
                submitBtn.disabled = true;
            }
        });
    }
    
    // Mostrar alertas
    function showAlert(message, type = 'info') {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type === 'error' ? 'danger' : type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        // Insertar después del primer .login-body o al principio del body
        const loginBody = document.querySelector('.login-body');
        if (loginBody) {
            loginBody.prepend(alertDiv);
        } else {
            document.body.prepend(alertDiv);
        }
        
        // Auto-remover después de 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.classList.remove('show');
                setTimeout(() => alertDiv.remove(), 300);
            }
        }, 5000);
    }
    
    // Verificar credenciales de demo
    const demoCredentialButtons = document.querySelectorAll('.demo-credential-btn');
    demoCredentialButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            const role = this.getAttribute('data-role');
            
            let username, password;
            switch(role) {
                case 'admin':
                    username = 'admin';
                    password = 'unah2024';
                    break;
                case 'profesor':
                    username = 'profesor';
                    password = 'unah2024';
                    break;
                case 'estudiante':
                    username = 'estudiante';
                    password = 'unah2024';
                    break;
            }
            
            document.getElementById('username').value = username;
            document.getElementById('password').value = password;
            document.getElementById('remember').checked = true;
            
            showAlert(`Credenciales de ${role} cargadas. Haga clic en "Iniciar Sesión"`, 'success');
        });
    });
});