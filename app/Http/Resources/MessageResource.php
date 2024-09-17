<?php

// MessageResource.php
    namespace App\Http\Resources;

    use Illuminate\Http\Resources\Json\JsonResource;

    class MessageResource extends JsonResource
    {
        public function toArray($request)
        {
            return [
                'message' => $this->message,
                'sender_type' => $this->sender_type,
                'created_at' => $this->created_at->toDateTimeString(),
                'doctor' => $this->when($this->sender_type === 'doctor', new DoctorResource($this->doctor)),
                'patient' => $this->when($this->sender_type === 'patient', new PatientResource($this->patient)),
            ];
        }
    }
