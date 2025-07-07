<?php

namespace Botble\JobBoard\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AccountsTemplateExport implements FromCollection, WithHeadings
{
    use Exportable;

    public function rules(): array
    {
        return [
            'first_name' => 'required|max:120|min:2',
            'last_name' => 'required|max:120|min:2',
            'description' => 'nullable|string',
            'gender' => 'nullable|string[male,female,other]',
            'email' => 'required|max:60|min:6|email|unique:jb_accounts',
            'password' => 'nullable|min:6[Leave empty to use random password]',
            'dob' => 'nullable|date',
            'phone' => 'nullable|max:20|min:6',
            'address' => 'nullable|max:255',
            'type' => 'required|string|in:job-seeker,employer',
            'bio' => 'nullable|string',
            'resume' => 'nullable|string|url',
            'cover_letter' => 'nullable|string|url',
            'is_public_profile' => 'nullable|boolean[Yes,No]',
            'is_featured' => 'nullable|boolean[Yes,No]',
            'available_for_hiring' => 'nullable|boolean[Yes,No]',
            'country_id' => 'nullable|string',
            'state_id' => 'nullable|string',
            'city_id' => 'nullable|string',
        ];
    }

    public function collection(): Collection
    {
        $candidates = [
            [
                'first_name' => 'Bella',
                'last_name' => 'Shanahan',
                'description' => 'Ad nisi repudiandae consequatur quod esse.',
                'gender' => 'male',
                'email' => 'benjamin44@example.net',
                'password' => '',
                'dob' => '1965-05-20',
                'phone' => '',
                'address' => '66830 Wilford Mountain',
                'bio' => "Hatter opened his eyes. 'I wasn't asleep,' he said to itself in a whisper, half afraid that it was an old Turtle--we used to say.' 'So he did, so he with his knuckles. It was the Hatter. 'You might.",
                'type' => 'job-seeker',
                'resume' => '',
                'cover_letter' => '',
                'is_public_profile' => 'Yes',
                'is_featured' => 'No',
                'available_for_hiring' => 'Yes',
                'country_id' => 'Canada',
                'state_id' => 'Ontario',
                'city_id' => 'Toronto',
            ],
            [
                'first_name' => 'Everette',
                'last_name' => 'Bailey',
                'description' => '',
                'gender' => 'female',
                'email' => 'wmorissette@example.com',
                'password' => '',
                'dob' => '2003-04-24',
                'phone' => '1-800-555-1234',
                'address' => 'Hoegerfurt, MD 49478-7859',
                'bio' => '',
                'type' => 'employer',
                'resume' => '',
                'cover_letter' => '',
                'is_public_profile' => 'Yes',
                'is_featured' => 'No',
                'available_for_hiring' => 'Yes',
            ],
            [
                'first_name' => 'Frances',
                'last_name' => 'Orn',
                'description' => 'Et aut est corporis officiis enim natus qui reiciendis',
                'gender' => 'other',
                'email' => 'saige05@example.com',
                'password' => '12345678',
                'dob' => '',
                'phone' => '',
                'address' => '98499 Yesenia Shoals Suite 732',
                'bio' => '',
                'type' => 'job-seeker',
                'resume' => '',
                'cover_letter' => '',
                'is_public_profile' => 'No',
                'is_featured' => 'No',
                'available_for_hiring' => 'Yes',
                'country_id' => 'United States',
                'state_id' => 'California',
                'city_id' => 'Los Angeles',
            ],
        ];

        return new Collection($candidates);
    }

    public function headings(): array
    {
        return [
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'description' => 'Description',
            'gender' => 'Gender',
            'email' => 'Email',
            'password' => 'Password',
            'dob' => 'Date of Birth',
            'phone' => 'Phone',
            'address' => 'Address',
            'bio' => 'Bio',
            'type' => 'Type',
            'resume' => 'Resume',
            'cover_letter' => 'Cover Letter',
            'is_public_profile' => 'Is public profile?',
            'is_featured' => 'Is featured?',
            'available_for_hiring' => 'Available for hiring?',
            'country_id' => 'Country',
            'state_id' => 'State',
            'city_id' => 'City',
        ];
    }
}
