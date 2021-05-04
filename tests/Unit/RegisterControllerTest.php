<?php


namespace Tests\Unit;
use PHPUnit\Framework\TestCase;

class RegisterControllerTest extends TestCase
{
    const BAD_EMAIL = "test...";
    const BAD_EMAIL_DOMAIN = "test1@mail.ru"; //as example
    const GOOD_EMAIL = "test1@google.com"; //as example

    public function test_email_validation() {
        $response = $this->post("/register", ["email" => self::BAD_EMAIL, "name" => "test", "password" => "1231223"]);
        $response->assertStatus(500);
    }
    public function test_empty_password() {
        $response = $this->post("/register", ["email" => self::GOOD_EMAIL, "name" => "test", "password" => ""]);
        $response->assertStatus(500);
    }
    public function test_email_banned_domain() {
        $response = $this->post("/register", ["email" => self::BAD_EMAIL_DOMAIN, "name" => "test", "password" => "1231223"]);
        $response->assertStatus(500);
    }
    public function test_invalid_ip() {
        $response = $this
            ->withHeaders(['X-Forwarded-For' => '123.12.12.342'])
            ->post("/register", ["email" => self::BAD_EMAIL, "name" => "test", "password" => "1231223"]);
        $response->assertStatus(500);
    }
    public function test_credentinals_ok() {
        $response = $this->post("/register", ["email" => self::GOOD_EMAIL, "name" => "test", "password" => "1231223"]);
        $response->assertStatus(200);
    }
}
