<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::resource('airports', 'AirportsController', ['only' => 'index']);
    Route::resource('countries', 'CountriesController', ['only' => 'index']);
    Route::resource('claims', 'ClaimsController');
    Route::resource('claim-types', 'ClaimTypesController', ['only' => 'index']);
    Route::resource('delays', 'DelaysController', ['only' => 'index']);
    Route::resource('contact-request', 'ContactRequestController', ['only' => 'store']);
    Route::resource('salutations', 'SalutationsController', ['only' => 'index']);

    Route::resource('airport-municipalities', 'AirportMunicipalitiesController', ['only' => 'index']);

    Route::get('destination-airports/{departureAirportId}', 'DestinationAirportsController');
    Route::get('airlines-for-flight-route/{departureAirportId}/{destinationAirportId}',
        'AirlinesForFlightRouteController');


    Route::get('user', function (Request $request) {
        return $request->user();
    });

    Route::get('mail/send-claim-registered-external-mail', 'MailController@sendClaimRegisteredExternalMail');
    Route::get('mail/send-claim-registered-internal-mail', 'MailController@sendClaimRegisteredInternalMail');
    Route::get('mail/send-contact-request-mail', 'MailController@sendContactRequestMail');

    Route::get('export/claims', 'ExportController@claims');
});
