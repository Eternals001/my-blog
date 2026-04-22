<?php

namespace App\View\Components\Seo;

use App\Services\SEOService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumb extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $items = [],
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $seo = app(SEOService::class);
        $schema = $seo->generateBreadcrumbSchema($this->items);

        $html = '<nav class="breadcrumb" aria-label="面包屑导航">';
        $html .= '<ol class="flex items-center space-x-2 text-sm">';

        foreach ($this->items as $index => $item) {
            $isLast = $index === count($this->items) - 1;
            $position = $index + 1;

            if ($isLast) {
                $html .= '<li class="flex items-center">';
                $html .= '<span class="text-gray-500 dark:text-gray-400" aria-current="page">';
                $html .= e($item['name']);
                $html .= '</span>';
                $html .= '</li>';
            } else {
                $html .= '<li class="flex items-center">';
                $html .= '<svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">';
                $html .= '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>';
                $html .= '</svg>';
                $html .= '<a href="' . e($item['url']) . '" class="ml-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">';
                $html .= e($item['name']);
                $html .= '</a>';
                $html .= '</li>';
            }
        }

        $html .= '</ol>';
        $html .= '</nav>';

        $html .= '<script type="application/ld+json">' . $schema . '</script>';

        return $html;
    }
}
