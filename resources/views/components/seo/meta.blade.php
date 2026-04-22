<?php

namespace App\View\Components\Seo;

use App\Models\Post;
use App\Services\SEOService;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Meta extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public ?Post $post = null,
        public ?string $title = null,
        public ?string $description = null,
        public ?string $keywords = null,
        public ?string $ogImage = null,
        public ?string $canonical = null,
        public ?string $robots = null,
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $seo = app(SEOService::class);

        if ($this->post) {
            $seo->setPost($this->post);
        } else {
            if ($this->title) {
                $seo->setTitle($this->title);
            }
            if ($this->description) {
                $seo->setDescription($this->description);
            }
            if ($this->keywords) {
                $seo->setKeywords($this->keywords);
            }
        }

        if ($this->ogImage) {
            $seo->setOgImage($this->ogImage);
        }

        if ($this->canonical) {
            $seo->setCanonical($this->canonical);
        }

        if ($this->robots) {
            $seo->setRobots($this->robots);
        }

        return <<<HTML
        <!-- SEO Meta Tags -->
        {$seo->renderMetaTags()}
        
        <!-- JSON-LD Structured Data -->
        <script type="application/ld+json">
        {$seo->generateJsonLd()}
        </script>
HTML;
    }
}
