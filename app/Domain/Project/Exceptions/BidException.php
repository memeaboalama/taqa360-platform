<?php

namespace App\Domain\Project\Exceptions;

use Exception;

class BidException extends Exception
{
    protected $code = 422;

    public function __construct($message = "Bid operation failed", $code = 422, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    public function render($request)
    {
        return response()->json([
            'message' => $this->getMessage(),
            'errors' => []
        ], $this->getCode());
    }
}
