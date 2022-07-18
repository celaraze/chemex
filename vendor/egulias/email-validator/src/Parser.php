<?php

namespace Egulias\EmailValidator;

use Egulias\EmailValidator\Result\InvalidEmail;
use Egulias\EmailValidator\Result\Reason\ExpectingATEXT;
use Egulias\EmailValidator\Result\Result;
use Egulias\EmailValidator\Result\ValidEmail;

abstract class Parser
{
    /**
     * @var Warning\Warning[]
     */
    protected $warnings = [];

    /**
     * @var EmailLexer
     */
    protected $lexer;

    public function __construct(EmailLexer $lexer)
    {
        $this->lexer = $lexer;
    }

    public function parse(string $str): Result
    {
        $this->lexer->setInput($str);

        if ($this->lexer->hasInvalidTokens()) {
            return new InvalidEmail(new ExpectingATEXT("Invalid tokens found"), $this->lexer->token["value"]);
        }

        $preParsingResult = $this->preLeftParsing();
        if ($preParsingResult->isInvalid()) {
            return $preParsingResult;
        }

        $localPartResult = $this->parseLeftFromAt();

        if ($localPartResult->isInvalid()) {
            return $localPartResult;
        }

        $domainPartResult = $this->parseRightFromAt();

        if ($domainPartResult->isInvalid()) {
            return $domainPartResult;
        }

        return new ValidEmail();
    }

    abstract protected function preLeftParsing(): Result;

    abstract protected function parseLeftFromAt(): Result;

    /**
     * id-left "@" id-right
     */
    abstract protected function parseRightFromAt(): Result;

    /**
     * @return Warning\Warning[]
     */
    public function getWarnings(): array
    {
        return $this->warnings;
    }

    protected function hasAtToken(): bool
    {
        $this->lexer->moveNext();
        $this->lexer->moveNext();

        return $this->lexer->token['type'] !== EmailLexer::S_AT;
    }
}
