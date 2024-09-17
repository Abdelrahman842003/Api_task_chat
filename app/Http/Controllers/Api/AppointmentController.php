<?php


    use App\Http\Controllers\Controller;
    use App\Http\Traits\ApiResponseTrait;
    use App\Models\Appointments;
    use Illuminate\Http\Request;

    class AppointmentController extends Controller
    {
        use ApiResponseTrait;

        public function store(Request $request)
        {
            $validated = $request->validate([
                'doctor_id' => 'required|exists:doctors,id',
                'appointment_date' => 'required|date',
                'details' => 'nullable|string',
            ]);

            $appointment = Appointments::create($validated);

            return $this->apiResponse(200, 'Created', $appointment);}
    }
