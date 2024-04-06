<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\FHIR\Exception\FHIRException;
use Satusehat\Integration\OAuth2Client;

class KFA extends OAuth2Client
{
    private array $identifier = ['kfa', 'lkpp', 'nie'];

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
            throw new FHIRException("Page / Size cannot be blank.");
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

        return $this->ss_kfa_get('kfa-v2/products/all', $queryString);
    }
}
