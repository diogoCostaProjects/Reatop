<?php

require_once MODELS . '/Conexao/Conexao.class.php';

class Gcm extends Conexao {


    public function __construct() {

        $this->Conecta();
        $this->tabela = "app_servicos";
        $this->tabela_gcm = "app_fcm";

    }


    public function lembrete_consulta_ios() {


      $data_atual = date('Y-m-d');

      //SELECIONA GCM de quem tenha consulta com data_de igual a data de hoje
      $query_push = "
      SELECT f.registration_id
      FROM `$this->tabela_gcm` as f
      INNER JOIN app_pets as p on p.app_users_id = f.app_users_id
      INNER JOIN app_users_medicametos as m on p.id = m.app_pets_id
      INNER JOIN app_users_medicamentos_days as md on md.app_users_medicametos_id = m.id
      WHERE md.data='$data_atual' and f.type=2
      GROUP BY p.app_users_id";


      $sql_push = $this->mysqli->query($query_push);

        $i = 0;
        $data = array();
        while ($res = $sql_push->fetch_object()) {

          $this->id_cadastro = $res->id_cadastro;
          $data[$i] = $res->registration_id;
          $i++;
        }

        $registrationIDs = array_values($data);


        $url = 'https://fcm.googleapis.com/fcm/send';

        if (!empty($registrationIDs)):

            $msg = array(
              'title'  =>  'Hoje é dia de consulta! :)',
              'body'     => 'Não esqueça de trazer seu Pet',
              'vibrate'   => 1,
              'sound'     => 1,
            );
            $fields = array(
              'registration_ids'  => array_values($registrationIDs),
              'notification'      => $msg
            );

            $headers = array(
              'Authorization: key=AAAAz1m-scQ:APA91bHavUPNcWgzytBI3yE50dj-gZ1S6g4wlP6DQAUMGolMHe4mTOYUswEDog8ltEo1zaMazrvI0lxB2zZtEWMMo4an7e5NaEw_pkWAVVmccEpNWgWn2Uu_Sl4J_u7g38R_DSgiGMS4',
              'Content-Type: application/json'
            );

            // Open connection
            $ch = curl_init();

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

            // Execute post
            $result = curl_exec($ch);

          


            // Close connection
            curl_close($ch);

        endif;
    }


    public function lembrete_consulta_android() {

        $data_atual = date('Y-m-d');

        //SELECIONA GCM de quem tenha consulta com data_de igual a data de hoje
        $query_push = "
        SELECT f.registration_id
        FROM `$this->tabela_gcm` as f
        INNER JOIN app_pets as p on p.app_users_id = f.app_users_id
        INNER JOIN app_users_medicametos as m on p.id = m.app_pets_id
        INNER JOIN app_users_medicamentos_days as md on md.app_users_medicametos_id = m.id
        WHERE md.data='$data_atual' and f.type=1
        GROUP BY p.app_users_id";

        $sql_push = $this->mysqli->query($query_push);

        $i = 0;
        $data = array();
        while ($res = $sql_push->fetch_object()) {

          $this->id_cadastro = $res->id_cadastro;
          $data[$i] = $res->registration_id;
          $i++;
        }

        $registrationIDs = array_values($data);


        $url = 'https://fcm.googleapis.com/fcm/send';

        if(sizeof($registrationIDs) > 1000){


            $newId = array_chunk($registrationIDs, 1000);

            foreach ($newId as $inner_id) {

              $fields = array(
                'registration_ids' => $inner_id,
                'data' => array(
                  "titulo" => "Hoje é dia de consulta! :) ",
                  "descricao" => "Não esqueça de trazer seu Pet."
                ),
              );


              $headers = array(
                'Authorization: key=AAAAz1m-scQ:APA91bHavUPNcWgzytBI3yE50dj-gZ1S6g4wlP6DQAUMGolMHe4mTOYUswEDog8ltEo1zaMazrvI0lxB2zZtEWMMo4an7e5NaEw_pkWAVVmccEpNWgWn2Uu_Sl4J_u7g38R_DSgiGMS4',
                'Content-Type: application/json'
              );

        }
      }else{

        $fields = array(
          'registration_ids' => $registrationIDs,
          'data' => array(

            "titulo" => "Hoje é dia de consulta! :) ",
            "descricao" => "Não esqueça de trazer seu Pet."
          ),
        );

        $headers = array(
          'Authorization: key=AAAAz1m-scQ:APA91bHavUPNcWgzytBI3yE50dj-gZ1S6g4wlP6DQAUMGolMHe4mTOYUswEDog8ltEo1zaMazrvI0lxB2zZtEWMMo4an7e5NaEw_pkWAVVmccEpNWgWn2Uu_Sl4J_u7g38R_DSgiGMS4',
          'Content-Type: application/json'
        );

      }

      // Open connection
      $ch = curl_init();

      // Set the url, number of POST vars, POST data
      curl_setopt($ch, CURLOPT_URL, $url);
      curl_setopt($ch, CURLOPT_POST, true);
      curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

      // Execute post

      $result = curl_exec($ch);
      echo $result; exit;
      // Close connection
      curl_close($ch);


    }
}
