<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Tests\TestCase;
use Inertia\Testing\AssertableInertia;
use Illuminate\Support\Facades\Session;

class QuizControllerTest extends TestCase
{
    public function test_it_can_get_quiz_page()
    {
        $this->call('GET', '/quiz')
            ->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Quiz')
                ->has('quizId')
            );
    }

    public function test_it_will_start_session()
    {
        // stop time
        Carbon::setTestNow(now());

        $quizId = 'quiz-' . Carbon::now()->timestamp;

        $this->assertNull(Session::get($quizId));

        $response = $this->call('GET', '/quiz')
            ->assertStatus(200)
            ->assertInertia(fn (AssertableInertia $page) => $page
                ->component('Quiz')
                ->has('quizId')
            );

        $this->assertEquals([], Session::get($quizId));
    }
}
