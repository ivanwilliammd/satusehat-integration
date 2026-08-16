<?php

declare(strict_types=1);

namespace Satusehat\Integration\Builder;

use Satusehat\Integration\DataType\CodeableConcept;
use Satusehat\Integration\DataType\Identifier;
use Satusehat\Integration\DataType\Period;
use Satusehat\Integration\DataType\Reference;

/**
 * EpisodeOfCare FHIR R4 Resource Builder
 * @link https://www.hl7.org/fhir/episodeofcare.html
 */
class PayloadBuilderEpisodeOfCare extends Builder
{
    protected string $resourceType = 'EpisodeOfCare';

    public function __construct()
    {
        $this->data['resourceType'] = $this->resourceType;
    }

    public function setId(string $id): self
    {
        $this->set('id', $id);
        return $this;
    }

    public function addIdentifier(Identifier $identifier): self
    {
        $this->push('identifier', $identifier->toArray());
        return $this;
    }

    public function setStatus(string $status): self
    {
        $validStatuses = ['planned', 'waitlist', 'active', 'onhold', 'finished', 'cancelled', 'entered-in-error'];
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid status: ' . $status);
        }
        $this->set('status', $status);
        return $this;
    }

    public function addStatusHistory(string $status, string $start, ?string $end = null): self
    {
        $validStatuses = ['planned', 'waitlist', 'active', 'onhold', 'finished', 'cancelled', 'entered-in-error'];
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException('Invalid status: ' . $status);
        }

        $period = ['start' => $start];
        if ($end !== null) {
            $period['end'] = $end;
        }

        $this->push('statusHistory', [
            'status' => $status,
            'period' => $period,
        ]);
        return $this;
    }

    public function setPatient(Reference $patient): self
    {
        $this->set('patient', $patient->toArray());
        return $this;
    }

    public function setManagingOrganization(Reference $organization): self
    {
        $this->set('managingOrganization', $organization->toArray());
        return $this;
    }

    public function addType(CodeableConcept $type): self
    {
        $this->push('type', $type->toArray());
        return $this;
    }

    public function setPeriod(Period $period): self
    {
        $this->set('period', $period->toArray());
        return $this;
    }

    public function addDiagnosis(
        Reference $condition,
        CodeableConcept $role,
        ?int $rank = null
    ): self {
        $diagnosis = [
            'condition' => $condition->toArray(),
            'role' => $role->toArray(),
        ];

        if ($rank !== null) {
            $diagnosis['rank'] = $rank;
        }

        $this->push('diagnosis', $diagnosis);
        return $this;
    }

    public function addReferralRequest(Reference $referralRequest): self
    {
        $this->push('referralRequest', $referralRequest->toArray());
        return $this;
    }

    public function setCareManager(Reference $careManager): self
    {
        $this->set('careManager', $careManager->toArray());
        return $this;
    }

    public function addTeam(Reference $team): self
    {
        $this->push('team', $team->toArray());
        return $this;
    }

    public function addAccount(Reference $account): self
    {
        $this->push('account', $account->toArray());
        return $this;
    }

    public function build(): array
    {
        return parent::build();
    }
}
