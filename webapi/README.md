# NDSE Web API — Servidor de Cálculo

Esta pasta contém a **API REST** responsável por executar o cálculo de fluxo de potência e análise de estabilidade transitória. Ela é o backend consumido pelo cliente (`simulador-web-FP`).

## Arquitetura

```
webapi/
├── index.php              # Ponto de entrada — define as rotas via Slim Framework v2
├── bootstrap.php          # Autoloader PSR-4 para o namespace NDSE
├── .htaccess              # Rewrite rules (Apache) — redireciona qualquer rota para index.php, sem caminho fixo
├── Slim/                  # Slim Framework v2.6.1 (incluído localmente)
├── templates/
│   ├── loadflow.php       # Template do endpoint POST /nws/v1/loadflow
└── src/NDSE/Core/
    ├── Math/
    │   ├── Complex.php    # Aritmética de números complexos
    │   ├── Matrix.php     # Matriz densa (PHP 8: eval() substituído por match)
    │   ├── Sparse.php     # Matriz esparsa (formato comprimido)
    │   ├── LinAlg.php     # Decomposição LU e resolução de sistemas lineares
    │   └── Angle.php      # Conversão de ângulos
    └── Tools/
        ├── LoadFlow.php   # Algoritmo de Newton-Raphson (sequencial — PHP 8)
        ├── LoadFlowT.php  # Versão com threads (requer pthreads — não usar em PHP 8)
```

## Endpoint

### `POST /nws/v1/loadflow`

Executa o cálculo de fluxo de potência pelo método de **Newton-Raphson**.

**Corpo da requisição (JSON):**
```json
{
  "info": "lf",
  "optLF": [100, 10, 0.001, 1],
  "bus": [[1, 3, ...], [2, 1, ...], ...],
  "branch": [[1, 2, 0.01, 0.05, ...], ...]
}
```

**Resposta (JSON):**
```json
{
  "iteration": 4,
  "bus": [[1, 1.06, 0.0, 232.4, -16.9, 0, 0, 0, 0], ...],
  "branch": [[1, 2, 156.9, -20.8, -153.6, 27.6, 3.3, 6.8], ...],
  "loss": [13.4, 54.7]
}
```

## Requisitos

- **PHP 8.0+** (recomendado PHP 8.1 ou superior)
- **Servidor web Apache** com `mod_rewrite` habilitado
