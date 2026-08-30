<?php

namespace Satusehat\Integration\Parser;

class GroupParser
{
    private $group;

    public function __construct($group)
    {
        $this->group = $group;
    }

    public function getIdentifiers()
    {
        return $this->group['identifier'] ?? null;
    }

    public function getActive()
    {
        return $this->group['active'] ?? null;
    }

    public function getType()
    {
        return $this->group['type'] ?? null;
    }

    public function getActual()
    {
        return $this->group['actual'] ?? null;
    }

    public function getCode()
    {
        return $this->group['code'] ?? null;
    }

    public function getName()
    {
        return $this->group['name'] ?? null;
    }

    public function getQuantity()
    {
        return $this->group['quantity'] ?? null;
    }

    public function getManagingEntity()
    {
        return $this->group['managingEntity'] ?? null;
    }

    public function getMembers()
    {
        return $this->group['member'] ?? null;
    }
}
