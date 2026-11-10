#!/usr/bin/env python3
# -*- coding: utf-8 -*-
"""
Script para integrar o widget de acessibilidade flutuante em todas as páginas do site.
Este script garante que todas as páginas HTML e PHP tenham:
1. O CSS de acessibilidade linkado no <head>
2. O widget HTML no <body>
3. O script JavaScript de acessibilidade no final do <body>
"""

import os
import re
from pathlib import Path

# Definir o diretório raiz do projeto
PROJECT_ROOT = os.path.dirname(os.path.abspath(__file__))

# HTML do widget de acessibilidade
ACCESSIBILITY_WIDGET_HTML = '''    <!-- Barra de Acessibilidade Flutuante -->
    <div class="barra-acessibilidade">
        <span class="barra-acessibilidade-label"></span>
        <button class="btn-acessibilidade btn-diminuir-fonte" onclick="diminuirFonte()" title="Diminuir tamanho da fonte" aria-label="Diminuir tamanho da fonte">
            A-
        </button>
        <button class="btn-acessibilidade btn-resetar-fonte" onclick="resetarFonte()" title="Tamanho normal da fonte" aria-label="Tamanho normal da fonte">
            A
        </button>
        <button class="btn-acessibilidade btn-aumentar-fonte" onclick="aumentarFonte()" title="Aumentar tamanho da fonte" aria-label="Aumentar tamanho da fonte">
            A+
        </button>
    </div>
'''

# Link CSS para acessibilidade
ACCESSIBILITY_CSS_LINK = '    <link rel="stylesheet" href="static/css/acessibilidade.css">'

# Script JavaScript para acessibilidade
ACCESSIBILITY_JS_SCRIPT = '    <script src="static/js/acessibilidade.js"></script>'


def adicionar_css_no_head(conteudo):
    """Adiciona o link CSS de acessibilidade no <head> se não existir."""
    if 'acessibilidade.css' in conteudo:
        return conteudo
    
    # Procurar por </head> e adicionar o CSS antes dele
    if '</head>' in conteudo:
        conteudo = conteudo.replace('</head>', f'{ACCESSIBILITY_CSS_LINK}\n</head>')
    
    return conteudo


def adicionar_widget_no_body(conteudo):
    """Adiciona o widget de acessibilidade no início do <body> se não existir."""
    if 'barra-acessibilidade' in conteudo:
        return conteudo
    
    # Procurar por <body> e adicionar o widget logo após
    if '<body>' in conteudo:
        conteudo = conteudo.replace('<body>', f'<body>\n{ACCESSIBILITY_WIDGET_HTML}')
    elif '<body ' in conteudo:
        # Para tags <body> com atributos
        match = re.search(r'<body[^>]*>', conteudo)
        if match:
            body_tag = match.group(0)
            conteudo = conteudo.replace(body_tag, f'{body_tag}\n{ACCESSIBILITY_WIDGET_HTML}', 1)
    
    return conteudo


def adicionar_script_no_final(conteudo):
    """Adiciona o script de acessibilidade no final do <body> se não existir."""
    if 'acessibilidade.js' in conteudo:
        return conteudo
    
    # Procurar por </body> e adicionar o script antes dele
    if '</body>' in conteudo:
        conteudo = conteudo.replace('</body>', f'{ACCESSIBILITY_JS_SCRIPT}\n</body>')
    
    return conteudo


def processar_arquivo(arquivo_path):
    """Processa um arquivo HTML/PHP e adiciona o widget de acessibilidade."""
    try:
        with open(arquivo_path, 'r', encoding='utf-8') as f:
            conteudo = f.read()
        
        # Aplicar as modificações
        conteudo = adicionar_css_no_head(conteudo)
        conteudo = adicionar_widget_no_body(conteudo)
        conteudo = adicionar_script_no_final(conteudo)
        
        # Salvar o arquivo modificado
        with open(arquivo_path, 'w', encoding='utf-8') as f:
            f.write(conteudo)
        
        print(f"✓ Processado: {arquivo_path}")
        return True
    except Exception as e:
        print(f"✗ Erro ao processar {arquivo_path}: {e}")
        return False


def main():
    """Função principal que processa todos os arquivos HTML e PHP."""
    print("=" * 60)
    print("Integrando Widget de Acessibilidade Flutuante")
    print("=" * 60)
    
    # Encontrar todos os arquivos HTML e PHP
    arquivos = []
    
    # Arquivos HTML na raiz
    for arquivo in Path(PROJECT_ROOT).glob('*.html'):
        arquivos.append(arquivo)
    
    # Arquivos PHP na raiz
    for arquivo in Path(PROJECT_ROOT).glob('*.php'):
        arquivos.append(arquivo)
    
    # Arquivos HTML em subdiretórios
    for arquivo in Path(PROJECT_ROOT).glob('**/*.html'):
        if arquivo not in arquivos:
            arquivos.append(arquivo)
    
    # Arquivos PHP em subdiretórios
    for arquivo in Path(PROJECT_ROOT).glob('**/*.php'):
        if arquivo not in arquivos:
            arquivos.append(arquivo)
    
    if not arquivos:
        print("Nenhum arquivo HTML ou PHP encontrado.")
        return
    
    print(f"\nEncontrados {len(arquivos)} arquivo(s) para processar:\n")
    
    processados = 0
    for arquivo in sorted(arquivos):
        if processar_arquivo(str(arquivo)):
            processados += 1
    
    print(f"\n{'=' * 60}")
    print(f"Processamento concluído: {processados}/{len(arquivos)} arquivo(s)")
    print("=" * 60)


if __name__ == '__main__':
    main()
