// Funções para controle de autenticação
document.addEventListener('DOMContentLoaded', function() {
    // Verificar se o usuário está logado ao carregar a página
    checkLoginStatus();
    
    // Adicionar event listeners para os formulários de login e cadastro
    setupAuthForms();
});

// Verificar status de login
function checkLoginStatus() {
    const isLoggedIn = localStorage.getItem('isLoggedIn');
    const userData = JSON.parse(localStorage.getItem('userData') || '{}');
    
    if (isLoggedIn === 'true' && userData) {
        // Usuário está logado, mostrar dashboard
        showDashboard(userData);
    } else {
        // Usuário não está logado, mostrar botões de autenticação
        showAuthButtons();
    }
}

// Configurar formulários de autenticação
function setupAuthForms() {
    // Form de login
    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Simulação de login (sem backend real)
            if (email && password) {
                // Criar dados do usuário
                const userData = {
                    name: email.split('@')[0], // Usar parte do email como nome
                    email: email,
                    profilePic: '' // Sem foto por padrão
                };
                
                // Salvar no localStorage
                localStorage.setItem('isLoggedIn', 'true');
                localStorage.setItem('userData', JSON.stringify(userData));
                
                // Redirecionar para a página inicial
                window.location.href = 'index.php';
            }
        });
    }
    
    // Form de cadastro
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            // Não impede mais o envio padrão inicialmente

            const password = document.getElementById('password').value;
            const confirmPassword = document.getElementById('confirm-password').value;

            // Verificar se as senhas coincidem ANTES de enviar
            if (password !== confirmPassword) {
                alert('As senhas não coincidem!');
                e.preventDefault(); // Impede o envio SOMENTE se as senhas não baterem
                return;
            }

            // Nenhuma simulação aqui, o formulário será enviado para o PHP
            // O envio ocorrerá automaticamente se e.preventDefault() não for chamado.
        });
    }
}

// Mostrar dashboard do usuário
function showDashboard(userData) {
    // Ocultar botões de autenticação
    const authButtons = document.querySelector('.auth-buttons');
    if (authButtons) {
        authButtons.style.display = 'none';
    }
    
    // Criar dashboard se não existir
    if (!document.getElementById('user-dashboard')) {
        const header = document.querySelector('header');
        
        // Criar elemento do dashboard
        const dashboard = document.createElement('div');
        dashboard.id = 'user-dashboard';
        dashboard.className = 'user-dashboard';
        
        // Conteúdo do dashboard
        dashboard.innerHTML = `
            <div class="dashboard-toggle">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <path d="M12 8v8"></path>
                    <path d="M8 12h8"></path>
                </svg>
            </div>
            <div class="dashboard-content">
                <div class="user-profile">
                    <div class="profile-pic-container">
                        <div class="profile-pic" id="profile-pic">
                            ${userData.profilePic ? `<img src="${userData.profilePic}" alt="Foto de perfil">` : 
                            `<svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                <circle cx="12" cy="7" r="4"></circle>
                            </svg>`}
                        </div>
                        <label for="profile-pic-upload" class="change-pic-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M23 19a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h4l2-3h6l2 3h4a2 2 0 0 1 2 2z"></path>
                                <circle cx="12" cy="13" r="4"></circle>
                            </svg>
                        </label>
                        <input type="file" id="profile-pic-upload" accept="image/*" style="display: none;">
                    </div>
                    <h3>${userData.name}</h3>
                    <p>${userData.email}</p>
                </div>
                <div class="dashboard-menu">
                    <ul>
                        <li><a href="index.php">Página Inicial</a></li>
                        <li><a href="em_construcao.html?origem=minhas_atividades">Minhas Atividades</a></li>
                    </ul>
                </div>
                <button id="logout-btn" class="logout-btn">Sair</button>
            </div>
        `;
        
        // Adicionar dashboard ao header
        header.appendChild(dashboard);
        
        // Configurar eventos do dashboard
        setupDashboardEvents();
    }
}

// Configurar eventos do dashboard
function setupDashboardEvents() {
    // Toggle do dashboard
    const dashboardToggle = document.querySelector('.dashboard-toggle');
    const dashboard = document.getElementById('user-dashboard');
    
    if (dashboardToggle && dashboard) {
        dashboardToggle.addEventListener('click', function() {
            dashboard.classList.toggle('open');
        });
    }
    
    // Upload de foto de perfil
    const profilePicUpload = document.getElementById('profile-pic-upload');
    if (profilePicUpload) {
        profilePicUpload.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    // Atualizar a imagem no dashboard
                    const profilePic = document.getElementById('profile-pic');
                    profilePic.innerHTML = `<img src="${event.target.result}" alt="Foto de perfil">`;
                    
                    // Atualizar no localStorage
                    const userData = JSON.parse(localStorage.getItem('userData') || '{}');
                    userData.profilePic = event.target.result;
                    localStorage.setItem('userData', JSON.stringify(userData));
                };
                reader.readAsDataURL(file);
            }
        });
    }
    
    // Botão de logout
    const logoutBtn = document.getElementById('logout-btn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            // Limpar dados de autenticação
            localStorage.removeItem('isLoggedIn');
            localStorage.removeItem('userData');
            
            // Redirecionar para a página inicial após logout
            window.location.href = 'index.php';
        });
    }
}

// Mostrar botões de autenticação
function showAuthButtons() {
    // Mostrar botões de autenticação
    const authButtons = document.querySelector('.auth-buttons');
    if (authButtons) {
        authButtons.style.display = 'flex';
    }
    
    // Remover dashboard se existir
    const dashboard = document.getElementById('user-dashboard');
    if (dashboard) {
        dashboard.remove();
    }
}


/// Função para alternar o modo claro/escuro
function toggleTheme() {
    const body = document.body;
    body.classList.toggle('light-mode');

    // Salva a preferência do usuário no localStorage
    if (body.classList.contains('light-mode')) {
        localStorage.setItem('theme', 'light');
    } else {
        localStorage.setItem('theme', 'dark');
    }
}

// Aplica o tema salvo e configura os botões flutuantes
document.addEventListener('DOMContentLoaded', function() {
    // Verificar e aplicar o tema salvo
    const savedTheme = localStorage.getItem('theme');
    if (savedTheme === 'light') {
        document.body.classList.add('light-mode');
    }

    // --- Criação do Botão de Tema ---
    const themeToggleButton = document.createElement('button');
    themeToggleButton.id = 'theme-toggle-button'; // Adicionar ID para facilitar o CSS
    themeToggleButton.classList.add('theme-toggle-button');
    themeToggleButton.innerHTML = '💡'; // Ícone de lâmpada
    themeToggleButton.title = 'Alternar Modo Claro/Escuro';
    themeToggleButton.addEventListener('click', toggleTheme);
    document.body.appendChild(themeToggleButton);

    // --- Criação do Botão "Voltar ao Topo" ---
    const scrollToTopBtn = document.createElement('button');
    scrollToTopBtn.id = 'scroll-to-top-btn'; // Adicionar ID para facilitar o CSS
    scrollToTopBtn.className = 'scroll-to-top';
    scrollToTopBtn.innerHTML = '↑';
    scrollToTopBtn.title = 'Voltar ao Topo';
    scrollToTopBtn.setAttribute('aria-label', 'Voltar ao Topo');
    document.body.appendChild(scrollToTopBtn);
    
    // --- Lógica do Botão "Voltar ao Topo" ---
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            scrollToTopBtn.classList.add('show');
        } else {
            scrollToTopBtn.classList.remove('show');
        }
    });
    
    scrollToTopBtn.addEventListener('click', function() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
});
