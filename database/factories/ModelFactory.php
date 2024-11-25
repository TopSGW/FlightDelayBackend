<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

use App\Airline;
use App\Airport;
use App\Claim;
use App\ClaimFlight;
use App\ClaimType;
use App\Continent;
use App\Country;
use App\Delay;
use App\FlightRoutes;
use App\Salutation;
use App\User;
use Carbon\Carbon;

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(User::class, function (Faker\Generator $fake) {
    static $password;

    return [
        'name' => $fake->name,
        'email' => $fake->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(Continent::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->unique()->randomNumber(),
        'code' => $fake->unique()->randomNumber(2),
        'name' => $fake->name,
        'created_at' => $fake->date('Y-m-d H:i:s'),
    ];
});

$factory->define(Country::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->unique()->randomNumber(),
        'iso_code' => substr($fake->md5(), 0, 2),
        'name' => $fake->name,
        'continent_id' => factory(Continent::class)->create(),
        'source_id' => $fake->randomNumber(),
        'created_at' => $fake->date('Y-m-d H:i:s'),
    ];
});

$factory->define(Airport::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->unique()->randomNumber(),
        'identification' => $fake->randomAscii(),
        'type' => $fake->randomElement(['medium_airport', 'large_airport']),
        'name' => $fake->name,
        'latitude' => $fake->latitude,
        'longitude' => $fake->longitude,
        'continent_id' => $fake->randomNumber(),
        'country_id' => $fake->randomNumber(),
        'region_id' => $fake->randomNumber(),
        'municipality' => $fake->city(),
        'gps_code' => $fake->unique()->randomNumber(4),
        'iata_code' => $fake->unique()->randomNumber(3),
        'source_id' => $fake->randomNumber(),
        'created_at' => $fake->date('Y-m-d H:i:s'),
    ];
});

$factory->define(Airline::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->unique()->randomNumber(),
        'name' => $fake->name,
        'iata_code' => $fake->unique()->randomNumber(2),
        'icao_code' => $fake->unique()->randomNumber(3),
        'call_sign' => $fake->name(),
        'country' => $fake->country,
        'country_id' => $fake->randomNumber(),
        'source_id' => $fake->randomNumber(),
        'created_at' => $fake->date('Y-m-d H:i:s'),
    ];
});

$factory->define(FlightRoutes::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->unique()->randomNumber(),
        'airline_id' => $fake->randomNumber(),
        'source_airport_id' => $fake->randomNumber(),
        'destination_airport_id' => $fake->randomNumber(),
        'code_share' => $fake->text(255),
        'stops' => $fake->randomElement([0, 1]),
        'equipment' => $fake->text(255),
        'source_airline_id' => $fake->randomNumber(),
        'source_source_airport_id' => $fake->randomNumber(),
        'source_destination_airport_id' => $fake->randomNumber(),
        'created_at' => $fake->date('Y-m-d H:i:s'),
    ];
});

$factory->define(ClaimType::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->randomElement([1, 2, 3]),
        'translation_code' => $fake->word(),
        'name' => $fake->sentence(),
        'created_at' => $fake->date('Y-m-d H:i:s'),
    ];
});

$factory->define(Delay::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->randomElement([1, 2, 3]),
        'translation_code' => $fake->word(),
        'name' => $fake->sentence(),
        'created_at' => $fake->date('Y-m-d H:i:s'),
    ];
});

$factory->define(Claim::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->unique()->randomNumber(),
        'claim_type_id' => $fake->randomElement([1, 2, 3]),
        'source' => $fake->text(255),
        'file_number' => $fake->text(255),
        'remarks' => $fake->text(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->define(Salutation::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->randomElement([1, 2]),
        'translation_code' => $fake->word(),
        'name' => $fake->sentence(),
        'created_at' => $fake->date('Y-m-d H:i:s'),
    ];
});

$factory->define(Claim::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->unique()->randomNumber(),
        'claim_type_id' => $fake->randomElement([1, 2, 3]),
        'remarks' => $fake->sentence(),
        'source' => $fake->text(),
        'file_number' => $fake->text(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->define(\App\Complainant::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->unique()->randomNumber(),
        'claim_id' => factory(Claim::class)->create()->id,
        'country_id' => factory(Country::class)->create()->id,
        'salutation_id' => $fake->randomElement([1, 2]),
        'language' => substr($fake->text(), 0, 2),
        'first_name' => $fake->firstName(),
        'last_name' => $fake->lastName(),
        'postal_code' => $fake->postcode(),
        'city' => $fake->city(),
        'street' => $fake->streetName(),
        'house_number' => $fake->numberBetween(1, 99999),
        'box_number' => $fake->text(255),
        'email' => $fake->email(),
        'phone_number' => $fake->phoneNumber(),
        'remarks' => $fake->text(),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});

$factory->define(ClaimFlight::class, function (Faker\Generator $fake) {
    return [
        'id' => $fake->unique()->randomNumber(),
        'claim_id' => factory(Claim::class)->create()->id,
        'departure_airport_id' => factory(Airport::class)->create()->id,
        'destination_airport_id' => factory(Airport::class)->create()->id,
        'airline_id' => factory(Airline::class)->create()->id,
        'delay_id' => $fake->randomElement([1, 2, 3]),
        'flight_number_is_known' => $fake->boolean(),
        'flight_number' => substr($fake->uuid(), 0, 10),
        'flight_date' => $fake->date('Y-m-d H:i:s'),
        'is_initial_flight' => $fake->boolean(80),
        'flight_order' => $fake->randomNumber(1),
        'created_at' => Carbon::now(),
        'updated_at' => Carbon::now(),
    ];
});