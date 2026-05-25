<?php
/**
 * Router para o servidor embutido do PHP (php -S).
 *
 * O servidor embutido do PHP não processa arquivos .htaccess, portanto as
 * regras de rewrite do Apache não têm efeito. Este arquivo substitui esse
 * comportamento roteando as requisições manualmente.
 *
 * Problema adicional: quando o router.php inclui webapi/index.php via require,
 * o $_SERVER['SCRIPT_NAME'] ainda contém o URI original da requisição
 * (ex: /webapi/nws/v1/loadflow). O Slim v2 usa SCRIPT_NAME para calcular o
 * PATH_INFO, então é necessário corrigi-lo para /webapi/index.php antes de
 * incluir o Slim, garantindo que o PATH_INFO seja /nws/v1/loadflow e a rota
 * seja despachada corretamente.
 *
 * Uso:
 *   php -S localhost:8000 router.php
 *
 * Com Apache (mod_rewrite + .htaccess em webapi/), este arquivo não é usado.
 */

$uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

// Serve arquivos estáticos existentes diretamente (CSS, JS, imagens, etc.)
if ($uri !== '/' && file_exists(__DIR__ . $uri)) {
    return false;
}

// Redireciona requisições da webapi para o index.php do Slim,
// corrigindo SCRIPT_NAME para que o Slim calcule PATH_INFO corretamente.
if (strpos($uri, '/webapi/') === 0) {
    $_SERVER['SCRIPT_NAME'] = '/webapi/index.php';
    require __DIR__ . '/webapi/index.php';
    return true;
}

// Para qualquer outra rota, serve o index.html da aplicação
require __DIR__ . '/index.html';
return true;
