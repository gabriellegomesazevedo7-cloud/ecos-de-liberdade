document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('story-form');
    const feedback = document.getElementById('story-feedback');
    if (!form || !feedback) return;

    form.addEventListener('submit', async (event) => {
        event.preventDefault();
        const button = form.querySelector('button[type="submit"]');
        const content = form.querySelector('[name="conteudo"]');
        const city = form.querySelector('[name="cidade"]');
        if (!content.value.trim()) return showFeedback('Escreva sua história antes de enviar.', 'error');
        if (!city.value.trim()) return showFeedback('Informe sua cidade ou estado.', 'error');

        button.disabled = true;
        button.textContent = 'Enviando...';
        try {
            const response = await fetch('php/salvar_relato.php', { method: 'POST', body: new FormData(form), headers: { Accept: 'application/json' } });
            const result = await response.json();
            if (!response.ok || !result.success) throw new Error(result.message || 'Não foi possível enviar o relato.');
            form.reset();
            showFeedback(result.message, 'success');
        } catch (error) {
            showFeedback(error.message, 'error');
        } finally {
            button.disabled = false;
            button.textContent = 'Enviar história';
        }
    });

    function showFeedback(message, type) {
        feedback.textContent = message;
        feedback.className = `form-feedback ${type}`;
        feedback.hidden = false;
    }
});
