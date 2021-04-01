<?php

require_once MODELS . '/Conexao/Conexao.class.php';
require_once MODELS . '/Cadastros/Cadastros.class.php';
require_once MODELS . '/Gcm/Gcm.class.php';


require 'autoload.php';

use Moip\Moip;
use Moip\Auth\BasicAuth;
use Moip\Auth\OAuth;

class MoipPayment extends Conexao {

  public function __construct() {
      $this->Conecta();

      $this->tabela = "app_consultas";
      $this->tabela_user = "app_users";
      $this->tabela_pagamentos = "app_servicos_pagamentos";
      $this->tabela_perc = "app_perc";

  }

  public function Percentual(){

    $sql2 = $this->mysqli->prepare("SELECT profissional FROM `$this->tabela_perc` WHERE id='1'");
    $sql2->execute();
    $sql2->bind_result($this->percentual);
    $sql2->fetch();

    return $this->percentual;

  }

  public function Order(
    $id_servico,
    $id_usuario,
    $id_fornecedor,
    $card_name,
    $card_cpf,
    $card_cep,
    $card_estado,
    $card_cidade,
    $card_endereco,
    $card_bairro,
    $card_numero,
    $card_complemento,
    $card_celular,
    $card_nascimento,
    $hash,
    $valor
    ) {


    $moip = new Moip(new BasicAuth(TOKEN_MOIP, KEY_MOIP), Moip::ENDPOINT_SANDBOX);

    $this->perc = $this->Percentual();

    $cadastros = New Cadastros();
    $cadastros->listDadosOrder($id_usuario);
    $cadastros->listIDMoip($id_fornecedor);

    $valor = str_replace(".","", $valor);
    $valor = (int) $valor;


    //CELULAR COMPRADOR
    $ddd_comprador = substr($cadastros->celular, 1, 2);
    $numero_comprador = substr($cadastros->celular, 4);
    $numero_comprador_final = tiraCarac($numero_comprador);

    //CELULAR PAGADOR
    $ddd_pagador = substr($card_celular, 1, 2);
    $numero_pagador = substr($card_celular, 4);
    $numero_pagador_final = tiraCarac($numero_pagador);

    try {

      $order = $moip->orders()
      ->setOwnId($id_servico)
      ->addItem("Descrição do pedido",1, "Pagamento de Consulta", $valor)
      ->setShippingAmount(0)->setAddition(0)->setDiscount(0)
      ->setCustomer($moip->customers()
      ->setOwnId($id_usuario)
      ->setFullname($cadastros->nome)
      ->setEmail($cadastros->email)
      ->setBirthDate($cadastros->data_nascimento)
      ->setTaxDocument($cadastros->cpf)
      ->setPhone($ddd_comprador, $numero_comprador_final, 55)
      ->addAddress("SHIPPING",
      $cadastros->endereco,
      $cadastros->numero,
      $cadastros->bairro,
      $cadastros->nome_cidade,
      $cadastros->sigla,
      $cadastros->cep,
      $cadastros->complemento
      ))
      ->addReceiver($cadastros->moip_id, "SECONDARY", 0, $this->perc, false)
      ->create();

      $holder = $moip->holders()
      ->setFullname($card_name)
      ->setBirthDate(dataUS($card_nascimento))
      ->setTaxDocument($card_cpf, 'CPF')
      ->setPhone($ddd_pagador, $numero_pagador_final, 55)
      ->setAddress('BILLING',
      $card_endereco,
      $card_numero,
      $card_bairro,
      $card_cidade,
      $card_estado,
      $card_cep,
      $card_complemento
      );

      $payment = $order->payments()
      ->setCreditCardHash($hash, $holder)
      ->setInstallmentCount(1)
      ->setStatementDescriptor("DrAgil")
      ->execute();



      $this->payment_id = $payment->getId();
      $this->payment_status = $payment->getStatus();
      $this->payment_data = $payment->getCreatedAt()->format('Y-m-d H:i:s');



    } catch (\Moip\Exceptions\UnautorizedException $e) {
      echo $e->getMessage();
    } catch (\Moip\Exceptions\ValidationException $e) {
      printf($e->__toString());
    } catch (\Moip\Exceptions\UnexpectedException $e) {
      echo $e->getMessage();
    }

  }

  public function CreateAccount($nome, $email, $documento, $data_nascimento) {

    $moip = new Moip(new OAuth(ACCESS_TOKEN), Moip::ENDPOINT_SANDBOX);

    try {


      $account = $moip->accounts()
      ->setName($nome)
      ->setLastName('')
      ->setEmail($email)
      ->setIdentityDocument('4737283520', 'SSP', '2015-06-21')
      ->setBirthDate($data_nascimento)
      ->setTaxDocument($documento)
      ->setType('MERCHANT')
      ->setPhone(51, 66778899, 55)
      ->setTransparentAccount(true)
      ->addAddress("av.ipiranga", "100", "petropolis", "porto alegre", "rs", "90690040", "10", 'BRA')
      ->create();

      $this->id = $account->getId();
      $this->ACCESS_TOKEN = $account->getaccessToken();

      $this->var_final = $this->id . "." . $this->ACCESS_TOKEN;


      return $this->var_final;


    } catch (Exception $e) {
      printf($e->__toString());
    }

  }

  public function addContaBancaria(
    $moip_id,
    $moip_access,
    $bank_number,
    $agency_number,
    $agency_number_d,
    $bank_account,
    $bank_account_d,
    $bank_titular,
    $bank_document
  ) {


    $moip = new Moip(new OAuth($moip_access), Moip::ENDPOINT_SANDBOX);

    try {

      $bank_account = $moip->bankaccount()
      ->setBankNumber($bank_number)
      ->setAgencyNumber($agency_number)
      ->setAgencyCheckNumber($agency_number_d)
      ->setAccountNumber($bank_account)
      ->setAccountCheckNumber($bank_account_d)
      ->setType("CHECKING")
      ->setHolder($bank_titular, $bank_document, "CPF")
      ->create($moip_id);


    } catch (Exception $e) {
      printf($e->__toString());
    }
  }

  public function listContaBancaria($moip_id, $moip_access) {

    $moip = new Moip(new OAuth($moip_access), Moip::ENDPOINT_SANDBOX);

    try {

      $bank_accounts = $moip->bankaccount()->getList($moip_id)->getBankAccounts();

      $json = json_encode($bank_accounts);
      echo $json;

    } catch (Exception $e) {
      printf($e->__toString());
    }
  }

  public function removeContaBancaria($bank_id, $moip_access) {

    $moip = new Moip(new OAuth($moip_access), Moip::ENDPOINT_SANDBOX);

    try {

      $moip->bankaccount()->delete($bank_id);

    } catch (Exception $e) {
      printf($e->__toString());
    }
  }

  public function SaldoAtual($moip_access) {

    $moip = new Moip(new OAuth($moip_access), Moip::ENDPOINT_SANDBOX);

    try {

      $balances = $moip->balances()->get();

      $json = json_encode($balances);
      echo $json;

    } catch (Exception $e) {
      printf($e->__toString());
    }
  }

  public function EfetuarSaque($moip_access, $bank_id, $valor) {

    $moip = new Moip(new OAuth($moip_access), Moip::ENDPOINT_SANDBOX);

    try {

      $transfer = $moip->transfers()
      ->setTransfersToBankAccount($valor, $bank_id)
      ->execute();


    } catch (Exception $e) {


    }
  }

  public function listSaques($moip_access) {


    $moip = new Moip(new OAuth($moip_access), Moip::ENDPOINT_SANDBOX);

    try {

      $transfers = $moip->transfers()->getList();

      $json = json_encode($transfers);
      echo $json;

    } catch (Exception $e) {

      $json = json_encode($e->__toString());
      echo $json;

    }
  }


  public function Notificacao() {

    $request = file_get_contents('php://input');
    $input = json_decode($request);

    $this->status = str_replace('PAYMENT.', '', $input->event);

    $resource = $input->resource;
    $payment = $resource->payment;
    $this->id = $payment->id;

    $sql = $this->mysqli->prepare("UPDATE `$this->tabela_pagamentos` SET status = ? WHERE token = ?");
    $sql->bind_param('ss', $this->status, $this->id);
    $sql->execute();
    $sql->close();

  }



  public function PreferenciaNew() {

    $moip = new Moip(new BasicAuth(TOKEN_MOIP, KEY_MOIP), Moip::ENDPOINT_SANDBOX);

    try {
      $notification = $moip->notifications()
      ->addEvent('PAYMENT.CREATED')
      ->addEvent('PAYMENT.WAITING')
      ->addEvent('PAYMENT.IN_ANALYSIS')
      ->addEvent('PAYMENT.AUTHORIZED')
      ->addEvent('PAYMENT.CANCELLED')
      ->addEvent('PAYMENT.REFUNDED')
      ->addEvent('PAYMENT.REVERSED')
      ->addEvent('PAYMENT.SETTLED')
      ->setTarget('https://benditasmaes.com.br/apiv2/user/moip/notificacao')
      ->create();
      print_r($notification);
    } catch (Exception $e) {
      printf($e->__toString());
    }

  }

  public function PreferenciaPesquisa() {

    $moip = new Moip(new BasicAuth(TOKEN_MOIP, KEY_MOIP), Moip::ENDPOINT_SANDBOX);
    $notifications = $moip->notifications()->getList();

    print_r($notifications);exit;

  }

  public function PreferenciaRemove() {

    $moip = new Moip(new BasicAuth(TOKEN_MOIP, KEY_MOIP), Moip::ENDPOINT_SANDBOX);
    $notification = $moip->notifications()->delete("NPR-Y4I9WBTQBPEG");
    // $notification = $moip->notifications()->delete("NPR-0R52THS2M4MV");

    print_r($notification);exit;

  }

  public function PaymentPesquisa() {

    $moip = new Moip(new BasicAuth(TOKEN_MOIP, KEY_MOIP), Moip::ENDPOINT_SANDBOX);
    $payment = $moip->payments()->get("PAY-7FRXT8J8DS26");

    echo $payment->getStatus();

  }

  public function PaymentCapture($token) {

    $moip = new Moip(new BasicAuth(TOKEN_MOIP, KEY_MOIP), Moip::ENDPOINT_SANDBOX);

    $captured_payment = $moip->payments()->get($token)->capture();

    //print_r($captured_payment);

  }



}

?>
