# Casos de Teste — Fluxo de Potência

Esta pasta contém os arquivos de entrada utilizados nos testes computacionais descritos na tese de doutorado que originou este software (Apêndice A). Os três sistemas são os mesmos utilizados para validação dos resultados em comparação com o MATPOWER.

## Casos disponíveis

| Arquivo | Sistema | Barras | Ramos | Referência |
|---|---|---|---|---|
| `ieee9bus.json` | IEEE 9 barras | 9 | 9 | Anderson & Fouad (1977) |
| `ieee57bus.json` | IEEE 57 barras | 57 | 78 | IEEE Test Case Archive |
| `ieee118bus.json` | IEEE 118 barras | 118 | 186 | IEEE Test Case Archive |

Todos os casos utilizam os seguintes parâmetros padrão de simulação (campo `optLF`):

| Campo | Valor | Descrição |
|---|---|---|
| `optLF[0]` | 100 | Potência base (MVA) |
| `optLF[1]` | 10 | Número máximo de iterações |
| `optLF[2]` | 1e-3 | Tolerância de convergência |
| `optLF[3]` | 1 | Verificação de limite Q (1 = ativo) |

## Formato do arquivo JSON

Cada arquivo segue a estrutura abaixo:

```json
{
  "info": ["lf"],
  "optLF": [potencia_base, max_iter, tolerancia, check_q],
  "bus": [
    [bus, tipo, Pgen, Qgen, Pload, Qload, Rshunt, Xshunt, U, Theta, Qgmax, Qgmin],
    ...
  ],
  "branch": [
    [de, para, Rser, Xser, Bpar, Tap, Phi, Status],
    ...
  ]
}
```

### Colunas de `bus`

| Col | Nome | Unidade | Descrição |
|---|---|---|---|
| 0 | Bus | — | Número da barra |
| 1 | Tipo | — | 1=PQ, 2=PV, 3=Slack |
| 2 | Pgen | MW | Potência ativa gerada |
| 3 | Qgen | MVAR | Potência reativa gerada |
| 4 | Pload | MW | Potência ativa de carga |
| 5 | Qload | MVAR | Potência reativa de carga |
| 6 | Rshunt | pu | Condutância shunt |
| 7 | Xshunt | pu | Susceptância shunt |
| 8 | U | pu | Tensão (módulo) |
| 9 | Theta | graus | Tensão (ângulo) |
| 10 | Qgmax | MVAR | Limite máximo de geração reativa |
| 11 | Qgmin | MVAR | Limite mínimo de geração reativa |

### Colunas de `branch`

| Col | Nome | Unidade | Descrição |
|---|---|---|---|
| 0 | De | — | Barra de origem |
| 1 | Para | — | Barra de destino |
| 2 | Rser | pu | Resistência série |
| 3 | Xser | pu | Reatância série |
| 4 | Bpar | pu | Susceptância paralela (shunt) |
| 5 | Tap | pu | Relação de transformação (0 = linha) |
| 6 | Phi | graus | Defasagem angular do transformador |
| 7 | Status | — | 1 = em serviço, 0 = fora de serviço |

## Como usar

1. Abra o simulador no navegador (`index.html`)
2. Clique no botão de seleção de arquivo
3. Selecione um dos arquivos `.json` desta pasta
4. Os dados serão carregados automaticamente nas grades de Barras e Ramos
5. Clique em **Run Load Flow** para executar o cálculo
6. Os resultados serão exibidos nas grades de resultados abaixo
