<?php declare(strict_types=1);

// PASSO 1: ESTRUTURA BASE DO ARQUIVO

// Array com 8 cotações abertas fictícias para exibir no filtro

$cotacoesAbertas = [

    [
        'id' => 1,
        'fornecedor' => 'Eletro Parts Ltda',
        'email' => 'contato@eletroparts.com.br',
        'categoria' => 'Eletrônicos',
        'descricao' => 'Bobinas de cobre 2mm - 100kg',
        'valor' => 4500.00,
        'prazo' => 7,
        'condicao' => '30 dias',
        'data_abertura' => '2026-09-10'
    ],

    [
        'id' => 2,
        'fornecedor' => 'Peças Mecânicas do Brasil',
        'email' => 'vendas@pecasmec.com.br',
        'categoria' => 'Mecânica',
        'descricao' => 'Rolamentos de esferas NSK 6204',
        'valor' => 1250.50,
        'prazo' => 5,
        'condicao' => 'À Vista',
        'data_abertura' => '2026-09-12'
    ],

    [
        'id' => 3,
        'fornecedor' => 'Supply Express',
        'email' => 'compras@supplyexpress.com.br',
        'categoria' => 'Consumíveis',
        'descricao' => 'Óleo hidráulico ISO 46 - 200L',
        'valor' => 2100.00,
        'prazo' => 3,
        'condicao' => '60 dias',
        'data_abertura' => '2026-09-15'
    ],

    [
        'id' => 4,
        'fornecedor' => 'Serviços Técnicos SENAI',
        'email' => 'servicos@senaigeral.com.br',
        'categoria' => 'Serviços',
        'descricao' => 'Manutenção preventiva - 40h',
        'valor' => 3200.00,
        'prazo' => 1,
        'condicao' => '30 dias',
        'data_abertura' => '2026-09-14'
    ],

    [
        'id' => 5,
        'fornecedor' => 'Eletrônicos Avançados SA',
        'email' => 'vendas@eletravanc.com.br',
        'categoria' => 'Eletrônicos',
        'descricao' => 'Transformador 10kVA 220/110',
        'valor' => 8750.00,
        'prazo' => 15,
        'condicao' => '90 dias',
        'data_abertura' => '2026-09-11'
    ],

    [
        'id' => 6,
        'fornecedor' => 'Plásticos Industriais',
        'email' => 'venda@plasticosindustriais.com.br',
        'categoria' => 'Consumíveis',
        'descricao' => 'Tubos PVC 75mm - 50 metros',
        'valor' => 650.00,
        'prazo' => 2,
        'condicao' => 'À Vista',
        'data_abertura' => '2026-09-13'
    ],

    [
        'id' => 7,
        'fornecedor' => 'Automação Industrial Plus',
        'email' => 'suporte@autoplus.com.br',
        'categoria' => 'Eletrônicos',
        'descricao' => 'CLP Siemens S7-1200',
        'valor' => 15800.00,
        'prazo' => 21,
        'condicao' => '60 dias',
        'data_abertura' => '2026-09-09'
    ],

    [
        'id' => 8,
        'fornecedor' => 'Consultoria Técnica Premium',
        'email' => 'info@consultoriatech.com.br',
        'categoria' => 'Serviços',
        'descricao' => 'Auditoria de processos - 3 dias',
        'valor' => 5600.00,
        'prazo' => 5,
        'condicao' => '30 dias',
        'data_abertura' => '2026-09-08'
    ]

];
//DESAFIO
//persistência de dados
// Se o arquivo JSON existir, carregar as cotações salvas 
if (file_exists('cotacoes.json')) { $cotacoesSalvas = json_decode( file_get_contents('cotacoes.json'), true ); if (is_array($cotacoesSalvas)) { $cotacoesAbertas = array_merge( $cotacoesAbertas, $cotacoesSalvas);} 
}


// Constantes

const CATEGORIAS_PERMITIDAS = [
    'Eletrônicos',
    'Mecânica',
    'Consumíveis',
    'Serviços'
];

const CONDICOES_PAGAMENTO = [
    'À Vista',
    '30 dias',
    '60 dias',
    '90 dias'
];
// PASSO 3: FUNÇÃO PARA FILTRAR COTAÇÕES
/**
 * Filtra cotações abertas por fornecedor e/ou valor máximo.
 *
 * @param array $cotacoes Array de todas as cotações
 * @param string $fornecedor Nome parcial do fornecedor
 * @param float|null $valorMaximo Valor máximo permitido
 * @return array Cotações filtradas
 */

function filtrarCotacoes(array $cotacoes,string $fornecedor = '', ? float $valorMaximo = null): array {
    return array_filter($cotacoes,function ($cotacao) use ($fornecedor, $valorMaximo) {
        // Filtro por fornecedor
        if($fornecedor !== "" && stripos($cotacao['fornecedor'], $fornecedor) === false){
            return false;
        }
       // Filtro por valor máximo
        if ($valorMaximo !== null && $cotacao['valor'] > $valorMaximo) {
             return false;
         }
            return true;
        }
    );
}



// PASSO 2: VALIDAÇÃO E SANITIZAÇÃO

/**
 * Valida uma nova cotação de fornecedor.
 *
 * @param array $dados Os dados do formulário
 * @return array Erros encontrados
 */

function validarCotacao(array $dados): array
{
    $erros = [];

    // Validar nome do fornecedor
    if (strlen(trim($dados['nome_fornecedor'] ?? '')) < 5) {
        $erros['nome_fornecedor'] = 'O nome do fornecedor deve ter no mínimo 5 caracteres.';
    }

    // Validar e-mail
    if (!filter_var($dados['email_fornecedor'] ?? '',FILTER_VALIDATE_EMAIL)) {
        $erros['email_fornecedor'] = 'Informe um e-mail corporativo válido.';
    }

    // Validar categoria
    if (!in_array($dados['categoria_produto'] ?? '',CATEGORIAS_PERMITIDAS,true)) {
        $erros['categoria_produto'] ='Selecione uma categoria permitida.';
    }

    // Validar descrição
    if (strlen(trim($dados['descricao_item'] ?? '')) < 10) {
        $erros['descricao_item'] = 'A descrição deve ter no mínimo 10 caracteres.';
    }

    // Validar valor
    $valor = str_replace(['R$', '.', ','],['', '', '.'], $dados['valor_cotacao'] ?? '');

    if (!is_numeric($valor) ||(float) $valor <= 0) {
        $erros['valor_cotacao'] = 'Informe um valor positivo em R$.';
    }

    // Validar prazo
    $prazo = filter_var($dados['prazo_entrega_dias'] ?? '',FILTER_VALIDATE_INT);

    if ($prazo === false ||$prazo < 1 ||$prazo > 60) {
        $erros['prazo_entrega_dias'] = 'O prazo deve estar entre 1 e 60 dias.';
    }

    // Validar condição de pagamento
    if (!in_array($dados['condicoes_pagamento'] ?? '',CONDICOES_PAGAMENTO,true)) {
        $erros['condicoes_pagamento'] = 'Selecione uma condição de pagamento válida.';
    }

    return $erros;
}



// SEÇÃO A: FILTRO DE COTAÇÕES (GET)


$filtroFornecedor = '';
$filtroValor = null;
$cotacoesFiltradas = $cotacoesAbertas;

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $filtroFornecedor = trim($_GET['fornecedor'] ?? '');

    $filtroValor = filter_var($_GET['valor_max'] ?? null,FILTER_VALIDATE_FLOAT);

    if ($filtroValor === false) {$filtroValor = null;
    }

    $cotacoesFiltradas = filtrarCotacoes($cotacoesAbertas,$filtroFornecedor,$filtroValor);
}



//SEÇÃO B: FORMULÁRIO DE NOVA COTAÇÃO (POST)
$dados = [];
$erros = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Receber dados

    $dados = [

        'nome_fornecedor' =>
            trim($_POST['nome_fornecedor'] ?? ''),

        'email_fornecedor' =>
            trim($_POST['email_fornecedor'] ?? ''),

        'categoria_produto' =>
            $_POST['categoria_produto'] ?? '',

        'descricao_item' =>
            trim($_POST['descricao_item'] ?? ''),

        'valor_cotacao' =>
            trim($_POST['valor_cotacao'] ?? ''),

        'prazo_entrega_dias' =>
            $_POST['prazo_entrega_dias'] ?? '',

        'condicoes_pagamento' =>
            $_POST['condicoes_pagamento'] ?? ''

    ];
}


// Validar dados
$erros = validarCotacao($dados);

// Se não houver erros
if (empty($erros)) {

    $valor = str_replace(
        ['R$', '.', ','],
        ['', '', '.'],
        $dados['valor_cotacao'] ?? ''
    );

    //nova cotação 
    $novaCotacao = [ 
        'id' => count($cotacoesAbertas) + 1, 
        'fornecedor' => $dados['nome_fornecedor'], 
        'email' => $dados['email_fornecedor'], 
        'categoria' => $dados['categoria_produto'], 
        'descricao' => $dados['descricao_item'], 
        'valor' => (float)$valor, 
        'prazo' => (int)$dados['prazo_entrega_dias'], 
        'condicao' => $dados['condicoes_pagamento'], 
        'data_abertura' => date('Y-m-d')
    ];

    //nova cotação ao array 
    $cotacoesAbertas[] = $novaCotacao; 

    // Ao submeter um novo formulário válido:
    file_put_contents(
        'cotacoes.json',
        json_encode(
            $cotacoesAbertas,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        )
    );

    // Ao carregar a página:
    if (file_exists('cotacoes.json')) {
        $cotacoesAbertas = json_decode(
            file_get_contents('cotacoes.json'),
            true
        );
    }
}



?>
<!DOCTYPE html>

<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SupplyChain SENAI</title>

```
<style>
    body {
        font-family: Arial;
        background: #f2f2f2;
        padding: 20px;
    }

    h1 {
        color: #ec3fa4;
    }

    form {
        background: white;
        padding: 15px;
        margin-bottom: 20
    }

    input, select, textarea {
        width: 100%;
        padding: 8px;
        margin: 5px 0 10px;
        box-sizing: border-box;
    }

    button {
        background: #ec3fa4;
        color: white;
        padding: 10px;
        border: none;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    th, td {
        padding: 8px;
        border: 1px solid #ddd;
    }

    th {
        background: #ec3fa4;
        color: white;
    }

    .erro {
        color: red;
    }
</style>
```

</head>

<body>

<h1>SupplyChain SENAI</h1>

<h2>Filtrar Cotações</h2>

<form method="GET">

```
<label>Fornecedor:</label>
<input type="text" name="fornecedor"
    value="<?= htmlspecialchars($filtroFornecedor) ?>">

<label>Valor máximo:</label>
<input type="number" name="valor_max" step="0.01"
    value="<?= htmlspecialchars($_GET['valor_max'] ?? '') ?>">

<button>Filtrar</button>
```

</form>

<h2>Cotações Abertas</h2>

<table>

```
<tr>
    <th>ID</th>
    <th>Fornecedor</th>
    <th>Categoria</th>
    <th>Descrição</th>
    <th>Valor</th>
    <th>Prazo</th>
    <th>Pagamento</th>
    <th>Data</th>
</tr>

<?php foreach ($cotacoesFiltradas as $cotacao): ?>

<tr>
    <td><?= $cotacao['id'] ?></td>
    <td><?= htmlspecialchars($cotacao['fornecedor']) ?></td>
    <td><?= htmlspecialchars($cotacao['categoria']) ?></td>
    <td><?= htmlspecialchars($cotacao['descricao']) ?></td>
    <td>R$ <?= number_format($cotacao['valor'], 2, ',', '.') ?></td>
    <td><?= $cotacao['prazo'] ?> dias</td>
    <td><?= htmlspecialchars($cotacao['condicao']) ?></td>
    <td><?= $cotacao['data_abertura'] ?></td>
</tr>

<?php endforeach; ?>
```

</table>

<h2>Nova Cotação</h2>

<form method="POST">

```
<label>Nome:</label>
<input type="text" name="nome_fornecedor"
    value="<?= htmlspecialchars($dados['nome_fornecedor'] ?? '') ?>">

<label>E-mail:</label>
<input type="text" name="email_fornecedor"
    value="<?= htmlspecialchars($dados['email_fornecedor'] ?? '') ?>">

<label>Categoria:</label>
<select name="categoria_produto">
    <option value="">Selecione</option>

    <?php foreach (CATEGORIAS_PERMITIDAS as $categoria): ?>
        <option value="<?= $categoria ?>"
            <?= (($dados['categoria_produto'] ?? '') == $categoria) ? 'selected' : '' ?>>
            <?= $categoria ?>
        </option>
    <?php endforeach; ?>

</select>

<label>Descrição:</label>
<textarea name="descricao_item"><?= htmlspecialchars($dados['descricao_item'] ?? '') ?></textarea>

<label>Valor:</label>
<input type="text" name="valor_cotacao"
    value="<?= htmlspecialchars($dados['valor_cotacao'] ?? '') ?>">

<label>Prazo:</label>
<input type="number" name="prazo_entrega_dias" min="1" max="60"
    value="<?= htmlspecialchars($dados['prazo_entrega_dias'] ?? '') ?>">

<label>Pagamento:</label>
<select name="condicoes_pagamento">
    <option value="">Selecione</option>

    <?php foreach (CONDICOES_PAGAMENTO as $condicao): ?>
        <option value="<?= $condicao ?>"
            <?= (($dados['condicoes_pagamento'] ?? '') == $condicao) ? 'selected' : '' ?>>
            <?= $condicao ?>
        </option>
    <?php endforeach; ?>

</select>

<button>Enviar Cotação</button>
```

</form>

</body>
</html>
