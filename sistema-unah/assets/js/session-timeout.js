// Configuración del timeout de sesión
const SESSION_TIMEOUT = 60; // 1 minuto en segundos (para demostración)
const WARNING_TIME = 10; // Mostrar advertencia 10 segundos antes

let timeout;
let warningTimeout;
let countdownInterval;
let isWarningShown = false;

// Inicializar el control de tiempo de inactividad
function initSessionTimeout() {
    resetTimeout();
    
    // Eventos que resetean el timeout
    document.addEventListener('mousemove', resetTimeout);
    document.addEventListener('keypress', resetTimeout);
    document.addEventListener('click', resetTimeout);
    document.addEventListener('scroll', resetTimeout);
    
    // Actualizar el timer en el dashboard
    if (document.getElementById('sessionTimer')) {
        updateSessionTimer();
        setInterval(updateSessionTimer, 1000);
    }
}

// Resetear el timeout
function resetTimeout() {
    // Limpiar timeouts existentes
    clearTimeout(timeout);
    clearTimeout(warningTimeout);
    clearInterval(countdownInterval);
    
    // Ocultar advertencia si está visible
    if (isWarningShown) {
        hideWarning();
    }
    
    // Establecer nuevo timeout para mostrar advertencia
    warningTimeout = setTimeout(showWarning, (SESSION_TIMEOUT - WARNING_TIME) * 1000);
    
    // Establecer timeout para cerrar sesión
    timeout = setTimeout(logoutDueToInactivity, SESSION_TIMEOUT * 1000);
}

// Mostrar advertencia de timeout
function showWarning() {
    const modal = document.getElementById('timeoutModal');
    if (!modal) return;
    
    const modalInstance = new bootstrap.Modal(modal);
    modalInstance.show();
    isWarningShown = true;
    
    // Iniciar cuenta regresiva
    let countdown = WARNING_TIME;
    const countdownElement = document.getElementById('countdown');
    countdownElement.textContent = countdown;
    
    countdownInterval = setInterval(() => {
        countdown--;
        countdownElement.textContent = countdown;
        
        if (countdown <= 0) {
            clearInterval(countdownInterval);
            logoutDueToInactivity();
        }
    }, 1000);
    
    // Configurar botones
    document.getElementById('continueSession').addEventListener('click', continueSession);
    document.getElementById('logoutNow').addEventListener('click', logoutNow);
}

// Ocultar advertencia
function hideWarning() {
    const modal = document.getElementById('timeoutModal');
    if (modal) {
        const modalInstance = bootstrap.Modal.getInstance(modal);
        if (modalInstance) {
            modalInstance.hide();
        }
    }
    isWarningShown = false;
    clearInterval(countdownInterval);
}

// Continuar sesión
function continueSession() {
    hideWarning();
    resetTimeout();
    
    // Mostrar mensaje de confirmación
    showNotification('Sesión extendida. Puede continuar trabajando.', 'success');
}

// Cerrar sesión ahora
function logoutNow() {
    hideWarning();
    window.location.href = 'logout.php';
}

// Cerrar sesión por inactividad
function logoutDueToInactivity() {
    hideWarning();
    
    // Mostrar mensaje antes de redirigir
    showNotification('Sesión cerrada por inactividad. Redirigiendo al login...', 'warning');
    
    setTimeout(() => {
        window.location.href = 'logout.php?timeout=1';
    }, 2000);
}

// Mostrar notificación
function showNotification(message, type) {
    // Crear elemento de notificación
    const notification = document.createElement('div');
    notification.className = `notification ${type}`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 15px 20px;
        border-radius: 8px;
        color: white;
        font-weight: 600;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: slideInRight 0.3s;
    `;
    
    if (type === 'success') {
        notification.style.backgroundColor = '#4caf50';
    } else if (type === 'warning') {
        notification.style.backgroundColor = '#ff9800';
    } else {
        notification.style.backgroundColor = '#333';
    }
    
    notification.innerHTML = `
        <i class="fas ${type === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle'}"></i>
        ${message}
    `;
    
    document.body.appendChild(notification);
    
    // Remover notificación después de 3 segundos
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s';
        setTimeout(() => {
            if (notification.parentNode) {
                document.body.removeChild(notification);
            }
        }, 300);
    }, 3000);
    
    // Animaciones CSS
    if (!document.getElementById('notification-styles')) {
        const style = document.createElement('style');
        style.id = 'notification-styles';
        style.textContent = `
            @keyframes slideInRight {
                from { transform: translateX(100%); opacity: 0; }
                to { transform: translateX(0); opacity: 1; }
            }
            @keyframes slideOutRight {
                from { transform: translateX(0); opacity: 1; }
                to { transform: translateX(100%); opacity: 0; }
            }
        `;
        document.head.appendChild(style);
    }
}

// Actualizar el temporizador de sesión en el dashboard
function updateSessionTimer() {
    const timerElement = document.getElementById('sessionTimer');
    if (!timerElement) return;
    
    const now = new Date();
    const loginTime = new Date(parseInt(timerElement.getAttribute('data-login-time')) || now.getTime());
    const diffInSeconds = Math.floor((now - loginTime) / 1000);
    
    const hours = Math.floor(diffInSeconds / 3600);
    const minutes = Math.floor((diffInSeconds % 3600) / 60);
    const seconds = diffInSeconds % 60;
    
    timerElement.textContent = 
        `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
}

// Inicializar cuando el DOM esté cargado
document.addEventListener('DOMContentLoaded', function() {
    // Solo inicializar timeout si el usuario está autenticado
    if (document.body.classList.contains('dashboard-container')) {
        initSessionTimeout();
    }
    
    // Verificar si hay parámetros de URL para mostrar mensajes
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('timeout')) {
        showNotification('Su sesión ha expirado por inactividad. Por favor, inicie sesión nuevamente.', 'warning');
    }
    if (urlParams.has('logout')) {
        showNotification('Sesión cerrada exitosamente.', 'success');
    }
});