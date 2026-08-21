<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

/**
 * ImagingStudy FHIR R4 Resource Builder
 * @link https://hl7.org/fhir/R4/imagingstudy.html
 */
class PayloadBuilderImagingStudy extends Builder
{
    protected string $resourceType = 'ImagingStudy';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(string $system, string $value): self
    {
        $this->push('identifier', ['system' => $system, 'value' => $value]);
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->set('status', $status);
        return $this;
    }

    public function addModality(string $system, string $code, string $display = ''): self
    {
        $this->push('modality', [
            'system' => $system,
            'code' => $code,
            'display' => $display,
        ]);
        return $this;
    }

    public function setSubject(string $reference): self
    {
        $this->set('subject', ['reference' => $reference]);
        return $this;
    }

    public function setEncounter(string $reference): self
    {
        $this->set('encounter', ['reference' => $reference]);
        return $this;
    }

    public function setStarted(string $dateTime): self
    {
        $this->set('started', $dateTime);
        return $this;
    }

    public function setEnded(string $dateTime): self
    {
        $this->set('ended', $dateTime);
        return $this;
    }

    public function setLocation(string $reference): self
    {
        $this->set('location', ['reference' => $reference]);
        return $this;
    }

    public function addReferrer(string $reference): self
    {
        $this->set('referrer', ['reference' => $reference]);
        return $this;
    }

    public function addPartOf(string $reference): self
    {
        $this->push('partOf', ['reference' => $reference]);
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
        $this->push('series', $series);
        return $this;
    }

    public function setSeriesStarted(int $seriesIdx, string $dateTime): self
    {
        $this->set("series/{$seriesIdx}/started", $dateTime);
        return $this;
    }

    public function setSeriesBodySite(int $seriesIdx, string $system, string $code, string $display = ''): self
    {
        $this->set("series/{$seriesIdx}/bodySite", [
            'system' => $system,
            'code' => $code,
            'display' => $display,
        ]);
        return $this;
    }

    public function addSeriesInstance(
        int $seriesIdx,
        string $uid,
        string $dicomClassSystem,
        string $dicomClassCode,
        string $dicomClassDisplay
    ): self {
        $this->push("series/{$seriesIdx}/instance", [
            'uid' => $uid,
            'sopClass' => [
                'system' => $dicomClassSystem,
                'code' => $dicomClassCode,
                'display' => $dicomClassDisplay,
            ],
        ]);
        return $this;
    }

    public function addSeriesInstanceNumber(int $seriesIdx, int $instanceIdx, int $number): self
    {
        $this->set("series/{$seriesIdx}/instance/{$instanceIdx}/number", $number);
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}
