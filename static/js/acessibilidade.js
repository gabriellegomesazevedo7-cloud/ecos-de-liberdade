// Controle de tamanho de fonte - Widget Flutuante
(function() {
    'use strict';
    
    let fontSizeLevel = 0; // -1 = menor, 0 = normal, 1 = maior
    const STORAGE_KEY = 'fontSizeLevel';
    const MIN_LEVEL = -1;
    const MAX_LEVEL = 1;
    
    /**
     * Aumentar o tamanho da fonte
     */
    window.aumentarFonte = function() {
        if (fontSizeLevel < MAX_LEVEL) {
            fontSizeLevel++;
            aplicarTamanhoFonte();
        }
    };
    
    /**
     * Diminuir o tamanho da fonte
     */
    window.diminuirFonte = function() {
        if (fontSizeLevel > MIN_LEVEL) {
            fontSizeLevel--;
            aplicarTamanhoFonte();
        }
    };
    
    /**
     * Resetar o tamanho da fonte para o padrão
     */
    window.resetarFonte = function() {
        fontSizeLevel = 0;
        aplicarTamanhoFonte();
    };
    
    /**
     * Aplicar o tamanho de fonte ao documento
     */
    function aplicarTamanhoFonte() {
        const root = document.documentElement;
        
        // Salvar preferência no localStorage
        try {
            localStorage.setItem(STORAGE_KEY, fontSizeLevel);
        } catch (e) {
            console.warn('localStorage não disponível:', e);
        }
        
        // Remover todas as classes de tamanho de fonte
        root.classList.remove('font-size-small', 'font-size-normal', 'font-size-large');
        
        // Adicionar a classe apropriada baseada no nível
        if (fontSizeLevel === MIN_LEVEL) {
            root.classList.add('font-size-small');
        } else if (fontSizeLevel === 0) {
            root.classList.add('font-size-normal');
        } else if (fontSizeLevel === MAX_LEVEL) {
            root.classList.add('font-size-large');
        }
        
        // Atualizar estado dos botões
        atualizarBotoesAcessibilidade();
    }
    
    /**
     * Atualizar o estado dos botões (habilitado/desabilitado)
     */
    function atualizarBotoesAcessibilidade() {
        const btnDiminuir = document.querySelector('.btn-diminuir-fonte');
        const btnAumentar = document.querySelector('.btn-aumentar-fonte');
        
        if (btnDiminuir) {
            btnDiminuir.disabled = fontSizeLevel <= MIN_LEVEL;
            btnDiminuir.setAttribute('aria-disabled', fontSizeLevel <= MIN_LEVEL);
        }
        
        if (btnAumentar) {
            btnAumentar.disabled = fontSizeLevel >= MAX_LEVEL;
            btnAumentar.setAttribute('aria-disabled', fontSizeLevel >= MAX_LEVEL);
        }
    }
    
    /**
     * Carregar preferência salva ao carregar a página
     */
    function inicializarAcessibilidade() {
        try {
            const savedLevel = localStorage.getItem(STORAGE_KEY);
            if (savedLevel !== null) {
                fontSizeLevel = parseInt(savedLevel, 10);
                // Validar o valor salvo
                if (fontSizeLevel < MIN_LEVEL || fontSizeLevel > MAX_LEVEL) {
                    fontSizeLevel = 0;
                }
            }
        } catch (e) {
            console.warn('Erro ao carregar preferência de tamanho de fonte:', e);
            fontSizeLevel = 0;
        }
        
        aplicarTamanhoFonte();
    }
    
    /**
     * Inicializar quando o DOM estiver pronto
     */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', inicializarAcessibilidade);
    } else {
        // Se o script for carregado após o DOMContentLoaded
        inicializarAcessibilidade();
    }
    
    /**
     * Suporte a atalhos de teclado (opcional)
     */
    document.addEventListener('keydown', function(event) {
        // Ctrl/Cmd + Plus/Equals para aumentar
        if ((event.ctrlKey || event.metaKey) && (event.key === '+' || event.key === '=')) {
            event.preventDefault();
            aumentarFonte();
        }
        // Ctrl/Cmd + Minus para diminuir
        if ((event.ctrlKey || event.metaKey) && event.key === '-') {
            event.preventDefault();
            diminuirFonte();
        }
        // Ctrl/Cmd + 0 para resetar
        if ((event.ctrlKey || event.metaKey) && event.key === '0') {
            event.preventDefault();
            resetarFonte();
        }
    });
})();
