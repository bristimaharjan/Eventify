<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfilePhotoFallbackTest extends TestCase
{
    use RefreshDatabase;

    public function test_missing_profile_photo_uses_the_initial_instead_of_a_broken_image(): void
    {
        $user = User::factory()->create(['name' => 'Bristi Maharjan']);
        $user->forceFill(['profile_photo' => 'missing-profile-photo.png'])->save();

        $this->assertStringNotContainsString(
            'missing-profile-photo.png',
            $user->fresh()->profile_photo_url
        );

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertDontSee('missing-profile-photo.png')
            ->assertSee('Bristi Maharjan');
    }

    public function test_unavailable_remote_photo_has_an_initial_fallback(): void
    {
        $user = User::factory()->create(['name' => 'Bristi Maharjan']);
        $user->forceFill(['profile_photo' => 'https://example.invalid/profile-photo.png'])->save();

        $this->actingAs($user)
            ->get(route('home'))
            ->assertOk()
            ->assertSee('https://example.invalid/profile-photo.png')
            ->assertSee("onerror=\"this.style.display='none';this.nextElementSibling.style.display='flex';\"", false)
            ->assertSee('Bristi Maharjan');
    }
}
