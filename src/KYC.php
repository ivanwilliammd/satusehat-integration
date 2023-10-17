<?php

namespace Satusehat\Integration;

// Cryptography
use phpseclib3\Crypt\AES;
use phpseclib3\Crypt\PublicKeyLoader;
use phpseclib3\Crypt\Random;
use phpseclib3\Crypt\RSA;

class KYC extends OAuth2Client
{
    public function generateKey()
    {
        // Generate a new RSA key pair
        $config = [
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => 2048,
        ];

        $keyPair = openssl_pkey_new($config);

        // Extract the public key
        $publicKey = openssl_pkey_get_details($keyPair)['key'];

        // Export the private key
        openssl_pkey_export($keyPair, $privateKey);

        return [
            'publicKey' => $publicKey,
            'privateKey' => $privateKey,
        ];
    }

    // Cryptography
    public function importRsaKey($pem)
    {
        // Fetch the part of the PEM string between header and footer
        $pemHeader = '-----BEGIN PUBLIC KEY-----';
        $pemFooter = '-----END PUBLIC KEY-----';
        $pemContents = substr($pem, strlen($pemHeader), strlen($pem) - strlen($pemFooter));

        // Base64 decode the string to get the binary data
        $binaryDerString = base64_decode($pemContents);

        // Save the binary DER data to a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'rsa_key');
        file_put_contents($tempFile, $binaryDerString);

        // Import the RSA key using openssl
        $key = openssl_pkey_get_public('file://'.$tempFile);

        // Generate the key details for encryption
        $keyDetails = openssl_pkey_get_details($key);

        // Clean up the temporary file
        unlink($tempFile);

        return $key;
    }

    public function generateSymmetricKey()
    {
        // Generate a random key using OpenSSL
        $cryptoStrong = true;
        $key = openssl_random_pseudo_bytes(32, $cryptoStrong);

        if ($cryptoStrong !== true) {
            // Error occurred during key generation
            return null;
        }

        // Return the generated key
        return $key;
    }

    public function generateRSAKeyPair()
    {
        // Generate private key
        $privateKeyConfig = [
            'digest_alg' => 'sha256',
            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
        ];
        $privateKeyResource = openssl_pkey_new($privateKeyConfig);

        // Extract the public key from the private key
        $privateKeyDetails = openssl_pkey_get_details($privateKeyResource);
        $publicKey = $privateKeyDetails['key'];

        // Prepare the result
        $result = [
            'privateKey' => $privateKeyResource,
            'publicKey' => $publicKey,
        ];

        return $result;
    }

    public function formatMessage($data)
    {
        $dataAsBase64 = chunk_split(base64_encode($data));

        return "-----BEGIN ENCRYPTED MESSAGE-----\r\n{$dataAsBase64}-----END ENCRYPTED MESSAGE-----";
    }

    public function aesEncrypt($data, $symmetricKey)
    {
        $cipher = 'aes-256-gcm';
        $ivLength = 12;
        $tag = '';
        $iv = '';

        // Generate random IV
        if (function_exists('random_bytes')) {
            $iv = random_bytes($ivLength);
        } elseif (function_exists('openssl_random_pseudo_bytes')) {
            $iv = openssl_random_pseudo_bytes($ivLength);
        } else {
            // Fallback if random bytes generation is not available
            $iv = '';
            for ($i = 0; $i < $ivLength; $i++) {
                $iv .= chr(mt_rand(0, 255));
            }
        }

        // $cipher = new AES(AES::MODE_GCM);
        $cipher = new AES('gcm');
        $cipher->setKeyLength(256);
        $cipher->setKey($symmetricKey);
        $cipher->setNonce($iv);

        $ciphertext = $cipher->encrypt($data);
        $tag = $cipher->getTag();

        // Concatenate the IV, ciphertext, and tag
        $encryptedData = $iv.$ciphertext.$tag;

        return $encryptedData;
    }

    public function aesDecrypt($encryptedData, $symmetricKey)
    {
        $cipher = 'aes-256-gcm';
        $ivLength = 12;

        // Extract IV and encrypted bytes
        $iv = substr($encryptedData, 0, $ivLength);
        $encryptedBytes = substr($encryptedData, $ivLength);

        $ivlen = openssl_cipher_iv_length($cipher);
        $tag_length = 16;
        $iv = substr($encryptedData, 0, $ivlen);
        $tag = substr($encryptedData, -$tag_length);
        $ciphertext = substr($encryptedData, $ivlen, -$tag_length);

        $ciphertext_raw = openssl_decrypt($ciphertext, $cipher, $symmetricKey, OPENSSL_NO_PADDING, $iv, $tag);

        return $ciphertext_raw;

        // Decrypt the data
        $decryptedData = openssl_decrypt(
            $encryptedBytes,
            $cipher,
            $symmetricKey,
            OPENSSL_RAW_DATA,
            $iv
        );

        return $decryptedData;
    }

    public function encryptMessage($message, $pubPEM)
    {
        // Generate a symmetric key
        $aesKey = $this->generateSymmetricKey(); // Generate a 256-bit key (32 bytes)

        $serverKey = PublicKeyLoader::load($pubPEM);
        $serverKey = $serverKey->withPadding(RSA::ENCRYPTION_OAEP);
        $wrappedAesKey = $serverKey->encrypt($aesKey);

        // echo ($wrappedAesKey);

        // Encrypt the message using the generated AES key
        $encryptedMessage = $this->aesEncrypt($message, $aesKey);

        // Combine wrapped AES key and encrypted message
        $payload = $wrappedAesKey.$encryptedMessage;

        return $this->formatMessage($payload);
    }

    public function decryptMessage($message, $privateKey)
    {
        $beginTag = '-----BEGIN ENCRYPTED MESSAGE-----';
        $endTag = '-----END ENCRYPTED MESSAGE-----';

        // Fetch the part of the PEM string between beginTag and endTag
        $messageContents = substr(
            $message,
            strlen($beginTag) + 1,
            strlen($message) - strlen($endTag) - strlen($beginTag) - 2
        );

        // Base64 decode the string to get the binary data
        $binaryDerString = base64_decode($messageContents);

        // Split the binary data into wrapped key and encrypted message
        $wrappedKeyLength = 256;
        $wrappedKey = substr($binaryDerString, 0, $wrappedKeyLength);
        $encryptedMessage = substr($binaryDerString, $wrappedKeyLength);

        // Unwrap the key using RSA private key
        // $unwrappedKey = unwrapKey($wrappedKey, $privateKey);

        // $key = new RSA();

        // $key->loadKey($privateKey);
        $key = PublicKeyLoader::load($privateKey);
        // $key = $key->withPadding(RSA::ENCRYPTION_OAEP);
        $aesKey = $key->decrypt($wrappedKey);

        // Decrypt the encrypted message using the unwrapped key
        $decryptedMessage = $this->aesDecrypt($encryptedMessage, $aesKey);

        return $decryptedMessage;
    }

    public function generateUrl($agen, $nik_agen)
    {
        $keyPair = $this->generateKey();
        $publicKey = $keyPair['publicKey'];
        $privateKey = $keyPair['privateKey'];

        $accessToken = $this->token();

        // Set the API URL and authentication token
        $apiUrl = 'https://api-satusehat.kemkes.go.id/kyc/v1/generate-url';

        $pubPEM = '-----BEGIN PUBLIC KEY-----
        MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAxLwvebfOrPLIODIxAwFp
        4Qhksdtn7bEby5OhkQNLTdClGAbTe2tOO5Tiib9pcdruKxTodo481iGXTHR5033I
        A5X55PegFeoY95NH5Noj6UUhyTFfRuwnhtGJgv9buTeBa4pLgHakfebqzKXr0Lce
        /Ff1MnmQAdJTlvpOdVWJggsb26fD3cXyxQsbgtQYntmek2qvex/gPM9Nqa5qYrXx
        8KuGuqHIFQa5t7UUH8WcxlLVRHWOtEQ3+Y6TQr8sIpSVszfhpjh9+Cag1EgaMzk+
        HhAxMtXZgpyHffGHmPJ9eXbBO008tUzrE88fcuJ5pMF0LATO6ayXTKgZVU0WO/4e
        iQIDAQAB
        -----END PUBLIC KEY-----';

        // Set the request data
        $data = [
            'agent_name' => $agen,
            'agent_nik' => $nik_agen,
            'public_key' => $publicKey,
        ];

        $jsonData = json_encode($data);

        $encryptedPayload = $this->encryptMessage($jsonData, $pubPEM);

        // Initialize cURL
        $ch = curl_init();

        // Set the cURL options
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $encryptedPayload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: text/plain',
            'Authorization: Bearer '.$accessToken,
        ]);

        // Execute the request
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'cURL error: '.curl_error($ch);
        }

        // Close cURL
        curl_close($ch);

        // Output the response
        return $this->decryptMessage($response, $privateKey);
    }
}
