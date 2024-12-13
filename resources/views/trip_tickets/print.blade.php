<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Trip Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* General styles */
        body {
            margin: 0;
            padding: 0;
            font-family: sans-serif;
            background-color: #f4f4f4;
        }

        /* Print styles for A4 */
        @media print {
            @page {
                size: A4;
                margin: 1cm;
            }

            body {
                background-color: white;
                -webkit-print-color-adjust: exact;
            }

            .no-print {
                display: none;
            }

            .print-container {
                width: 100%;
                height: auto;
                margin: 0 auto;
                padding: 0;
                page-break-inside: avoid;
            }

            .custom-divider {
                border-bottom: 1px solid black;
            }

            header,
            section {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body>
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded">
        <header class="text-center">
            <img src="{{ asset('images/sclogo.png') }}" alt="Republic of the Philippines Logo"
                style="width: 50px; height: 50px; display: block; margin: 0 auto;">
            <h6 class="text-xs font-extralight" style="margin-top: 0;">Republic of the Philippines</h6>
            <h6 class="text-xs font-extralight">Province of South Cotabato</h6>
            <h6 class="text-xs font-bold">OFFICE OF THE PROVINCIAL DISASTER RISK REDUCTION AND MANAGEMENT OFFICER
            </h6>
            <h6 class="text-xs font-extralight">City of Koronadal</h6>

            <h6 class="text-xs font-extralight">Driver's Trip Ticket No:
                <b class="font-bold"><u>
                        {{ $tripTicket->TripTicketNumber ?? '' }}
                    </u>
                </b>
            </h6>

            <h6 class="text-xs font-extralight">Date:
                <b class="font-bold"><u>
                        {{ $tripTicket->ArrivalDate ? $tripTicket->ArrivalDate->format('m/d/Y') : 'mm/dd/yyyy' }} -
                        {{ $tripTicket->ReturnDate ? $tripTicket->ReturnDate->format('m/d/Y') : 'mm/dd/yyyy' }}
                    </u>
                </b>
            </h6>

        </header>

        <form action="{{ route('tripTickets.store') }}" method="POST">
            @csrf
            <section class="mb-0">
                <h4 class="text-xs font-extralight">A. To be filled out by the Officer authorizing the travel</h4>

                <!--Driver Name-->
                <div class="mb-2 flex items-center">
                    <label for="personnels_id" class="block text-xs font-extralight mr-2">
                        1. Name of driver of Vehicle:
                    </label>
                    <div class="flex-grow" style="margin-left: 140px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px;">
                            <b class="font-bold">{{ optional($tripTicket->user)->name ?? '' }}
                                <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Vehicle Name-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">2. Vehicle used and plate
                        number:</label>
                    <div class="flex-grow" style="margin-left: 118px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px;">
                            <b class="font-bold">{{ optional($tripTicket->vehicle)->MvfileNo ?? '' }}</b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!-- Display the First Responder -->
                @if($tripTicket->responders && count($tripTicket->responders) > 0)
                    <div class="mb-1 flex items-center">
                        <label for="responders" class="block text-xs font-extralight">3. Name of authorized
                            passenger:</label>
                        <div class="flex-grow ml-8" style="margin-left: 118px;">
                            <span class="text-sm font-extralight relative -top-2">
                                <b class="font-bold">
                                    {{ \App\Models\Personnel::find($tripTicket->responders[0]['responder_id'])->Name }}
                                </b>
                            </span>
                            <div class="border-b border-black mt-0 h-1"></div>
                        </div>
                    </div>
                @endif


                <!--Destination-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">4. Place to be visited:</label>
                    <div class="flex-grow" style="margin-left: 183px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px;">
                            <b class="font-bold">{{ $tripTicket->Destination ?? '' }}</b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Purpose-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">5. Purpose:</label>
                    <div class="flex-grow" style="margin-left: 233px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px;">
                            <b class="font-bold">{{ $tripTicket->Purpose ?? '' }}</b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--KM Before Travel-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">6. Km reading before
                        travel:</label>
                    <div class="flex-grow" style="margin-left: 150px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b class="font-bold">{{ number_format($tripTicket->KmBeforeTravel ?? '') }} Km
                            </b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--KM After Travel-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">7. Km reading after
                        travel:</label>
                    <div class="flex-grow" style="margin-left: 160px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b class="font-bold">{{ number_format($tripTicket->KmAfterTravel) }} Km </b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>
            </section>
            <br>

            <section class="mb-0">
                <div class="mb-0" style="padding-left: 80px;">
                    <label for="vehicles_id" class="block text-xs font-extralight">Approved:</label>
                </div>
                <br>
                <div class="mb-0" style="padding-left: 160px;">
                    <label for="vehicles_id" class="block text-xs font-bold">ROLLY DOANE C. AQUINO, RN, MPA,
                        MMNSA</label>
                </div>
                <div class="mb-0" style="padding-left: 160px;">
                    <label for="vehicles_id" class="block text-xs font-thin">Local Disaster Risk Reduction and
                        Management Officer</label>
                </div>
                <br>
            </section>

            <section class="mb-0">
                <h4 class="text-xs font-extralight">B. To be filled out by the Driver</h4>

                <!--Time Departure A-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">1. Time of departure from the
                        office/garage:</label>
                    <div class="flex-grow" style="margin-left: 70px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b
                                class="font-bold">{{ isset($tripTicket->TimeDeparture_A) ? \Carbon\Carbon::parse($tripTicket->TimeDeparture_A)->format('h:i A') : '' }}</b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Time Arrival A-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">2. Time of arrival at (per item
                        A.4):</label>
                    <div class="flex-grow" style="margin-left: 120px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b
                                class="font-bold">{{ isset($tripTicket->TimeArrival_A) ? \Carbon\Carbon::parse($tripTicket->TimeArrival_A)->format('h:i A') : '' }}</b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Time Departure B-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">3. Time of departure from (per item
                        A.4):</label>
                    <div class="flex-grow" style="margin-left: 85px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b
                                class="font-bold">{{ isset($tripTicket->TimeDeparture_B) ? \Carbon\Carbon::parse($tripTicket->TimeDeparture_B)->format('h:i A') : '' }}</b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Time Arrival B-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">4. Time of arrival back to
                        office/garage:</label>
                    <div class="flex-grow" style="margin-left: 93px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b
                                class="font-bold">{{ isset($tripTicket->TimeArrival_B) ? \Carbon\Carbon::parse($tripTicket->TimeArrival_B)->format('h:i A') : '' }}</b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Distance Traveled-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">5. Approximate distance travelled
                        (to&from):</label>
                    <div class="flex-grow" style="margin-left: 68px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b class="font-bold"> {{ $tripTicket->KmAfterTravel && $tripTicket->KmBeforeTravel ?
    number_format($tripTicket->KmAfterTravel - $tripTicket->KmBeforeTravel) : '0' }} Km</b></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">6. GOL issued, purchased and
                        used:</label>
                </div>
            </section>

            <section class="mb-0" style="padding-left: 60px;">
                <!--Distance Traveled-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">1. Balance in tank:</label>
                    <div class="flex-grow" style="margin-left: 164px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b class="font-bold">{{ $tripTicket->BalanceStart ?? '' }}&nbsp;</b><i>Liters</i></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Issued From Office-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">2. Issued by office from
                        stock:</label>
                    <div class="flex-grow" style="margin-left: 105px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b
                                class="font-bold">{{ $tripTicket->IssuedFromOffice ?? '0.00' }}&nbsp;</b><i>Liters</i></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Added Purchase-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">3. Added Purchases during the
                        trip:</label>
                    <div class="flex-grow" style="margin-left: 76px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b
                                class="font-bold">{{ number_format($tripTicket->AddedDuringTrip ?? 0, 2) }}&nbsp;</b><i>Liters</i></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Total-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">Total:</label>
                    <div class="flex-grow" style="margin-left: 235px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b
                                class="font-bold">{{ number_format($tripTicket->TotalFuelTank ?? 0, 2) }}&nbsp;</b><i>Liters</i></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Fuel Consumed-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">4. GOL used during the
                        trip(to&from):</label>
                    <div class="flex-grow" style="margin-left: 63px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b
                                class="font-bold">{{ number_format($tripTicket->FuelConsumption ?? 0, 2) }}&nbsp;</b><i>Liters</i></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

                <!--Balance End-->
                <div class="mb-1 flex items-center">
                    <label for="vehicles_id" class="block text-xs font-extralight">5. Balance in tank, end of
                        trip:</label>
                    <div class="flex-grow" style="margin-left: 103px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b
                                class="font-bold">{{ number_format($tripTicket->BalanceEnd ?? 0, 2) }}&nbsp;</b><i>Liters</i></span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>

            </section>

            <section class="mb-0">
                <div class="mb-1">
                    <label for="personnels_id" class="block text-xs font-extralight">7. Oil
                        issued/purchased/consumed</label>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">8. Others</label>
                    <div class="flex-grow" style="margin-left: 70px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b class="font-bold">{{ $tripTicket->Others ?? '' }}</span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>
                <div class="mb-1" style="padding-left: 12px;">
                    <label for="vehicles_id" class="block text-xs font-extralight">Remarks</label>
                    <div class="flex-grow" style="margin-left: 57px;">
                        <span class="text-sm font-extralight" style="position: relative; top: -8px; ">
                            <b class="font-bold">{{ $tripTicket->Remarks ?? '' }}</span>
                        <div style="border-bottom: 1px solid black; margin-top: 0; height: 1px;"></div>
                    </div>
                </div>
                <br>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight"><i>I hereby certify to the
                            correctness
                            of records of travel</i></label>
                </div>
            </section>
            <br>

            <section class="mb-0">
                <div class=" mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight"
                        style="padding-left: 350px;">_______________________________________</label>
                    <label for="vehicles_id" class="block text-xs font-extralight"
                        style="padding-left: 430px;">Driver</label>
                </div>
            </section>

            @if($tripTicket->responders && count($tripTicket->responders) > 0)
                <div class="max-w-2xl mx-auto bg-white shadow-md rounded p-1 text-left">
                    <section class="mb-0">
                        <p class="text-xs font-extralight text-left mb-2">
                            <i>I hereby certify that I/we used this vehicle on official business as stated above:</i>
                        </p>
                        <div class="flex mb-2">
                            <!-- Headers -->
                            <div class="w-1/2 text-xs font-bold text-center">Passenger/s</div>
                            <div class="w-1/2 text-xs font-bold text-center">Signature</div>
                        </div>
                        @foreach(array_slice($tripTicket->responders, 0) as $responder)
                            <div class="flex items-center mb-2">
                                <!-- Left Side: Responder Name -->
                                <div class="w-1/2 text-xs font-bold border-b border-black py-1 text-center px-4">
                                    {{ \App\Models\Personnel::find($responder['responder_id'])->Name }}
                                </div>

                                <!-- Right Side: Signature Line -->
                                <div class="w-1/2 border-b border-black h-6 px-4"></div>
                            </div>
                        @endforeach
                    </section>
                </div>
            @endif
        </form>
        <footer class="text-center mt-4">
            <p class="text-xs font-light">Generated by Trip Ticket System</p>
        </footer>
    </div>
</body>

</html>