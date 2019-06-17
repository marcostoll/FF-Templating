<?php
/**
 * Definition of OnPostRender
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Templating\Events;

use FF\Events\AbstractEvent;
use FF\Templating\RenderedDocument;

/**
 * Class OnPostRender
 *
 * @package FF\Templating\Events
 */
class OnPostRender extends AbstractEvent
{
    /**
     * @var RenderedDocument
     */
    protected $doc;

    /**
     * @param RenderedDocument $doc
     */
    public function __construct(RenderedDocument $doc)
    {
        $this->doc = $doc;
    }

    /**
     * @return RenderedDocument
     */
    public function getDoc(): RenderedDocument
    {
        return $this->doc;
    }
}