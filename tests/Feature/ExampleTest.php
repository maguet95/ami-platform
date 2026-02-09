<?php

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_home_page_returns_successful_response(): void
    {
        $this->get('/')->assertStatus(200);
    }

    public function test_about_page_returns_successful_response(): void
    {
        $this->get('/nosotros')->assertStatus(200);
    }

    public function test_methodology_page_returns_successful_response(): void
    {
        $this->get('/metodologia')->assertStatus(200);
    }

    public function test_courses_page_returns_successful_response(): void
    {
        $this->get('/cursos')->assertStatus(200);
    }

    public function test_contact_page_returns_successful_response(): void
    {
        $this->get('/contacto')->assertStatus(200);
    }

    public function test_terms_page_returns_successful_response(): void
    {
        $this->get('/terminos')->assertStatus(200);
    }

    public function test_privacy_page_returns_successful_response(): void
    {
        $this->get('/privacidad')->assertStatus(200);
    }
}
