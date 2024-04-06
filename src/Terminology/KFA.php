<?php

namespace Satusehat\Integration\Terminology;

use Satusehat\Integration\FHIR\Exception\FHIRException;
use Satusehat\Integration\OAuth2Client;

class KFA extends OAuth2Client
{
    private array $identifier = ['kfa', 'lkpp', 'nie'];

    /**
     * Get Detail KFA Product
     *
     * @param string $identifier currently available stroed in : $identifier
     * @param string $code
     * @return void
     */
    public function getProduct(string $identifier, string $code)
    {
        if (!in_array($identifier, $this->identifier)) {
            throw new FHIRException("Identifier currently available : " . implode("','", $this->identifier));
        }

        if (empty($code)) {
            throw new FHIRException("code product required", 422);
        }

        $queryStringBuilder = [
            'identifier' => $identifier,
            'code' => $code
        ];

        $queryString = http_build_query($queryStringBuilder);

        return $this->ss_kfa_get("products?", $queryString);
    }
    /**
     * Get paginated KFA Products
     *
     * @param string $productType currently available : 'alkes' | 'farmasi'
     * @param integer $page min 1 no max
     * @param integer $size min 1 max 1000
     * @param string|null $keyword
     * @return void
     */
    public function getProducts(string $productType, string $keyword = null, int $page = 1, int $size = 100)
    {
        if (empty($productType)) {
            throw new FHIRException("Product type required", 422);
        }

        if (!in_array($productType, ['alkes', 'farmasi'])) {
            throw new FHIRException("Product types of currently available for : 'alkes' | 'farmasi'", 422);
        }

        if ($size > 1000) {
            throw new FHIRException("Maximum size record 1000/request", 422);
        }

        if ($page < 1 || $size < 1) {
            throw new FHIRException("Page / Size can't be blank.");
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
