<?php

namespace PhpTokenEnricher;

class NamespaceToken extends Token
{
    private $nameToken;
    private $lastToken;
    private $hasBrackets;

    public function getName()
    {
        if (null === $this->getNameToken()) {
            return '';
        }
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

    public function getLastToken()
    {
        return $this->lastToken;
    }

    public function setLastToken($lastToken)
    {
        $this->lastToken = $lastToken;
    }

    public function hasBrackets()
    {
        return $this->hasBrackets;
    }

    public function setHasBrackets($hasBrackets)
    {
        $this->hasBrackets = $hasBrackets;
    }
}
