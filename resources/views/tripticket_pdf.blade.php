<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Trip Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles (if needed) */
        body {
            background-color: #f4f4f4;
            margin-top: 0;
            position: 0;
        }

        .mb-0 {
            margin-bottom: 0 !important;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
    </style>

    </style>
</head>

<body>
    <div class="max-w-2xl mx-auto bg-white shadow-md rounded">
        <header class="text-center">
            <img src="images/sclogo.png" alt="Republic of the Philippines Logo"
                style="width: 50px; height: 50px; display: block; margin: 0 auto;">
            <h6 class="text-xs font-extralight" style="margin-top: 0;">Republic of the Philippines</h6>
            <h6 class="text-xs font-extralight">Province of South Cotabato</h6>
            <h6 class="text-xs font-extralight">OFFICE OF THE PROVINCIAL DISASTER RISK REDUCTION AND MANAGEMENT OFFICER
            </h6>
            <h6 class="text-xs font-extralight">City of Koronadal</h6>

            <h6 class="text-xs font-extralight">Driver's Trip Ticket No.
                <input type="text" name="TripTicketNumber" id="TripTicketNumber"
                    value="{{ old('TripTicketNumber', $tripTicket->TripTicketNumber ?? '') }}">
            </h6>

            <h6 class="text-xs font-extralight">Date:
                <input type="date" name="ArrivalDate" id="ArrivalDate" value="{{ old('ArrivalDate') }}">
                <div class="mb-0">
                    <label for="ReturnDate" class="block font-semibold mb-1"></label>
                    <input type="date" name="ReturnDate" id="ReturnDate" value="{{ old('ReturnDate') }}">
                </div>
            </h6>
        </header>

        <form action="{{ route('tripTickets.store') }}" method="POST">
            @csrf
            <section class="mb-0">
                <h4 class="text-xs font-extralight">A. To be filled out by the Officer authorizing the travel</h4>
                <div class="mb-1">
                    <label for="personnels_id" class="block text-xs font-extralight mr-2">1. Name of driver of
                        Vehicle:</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">2. Vehicle used and plate
                        number:</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">3. Name of authorized
                        passenger:</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">4. Place to be visited:</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">5. Purpose:</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                    <br>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">6. Km reading before
                        travel:</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">7. Km reading after
                        travel:</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
            </section>
            <br>

            <section class="mb-0">
                <div class="mb-0" style="padding-left: 80px;">
                    <label for="vehicles_id" class="block text-xs font-extralight">Approved:</label>
                </div>
                <br>
                <div class="mb-0" style="padding-left: 160px;">
                    <label for="vehicles_id" class="block text-xs font-thin">ROLLY DOANE C. AQUINO, RN, MPA,
                        MMNSA</label>
                </div>
                <div class="mb-0" style="padding-left: 160px;">
                    <label for="vehicles_id" class="block text-xs font-thin">LDRRMO</label>
                </div>
                <br>
            </section>

            <section class="mb-0">
                <h4 class="text-xs font-extralight">B. To be filled out by the Driver</h4>
                <div class="mb-1">
                    <label for="personnels_id" class="block text-xs font-extralight">1. Time of departure from the
                        office/garage</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">2. Time of arrival at (per item
                        A.4)</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">3. Time of departure from (per item
                        A.4) </label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">4. Time of arrival back to
                        office/garage</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">5. Approximate distance travelled
                        (to&from)</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 290px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">6. GOL issued, purchased and
                        used</label>
                </div>
            </section>

            <section class="mb-0" style="padding-left: 60px;">
                <div class="mb-1">
                    <label for="personnels_id" class="block text-xs font-extralight">1. Balance in tank</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 250px; height: 1px;"></div>

                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">2. Issued by office from
                        stock</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 250px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">3. Added Purchases during the
                        trip</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 250px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">Total</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 250px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">4. GOL used during the
                        trip(to&from)</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 250px; height: 1px;"></div>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">5. Balance in tank, end of
                        trip</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 250px; height: 1px;"></div>
                </div>
            </section>

            <section class="mb-0">
                <div class="mb-1">
                    <label for="personnels_id" class="block text-xs font-extralight">7. Oil
                        issued/purchased/consumed</label>
                </div>
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight">8. Others</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 68px; height: 1px;"></div>
                </div>
                <div class="mb-1" style="padding-left: 12px;">
                    <label for="vehicles_id" class="block text-xs font-extralight">Remarks</label>
                    <div style="flex-grow: 1; border-bottom: 1px solid black; margin-left: 56px; height: 1px;"></div>
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
                        style="padding-left: 350px;">_________________________________</label>
                    <label for="vehicles_id" class="block text-xs font-extralight"
                        style="padding-left: 430px;">Driver</label>
                </div>
            </section>

            <section class="mb-0">
                <div class="mb-1">
                    <label for="vehicles_id" class="block text-xs font-extralight"><i>I hereby certifyto that I/We used
                            this vehicle on official business as stated above:</i></label>
                </div>
            </section>

            <div class="max-w-2xl mx-auto bg-white shadow-md rounded p-1 text-align: left;">
                <section class="mb-0">
                    <div class="mb-1 flex">
                        <label for="personnels_id" class="block text-xs font-extralight"
                            style="padding-left: 150px;">Passenger/s</label>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                    </div>
                </section>
            </div>

            <div class="max-w-2xl mx-auto bg-white shadow-md rounded p-1 text-align: right;">
                <section class="mb-0">
                    <div class="mb-1 flex">
                        <label for="personnels_id" class="block text-xs font-extralight"
                            style="padding-left: 150px;">Signature</label>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                        <br>
                        <div style="flex-grow: 1; border-bottom: 1px solid black; margin-right: 300px; height: 1px;">
                        </div>
                    </div>
                </section>
            </div>
        </form>
    </div>
</body>

</html>