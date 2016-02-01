<?php

namespace PhpTokenEnricher;

class Token
{
    private $position;
    private $content;
    private $typeIndex;
    private $typeName;
    private $index;
    private $line;
    private $originalToken;
    private $namespaceToken;
    private $detailedType;
    private $parentToken;

    public function is($typeIndex)
    {
        return $this->typeIndex == $typeIndex;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function setPosition($position)
    {
        $this->position = $position;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getTypeIndex()
    {
        return $this->typeIndex;
    }

    public function setTypeIndex($typeIndex)
    {
        $this->typeIndex = $typeIndex;
    }

    public function getTypeName()
    {
        return $this->typeName;
    }

    public function setTypeName($typeName)
    {
        $this->typeName = $typeName;
    }

    public function getIndex()
    {
        return $this->index;
    }

    public function setIndex($index)
    {
        $this->index = $index;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function setLine($line)
    {
        $this->line = $line;
    }

    public function getOriginalToken()
    {
        return $this->originalToken;
    }

    public function setOriginalToken($originalToken)
    {
        $this->originalToken = $originalToken;
    }

    public function getNamespaceToken()
    {
        return $this->namespaceToken;
    }

    public function setNamespaceToken($namespaceToken)
    {
        $this->namespaceToken = $namespaceToken;
    }

    public function getDetailedType()
    {
        return $this->detailedType;
    }

    public function setDetailedType($detailedType)
    {
        $this->detailedType = $detailedType;
    }

    public function getParentToken()
    {
        return $this->parentToken;
    }

    public function setParentToken($parentToken)
    {
        $this->parentToken = $parentToken;
    }
}
