<?php

namespace Tests\Unit;

use App\Services\KinelaScoringService;
use PHPUnit\Framework\TestCase;

class KinelaScoringServiceTest extends TestCase
{
    private KinelaScoringService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new KinelaScoringService;
    }

    public function test_exact_score_awards_three_points(): void
    {
        $this->assertSame(3, $this->service->calculatePoints(2, 1, 2, 1));
    }

    public function test_correct_winner_awards_one_point(): void
    {
        $this->assertSame(1, $this->service->calculatePoints(3, 0, 2, 1));
    }

    public function test_correct_draw_awards_one_point(): void
    {
        $this->assertSame(1, $this->service->calculatePoints(1, 1, 0, 0));
    }

    public function test_wrong_prediction_awards_zero_points(): void
    {
        $this->assertSame(0, $this->service->calculatePoints(2, 0, 0, 2));
    }
}
