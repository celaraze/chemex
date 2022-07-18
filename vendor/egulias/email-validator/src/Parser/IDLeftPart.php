<?php

namespace Egulias\EmailValidator\Parser;

use Egulias\EmailValidator\Result\InvalidEmail;
use Egulias\EmailValidator\Result\Reason\CommentsInIDRight;
use Egulias\EmailValidator\Result\Result;

class IDLeftPart extends LocalPart
{
    protected function parseComments(): Result
    {
        return new InvalidEmail(new CommentsInIDRight(), $this->lexer->token['value']);
    }
}
