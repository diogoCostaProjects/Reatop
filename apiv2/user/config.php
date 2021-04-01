<?php
//DISPARA OS ERROS
ini_set('display_errors', 0);
error_reporting(E_ALL);


$dominiowww = $_SERVER['SERVER_NAME'];
$dominio = str_replace('www.', '', $_SERVER['HTTP_HOST']);
date_default_timezone_set('America/Sao_Paulo');

define('RAIZ', dirname(__FILE__));
define('PASTA_RAIZ', '/iusui1872a5a78512rew');
// URL da home
define('HOME_URI', 'https://localhost:80/reatop/apiv2/user');

// define('HOME_URI', 'https://' . $dominiowww .'/apiv2/user');
// usado para acessos no front
    
// email remetente padrão
define('EMAIL_REMETENTE', '');
define('NOME_REMETENTE', '');
define('TOKEN', '123');
define('KEY_API', 'AIzaSyAQUpPqQ7UDU__BucfNDzOo1CxNWBR3yC8');
define('COD_API', '$1$p4YWKzi0$OjCKuseQziQ5HIs6wkP.y1');
/*
    por medidas de usuabilidade, estamos setando
    a DEVELOPER_KEY e a APP_ID diretamente no código
*/
// define('DEVELOPER_KEY', '5d40eb0193f745409e182b04ef08d30a');
// define('APP_ID', 'f31b50.vidyo.io');

// URL de INCLUDE
define('INCLUSAO', '/views/_include');
// URL de INCLUDE HEAD
define('INCLUDE_HEAD', HOME_URI . '/views/_include');
// URL de CSS
define('CSS', HOME_URI . '/views/_css');
// URL de JS
define('JS', HOME_URI . '/views/_js');
// URL de images
define('IMAGES', HOME_URI . '/views/_images');

// URL DE LOAD VIEWS
define('LOAD_VIEW', HOME_URI . '/views/');

define('AVATAR_WIDTH', '200');
define('AVATAR_HEIGHT', '0');
define('AVATAR_QUALITY', '100');

//CONSTANTES PARA USO GLOBAL

define('USUARIO', 1);
define('PROF', 2);

//CONEXAO BD
define('MYSQL', 'mysql.app5m.com.br');
define('USER', 'app5m94');
define('PASS', 'CXK8345h');
define('BD', 'app5m94');

//DADOS ACESSO MOIP
define('TOKEN_MOIP', 'IGKME8SRD1ZOLYP9PYJJZF3R6HMGKDOL');
define('KEY_MOIP', 'XAOMJZGLOMMUC5ZXVAJCAXTUANNZEDESH5HNJHIF');
define('TOKEN_MOIP_PRODUCTION', 'RKSUZV6VN2XJMPPJA1NW13YT8FCCSOYU');
define('KEY_MOIP_PRODUCTION', 'GDSNIX2XHAG3ZYJFKGQE2QLBUHYSI1ECKKXLLX3U');
define('TOKEN_NOTIFICATION', '715db9472eb3456099e0fc684a47e20e');
define('TOKEN_NOTIFICATION_SANDBOX', '29a0752e63234a459c836ebd3a968d73');
define('ACCESS_TOKEN', 'dc061ed54107408d816a745092fb0a40_v2');
define('ACCESS_TOKEN_PRODUCTION', 'e5dfed8d04504bb19b2496f0f20a240e_v2');
define('secret', 'f54edebb323f4f7b8831946fc46cd122');


define('VIEWS', 'views');
define('MODELS', 'models');
define('DAOS', 'daos');
define('CONTROLLERS', 'controllers');

define('DEBUG', false);

require_once 'includes/functions.php';
require_once 'load.php';
?>
