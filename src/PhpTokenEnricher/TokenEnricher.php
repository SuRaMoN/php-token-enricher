<?php

namespace PhpTokenEnricher;

use iController\Platform\Utils\FileSystemUtil;
use InvalidArgumentException;

class TokenEnricher
{
    public function __construct()
    {
    }

    public static function newInstance()
    {
        return new self();
    }

    public function parseFromFilePath($path)
    {
        $source = file_get_contents($path);
        if (null === $source) {
            throw new InvalidArgumentException('Error reading given file');
        }
        return $this->parseFromString($source);
    }

    public function parseFromString($source)
    {
        $tokens = token_get_all($source);
        if (null === $tokens) {
            throw new InvalidArgumentException('Could not parse given PHP source');
        }
        return $this->enrichTokenArray(array_values($tokens));
    }

    public function enrichTokenArray(array $tokens)
    {
        $tokens = $this->normalizeTokens($tokens);
        $tokens = new Collection($tokens);
        $tokens = $this->addOpenCloseBracketInformation($tokens);
        $tokens = $this->addNamespaceInformation($tokens);
        $tokens = $this->addConstantInformation($tokens);
        return $tokens;
    }

    private function addConstantInformation(Collection $tokens)
    {
        foreach ($tokens as $token) {
            if (! $token->is(T_CONST)) {
                continue;
            }

            $name = $tokens->getNextNonWhitespace($token);
            $name->setDetailedType('constant-name');
            $name->setParentToken($token);
            $token->setNameToken($name);

            $assignment = $tokens->getNextNonWhitespace($name);
            $assignment->setDetailedType('constant-assignment');
            $assignment->setParentToken($token);
            $token->setAssignmentToken($assignment);

            $valueTokens = array();
            for ($i = $assignment->getIndex(), $count = $tokens->count(); $i < $count && ! $tokens[$i]->is(';'); $i += 1) {
                $tokens[$i]->setDetailedType('contant-value');
                $tokens[$i]->setParentToken($token);
                $valueTokens[] = $tokens[$i];
            }
            $token->setValueTokens($valueTokens);
        }
        return $tokens;
    }

    private function addNamespaceInformation(Collection $tokens)
    {
        $previousNamespaceToken = null;
        foreach ($tokens as $i => $token) {
            if ($token instanceof NamespaceToken) {
                $nameOrBracket = $tokens->getNextNonWhitespace($token);
                if (! $nameOrBracket->is('{')) {
                    $token->setNameToken($nameOrBracket);
                    $nameOrBracket->setDetailedType('namespace-name');
                    $nameOrBracket = $tokens->getNextNonWhitespace($nameOrBracket);
                }
                $token->setHasBrackets($nameOrBracket->is('{'));
                if ($token->hasBrackets()) {
                    $token->setLastToken($nameOrBracket->getCorrespondingBracket());
                }
                if (null !== $previousNamespaceToken && null === $previousNamespaceToken->getLastToken()) {
                    $previousNamespaceToken->setLastToken($tokens[$i - 1]);
                }
                $previousNamespaceToken = $token;
            }
            $token->setNamespaceToken($previousNamespaceToken);
        }
        if (null !== $previousNamespaceToken && null === $previousNamespaceToken->getLastToken()) {
            $previousNamespaceToken->setLastToken($tokens[$tokens->count() - 1]);
        }
        return $tokens;
    }

    private function convertToObjects(array $tokens)
    {
        $tokenObjects = array();
        foreach ($tokenObjects as & $tokenObject) {
            if ($tokenObject instanceof BracketToken) {
                $tokenObject->setCorrespondingBracket($tokenObjects[$tokens[$tokenObject->getIndex()]['correspondingBracketIndex']]);
            }
        }
        return $tokenObjects;
    }

    private function addOpenCloseBracketInformation(Collection $tokens)
    {
        foreach (array(array('(', ')'), array('{', '}')) as $bracketPair) {
            list($open, $close) = $bracketPair;
            $stack = array();
            foreach ($tokens as $token) {
                if ($token->getContent() == $open) {
                    $stack[] = $token;
                } elseif ($token->getContent() == $close) {
                    $startToken = array_pop($stack);
                    $token->setCorrespondingBracket($startToken);
                    $startToken->setCorrespondingBracket($token);
                }
            }
        }
        return $tokens;
    }

    private function normalizeTokens(array $tokens)
    {
        $position = 0;
        $tokenObjects = array();
        foreach ($tokens as $i => & $token) {
            $previousToken = (0 == $i ? null : $tokens[$i - 1]);
            $originalToken = $token;
            if (is_string($token)) {
                $token = array(
                    0 => $token,
                    1 => $token,
                    2 => 0 == $i ? 0 : $previousToken[2],
                );
            }
            switch ($token[0]) {
                case '{': case '}':
                case '(': case ')':
                    $tokenObject = new BracketToken();
                    break;

                case T_NAMESPACE:
                    $tokenObject = new NamespaceToken();
                    break;

                case T_CONST:
                    $tokenObject = new ConstantToken();
                    break;

                default:
                    $tokenObject = new Token();
            }
            $tokenObject->setTypeIndex($token[0]);
            $tokenObject->setContent($token[1]);
            $tokenObject->setLine($token[2]);
            $tokenObject->setTypeName(is_int($tokenObject->getTypeIndex()) ? token_name($tokenObject->getTypeIndex()) : $tokenObject->getContent());
            $tokenObject->setPosition($position);
            $tokenObject->setIndex($i);
            $tokenObject->setOriginalToken($originalToken);

            $tokenObjects[] = $tokenObject;
            $position += strlen($tokenObject->getContent());
        }
        return $tokenObjects;
    }
}
