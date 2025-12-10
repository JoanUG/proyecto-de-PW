
---

### **📍 NIVEL 2: CARPETA ASSETS/CSS**

#### **13. 📄 style.css**
**Ubicación:** `sistema-unah/assets/css/style.css`
```css
/* Variables de colores */
:root {
    --primary-color: #2c5e1a;
    --primary-dark: #1e3d12;
    --secondary-color: #4a7c3f;
    --accent-color: #8bc34a;
    --light-color: #f5f9f3;
    --dark-color: #333;
    --warning-color: #ff9800;
    --danger-color: #f44336;
    --success-color: #4caf50;
    --text-color: #333;
    --text-light: #666;
    --border-color: #ddd;
    --shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Reset y estilos generales */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

body {
    background: linear-gradient(135deg, #f5f9f3 0%, #e8f5e9 100%);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    color: var(--text-color);
}

/* Contenedor principal de login */
.login-container {
    max-width: 1200px;
    width: 100%;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 30px;
    align-items: start;
}

@media (max-width: 900px) {
    .login-container {
        grid-template-columns: 1fr;
    }
}

/* Encabezado */
.login-header {
    grid-column: 1 / -1;
    text-align: center;
    margin-bottom: 20px;
}

.logo-container {
    margin-bottom: 15px;
}

.logo-icon {
    font-size: 4rem;
    color: var(--primary-color);
    margin-bottom: 10px;
}

.login-header h1 {
    color: var(--primary-color);
    font-size: 2.2rem;
    margin-bottom: 5px;
}

.login-header h2 {
    color: var(--secondary-color);
    font-size: 1.5rem;
    font-weight: 500;
    margin-bottom: 10px;
}

.login-header h3 {
    color: var(--dark-color);
    font-size: 1.3rem;
    font-weight: 600;
    background: rgba(76, 175, 80, 0.1);
    padding: 10px 20px;
    border-radius: 30px;
    display: inline-block;
}

/* Tarjeta de login */
.login-card {
    background: white;
    border-radius: 15px;
    box-shadow: var(--shadow);
    padding: 30px;
    border-top: 5px solid var(--primary-color);
}

.card-header {
    text-align: center;
    margin-bottom: 30px;
}

.card-header h2 {
    color: var(--primary-color);
    font-size: 1.8rem;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
}

.card-header p {
    color: var(--text-light);
    font-size: 1rem;
}

/* Formulario */
.input-group {
    margin-bottom: 25px;
    position: relative;
}

.input-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--dark-color);
    display: flex;
    align-items: center;
    gap: 8px;
}

.input-group input {
    width: 100%;
    padding: 14px 15px;
    border: 2px solid var(--border-color);
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s;
}

.input-group input:focus {
    border-color: var(--primary-color);
    outline: none;
    box-shadow: 0 0 0 3px rgba(44, 94, 26, 0.1);
}

.toggle-password {
    position: absolute;
    right: 15px;
    top: 42px;
    cursor: pointer;
    color: var(--text-light);
    font-size: 1.2rem;
}

.toggle-password:hover {
    color: var(--primary-color);
}

/* Recordar contraseña */
.remember-forgot {
    margin-bottom: 25px;
}

.checkbox-container {
    display: block;
    position: relative;
    padding-left: 35px;
    cursor: pointer;
    font-size: 1rem;
    user-select: none;
}

.checkbox-container input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;
}

.checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 22px;
    width: 22px;
    background-color: #eee;
    border-radius: 4px;
}

.checkbox-container:hover input ~ .checkmark {
    background-color: #ccc;
}

.checkbox-container input:checked ~ .checkmark {
    background-color: var(--primary-color);
}

.checkmark:after {
    content: "";
    position: absolute;
    display: none;
}

.checkbox-container input:checked ~ .checkmark:after {
    display: block;
}

.checkbox-container .checkmark:after {
    left: 8px;
    top: 4px;
    width: 5px;
    height: 10px;
    border: solid white;
    border-width: 0 3px 3px 0;
    transform: rotate(45deg);
}

/* Botón de login */
.login-btn {
    width: 100%;
    padding: 16px;
    background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1.1rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-bottom: 20px;
}

.login-btn:hover {
    background: linear-gradient(to right, var(--primary-dark), var(--primary-color));
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(44, 94, 26, 0.2);
}

.login-btn:active {
    transform: translateY(0);
}

/* Credenciales de demostración */
.demo-credentials {
    background-color: #f8f9fa;
    border-left: 4px solid var(--accent-color);
    padding: 15px;
    border-radius: 0 8px 8px 0;
    margin-bottom: 20px;
}

.demo-credentials p {
    margin-bottom: 5px;
    font-size: 0.9rem;
}

.demo-credentials code {
    background-color: #e8f5e9;
    padding: 2px 6px;
    border-radius: 4px;
    font-weight: 600;
    color: var(--primary-dark);
}

/* Footer del login */
.login-footer {
    text-align: center;
    padding-top: 20px;
    border-top: 1px solid var(--border-color);
    color: var(--text-light);
    font-size: 0.9rem;
}

.login-footer p:first-child {
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 5px;
}

/* Información del sistema */
.system-info {
    background: white;
    border-radius: 15px;
    box-shadow: var(--shadow);
    padding: 30px;
    border-top: 5px solid var(--accent-color);
}

.system-info h3 {
    color: var(--secondary-color);
    font-size: 1.5rem;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.problem-list {
    background-color: #f9fdf8;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #e1f0e1;
}

.problem-list p {
    font-weight: 600;
    color: var(--primary-color);
    margin-bottom: 10px;
}

.problem-list ul {
    padding-left: 20px;
}

.problem-list li {
    margin-bottom: 8px;
    line-height: 1.5;
    color: var(--text-color);
}

/* Responsive */
@media (max-width: 768px) {
    .login-header h1 {
        font-size: 1.8rem;
    }
    
    .login-header h2 {
        font-size: 1.3rem;
    }
    
    .login-card, .system-info {
        padding: 20px;
    }
}