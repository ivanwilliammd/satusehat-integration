<?php

namespace Satusehat\Integration\Terminology;

use Satusehat\Integration\Exception\Terminology\TerminologyException;
use Satusehat\Integration\Exception\Terminology\TerminologyInvalidArgumentException;
use Satusehat\Integration\Exception\Terminology\TerminologyMissingArgumentException;
use Satusehat\Integration\OAuth2Client;

class Kfa extends OAuth2Client
{
    private array $identifier = ['kfa', 'lkpp', 'nie'];

    /**
     * Get Detail Kfa Product
     *
     * @param string $identifier currently available stroed in : $identifier
     * @param string $code
     * @return void
     */
    public function getProduct(string $identifier, string $code)
    {
        if (!in_array($identifier, $this->identifier)) {
            throw new TerminologyInvalidArgumentException("Identifier currently available (" . implode(", ", $this->identifier) . "), $identifier given");
        }

        $queryStringBuilder = [
            'identifier' => $identifier,
            'code' => $code
        ];

        $queryString = http_build_query($queryStringBuilder);

        return $this->ss_kfa_get("products?", $queryString);
    }
    /**
     * Get paginated Kfa Products
     *
     * @param string $productType currently available : 'alkes' | 'farmasi'
     * @param integer $page min 1 no max
     * @param integer $size min 1 max 1000
     * @param string|null $keyword
     * @return void
     */
    public function getProducts(string $productType, string $keyword = null, int $page = 1, int $size = 100)
    {
        if (!in_array($productType, ['alkes', 'farmasi'])) {
            throw new TerminologyInvalidArgumentException("\$productType currently available (alkes | farmasi), $productType given.");
        }

        if ($size > 1000) {
            throw new TerminologyException("Maximum size record 1000/request, $size given.");
        }

        if ($page < 1 || $size < 1) {
            throw new TerminologyInvalidArgumentException("Page / Size cant be blank.");
        }

        $queryStringBuilder = [
            'product_type' => $productType,
            'page' => $page,
            'size' => $size
        ];

        if (!empty($keyword)) {
            $queryStringBuilder['keyword'] = $keyword;
        }

        $queryString = http_build_query($queryStringBuilder);

        return $this->ss_kfa_get('products/all?', $queryString);
    }
}
