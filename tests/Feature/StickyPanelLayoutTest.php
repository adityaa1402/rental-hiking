<?php

namespace Tests\Feature;

use Tests\TestCase;

class StickyPanelLayoutTest extends TestCase
{
    public function test_shared_sticky_panels_stay_below_navigation_and_flow_on_small_screens(): void
    {
        $stylesheet = file_get_contents(public_path('assets/css/style.css'));

        $this->assertStringContainsString('.sticky-panel {', $stylesheet);
        $this->assertStringContainsString('top: var(--sticky-panel-top);', $stylesheet);
        $this->assertStringContainsString('z-index: 5;', $stylesheet);
        $this->assertStringContainsString('@media (max-width: 991.98px), (max-height: 700px)', $stylesheet);
        $this->assertStringContainsString('position: static;', $stylesheet);

        foreach ([
            resource_path('views/booking/create.blade.php'),
            resource_path('views/customer/payment/show.blade.php'),
            resource_path('views/katalog/index.blade.php'),
            resource_path('views/admin/barang/create.blade.php'),
            resource_path('views/admin/barang/edit.blade.php'),
        ] as $viewPath) {
            $view = file_get_contents($viewPath);

            $this->assertStringContainsString('sticky-panel', $view);
            $this->assertStringNotContainsString('sticky-top', $view);
        }
    }
}
