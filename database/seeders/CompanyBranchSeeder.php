<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Company;

class CompanyBranchSeeder extends Seeder
{
    public function run()
    {
        // 1 Company
        $company = Company::create([
            'company_name_th' => 'บริษัท ตัวอย่าง จำกัด',
            'company_name_en' => 'Example Company Ltd.',
            'tax_no'          => '1234567890123',
            'company_logo'    => 'iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGMAAQAABQABDQottAAAAABJRU5ErkJggg==',
        ]);

        // 5 Branches
        $branches = [];
        for ($i = 1; $i <= 5; $i++) {
            $branches[] = [
                'branch_code'        => sprintf('BR%03d', $i),
                'name_th'            => "สาขาที่ $i",
                'name_en'            => "Branch $i",
                'address_th'         => "123/{$i} ถนนสุขุมวิท แขวงคลองเตย เขตคลองเตย กรุงเทพมหานคร",
                'address_en'         => "123/{$i} Sukhumvit Rd, Khlong Toei, Bangkok",
                'bill_address_th'    => "456/{$i} ถนนพระรามที่ 9 แขวงสวนหลวง เขตสวนหลวง กรุงเทพมหานคร",
                'bill_address_en'    => "456/{$i} Rama IX Rd, Suan Luang, Bangkok",
                'post_code'          => '10110',
                'phone_country_code' => '+66',
                'phone_number'       => '021234567',
                'fax'                => '021234568',
                'website'            => 'https://example.com',
                'email'              => "branch{$i}@example.com",
                'is_active'          => true,
                'is_head_office'     => ($i === 1),
                'latitude'           => null,
                'longitude'          => null,
                'contact_name'       => "Contact $i",
                'contact_email'      => "contact{$i}@example.com",
                'contact_mobile'     => "081234567{$i}",
            ];
        }

        $company->branches()->createMany($branches);
    }
}