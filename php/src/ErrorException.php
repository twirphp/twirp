<?php

namespace Twirp;

/**
 * Simple exception accepting an {@see Error}.
 */
final class ErrorException extends \Exception implements Exception
{
    /**
     * @var Error
     */
    private $error;

    public function __construct(Error $error)
    {
        parent::__construct($error->msg());

        $this->error = $error;
    }

    /**
     * {@inheritdoc}
     */
    public function getError()
    {
        return $this->error;
    }
}
