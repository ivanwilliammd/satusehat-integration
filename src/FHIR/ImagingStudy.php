<?php

namespace Satusehat\Integration\FHIR;

/**
 * ImagingStudy FHIR R4 Resource
 * @link https://hl7.org/fhir/R4/imagingstudy.html
 */
class ImagingStudy
{
    public array $data = ['resourceType' => 'ImagingStudy'];

    public function setId(string $id): self
    {
        $this->data['id'] = $id;
        return $this;
    }

    public function addIdentifier(string $system, string $value): self
    {
        $this->data['identifier'][] = ['system' => $system, 'value' => $value];
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->data['status'] = $status;
        return $this;
    }

    public function setModality(string $system, string $code, string $display = ''): self
    {
        $this->data['modality'][] = [
            'system' => $system,
            'code' => $code,
            'display' => $display,
        ];
        return $this;
    }

    public function setSubject(string $reference): self
    {
        $this->data['subject'] = ['reference' => $reference];
        return $this;
    }

    public function setEncounter(string $reference): self
    {
        $this->data['encounter'] = ['reference' => $reference];
        return $this;
    }

    public function setStarted(string $dateTime): self
    {
        $this->data['started'] = $dateTime;
        return $this;
    }

    public function setEnded(string $dateTime): self
    {
        $this->data['ended'] = $dateTime;
        return $this;
    }

    public function setLocation(string $reference): self
    {
        $this->data['location'] = ['reference' => $reference];
        return $this;
    }

    public function addReferrer(string $reference): self
    {
        $this->data['referrer'] = ['reference' => $reference];
        return $this;
    }

    public function addPartOf(string $reference): self
    {
        $this->data['partOf'][] = ['reference' => $reference];
        return $this;
    }

    public function addSeries(
        string $uid,
        string $system,
        string $code,
        string $display,
        ?string $status = null,
        ?string $description = null,
        ?int $number = null
    ): self {
        $series = [
            'uid' => $uid,
            'modality' => ['system' => $system, 'code' => $code, 'display' => $display],
        ];
        if ($status !== null) {
            $series['status'] = $status;
        }
        if ($description !== null) {
            $series['description'] = $description;
        }
        if ($number !== null) {
            $series['number'] = $number;
        }
        $this->data['series'][] = $series;
        return $this;
    }

    public function setSeriesStarted(int $seriesIdx, string $dateTime): self
    {
        if (isset($this->data['series'][$seriesIdx])) {
            $this->data['series'][$seriesIdx]['started'] = $dateTime;
        }
        return $this;
    }

    public function setSeriesBodySite(int $seriesIdx, string $system, string $code, string $display = ''): self
    {
        if (isset($this->data['series'][$seriesIdx])) {
            $this->data['series'][$seriesIdx]['bodySite'] = [
                'system' => $system,
                'code' => $code,
                'display' => $display,
            ];
        }
        return $this;
    }

    public function addSeriesInstance(
        int $seriesIdx,
        string $uid,
        string $dicomClassSystem,
        string $dicomClassCode,
        string $dicomClassDisplay
    ): self {
        if (!isset($this->data['series'][$seriesIdx])) {
            return $this;
        }
        $this->data['series'][$seriesIdx]['instance'][] = [
            'uid' => $uid,
            'sopClass' => [
                'system' => $dicomClassSystem,
                'code' => $dicomClassCode,
                'display' => $dicomClassDisplay,
            ],
        ];
        return $this;
    }

    public function addSeriesInstanceNumber(int $seriesIdx, int $instanceIdx, int $number): self
    {
        if (isset($this->data['series'][$seriesIdx]['instance'][$instanceIdx])) {
            $this->data['series'][$seriesIdx]['instance'][$instanceIdx]['number'] = $number;
        }
        return $this;
    }

    public function json(): array
    {
        return $this->data;
    }
}
