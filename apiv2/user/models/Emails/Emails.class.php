<?php

require_once MODELS . '/phpMailer/class.phpmailer.php';

class Emails extends PHPMailer {

    public function cadastro($email, $nome) {

      $mail = new PHPMailer();
      $mail->IsMail(true);
      $mail->IsHTML(true);
      $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
      $this->email_remetente = EMAIL_REMETENTE;
      $mail->From = EMAIL_REMETENTE; // Seu e-mail
      $mail->FromName = "Seja bem-vindo | " . NOME_REMETENTE; // Seu nome
      $mail->AddAddress($email); //E-mail Destinatario
      $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
      $mail->Subject = "Seja bem-vindo" . " | " . NOME_REMETENTE; // Assunto da mensagem
      $mail->Body .= "Prezado(a) <strong>" . ucwords($nome) . "</strong>, <br/><br/>";
      $mail->Body .= "Obrigado por efetuar o download e o cadastro em nosso aplicativo.<br /><br />";
      $mail->Body .= "<strong>Faça seu pedido em determinado mercado,</strong> receba seus produtos em casa ou retire no estabelecimento.<br /><br />";
      $mail->Body .= "Atenciosamente, <br/> ";
      $mail->Body .= "Equipe Brasil Mercado, <br/><br/> ";
      $mail->Body .= "<img src='".LOGO_EMAIL."' width='120'>";
      $mail->Send();

    }

    public function recuperarsenha($email, $nome, $token) {

      $mail = new PHPMailer();
      $mail->IsMail(true);
      $mail->IsHTML(true);
      $mail->CharSet = 'utf-8'; // Charset da mensagem (opcional)
      $this->email_remetente = EMAIL_REMETENTE;
      $mail->From = EMAIL_REMETENTE; // Seu e-mail
      $mail->FromName = "Recuperar Senha | " . NOME_REMETENTE; // Seu nome
      $this->link = LINK_RECUPERAR_SENHA . "/" . $token;
      $mail->AddAddress($email); //E-mail Destinatario
      $mail->IsHTML(true); // Define que o e-mail será enviado como HTML
      $mail->Subject = "Olá," . " | " . ucwords($nome); // Assunto da mensagem
      $mail->Body .= "Prezado(a) <strong>" . ucwords($nome) . "</strong>, <br/><br/>";
      $mail->Body .= "Você perdeu sua senha?.<br />";
      $mail->Body .= "<strong>Para redefinir sua senha, basta clicar no link abaixo.<br /><br />";
      $mail->Body .= "<strong><a href='$this->link'>Clique aqui</a><br /><br />";
      $mail->Body .= "Atenciosamente, <br/> ";
      $mail->Body .= "Equipe MyfavDoc, <br/><br/> ";
      $mail->Body .= "<img src='".LOGO_EMAIL."' width='120'>";
      $mail->Send();

    }


}
