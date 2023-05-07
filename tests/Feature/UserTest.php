<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class UserControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex()
    {
        session(['user' => ['id'  => 1801,   "email" => "mp.prasetio@gmail.com",
        'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLXNwb3J0LWV2ZW50cy5waHA2LTAyLnRlc3Qudm94dGVuZW8uY29tXC9hcGlcL3YxXC91c2Vyc1wvbG9naW4iLCJpYXQiOjE2ODM0MTkwMDYsImV4cCI6MTY4MzUwNTQwNiwibmJmIjoxNjgzNDE5MDA2LCJqdGkiOiJ4WXVVRWhTQm1QVG9NS2ptIiwic3ViIjoxODAxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.odn3uqp2gWxhYzQiOadAfXLtB0riRxXcSCRXvc-L-z0']]);

        $response = $this->get(route('users.index'));

        $response->assertStatus(200);
        $response->assertViewIs('page.content.index');
        $response->assertViewHas('datas');
        $response->assertViewHas('contents');
        $response->assertViewHas('view_options');
    }

    public function testCreate()
    {
        session(['user' => ['id'  => 1801,   "email" => "mp.prasetio@gmail.com",
        'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLXNwb3J0LWV2ZW50cy5waHA2LTAyLnRlc3Qudm94dGVuZW8uY29tXC9hcGlcL3YxXC91c2Vyc1wvbG9naW4iLCJpYXQiOjE2ODM0MTkwMDYsImV4cCI6MTY4MzUwNTQwNiwibmJmIjoxNjgzNDE5MDA2LCJqdGkiOiJ4WXVVRWhTQm1QVG9NS2ptIiwic3ViIjoxODAxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.odn3uqp2gWxhYzQiOadAfXLtB0riRxXcSCRXvc-L-z0']]);

        $response = $this->get(route('users.create'));

        $response->assertStatus(200);
        $response->assertViewIs('page.content.add');
        $response->assertViewHas('contents');
    }

    public function testStore()
    {
        session(['user' => ['id'  => 1801,   "email" => "mp.prasetio@gmail.com",
        'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLXNwb3J0LWV2ZW50cy5waHA2LTAyLnRlc3Qudm94dGVuZW8uY29tXC9hcGlcL3YxXC91c2Vyc1wvbG9naW4iLCJpYXQiOjE2ODM0MTkwMDYsImV4cCI6MTY4MzUwNTQwNiwibmJmIjoxNjgzNDE5MDA2LCJqdGkiOiJ4WXVVRWhTQm1QVG9NS2ptIiwic3ViIjoxODAxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.odn3uqp2gWxhYzQiOadAfXLtB0riRxXcSCRXvc-L-z0']]);

        $data = [
            'firstName' => $this->faker->firstName,
            'lastName' => $this->faker->lastName,
            'email' => $this->faker->safeEmail,
            'password' => 'Password123#',
            'confirmed' => 'Password123#',
        ];

        Http::fake([
            '*' => Http::response(['status' => 'success'], 200)
        ]);

        $response = $this->post(route('users.store'), $data);

        $response->assertRedirect(route('users.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', 'User has been Added Successfully');
    }

    public function testShow()
    {
        session(['user' => ['id'  => 1801,   "email" => "mp.prasetio@gmail.com",
        'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLXNwb3J0LWV2ZW50cy5waHA2LTAyLnRlc3Qudm94dGVuZW8uY29tXC9hcGlcL3YxXC91c2Vyc1wvbG9naW4iLCJpYXQiOjE2ODM0MTkwMDYsImV4cCI6MTY4MzUwNTQwNiwibmJmIjoxNjgzNDE5MDA2LCJqdGkiOiJ4WXVVRWhTQm1QVG9NS2ptIiwic3ViIjoxODAxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.odn3uqp2gWxhYzQiOadAfXLtB0riRxXcSCRXvc-L-z0']]);

        $id = 1801;
        $firstName = $this->faker->firstName;
        $lastName = $this->faker->lastName;
        $email = $this->faker->email;
        Http::fake([
            '*' => Http::response(['id'=> $id, 'firstName' => $firstName, 'lastName' => $lastName, 'email' => $email], 200)
        ]);

        $response = $this->get(route('users.show', $id));

        $response->assertStatus(200);
        $response->assertViewIs('page.content.edit');
        $response->assertViewHas('model', ['id'=> $id, 'firstName' => $firstName, 'lastName' => $lastName, 'email' => $email]);
        $response->assertViewHas('contents');
    }
}