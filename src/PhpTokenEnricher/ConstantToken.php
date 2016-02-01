<?php

namespace PhpTokenEnricher;

class ConstantToken extends Token
{
    private $nameToken;
    private $assignmentToken;
    private $valueTokens;

    public function getName()
    {
        return $this->getNameToken()->getContent();
    }

    public function getNameToken()
    {
        return $this->nameToken;
    }

    public function setNameToken($nameToken)
    {
        $this->nameToken = $nameToken;
    }

    public function getAssignmentToken()
    {
        return $this->assignmentToken;
    }

    public function setAssignmentToken($assignmentToken)
    {
        $this->assignmentToken = $assignmentToken;
    }

    public function getValueTokens()
    {
        return $this->valueTokens;
    }

    public function setValueTokens($valueTokens)
    {
        $this->valueTokens = $valueTokens;
    }
}
