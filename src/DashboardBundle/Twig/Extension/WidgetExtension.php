<?php

declare(strict_types=1);

/*
 * This file is part of SolidInvoice project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SolidInvoice\DashboardBundle\Twig\Extension;

use SolidInvoice\DashboardBundle\WidgetFactory;
use SolidInvoice\DashboardBundle\Widgets\WidgetInterface;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class WidgetExtension extends AbstractExtension
{
    /**
     * @var WidgetFactory
     */
    private $widgetFactory;

    public function __construct(WidgetFactory $widgetFactory)
    {
        $this->widgetFactory = $widgetFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('render_dashboard_widget', [$this, 'renderDashboardWidget'], ['needs_environment' => true, 'is_safe' => ['html']]),
        ];
    }

    /**
     * Renders a dashboard widget at a specific location.
     */
    public function renderDashboardWidget(Environment $environment, string $location): string
    {
        /** @var WidgetInterface[] $widgets */
        $widgets = $this->widgetFactory->get($location);

        $content = '';

        foreach ($widgets as $widget) {
            $content .= $environment->render($widget->getTemplate(), $widget->getData());
        }

        return $content;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'dashboard_widget_extension';
    }
}
