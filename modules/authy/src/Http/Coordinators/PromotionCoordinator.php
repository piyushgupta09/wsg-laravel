<?php

namespace Fpaipl\Authy\Http\Coordinators;

use Illuminate\Http\Request;
use Fpaipl\Panel\Http\Responses\ApiResponse;
use Fpaipl\Panel\Http\Coordinators\Coordinator;
use Fpaipl\Authy\Http\Resources\NotificationResource;

class PromotionCoordinator extends Coordinator
{
    public function index(Request $request)
    {
        // Example data, you might want to fetch this from your database
        $promotions = [
            [
                'id' => 1,
                'title' => 'Promotion 1',
                'description' => 'This is the first promotion',
                'image' => 'https://via.placeholder.com/150',
                'status' => 'active',
                'details' => [
                    "Detail 1 for Promotion 1",
                    "Detail 2 for Promotion 1",
                ],
                'conditions' => [
                    "Condition 1 for Promotion 1",
                    "Condition 2 for Promotion 1",
                ],
            ],
            [
                'id' => 2,
                'title' => 'Promotion 2',
                'description' => 'This is the second promotion',
                'image' => 'https://via.placeholder.com/150',
                'status' => 'active',
                'details' => [
                    "Detail 1 for Promotion 2",
                    "Detail 2 for Promotion 2",
                ],
                'conditions' => [
                    "Condition 1 for Promotion 2",
                    "Condition 2 for Promotion 2",
                ],
            ],
        ];

        return ApiResponse::success($promotions);
    }
}
