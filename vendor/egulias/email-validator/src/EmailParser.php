<?php

namespace Egulias\EmailValidator;

use Egulias\EmailValidator\Parser\DomainPart;
use Egulias\EmailValidator\Parser\LocalPart;
use Egulias\EmailValidator\Result\InvalidEmail;
use Egulias\EmailValidator\Result\Reason\NoLocalPart;
use Egulias\EmailValidator\Result\Result;
use Egulias\EmailValidator\Result\ValidEmail;
use Egulias\EmailValidator\Warning\EmailTooLong;

class EmailParser extends Parser
{
    const EMAIL_MAX_LENGTH = 254;

    /**
     * @var string
     */
    protected $domainPart = '';

    /**
     * @var string
     */
    protected $localPart = '';

    public function getDomainPart(): string
    {
        return $this->domainPart;
    }

    public function getLocalPart(): string
    {
        return $this->localPart;
    }

    protected function preLeftParsing(): Result
    {
        if (!$this->hasAtToken()) {
            return new InvalidEmail(new NoLocalPart(), $this->lexer->token["value"]);
        }
        return new ValidEmail();
    }

    protected function parseLeftFromAt(): Result
    {
        return $this->processLocalPart();
    }

    private function processLocalPart(): Result
    {
        $localPartParser = new LocalPart($this->lexer);
        $localPartResult = $localPartParser->parse();
        $this->localPart = $localPartParser->localPart();
        $this->warnings = array_merge($localPartParser->getWarnings(), $this->warnings);

        return $localPartResult;
    }

    public function parse(string $str): Result
    {
        $result = parent::parse($str);

        $this->addLongEmailWarning($this->localPart, $this->domainPart);

        return $result;
    }

    private function addLongEmailWarning(string $localPart, string $parsedDomainPart): void
    {
        if (strlen($localPart . '@' . $parsedDomainPart) > self::EMAIL_MAX_LENGTH) {
            $this->warnings[EmailTooLong::CODE] = new EmailTooLong();
        }
    }

    protected function parseRightFromAt(): Result
    {
        return $this->processDomainPart();
    }

    private function processDomainPart(): Result
    {
        $domainPartParser = new DomainPart($this->lexer);
        $domainPartResult = $domainPartParser->parse();
        $this->domainPart = $domainPartParser->domainPart();
        $this->warnings = array_merge($domainPartParser->getWarnings(), $this->warnings);

        return $domainPartResult;
    }
}
