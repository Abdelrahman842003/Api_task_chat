<?php

    namespace App\Models;

    use Illuminate\Database\Eloquent\Model;

    class Message extends Model
    {
        protected $fillable = [
            'doctor_id', 'patient_id', 'message', 'sender_type',
        ];

        // علاقة مع نموذج Doctor
        public function doctor()
        {
            return $this->belongsTo(Doctor::class);
        }

        // علاقة مع نموذج Patient
        public function patient()
        {
            return $this->belongsTo(Patient::class);
        }
    }
