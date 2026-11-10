// Função para salvar a história no localStorage
function salvarHistoria(nome, cidade, idade, relato) {
    const historias = JSON.parse(localStorage.getItem('historias') || '[]');
    historias.push({ nome, cidade, idade, relato });
    localStorage.setItem('historias', JSON.stringify(historias));
}

// Função para renderizar as histórias na página relatos.html
function renderizarHistorias(containerId) {
    const container = document.getElementById(containerId);
    const historias = JSON.parse(localStorage.getItem('historias') || '[]');

    if (!container) return;

    if (historias.length === 0) {
        container.innerHTML = "<p>Não há relatos compartilhados ainda. Seja a primeira pessoa a compartilhar!</p>";
        return;
    }

    container.innerHTML = '';

    historias.forEach(historia => {
        const card = document.createElement('div');
        card.className = 'testimonial-card';

        card.innerHTML = `
            <div class="testimonial-header">
                <div class="testimonial-avatar"></div>
                <div class="testimonial-info">
                    <h3>${historia.nome}</h3>
                    <p>${historia.idade} anos, ${historia.cidade}</p>
                </div>
            </div>
             <h4 style="margin-bottom: 0.5rem;">${historia.titulo}</h4>
            <p class="testimonial-text">"${historia.relato}"</p>
        `;

        container.appendChild(card);
    });
}
