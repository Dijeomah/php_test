<?php

    namespace Tests\Feature;

    use App\Mail\ContactForm;
    use App\Models\Contact;
    use App\Models\User;
    use Faker\Factory;
    use Illuminate\Foundation\Testing\DatabaseMigrations;
    use Illuminate\Foundation\Testing\RefreshDatabase;
    use Illuminate\Foundation\Testing\WithFaker;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Mail;
    use Tests\TestCase;

    class ContactTest extends TestCase
    {

        /**
         * A basic feature test example.
         *
         * @return void
         */
        public function test_example()
        {
            $response = $this->get('/');

            $response->assertStatus(200);
        }

        public function test_contact_form()
        {
            $this->withoutExceptionHandling();
            $faker = Factory::create();

            $response = $this->post('api/contact/create', [
                'name' => $faker->name,
                'email' => $faker->unique()->email,
//                'attachment' => null,
//                'attachment' => $faker->image('/uploads/contact_att/1676851000_naruto.png'),
                'message' => $faker->text
            ]);

            Mail::to(\env('MAIL_TO_ADDRESS'))->send(new ContactForm($response));
            $response->assertStatus(200);
            $this->assertTrue(true);
        }
    }
