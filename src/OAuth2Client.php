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

    public $base_url;
    public $auth_url;

    public $fhir_url;

    public $client_id;
    public $client_secret;

    public $organization_id;

    public $override;

    public $satusehat_env;

    public $oauth2_error = [
        'statusCode' => 401,
        'res' => 'Unauthorized. Token not found',
    ];

    public function __construct()
    {
        $dotenv = Dotenv::createUnsafeImmutable(getcwd());
        $dotenv->safeLoad();

        $this->override = config('satusehatintegration.ss_parameter_override');

        $this->satusehat_env = $this->override ? null : getenv('SATUSEHAT_ENV');

        if ($this->satusehat_env == 'PROD') {
            $this->base_url = getenv('SATUSEHAT_BASE_URL_PROD') ?: 'https://api-satusehat.kemkes.go.id';
            $this->client_id = getenv('CLIENTID_PROD');
            $this->client_secret = getenv('CLIENTSECRET_PROD');
            $this->organization_id = getenv('ORGID_PROD');
        } elseif ($this->satusehat_env == 'STG') {
            $this->base_url = getenv('SATUSEHAT_BASE_URL_STG') ?: 'https://api-satusehat-stg.dto.kemkes.go.id';
            $this->client_id = getenv('CLIENTID_STG');
            $this->client_secret = getenv('CLIENTSECRET_STG');
            $this->organization_id = getenv('ORGID_STG');
        } elseif ($this->satusehat_env == 'DEV') {
            $this->base_url = getenv('SATUSEHAT_BASE_URL_DEV') ?: 'https://api-satusehat-dev.dto.kemkes.go.id';
            $this->client_id = getenv('CLIENTID_DEV');
            $this->client_secret = getenv('CLIENTSECRET_DEV');
            $this->organization_id = getenv('ORGID_DEV');
        }

        $this->base_url = $this->override ? null : $this->base_url;

        $authEndpoint = getenv('SATUSEHAT_AUTH_ENDPOINT') ?: '/oauth2/v1';
        $fhirEndpoint = getenv('SATUSEHAT_FHIR_ENDPOINT') ?: '/fhir-r4/v1';


        // // untuk handle versioning endpoint
        $this->auth_url = $this->base_url . $authEndpoint;
        $this->fhir_url = $this->base_url . $fhirEndpoint;


        if (!$this->override && $this->organization_id == null) {
            return 'Add your organization_id at environment first';
        }
    }

    public function token()
    {
        $token = SatusehatToken::where('environment', $this->satusehat_env)->where('client_id', $this->client_id)->orderBy('created_at', 'desc')
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
        $url = $this->auth_url . '/accesstoken?grant_type=client_credentials';
        $request = new Request('POST', $url, $headers);

        try {
            $res = $client->sendAsync($request, $options)->wait();
            $contents = json_decode($res->getBody()->getContents());

            if (isset($contents->access_token)) {
                SatusehatToken::create([
                    'environment' => $this->satusehat_env,
                    'client_id' => $this->client_id,
                    'token' => $contents->access_token,
                ]);

                return $contents->access_token;
            } else {
                return $this->respondError($this->oauth2_error);
            }
        } catch (ClientException $e) {
            // error.
            $res = json_decode($e->getResponse()->getBody()->getContents());
            $issue_information = $res->issue[0]->details->text;

            $this->log($issue_information, 'POST Token', $url, null, (array) $res);

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

    public function respondError($message)
    {
        $statusCode = $message['statusCode'];
        $res = $message['res'];

        return [$statusCode, $res];
    }

    public function get_by_id($resource, $id)
    {
        $access_token = $this->token();

        if (!isset($access_token)) {
            return $this->respondError($this->oauth2_error);
        }

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $access_token,
        ];

        $url = $this->fhir_url . '/' . $resource . '/' . $id;
        $request = new Request('GET', $url, $headers);

        try {
            $res = $client->sendAsync($request)->wait();
            $statusCode = $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());

            if ($response->resourceType == 'OperationOutcome' | $response->total == 0) {
                $id = 'Error ' . $statusCode;
            }
            $this->log($id, 'GET', $url, null, (array) $response);

            return [$statusCode, $response];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $res = json_decode($e->getResponse()->getBody()->getContents());

            $this->log('Error ' . $statusCode, 'GET', $url, null, (array) $res);

            return [$statusCode, $res];
        }
    }

    public function get_by_nik($resource, $nik)
    {
        $access_token = $this->token();

        if (!isset($access_token)) {
            return $this->respondError($this->oauth2_error);
        }

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $access_token,
        ];

        $url = $this->base_url . '/' . $resource . '?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik;
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

            $this->log('Error ' . $statusCode, 'GET', $url, null, (array) $res);

            return [$statusCode, $res];
        }
    }

    /**
     * Get request to satu sehat master data resource
     *
     * @param [type] $resource
     * @param [type] $queryString
     * @return void
     */
    public function ss_kfa_get($resource, $queryString)
    {

        $access_token = $this->token();

        if (!isset($access_token)) {
            return $this->respondError($this->oauth2_error);
        }

        $client = new Client();
        $headers = [
            'Authorization' => 'Bearer ' . $access_token,
        ];

        $url = $this->base_url . '/kfa-v2/' . $resource . $queryString;

        $request = new Request('GET', $url, $headers);

        try {
            $res = $client->sendAsync($request)->wait();
            $statusCode = $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());

            if ($resource == 'products/all?') {
                if (!empty($response) && empty($response->total)) {
                    $id = 'Not Found';
                } else {
                    $id  = 'KFA_GET_' . $resource;
                }
            }

            if ($resource == 'products?') {
                if (!empty($response) && empty($response->result)) {
                    $id = 'Not Found';
                } else {
                    $id  = 'KFA_GET_' . $resource;
                }
            }

            $this->log($id, 'GET', $url, null, (array) $response);

            return [$statusCode, $response];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $res = json_decode($e->getResponse()->getBody()->getContents());

            $this->log('Error ' . $statusCode, 'GET', $url, null, (array) $res);

            return [$statusCode, $res];
        }
    }

    public function ss_post($resource, $body)
    {
        $access_token = $this->token();

        if (!isset($access_token)) {
            return $this->respondError($this->oauth2_error);
        }

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ];

        $url = $this->fhir_url . ($resource == 'Bundle' ? '' : '/' . $resource);
        $request = new Request('POST', $url, $headers, $body);

        try {
            $res = $client->sendAsync($request)->wait();
            $statusCode = $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());

            if ($resource === 'Patient') {
                // Patient

                // Get patient identifer
                $patient_obj = json_decode($body);
                $url = $patient_obj->identifier[0]->system;
                $parsed_url = parse_url($url, PHP_URL_PATH);
                $exploded_url = explode('/', $parsed_url);
                $identifier_type = $exploded_url[2];

                if ($identifier_type === 'nik') {
                    if ($response->success !== true) {
                        $id = 'Error ' . $statusCode;
                    }
                    $id = $response->data->patient_id;
                } else if ($identifier_type === 'nik-ibu') {
                    if ($response->create_patient->success !== true) {
                        $id = 'Error ' . $statusCode;
                    }
                    $id = $response->create_patient->data->patient_id;
                }
            } else {
                // Other than patient
                if ($response->resourceType == 'OperationOutcome' || $statusCode >= 400) {
                    $id = 'Error ' . $statusCode;
                } else {
                    if ($resource == 'Bundle') {
                        $id = 'Success ' . $statusCode;
                    } else {
                        $id = $response->id;
                    }
                }
            }
            $this->log($id, 'POST', $url, (array) json_decode($body), (array) $response);

            return [$statusCode, $response];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $res = json_decode($e->getResponse()->getBody()->getContents());

            $this->log('Error ' . $statusCode, 'POST', $url, (array) json_decode($body), (array) $res);

            return [$statusCode, $res];
        }

        $res = $client->sendAsync($request)->wait();
        echo $res->getBody();
    }

    public function ss_put($resource, $id, $body)
    {
        $access_token = $this->token();

        if (!isset($access_token)) {
            return $this->respondError($this->oauth2_error);
        }

        $client = new Client();
        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $access_token,
        ];

        $url = $this->fhir_url . '/' . $resource . '/' . $id;
        $request = new Request('PUT', $url, $headers, $body);

        try {
            $res = $client->sendAsync($request)->wait();
            $statusCode = $res->getStatusCode();
            $response = json_decode($res->getBody()->getContents());

            if ($response->resourceType == 'OperationOutcome' || $statusCode >= 400) {
                $id = 'Error ' . $statusCode;
            } else {
                $id = $response->id;
            }
            $this->log($id, 'PUT', $url, (array) json_decode($body), (array) $response);

            return [$statusCode, $response];
        } catch (ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $res = json_decode($e->getResponse()->getBody()->getContents());

            $this->log('Error ' . $statusCode, 'PUT', $url, null, (array) $res);

            return [$statusCode, $res];
        }
    }
}
