<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Staff;


class UpdateStaffRequeat extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
      
        $userId = $this->route('user'); // this is the user ID
        $staff = Staff::where('user_id', $userId)->first(); // get staffdetails row
        $staffId = $staff?->id ?? null;
        $rules = [
            'first_name' => 'required',
            // 'email' => [
            //         'required',
            //         'email',
            //         Rule::unique('staffdetails')->ignore($staffId, 'user_id'),
            //     ],
          'password' => 'required|min:6',
            'mobile' => 'required|digits:10',
            'alternate_mobile' => 'required|digits:10',
            'gender' => 'required',
            'marital_status' => 'required',
            'employee_category' => 'required',
            'aadharnumber' => 'required',
            'pannumber' => 'required',
            'dob' => 'required',
            'role_id' => 'required',



            
        ];
                // Conditional validation: if role_id is 3, bank or upi is required
                if ($this->input('role_id') == 3 || $this->input('role_id') === '3') {
                    $rules['bankname'] = 'required_without:upi_id|max:255';
                    $rules['accountnumber'] = 'required_without:upi_id|max:30';
                    $rules['ifsccode'] = 'required_without:upi_id|max:15';
                    $rules['bankaddress'] = 'required_without:upi_id|max:500';
                    $rules['upi_id'] = 'required_without_all:bankname,accountnumber,ifsccode,bankaddress|max:255';
                }

                return $rules;
    }
    public function messages(): array
    {
        return [
            'bankname.required_without' => 'Bank Name is required if UPI ID is not provided.',
            'accountnumber.required_without' => 'Account Number is required if UPI ID is not provided.',
            'ifsccode.required_without' => 'IFSC Code is required if UPI ID is not provided.',
            'bankaddress.required_without' => 'Bank Address is required if UPI ID is not provided.',
            'upi_id.required_without_all' => 'UPI ID is required if Bank details are not provided.',
        ];
    }
}
