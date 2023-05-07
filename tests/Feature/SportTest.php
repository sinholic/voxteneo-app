<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SportTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function testIndex()
    {
        session(['user' => ['id'  => 1801,   "email" => "mp.prasetio@gmail.com",
        'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLXNwb3J0LWV2ZW50cy5waHA2LTAyLnRlc3Qudm94dGVuZW8uY29tXC9hcGlcL3YxXC91c2Vyc1wvbG9naW4iLCJpYXQiOjE2ODM0MTkwMDYsImV4cCI6MTY4MzUwNTQwNiwibmJmIjoxNjgzNDE5MDA2LCJqdGkiOiJ4WXVVRWhTQm1QVG9NS2ptIiwic3ViIjoxODAxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.odn3uqp2gWxhYzQiOadAfXLtB0riRxXcSCRXvc-L-z0']]);

        $response = $this->get(route('sports.index'));

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

        $response = $this->get(route('sports.create'));

        $response->assertStatus(200);
        $response->assertViewIs('page.content.add');
        $response->assertViewHas('contents');
    }

    public function testStore()
    {
        session(['user' => ['id'  => 1801,   "email" => "mp.prasetio@gmail.com",
        'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLXNwb3J0LWV2ZW50cy5waHA2LTAyLnRlc3Qudm94dGVuZW8uY29tXC9hcGlcL3YxXC91c2Vyc1wvbG9naW4iLCJpYXQiOjE2ODM0MTkwMDYsImV4cCI6MTY4MzUwNTQwNiwibmJmIjoxNjgzNDE5MDA2LCJqdGkiOiJ4WXVVRWhTQm1QVG9NS2ptIiwic3ViIjoxODAxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.odn3uqp2gWxhYzQiOadAfXLtB0riRxXcSCRXvc-L-z0']]);

        $data = [
            'eventDate' => $this->faker->date,
            'eventType' => $this->faker->lastName,
            'eventName' => $this->faker->safeEmail,
            'organizerId' => 202,
        ];

        Http::fake([
            '*' => Http::response(['status' => 'success'], 200)
        ]);

        $response = $this->post(route('sports.store'), $data);

        $response->assertRedirect(route('sports.index'));
        $response->assertSessionHasNoErrors();
        $response->assertSessionHas('success', 'Sport has been Added Successfully');
    }

    public function testShow()
    {
        session(['user' => ['id'  => 1801,   "email" => "mp.prasetio@gmail.com",
        'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvYXBpLXNwb3J0LWV2ZW50cy5waHA2LTAyLnRlc3Qudm94dGVuZW8uY29tXC9hcGlcL3YxXC91c2Vyc1wvbG9naW4iLCJpYXQiOjE2ODM0MTkwMDYsImV4cCI6MTY4MzUwNTQwNiwibmJmIjoxNjgzNDE5MDA2LCJqdGkiOiJ4WXVVRWhTQm1QVG9NS2ptIiwic3ViIjoxODAxLCJwcnYiOiI4N2UwYWYxZWY5ZmQxNTgxMmZkZWM5NzE1M2ExNGUwYjA0NzU0NmFhIn0.odn3uqp2gWxhYzQiOadAfXLtB0riRxXcSCRXvc-L-z0']]);

        $id = 1171;
        $eventDate = $this->faker->date;
        $eventName = $this->faker->word;
        $eventType = $this->faker->word;
        Http::fake([
            '*' => Http::response(['id'=> $id,'eventDate' => $eventDate, 'eventName' => $eventName, 'eventType' => $eventType, 'organizer' => [
                "id"=> 453,
                "imageLocation"=> "https://img.passeportsante.net/1200x675/2022-09-08/shutterstock-625958894.webp",
                "organizerName"=> "OvaliOrg2"
            ]], 200)
        ]);

        $response = $this->get(route('sports.show', $id));
        $response->assertStatus(200);
        $response->assertViewIs('page.content.edit');
        $response->assertViewHas('model', ['id'=> $id,'eventDate' => $eventDate, 'eventName' => $eventName, 'eventType' => $eventType, 'organizer' => [
            "id"=> 453,
            "imageLocation"=> "https://img.passeportsante.net/1200x675/2022-09-08/shutterstock-625958894.webp",
            "organizerName"=> "OvaliOrg2"
        ]]);
        $response->assertViewHas('contents');
    }
}
