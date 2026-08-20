<?php

namespace Satusehat\Integration\FHIR;

use Satusehat\Integration\Builder\PayloadBuilderDiagnosticReport;
use Satusehat\Integration\Exception\FHIR\FHIRMissingProperty;
use Satusehat\Integration\OAuth2Client;

/**
 * DiagnosticReport FHIR R4 Resource
 * @link https://www.hl7.org/fhir/diagnosticreport.html
 *
 * Uses PayloadBuilderDiagnosticReport for clean typed building.
 * Backward compatible: still extends OAuth2Client for old SSRequest pattern.
 */
class DiagnosticReport extends OAuth2Client
{
    public array $diagnosticReport = ['resourceType' => 'DiagnosticReport'];

    public function setId(string $id): self
    {
        $this->diagnosticReport['id'] = $id;
        return $this;
    }

    public function addIdentifier(string $system, string $value): self
    {
        $this->diagnosticReport['identifier'][] = [
            'system' => $system,
            'value' => $value,
        ];
        return $this;
    }

    public function setStatus(string $status): self
    {
        $this->diagnosticReport['status'] = $status;
        return $this;
    }

    public function addCategory(string $system, string $code, string $display = '', string $text = null): self
    {
        $category = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
        if ($text !== null) {
            $category['text'] = $text;
        }
        $this->diagnosticReport['category'][] = $category;
        return $this;
    }

    public function setCode(string $system, string $code, string $display, string $text = null): self
    {
        $codeArr = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
        if ($text !== null) {
            $codeArr['text'] = $text;
        }
        $this->diagnosticReport['code'] = $codeArr;
        return $this;
    }

    public function setSubject(string $reference, string $display = null): self
    {
        $this->diagnosticReport['subject'] = ['reference' => $reference];
        if ($display !== null) {
            $this->diagnosticReport['subject']['display'] = $display;
        }
        return $this;
    }

    public function setEncounter(string $reference): self
    {
        $this->diagnosticReport['encounter'] = ['reference' => $reference];
        return $this;
    }

    public function setEffectiveDateTime(string $dateTime): self
    {
        $this->diagnosticReport['effectiveDateTime'] = $dateTime;
        return $this;
    }

    public function setIssued(string $instant): self
    {
        $this->diagnosticReport['issued'] = $instant;
        return $this;
    }

    public function addPerformer(string $reference, string $display = null): self
    {
        $performer = ['reference' => $reference];
        if ($display !== null) {
            $performer['display'] = $display;
        }
        $this->diagnosticReport['performer'][] = $performer;
        return $this;
    }

    public function addResultsInterpreter(string $reference, string $display = null): self
    {
        $interpreter = ['reference' => $reference];
        if ($display !== null) {
            $interpreter['display'] = $display;
        }
        $this->diagnosticReport['resultsInterpreter'][] = $interpreter;
        return $this;
    }

    public function addSpecimen(string $reference): self
    {
        $this->diagnosticReport['specimen'][] = ['reference' => $reference];
        return $this;
    }

    public function addResult(string $reference): self
    {
        $this->diagnosticReport['result'][] = ['reference' => $reference];
        return $this;
    }

    public function addImagingStudy(string $reference): self
    {
        $this->diagnosticReport['imagingStudy'][] = ['reference' => $reference];
        return $this;
    }

    public function addMedia(string $comment, string $linkReference): self
    {
        $this->diagnosticReport['media'][] = [
            'comment' => $comment,
            'link' => ['reference' => $linkReference],
        ];
        return $this;
    }

    public function setConclusion(string $conclusion): self
    {
        $this->diagnosticReport['conclusion'] = $conclusion;
        return $this;
    }

    public function addConclusionCode(string $system, string $code, string $display = ''): self
    {
        $this->diagnosticReport['conclusionCode'][] = [
            'coding' => [
                [
                    'system' => $system,
                    'code' => $code,
                    'display' => $display,
                ],
            ],
        ];
        return $this;
    }

    /**
     * Build using PayloadBuilderDiagnosticReport (Phase 3 pattern).
     */
    public static function build(): PayloadBuilderDiagnosticReport
    {
        return new PayloadBuilderDiagnosticReport();
    }

    public function json(): string
    {
        if (! array_key_exists('status', $this->diagnosticReport)) {
            throw new FHIRMissingProperty('DiagnosticReport.status is required');
        }

        if (! array_key_exists('code', $this->diagnosticReport)) {
            throw new FHIRMissingProperty('DiagnosticReport.code is required');
        }

        if (! array_key_exists('subject', $this->diagnosticReport)) {
            throw new FHIRMissingProperty('DiagnosticReport.subject is required');
        }

        return json_encode($this->diagnosticReport, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    public function post(): array
    {
        [$statusCode, $res] = $this->ss_post('DiagnosticReport', $this->json());
        return [$statusCode, $res];
    }

    public function put(string $id): array
    {
        $this->diagnosticReport['id'] = $id;
        [$statusCode, $res] = $this->ss_put('DiagnosticReport', $id, $this->json());
        return [$statusCode, $res];
    }
}
