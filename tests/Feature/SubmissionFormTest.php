<?php

use App\Models\Submission;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

describe('Submission Form', function () {
    beforeEach(function () {
        Submission::query()->delete();
    });

    it('can submit a valid form', function () {
        $formData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'jdoe@email.com',
            'company_name' => 'Test Company',
        ];

        $response = $this->post(route('submission.store'), $formData);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('submissions', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'jdoe@email.com',
            'company_name' => 'Test Company',
        ]);
    });

    it('can submit without company name', function () {
        $formData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'jdoe@email.com',
            'company_name' => null,
        ];

        $response = $this->post(route('submission.store'), $formData);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseHas('submissions', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'jdoe@email.com',
            'company_name' => null,
        ]);
    });

    it('validates required fields', function () {
        $response = $this->post(route('submission.store'), []);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'email',
        ]);

        $this->assertDatabaseCount('submissions', 0);
    });

    it('validates email format', function () {
        $formData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'wrong-format',
        ];

        $response = $this->post(route('submission.store'), $formData);

        $response->assertSessionHasErrors(['email']);

        $this->assertDatabaseCount('submissions', 0);
    });

    it('validates minimum length', function () {
        $formData = [
            'first_name' => 'J',
            'last_name' => 'D',
            'email' => 'jdoe@email.com',
        ];

        $response = $this->post(route('submission.store'), $formData);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
        ]);

        $this->assertDatabaseCount('submissions', 0);
    });

    it('validates company name length when provided', function () {
        $formData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'jdoe@email.com',
            'company_name' => 'T',
        ];

        $response = $this->post(route('submission.store'), $formData);

        $response->assertSessionHasErrors(['company_name']);

        $this->assertDatabaseCount('submissions', 0);
    });

    it('prevents duplicate email submissions', function () {
        Submission::create([
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'jdoe@email.com',
        ]);

        $formData = [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'jdoe@email.com',
        ];

        $response = $this->post(route('submission.store'), $formData);

        $response->assertRedirect(route('home'));

        $this->assertDatabaseCount('submissions', 1);
    });

    it('validates maximum length', function () {
        $formData = [
            'first_name' => str_repeat('J', 256),
            'last_name' => str_repeat('J', 256),
            'email' => str_repeat('test', 256).'@email.com',
            'company_name' => str_repeat('J', 256),
        ];

        $response = $this->post(route('submission.store'), $formData);

        $response->assertSessionHasErrors([
            'first_name',
            'last_name',
            'email',
            'company_name',
        ]);

        $this->assertDatabaseCount('submissions', 0);
    });
});
