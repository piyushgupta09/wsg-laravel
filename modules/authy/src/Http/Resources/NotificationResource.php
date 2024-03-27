<?php

namespace Fpaipl\Authy\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class NotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->data['subject'] ?? 'Notification',
            'type' => $this->getNotificationType(),
            'data' => $this->data['data'] ?? null,
            'id' => $this->id,
            'sid' => $this->getMySid($this->data),
            'created_at' => $this->created_at->diffForHumans(),
            'read_at' => $this->read_at ? $this->read_at->diffForHumans() : 'unread',
        ];
    }

    private function getMySid($data): ?string
    {
        if (!isset($data['data']) || !isset($data['data']['id'])) {
            Log::error("Model ID or Model Type not found in notification data.");
            return null;
        }

        $modelId = $data['data']['id'];

        $modelName = $this->getNotificationType();
        $modelClass = 'Fpaipl\\Shopy\\Models\\' . ucfirst($modelName);
    
        if (!class_exists($modelClass)) {
            Log::error("Model class {$modelClass} does not exist.");
            return null;
        }
    
        $model = $modelClass::where('id', $modelId)->first();
    
        if (!$model) {
            Log::error("No model found with ID {$modelId}.");
            return null;
        }
    
        if (!method_exists($model, 'getSid')) {
            Log::error("Method getSid does not exist on model {$modelClass}.");
            return null;
        }
    
        return $model->getSid();
    }    

    private function getNotificationType(): string
    {
        switch ($this->type) {
            case 'Fpaipl\\Shopy\\Notifications\\SendOrderNotification': return 'order';
            case 'Fpaipl\\Shopy\\Notifications\\SendPaymentNotification': return 'payment';
            case 'Fpaipl\\Shopy\\Notifications\\SendDeliveryNotification': return 'delivery';
            default: return 'default';
        }
    }
}
