<?php
/**
 * Definition of TwigRenderer
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */

declare(strict_types=1);

namespace FF\Templating\Twig;

use FF\DataStructures\Record;
use FF\Events\EventBroker;
use FF\Templating\Exceptions\RenderingException;
use FF\Templating\RenderedDocument;
use FF\Templating\TemplateRendererInterface;
use Twig\Environment;
use Twig\Error\Error;
use Twig\Extension\ExtensionInterface;
use Twig\Loader\FilesystemLoader;
use Twig\NodeVisitor\NodeVisitorInterface;
use Twig\RuntimeLoader\RuntimeLoaderInterface;
use Twig\TokenParser\TokenParserInterface;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

/**
 * Class TwigRenderer
 *
 * @method void addRuntimeLoader(RuntimeLoaderInterface $loader)
 * @method void addExtension(ExtensionInterface $extension)
 * @method void addTokenParser(TokenParserInterface $parser)
 * @method void addNodeVisitor(NodeVisitorInterface $visitor)
 * @method void addFilter(TwigFilter $filter)
 * @method void addTest(TwigTest $test)
 * @method void addFunction(TwigFunction $function)
 * @method void addGlobal(string $name, mixed $value)
 *
 * @package FF\Templating\Twig
 *
 * @link https://twig.symfony.com/doc/2.x/api.html#environment-options Twig Environment options
 * @link https://twig.symfony.com/doc/2.x/advanced.html Extending Twig
 */
class TwigRenderer implements TemplateRendererInterface
{
    /**
     * @var Environment
     */
    protected $twig;

    /**
     * @param string $templateDir
     * @param array $environmentOptions
     */
    public function __construct(string $templateDir, array $environmentOptions = [])
    {
        $this->initialize($templateDir, $environmentOptions);
    }

    /**
     * @return Environment
     */
    public function getTwig(): Environment
    {
        return $this->twig;
    }

    /**
     * @param Environment $twig
     * @return $this
     */
    public function setTwig(Environment $twig)
    {
        $this->twig = $twig;
        return $this;
    }

    /**
     * Renders a template using the given data
     *
     * @param string $template
     * @param array $data
     * @return string
     * @throws RenderingException
     * @fires Templating\OnPreRender
     * @fires Templating\OnPostRender
     */
    public function render(string $template, array $data): string
    {
        // wrap data in record to support data manipulation via event listener
        $record = new Record($data);
        EventBroker::getInstance()->fire('Templating\OnPreRender', $template, $record);

        try {
            $doc = new RenderedDocument($this->twig->render($template, $record->getDataAsArray()));

            EventBroker::getInstance()->fire('Templating\OnPostRender', $doc);

            return $doc->getContents();
        } catch (Error $e) {
            throw new RenderingException($e);
        }
    }

    /**
     * Magic proxy for the public api of Twig\Environment
     *
     * Routes the method call to the twig environment instance encapsulated
     * within this service.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \BadMethodCallException Method is not defined or accessible on Twig\Environment
     */
    public function __call(string $name, array $arguments = [])
    {
        $callable = [$this->twig, $name];
        if (!is_callable($callable)) {
            // trigger fatal error: unsupported method call
            // mimic standard php error message
            // Fatal error: Call to undefined method {class}::{method}() in {file} on line {line}
            $backTrace = debug_backtrace();
            $errorMsg = 'Call to undefined method ' . __CLASS__ . '::' . $name . '() '
                . 'in ' . $backTrace[0]['file'] . ' on line ' . $backTrace[0]['line'];
            trigger_error($errorMsg, E_USER_ERROR);
        }

        return call_user_func_array($callable, $arguments);
    }

    /**
     * @param string $templateDir
     * @param array $options
     */
    protected function initialize(string $templateDir, array $options)
    {
        $loader = new FilesystemLoader($templateDir);
        $this->twig = new Environment($loader, $options);
    }
}
