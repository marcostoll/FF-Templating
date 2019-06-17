<?php
/**
 * Definition of TemplateRendererInterface
 *
 * @author Marco Stoll <marco@fast-forward-encoding.de>
 * @copyright 2019-forever Marco Stoll
 * @filesource
 */
declare(strict_types=1);

namespace FF\Templating;

use FF\Templating\Exceptions\RenderingException;

/**
 * Interface TemplateRendererInterface
 *
 * @package FF\Templating
 */
interface TemplateRendererInterface
{
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
    public function render(string $template, array $data): string;
}
