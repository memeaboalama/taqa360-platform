<?php

namespace Tests\Feature;

use Tests\TestCase;

class EcommerceRegistrationTermsTest extends TestCase
{
    public function test_registration_requires_terms_acceptance(): void
    {
        $response = $this->from(route('auth.register'))
            ->post(route('register.post'), ['agree_terms' => false]);

        $response->assertRedirect(route('auth.register'));
        $response->assertSessionHasErrors('agree_terms');
    }
}
