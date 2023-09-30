<?php

namespace Satusehat\Integration;

use Dotenv\Dotenv;

// Guzzle HTTP Package
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;

class SatusehatIntegrationClass
{
    public $patient_dev = ['P02478375538', 'P02428473601', 'P03647103112', 'P01058967035', 'P01836748436', 'P01654557057', 'P00805884304', 'P00883356749', 'P00912894463'];

    public $practitioner_dev = ['10009880728', '10006926841', '10001354453', '10010910332', '10018180913', '10002074224', '10012572188', '10018452434', '10014058550', '10001915884'];

    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(getcwd());
		$dotenv->safeLoad();

        if (getenv('SATUSEHAT_ENV') == 'PROD') {
            $this->auth_url = 'https://api-satusehat.kemkes.go.id/oauth2/v1';
            $this->base_url = 'https://api-satusehat.kemkes.go.id/fhir-r4/v1';
            $this->client_id = getenv('CLIENTID_PROD');
            $this->client_secret = getenv('CLIENTSECRET_PROD');
            $this->organization_id = getenv('ORGID_PROD');
        } elseif (getenv('SATUSEHAT_ENV') == 'STG') {
            $this->auth_url = 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1';
            $this->base_url = 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1';
            $this->client_id = getenv('CLIENTID_STG');
            $this->client_secret = getenv('CLIENTSECRET_STG');
            $this->organization_id = getenv('ORGID_STG');
        } elseif (getenv('SATUSEHAT_ENV') == 'DEV') {
            $this->auth_url = 'https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1';
            $this->base_url = 'https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1';
            $this->client_id = getenv('CLIENTID_DEV');
            $this->client_secret = getenv('CLIENTSECRET_DEV');
            $this->organization_id = getenv('ORGID_DEV');
        }
    }

    public static function oauth2()
    {
        $token = SatusehatToken::where('environment', getenv('SATUSEHAT_ENV'))->orderBy('created_at', 'desc')
            ->where('created_at', '>', now()->subMinutes(50))->first();

        if ($token) {
            return $token->token;
        }

        $client = new Client();

        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $options = [
            'form_params' => [
                'client_id' => $this->client_id,
                'client_secret' => $this->client_secret,
            ]];

        // Create session
        $url = $this->auth_url.'/accesstoken?grant_type=client_credentials';
        $request = new Request('POST', $url, $headers);

        try {
            $res = $client->sendAsync($request, $options)->wait();
            $contents = json_decode($res->getBody()->getContents());

            if (isset($contents->access_token)) {
                SatusehatToken::create([
                    'environment' => getenv('SATUSEHAT_ENV'),
                    'token' => $contents->access_token,
                ]);

                return $contents->access_token;
            } else {
                // return $this->respondError($oauth2);
                return null;
            }
        } catch (ClientException $e) {
            // error.
            $res = json_decode($e->getResponse()->getBody()->getContents());
            $issue_information = $res->issue[0]->details->text;

            return $issue_information;
        }
    }
}
