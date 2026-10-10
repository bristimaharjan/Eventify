<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportAuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_pdf_routes_require_authentication(): void
    {
        foreach ([
            'vendor.reports.booking.pdf',
            'vendor.reports.eventbooking.pdf',
            'admin.reports.adminbooking.pdf',
            'admin.reports.admineventbooking.pdf',
        ] as $route) {
            $this->get(route($route))->assertRedirect(route('login.form'));
        }
    }

    public function test_report_pdf_routes_restrict_access_by_role(): void
    {
        $customer = User::factory()->create(['role' => 'user']);

        foreach ([
            'admin.reports.adminbooking.pdf',
            'admin.reports.admineventbooking.pdf',
        ] as $route) {
            $this->actingAs($customer)
                ->get(route($route))
                ->assertForbidden();
        }

        foreach ([
            'vendor.reports.booking.pdf',
            'vendor.reports.eventbooking.pdf',
        ] as $route) {
            $this->actingAs($customer)
                ->get(route($route))
                ->assertRedirect(route('login.form'));
        }
    }

    public function test_admin_and_vendor_can_download_their_report_pdfs(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        foreach ([
            'admin.reports.adminbooking.pdf',
            'admin.reports.admineventbooking.pdf',
        ] as $route) {
            $this->actingAs($admin)
                ->get(route($route))
                ->assertOk();
        }

        $vendor = User::factory()->create(['role' => 'vendor']);

        foreach ([
            'vendor.reports.booking.pdf',
            'vendor.reports.eventbooking.pdf',
        ] as $route) {
            $this->actingAs($vendor)
                ->get(route($route))
                ->assertOk();
        }
    }

    public function test_unknown_routes_return_not_found_status(): void
    {
        $this->get('/pre-production-smoke-test-unknown-route')
            ->assertNotFound();
    }
}
