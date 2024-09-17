<?php

    namespace App\Http\Controllers\Api;

    use App\Http\Controllers\Controller;
    use App\Http\Requests\DoctorRequest;
    use App\Http\Traits\ApiResponseTrait;
    use App\Models\Doctor;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;

    class DoctorAuthController extends Controller
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

            Doctor::create([
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
            $doctors = Doctor::get();
            return $this->apiResponse(200, 'Successfully', null, $doctors);
        }

        public function store(DoctorRequest $request)
        {
            $validator = Validator::make($request->all(), $request->rules());

            if ($validator->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validator->errors());
            }

            $doctor = Doctor::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'specialization' => $request->specialization,
                'department' => $request->department,
                'years_of_experience' => $request->years_of_experience,
                'university' => $request->university,
                'cv' => $request->cv,
                'phone_number' => $request->phone_number,
                'identification_number' => $request->identification_number,
                'address' => $request->address,
                'age' => $request->age,
            ]);

            return $this->apiResponse(200, 'Created', null, $doctor);
        }

        public function show($id)
        {
            $doctor = Doctor::findOrFail($id);
            return $this->apiResponse(200, 'Successfully', null, $doctor);
        }

        public function update(DoctorRequest $request, $id)
        {
            $validator = Validator::make($request->all(), $request->rules());

            if ($validator->fails()) {
                return $this->apiResponse(400, 'Validation Error', $validator->errors());
            }

            $doctor = Doctor::findOrFail($id);

            // Check if the user is an admin
            if (Auth::guard('admin')->check()) {
                $doctor->update($request->only([
                    'name', // Make sure to include 'name' if it needs to be updated
                    'specialization',
                    'department',
                    'years_of_experience',
                    'university',
                    'cv',
                    'phone_number',
                    'identification_number',
                    'address',
                    'age',
                ]));

                return $this->apiResponse(200, 'Updated', null, $doctor);
            } else {
                return $this->apiResponse(403, 'Forbidden', 'You do not have permission to update this doctor');
            }
        }

        public function destroy($id)
        {
            $doctor = Doctor::findOrFail($id);

            // Check if the user is an admin
            if (Auth::guard('admin')->check()) {
                $doctor->delete();
                return $this->apiResponse(200, 'Deleted');
            } else {
                return $this->apiResponse(403, 'Forbidden', 'You do not have permission to delete this doctor');
            }
        }
    }
