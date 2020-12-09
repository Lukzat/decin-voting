<?php
/**
 * Created by PhpStorm.
 * Company: EasyComp s.r.o.
 * User: Vojtěch Heřmánek <vhermanek@easycomp.cz>
 * Date: 22.05.2019
 * Time: 9:50
 */

namespace TranslateService;


class TranslateService implements ITranslate {

    /** @var string Adressa serveru kam se dotazuji na EasyComp Překladovou službu */
    private $address;

    public function setConnection($address) {
        $this->address = $address;
        // TODO: Implement connect() method.
    }

    public function requestTranslation($projectId, $transKey, $method, $requestMethod, TranslateOptions $options = null) {
        // TODO: Implement requestTranslation() method.

        switch ($requestMethod){
            case ITranslate::REQUEST_TYPE_CURL:
                $postData = ['projectId' => $projectId, 'transKey' => $transKey, 'method' => $method];
                if($options){
                    $postData['options'] = $options->getArray();
                }
                // Setup cURL
                //dump($postData);die;
                $ch = curl_init($this->address);
                curl_setopt_array($ch, [
                    CURLOPT_POST => TRUE,
                    CURLOPT_RETURNTRANSFER => TRUE,
                    CURLOPT_POSTFIELDS => json_encode($postData)
                ]);

                // Send the request
                $response = curl_exec($ch);
                //var_dump($response);
                //echo $response;
                // Check for errors
                if($response === FALSE){
                    die(curl_error($ch));
                }

                // Decode the response
                return json_decode($response, TRUE);
                break;
        }
    }
}
