<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Exception\FHIR\FHIRInvalidPropertyValue;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

class MolecularSequence extends OAuth2Client
{
    public array $molecularSequence = ['resourceType' => 'MolecularSequence'];

    public function setText($status, $div)
    {
        $this->molecularSequence['text'] = [
            'status' => $status,
            'div' => $div
        ];
    }

    public function setType($type)
    {
        $validTypes = ['aa', 'dna', 'rna'];
        if (!in_array($type, $validTypes)) {
            throw new FHIRInvalidPropertyValue('Invalid type');
        }
        $this->molecularSequence['type'] = $type;
    }

    public function setCoordinateSystem($coordinateSystem)
    {
        $this->molecularSequence['coordinateSystem'] = $coordinateSystem;
    }

    public function setPatient($patientId)
    {
        $this->molecularSequence['patient'] = [
            'reference' => 'Patient/' . $patientId
        ];
    }

    public function setSpecimen($specimenId)
    {
        $this->molecularSequence['specimen'] = [
            'reference' => 'Specimen/' . $specimenId
        ];
    }

    public function setDevice($deviceId)
    {
        $this->molecularSequence['device'] = [
            'reference' => 'Device/' . $deviceId
        ];
    }

    public function setPerformer($performerId)
    {
        $this->molecularSequence['performer'] = [
            'reference' => 'Organization/' . $performerId
        ];
    }

    public function setReferenceSeq($referenceSeqId, $display, $windowStart, $windowEnd, $strand = 'watson', $orientation = null)
    {
        // Add validation if each exist or not --> if not exist, don't add to the array
        if($referenceSeqId){
            $referenceSeqId_coding = [
                'coding' => [
                    [
                        'system' => 'http://www.ncbi.nlm.nih.gov/nuccore',
                        'code' => $referenceSeqId,
                    ]
                ]
            ];

        }
        if($display){
            $referenceSeqId_coding['coding'][0]['display'] = $display;
        }

        if($strand){
            $this->molecularSequence['referenceSeq']['strand'] = $strand;
        }

        if($windowStart){
            $this->molecularSequence['referenceSeq']['windowStart'] = $windowStart;
        }

        if($windowEnd){
            $this->molecularSequence['referenceSeq']['windowEnd'] = $windowEnd;
        }

        if ($orientation) {
            $this->molecularSequence['referenceSeq']['orientation'] = $orientation;
        }

        $this->molecularSequence['referenceSeq']['referenceSeqId'] = $referenceSeqId_coding;

    }

    public function addVariant($start, $end, $observedAllele, $referenceAllele, $variantPointer = null)
    {
        $variant = [
            'start' => $start,
            'end' => $end,
            'observedAllele' => $observedAllele,
            'referenceAllele' => $referenceAllele
        ];

        if ($variantPointer) {
            $variant['variantPointer'] = $variantPointer;
        }

        $this->molecularSequence['variant'][] = $variant;
    }

    public function json()
    {
        // Ensure mandatory fields are set
        if (!isset($this->molecularSequence['coordinateSystem'])) {
            throw new FHIRMissingProperty('MolecularSequence.coordinateSystem is required');
        }

        return json_encode($this->molecularSequence, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post()
    {
        $payload = $this->json();
        [$statusCode, $res] = $this->ss_post('MolecularSequence', $payload);

        return [$statusCode, $res];
    }

    public function put($id)
    {
        $this->molecularSequence['id'] = $id;

        $payload = $this->json();
        [$statusCode, $res] = $this->ss_put('MolecularSequence', $id, $payload);

        return [$statusCode, $res];
    }
}
