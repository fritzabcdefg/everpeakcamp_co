<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_with_incomplete_profile_is_sent_to_create_page_when_visiting_profile()
    {
        $user = User::factory()->create([ 'phone' => null, 'address' => null ]);
        $this->actingAs($user)
             ->get(route('profile.show'))
             ->assertRedirect(route('profile.create'));
    }

}
