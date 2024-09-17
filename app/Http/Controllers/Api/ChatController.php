<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Http\Resources\MessageResource;
    use App\Models\Message;
    use App\Models\Doctor;
    use App\Models\Patient;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Broadcast;
    use Illuminate\Support\Facades\Validator;
    use App\Events\MessageSent;

    class ChatController extends Controller
    {
        public function sendMessage(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'doctor_id' => 'required|exists:doctors,id',
                'patient_id' => 'required|exists:patients,id',
                'message' => 'required|string',
                'sender_type' => 'required|in:doctor,patient',
            ]);

            if ($validator->fails()) {
                return response()->json(['error' => 'Validation Error', 'details' => $validator->errors()], 400);
            }

            try {
                $message = Message::create([
                    'doctor_id' => $request->doctor_id,
                    'patient_id' => $request->patient_id,
                    'message' => $request->message,
                    'sender_type' => $request->sender_type,
                ]);

                Broadcast::channel('chat.' . $request->doctor_id . '.' . $request->patient_id, function ($user) {
                    return Auth::check();
                });

                broadcast(new MessageSent($message))->toOthers();

                return response()->json(['message' => new MessageResource($message)], 200);
            } catch (\Exception $e) {
                return response()->json(['error' => 'Failed to send message'], 500);
            }
        }

        public function fetchMessages($doctor_id, $patient_id)
        {
            if (!Doctor::find($doctor_id) || !Patient::find($patient_id)) {
                return response()->json(['error' => 'Invalid doctor or patient ID'], 400);
            }

            $messages = Message::with(['doctor', 'patient'])
                ->where('doctor_id', $doctor_id)
                ->where('patient_id', $patient_id)
                ->orderBy('created_at', 'asc')
                ->paginate(20);

            return MessageResource::collection($messages);
        }
    }
