<?php
/**
 * Definition of TwigRendererTest
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Tests\Templating;

use FF\Events\AbstractEvent;
use FF\Events\EventBroker;
use FF\Templating\Events\OnPostRender;
use FF\Templating\Events\OnPreRender;
use FF\Templating\Exceptions\RenderingException;
use FF\Templating\Twig\TwigRenderer;
use PHPUnit\Framework\Error\Error;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

/**
 * Test TwigRendererTest
 *
 * @package FF\Tests
 */
class TwigRendererTest extends TestCase
{
    /**
     * @var TwigRenderer
     */
    protected $uut;

    /**
     * @var AbstractEvent[]
     */
    protected static $lastEvents;

    /**
     * {@inheritdoc}
     */
    public static function setUpBeforeClass(): void
    {
        // register test listener
        EventBroker::getInstance()
            ->subscribe([__CLASS__, 'listener'], 'Templating\OnPreRender')
            ->subscribe([__CLASS__, 'listener'], 'Templating\OnPostRender');
    }

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->uut = new TwigRenderer(
            __DIR__ . '/templates',
            [
                'debug' => true,
                'strict_variables' => true,
                'cache' => false
            ]
        );

        self::$lastEvents = [];
    }

    /**
     * Dummy event listener
     *
     * @param AbstractEvent $event
     */
    public static function listener(AbstractEvent $event)
    {
        self::$lastEvents[get_class($event)] = $event;
    }

    /**
     * Tests the namesake method/feature
     */
    public function testSetGetTwig()
    {
        $value = new Environment(new ArrayLoader());
        $same = $this->uut->setTwig($value);
        $this->assertSame($this->uut, $same);
        $this->assertEquals($value, $this->uut->getTwig());
    }

    /**
     * Tests the namesake method/feature
     */
    public function testRender()
    {
        $doc = $this->uut->render('basic.html.twig', ['foo' => 'bar']);
        $this->assertEquals('foo: bar', $doc);

        $this->assertArrayHasKey(OnPreRender::class, self::$lastEvents);
        $this->assertArrayHasKey(OnPostRender::class, self::$lastEvents);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testRenderErrorLoader()
    {
        $this->expectException(RenderingException::class);

        $this->uut->render('missing.html.twig', []);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testRenderErrorSyntax()
    {
        $this->expectException(RenderingException::class);

        $this->uut->render('invalid.html.twig', []);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicCall()
    {
        $this->uut->addGlobal('foo', 'bar');

        $doc = $this->uut->render('basic.html.twig', []);
        $this->assertEquals('foo: bar', $doc);
    }

    /**
     * Tests the namesake method/feature
     */
    public function testMagicCallUnknown()
    {
        $this->expectException(Error::class);

        $this->uut->foo();
    }
}