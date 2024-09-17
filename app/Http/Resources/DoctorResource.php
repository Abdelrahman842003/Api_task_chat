<?php
    namespace App\Http\Resources;

    use Illuminate\Http\Resources\Json\JsonResource;

    class DoctorResource extends JsonResource
    {
        public function toArray($request)
        {
            return [
                'id' => $this->id,
                'name' => $this->name,
                'email' => $this->email,
                'specialization' => $this->specialization,
                'department' => $this->department,
                'years_of_experience' => $this->years_of_experience,
                'university' => $this->university,
                'cv' => $this->cv,
                'phone_number' => $this->phone_number,
                'identification_number' => $this->identification_number,
                'address' => $this->address,
                'age' => $this->age,
                'appointments' => AppointmentResource::collection($this->appointments), // إضافة بيانات علاقات
            ];
        }
    }

