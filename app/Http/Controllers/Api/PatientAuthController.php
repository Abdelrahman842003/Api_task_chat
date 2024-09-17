<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\PatientRequest;
    use App\Http\Traits\ApiResponseTrait;
    use App\Models\Patient;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;

    class PatientAuthController extends Controller
    {
        use ApiResponseTrait;

        public function __construct()
        {
            $this->middleware('auth:admin'); // Ensure the user is an authenticated admin
        }


        public function register(Request $request)
        {
            $validation = Validator::make($request->all(), [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:admins'],
                'password' => ['required', 'min:6'],
            ]);

            if ($validation->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validation->errors());
            }

            Patient::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return $this->apiResponse(200, 'Created');
        }


        public function login(Request $request)
        {

            $validation = Validator::make($request->all(), [
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'min:6'],
            ]);
            if ($validation->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validation->errors());
            }

            $adminData = $request->only(['email', 'password']);
            if ($token = auth()->guard('admin')->attempt($adminData)) {
                return $this->respondWithToken($token);
            }

            return $this->apiResponse(400, 'not found', $validation->errors());


        }

        protected function respondWithToken($token)
        {
            $array = [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ];
            return $this->apiResponse(200, 'login', null, $array);
        }

        public function index()
        {
            $patients = Patient::get();
            return $this->apiResponse(200, 'Successfully', null, $patients);
        }

        public function store(PatientRequest $request)
        {
            $validator = Validator::make($request->all(), $request->rules());

            if ($validator->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validator->errors());
            }

            $patient = Patient::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone_number' => $request->phone_number,
                'identification_number' => $request->identification_number,
                'address' => $request->address,
                'age' => $request->age,
                'medical_history' => $request->medical_history,
                'allergies' => $request->allergies,
                'medications' => $request->medications,
            ]);

            return $this->apiResponse(200, 'Created', null, $patient);
        }

        public function show($id)
        {
            $patient = Patient::findOrFail($id);
            $patient->where('id', $id)->get();

            return $this->apiResponse(200, 'Successfully', null, $patient);
        }

        public function update(PatientRequest $request, $id)
        {
            $validator = Validator::make($request->all(), $request->rules());

            if ($validator->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validator->errors());
            }

            $patient = Patient::findOrFail($id);
            $patient->update($request->only([
                'phone_number',
                'identification_number',
                'address',
                'age',
                'medical_history',
                'allergies',
                'medications',
            ]));

            return $this->apiResponse(200, 'Updated', null, $patient);
        }

        public function destroy($id)
        {
            $patient = Patient::findOrFail($id);
            $patient->delete();

            return $this->apiResponse(200, 'Deleted');
        }
    }
