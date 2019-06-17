<?php
/**
* Definition of RenderingException
*
* @author Marco Stoll <marco@fast-forward-encoding.de>
* @copyright 2019-forever Marco Stoll
* @filesource
*/
declare(strict_types=1);

namespace FF\Templating\Exceptions;

use Twig\Error\Error;

/**
* Class RenderingException
*
* @package FF\Templating\Exceptions
*/
class RenderingException extends \RuntimeException
{
    /**
     * @var Error
     */
    protected $twigError;

    /**
     * RenderingException constructor.
     * @param Error $twigError
     */
    public function __construct(Error $twigError)
    {
        parent::__construct(
            $twigError->getMessage(),
            $twigError->getCode(),
            $twigError
        );
        $this->twigError = $twigError;
    }

    /**
     * @return Error
     */
    public function getTwigError(): Error
    {
        return $this->twigError;
    }
}