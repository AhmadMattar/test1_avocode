<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\CityTranslation;
use App\Models\Country;
use Illuminate\Database\Seeder;
use App\Models\CountryTranslation;
use App\Models\District;
use App\Models\DistrictTranslation;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class locationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //countries seeder
        $palestine = Country::create([
            'code'      => 972,
            'cover'     => 'test.jpg',
            'status'    => 1,
        ]);
        CountryTranslation::create([
            'country_id'    => $palestine->id,
            'locale'        => 'en',
            'name'          => 'Palestine',
        ]);
        CountryTranslation::create([
            'country_id'    => $palestine->id,
            'locale'        => 'ar',
            'name'          => 'فلسطين',
        ]);

        $saudi = Country::create([
            'code'      => 777,
            'cover'     => 'test.jpg',
            'status'    => 1,
        ]);
        CountryTranslation::create([
            'country_id'    => $saudi->id,
            'locale'        => 'en',
            'name'          => 'Saudi',
        ]);
        CountryTranslation::create([
            'country_id'    => $saudi->id,
            'locale'        => 'ar',
            'name'          => 'السعودية',
        ]);

        $qatar = Country::create([
            'code'      => 888,
            'cover'     => 'test.jpg',
            'status'    => 1,
        ]);
        CountryTranslation::create([
            'country_id'    => $qatar->id,
            'locale'        => 'en',
            'name'          => 'Qatar',
        ]);
        CountryTranslation::create([
            'country_id'    => $qatar->id,
            'locale'        => 'ar',
            'name'          => 'قطر',
        ]);

        //cities seeder
        $gaza = City::create([
            'country_id'    => $palestine->id,
            'status'        => 1,
        ]);

        CityTranslation::create([
            'city_id'       => $gaza->id,
            'locale'        => 'en',
            'name'          => 'Gaza',
        ]);

        CityTranslation::create([
            'city_id'       => $gaza->id,
            'locale'        => 'ar',
            'name'          => 'غزة',
        ]);

        $jerusalem = City::create([
            'country_id'    => $palestine->id,
            'status'        => 1,
        ]);

        CityTranslation::create([
            'city_id'       => $jerusalem->id,
            'locale'        => 'en',
            'name'          => 'Jerusalem',
        ]);

        CityTranslation::create([
            'city_id'       => $jerusalem->id,
            'locale'        => 'ar',
            'name'          => 'القدس',
        ]);

        $mekkah = City::create([
            'country_id'    => $saudi->id,
            'status'        => 1,
        ]);

        CityTranslation::create([
            'city_id'       => $mekkah->id,
            'locale'        => 'en',
            'name'          => 'Mekkah',
        ]);

        CityTranslation::create([
            'city_id'       => $mekkah->id,
            'locale'        => 'ar',
            'name'          => 'مكة المكرمة',
        ]);

        $riyadh = City::create([
            'country_id'    => $saudi->id,
            'status'        => 1,
        ]);

        CityTranslation::create([
            'city_id'       => $riyadh->id,
            'locale'        => 'en',
            'name'          => 'Riyadh',
        ]);

        CityTranslation::create([
            'city_id'       => $riyadh->id,
            'locale'        => 'ar',
            'name'          => 'الرياض',
        ]);


        $ryan = City::create([
            'country_id'    => $qatar->id,
            'status'        => 1,
        ]);

        CityTranslation::create([
            'city_id'       => $ryan->id,
            'locale'        => 'en',
            'name'          => 'Al-Ryan',
        ]);

        CityTranslation::create([
            'city_id'       => $ryan->id,
            'locale'        => 'ar',
            'name'          => 'الريان',
        ]);

        $doha = City::create([
            'country_id'    => $qatar->id,
            'status'        => 1,
        ]);

        CityTranslation::create([
            'city_id'       => $doha->id,
            'locale'        => 'en',
            'name'          => 'Doha',
        ]);

        CityTranslation::create([
            'city_id'       => $doha->id,
            'locale'        => 'ar',
            'name'          => 'الدوحة',
        ]);

        $district1 = District::create([
            'country_id' => $palestine->id,
            'city_id'       => $gaza->id,
        ]);

        DistrictTranslation::create([
            'district_id'   => $district1->id,
            'locale'        => 'en',
            'name'          => 'district1',
        ]);

        DistrictTranslation::create([
            'district_id'   => $district1->id,
            'locale'        => 'ar',
            'name'          => 'حي1',
        ]);

        $district2 = District::create([
            'country_id' => $palestine->id,
            'city_id'       => $jerusalem->id,
        ]);

        DistrictTranslation::create([
            'district_id'   => $district2->id,
            'locale'        => 'en',
            'name'          => 'district2',
        ]);

        DistrictTranslation::create([
            'district_id'   => $district2->id,
            'locale'        => 'ar',
            'name'          => 'حي2',
        ]);

        $district3 = District::create([
            'country_id' => $saudi->id,
            'city_id'       => $mekkah->id,
        ]);

        DistrictTranslation::create([
            'district_id'   => $district3->id,
            'locale'        => 'en',
            'name'          => 'district3',
        ]);

        DistrictTranslation::create([
            'district_id'   => $district3->id,
            'locale'        => 'ar',
            'name'          => 'حي3',
        ]);

        $district4 = District::create([
            'country_id' => $saudi->id,
            'city_id'       => $riyadh->id,
        ]);

        DistrictTranslation::create([
            'district_id'   => $district4->id,
            'locale'        => 'en',
            'name'          => 'district4',
        ]);

        DistrictTranslation::create([
            'district_id'   => $district4->id,
            'locale'        => 'ar',
            'name'          => 'حي4',
        ]);

        $district5 = District::create([
            'country_id' => $qatar->id,
            'city_id'       => $ryan->id,
        ]);

        DistrictTranslation::create([
            'district_id'   => $district5->id,
            'locale'        => 'en',
            'name'          => 'district5',
        ]);

        DistrictTranslation::create([
            'district_id'   => $district5->id,
            'locale'        => 'ar',
            'name'          => 'حي5',
        ]);

        $district6 = District::create([
            'country_id' => $qatar->id,
            'city_id'       => $doha->id,
        ]);

        DistrictTranslation::create([
            'district_id'   => $district6->id,
            'locale'        => 'en',
            'name'          => 'district6',
        ]);

        DistrictTranslation::create([
            'district_id'   => $district6->id,
            'locale'        => 'ar',
            'name'          => 'حي6',
        ]);

    }
}
