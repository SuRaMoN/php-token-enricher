<?php

namespace PhpTokenEnricher;

class BracketToken extends Token
{
    private $correspondingBracket;

 	public function getCorrespondingBracket()
 	{
 		return $this->correspondingBracket;
 	}

 	public function setCorrespondingBracket($correspondingBracket)
 	{
 		$this->correspondingBracket = $correspondingBracket;
 	}
}
