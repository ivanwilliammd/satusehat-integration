<?php

namespace Satusehat\Integration;

use Dotenv\Dotenv;
use GuzzleHttp\Client;
// Guzzle HTTP Package
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Psr7\Request;
// SATUSEHAT Model & Log
use Satusehat\Integration\Models\SatusehatLog;
use Satusehat\Integration\Models\SatusehatToken;

class OAuth2Client
{
    public $patient_dev = ['P02478375538', 'P02428473601', 'P03647103112', 'P01058967035', 'P01836748436', 'P01654557057', 'P00805884304', 'P00883356749', 'P00912894463'];

    public $practitioner_dev = ['10009880728', '10006926841', '10001354453', '10010910332', '10018180913', '10002074224', '10012572188', '10018452434', '10014058550', '10001915884'];

    public string $auth_url;

    public string $base_url;

    public string $client_id;

    public string $client_secret;

    public string $organization_id;

    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(getcwd());
        $dotenv->safeLoad();

        if (getenv('SATUSEHAT_ENV') == 'PROD') {
            $this->auth_url = getenv('SATUSEHAT_AUTH_PROD', 'https://api-satusehat.kemkes.go.id/oauth2/v1');
            $this->base_url = getenv('SATUSEHAT_FHIR_PROD', 'https://api-satusehat.kemkes.go.id/fhir-r4/v1');
            $this->client_id = getenv('CLIENTID_PROD');
            $this->client_secret = getenv('CLIENTSECRET_PROD');
            $this->organization_id = getenv('ORGID_PROD');
        } elseif (getenv('SATUSEHAT_ENV') == 'STG') {
            $this->auth_url = getenv('SATUSEHAT_AUTH_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/oauth2/v1');
            $this->base_url = getenv('SATUSEHAT_FHIR_STG', 'https://api-satusehat-stg.dto.kemkes.go.id/fhir-r4/v1');
            $this->client_id = getenv('CLIENTID_STG');
            $this->client_secret = getenv('CLIENTSECRET_STG');
            $this->organization_id = getenv('ORGID_STG');
        } elseif (getenv('SATUSEHAT_ENV') == 'DEV') {
            $this->auth_url = getenv('SATUSEHAT_AUTH_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/oauth2/v1');
            $this->base_url = getenv('SATUSEHAT_FHIR_DEV', 'https://api-satusehat-dev.dto.kemkes.go.id/fhir-r4/v1');
            $this->client_id = getenv('CLIENTID_DEV');
            $this->client_secret = getenv('CLIENTSECRET_DEV');
            $this->organization_id = getenv('ORGID_DEV');
        }

        if ($this->organization_id == null) {
            return 'Add your organization_id at environment first';
        }
    }

    public function token()
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
            ],
        ];

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

    public function log($id, $action, $url, $payload, $response)
    {
        $status = new SatusehatLog();
        $status->response_id = $id;
        $status->action = $action;
        $status->url = $url;
        $status->payload = $payload;
        $status->response = $response;
        $status->user_id = auth()->user() ? auth()->user()->id : 'Cron Job';
        $status->save();
    }

    public function get_by_id($resource, $id)
    {
        $access_token = $this->token();

        if (! isset($access_token)) {
            return $this->respondError($oauth2);
        }

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = $this->base_url.'/'.$resource.'/'.$id;
        $request = new Request('GET', $url, $headers);

        try {
            $res = $client->sendAsync($request)->wait();
            $statusCode = $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());

            if ($response->resourceType == 'OperationOutcome' | $response->total == 0) {
                $id = 'Error '.$statusCode;
            }
            $this->log($id, 'GET', $url, null, json_encode($response));

            return [$statusCode, $response];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $res = json_decode($e->getResponse()->getBody()->getContents());

            $this->log('Error '.$statusCode, 'GET', $url, null, (array) $res);

            return [$statusCode, $res];
        }
    }

    public function get_by_nik($resource, $nik)
    {
        $access_token = $this->token();

        if (! isset($access_token)) {
            return $this->respondError($oauth2);
        }

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = $this->base_url.'/'.$resource.'?identifier=https://fhir.kemkes.go.id/id/nik|'.$nik;
        $request = new Request('GET', $url, $headers);

        try {
            $res = $client->sendAsync($request)->wait();
            $statusCode = $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());

            if ($response->resourceType == 'OperationOutcome' | $response->total == 0) {
                $id = 'Not Found';
            } else {
                $id = $response->entry['0']->resource->id;
            }
            $this->log($id, 'GET', $url, null, (array) $response);

            return [$statusCode, $response];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $res = json_decode($e->getResponse()->getBody()->getContents());

            $this->log('Error '.$statusCode, 'GET', $url, null, (array) $res);

            return [$statusCode, $res];
        }
    }

    public function ss_post($resource, $body)
    {
        $access_token = $this->token();

        if (! isset($access_token)) {
            return $this->respondError($oauth2);
        }

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = $this->base_url.($resource == 'Bundle' ? '' : '/'.$resource);
        $request = new Request('POST', $url, $headers, $body);

        try {
            $res = $client->sendAsync($request)->wait();
            $statusCode = $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());

            if ($response->resourceType == 'OperationOutcome' || $statusCode >= 400) {
                $id = 'Error '.$statusCode;
            } else {
                if ($resource == 'Bundle') {
                    $id = 'Success '.$statusCode;
                } else {
                    $id = $response->id;
                }
            }
            $this->log($id, 'POST', $url, (array) $body, (array) $response);

            return [$statusCode, $response];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $res = json_decode($e->getResponse()->getBody()->getContents());

            $this->log('Error '.$statusCode, 'POST', $url, (array) $body, (array) $res);

            return [$statusCode, $res];
        }

        $res = $client->sendAsync($request)->wait();
        echo $res->getBody();
    }

    public function ss_put($resource, $id, $body)
    {
        $access_token = $this->token();

        if (! isset($access_token)) {
            return $this->respondError($oauth2);
        }

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$access_token,
        ];

        $url = $this->base_url.'/'.$resource.'/'.$id;
        $request = new Request('PUT', $url, $headers, $body);

        try {
            $res = $client->sendAsync($request)->wait();
            $statusCode = $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());

            if ($response->resourceType == 'OperationOutcome' || $statusCode >= 400) {
                $id = 'Error '.$statusCode;
            } else {
                $id = $response->id;
            }
            $this->log($id, 'PUT', $url, (array) $body, (array) $response);

            return [$statusCode, $response];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $res = json_decode($e->getResponse()->getBody()->getContents());

            $this->log('Error '.$statusCode, 'PUT', $url, null, (array) $res);

            return [$statusCode, $res];
        }
    }
    
    public function get($resource, $id)
    {
    $access_token = $this->token();

    if (!isset($access_token)) {
        return $this->respondError($oauth2);
    }

    $client = new Client();
    $headers = [
        'Authorization' => 'Bearer ' . $access_token,
    ];

    $url = $this->base_url . '/' . $resource . '/' . $id;
    $request = new Request('GET', $url, $headers);

    try {
        $res = $client->sendAsync($request)->wait();
        $statusCode = $res->getStatusCode();
        $response = json_decode($res->getBody()->getContents());

        if ($response && isset($response->resourceType) && $response->resourceType == 'OperationOutcome' || isset($response->total) && $response->total == 0) {
            $id = 'Error ' . $statusCode;
        }

        $this->log($id, 'GET', $url, null, json_encode($response));

        return [$statusCode, $response];
    } catch (ClientException $e) {
        $statusCode = $e->getResponse()->getStatusCode();
        $res = json_decode($e->getResponse()->getBody()->getContents());

        $this->log('Error ' . $statusCode, 'GET', $url, null, (array) $res);

        return [$statusCode, $res];
    }  
    }
}
