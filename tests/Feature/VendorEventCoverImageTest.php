<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\VendorKyc;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class VendorEventCoverImageTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_vendor_event_create_page_shows_crop_guidance_and_modal_labels(): void
    {
        $vendor = User::factory()->create([
            'role' => 'vendor',
        ]);

        VendorKyc::create([
            'user_id' => $vendor->id,
            'business_name' => 'Test Events Ltd.',
            'document_type' => 'citizenship',
            'document_front' => 'kyc/test-front.jpg',
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        $this->actingAs($vendor)
            ->get('/vendor/events/create')
            ->assertOk()
            ->assertSee('Event Cover Image')
            ->assertSee('Recommended format: JPG, JPEG, or PNG')
            ->assertSee('Adjust Event Cover')
            ->assertSee('Use Image')
            ->assertSee('For best results, upload a landscape image suitable for the event card.');
    }
}
