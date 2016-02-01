<?php

namespace PhpTokenEnricher;

use ArrayObject;

class Collection extends ArrayObject
{
    public function __construct(array $tokens)
    {
        parent::__construct($tokens);
    }

    public function getNextNonWhitespace(Token $token)
    {
        for ($i = $token->getIndex() + 1, $count = $this->count(); $i < $count; $i += 1) {
            if (! $this[$i]->is(T_WHITESPACE)) {
                return $this[$i];
            }
        }
        return null;
    }

    public function findOneByContent($content)
    {
        foreach ($this as $token) {
            if ($token->getContent() == $content) {
                return $token;
            }
        }
        return null;
    }
}
