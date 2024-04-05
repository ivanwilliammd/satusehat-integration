<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\FHIR\Exception\FHIRException;
use Satusehat\Integration\OAuth2Client;

class KFA extends OAuth2Client
{
    private array $identifier = ['kfa', 'lkpp', 'nie'];

    public function getProducts(string $productType, int $page = 1, int $size = 100, string $keyword = null)
    {
        if (empty($productType)) {
            throw new FHIRException("Product type required", 422);
        }

        if (!in_array($productType, ['alkes', 'farmasi'])) {
            throw new FHIRException("Product types of currently available for : 'alkes' | 'farmasi'", 422);
        }

        if ($size > 1000) {
            throw new FHIRException("Maximum size record 1000/request");
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

        return $this->ss_get('kfa-v2/product', $queryString);
    }
}
