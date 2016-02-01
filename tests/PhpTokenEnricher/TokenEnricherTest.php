<?php

namespace PhpTokenEnricher;

use PhpTokenEnricher\TokenEnricher;
use PHPUnit_Framework_TestCase;

class TokenEnricherTest extends PHPUnit_Framework_TestCase
{
    /** @test */
    public function testTokenEnricher()
    {
        $tokens = TokenEnricher::newInstance()
            ->parseFromString('<?php (f())();');
        $this->assertEquals(0, $tokens[0]->getPosition());
        $this->assertEquals(10, $tokens[1]->getCorrespondingBracket()->getPosition());
    }

    /** @test */
    public function testAddNamespaceInformation()
    {
        $tokens = TokenEnricher::newInstance()
            ->parseFromString('<?php namespace A; 1; namespace B; 2; 3');
        $this->assertEquals('A', $tokens->findOneByContent('1')->getNamespaceToken()->getName());
        $this->assertEquals('B', $tokens->findOneByContent('2')->getNamespaceToken()->getName());
        $this->assertEquals('B', $tokens->findOneByContent('3')->getNamespaceToken()->getName());

        $tokens = TokenEnricher::newInstance()
            ->parseFromString('<?php namespace A { 1; }; namespace { 2; }');
        $this->assertEquals('A', $tokens->findOneByContent('1')->getNamespaceToken()->getName());
        $this->assertNull($tokens->findOneByContent('2')->getNamespaceToken()->getNameToken());
        $this->assertEquals('', $tokens->findOneByContent('2')->getNamespaceToken()->getName());
    }

    /** @test */
    public function testConstantInformation()
    {
        $tokens = TokenEnricher::newInstance()
            ->parseFromString('<?php const A = 1; 5; const B = 2;');
        $this->assertEquals('A', $tokens->findOneByContent('A')->getParentToken()->getName());
        $this->assertEquals('A', $tokens->findOneByContent('1')->getParentToken()->getName());
        $this->assertEquals('B', $tokens->findOneByContent('B')->getParentToken()->getName());
        $this->assertEquals('B', $tokens->findOneByContent('2')->getParentToken()->getName());
        $this->assertNull(null, $tokens->findOneByContent('5')->getParentToken());
    }
}
