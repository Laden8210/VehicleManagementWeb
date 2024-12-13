<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FUEL WITHDRAWAL SLIP</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        @media print {

            /* Set the page size to A4 */
            @page {
                size: A4;
                margin: 1cm;
                /* Adjust margins as needed */
            }

            /* Make the body content fit within the page */
            body {
                width: 100%;
                padding: 0;
                margin: 0;
            }

            .print-container {
                width: 100%;
                max-width: 21cm;
                /* A4 width */
                padding: 1cm;
                margin: auto;
            }
        }
    </style>
</head>

<body class="p-8">
    <header class="text-center">
        <img src="{{ asset('images/sclogo.png') }}" alt="Republic of the Philippines Logo"
            style="width: 50px; height: 50px; display: block; margin: 0 auto;">
        <h6 class="text-xs font-extralight" style="margin-top: 0;">Republic of the Philippines</h6>
        <h6 class="text-xs font-extralight">Province of South Cotabato</h6>
        <h6 class="text-xs font-bold">OFFICE OF THE PROVINCIAL DISASTER RISK REDUCTION AND MANAGEMENT OFFICER
        </h6>
        <h6 class="text-xs font-extralight">City of Koronadal</h6>
        <br>
        <h4 class="text-xs font-bold"><b>GASOLINE/DIESOLINE, OIL AND LUBRICANTS</b></h4>
        <h5 class="text-xs font-bold">WITHDRAWAL SLIP NO:&nbsp;<b>{{ $fuelSlips->WithdrawalSlipNo ?? '' }}</b>
        </h5>

    </header>
    <div class="max-w-2xl mx-auto p-4">
        <div class="mb-4">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">TO</th>
                        <th class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">AGREDA SHELL
                            STATION</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">DATE:</td>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">
                            {{ \Carbon\Carbon::parse($fuelSlips->RequestDate)->format('F j, Y') }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">NAME OF
                            AUTHORIZED PERSONNEL:</td>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">
                            {{ optional($fuelSlips->tripTicket->user)->name ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">VEHICLE'S
                            PLATE NUMBER:</td>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">
                            {{ optional($fuelSlips->tripTicket->vehicle)->MvfileNo ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">TYPE/KIND OF
                            FUEL:</td>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">
                            {{ optional($fuelSlips->tripTicket->vehicle)->Fuel ?? '' }}
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">NUMBER OF
                            LITERS:</td>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">
                            {{ $fuelSlips->Quantity ?? '' }}&nbsp;Liters
                        </td>
                    </tr>
                    <tr>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">DELIVERY
                            RECEIPT NUMBER:</td>
                        <td class="border border-gray-400 p-2 font-semibold text-xs" style="width: 150px;">

                        </td>
                    </tr>
                </tbody>
            </table>
            <br>


            <div class="mb-4 flex space-x-4">
                <div class="mb-2 flex-1">
                    <label class="block font-semibold"></label>
                    <br>
                    <span class="block font-sm font-semibold uppercase"></span>
                    <span class="text-sm"></span>
                </div>
                <div class="mb-2 flex-1">
                    <label class="block font-semibold">AUTHORIZED BY:</label>
                    <br>
                    <span class="block font-sm font-semibold">ROLLY DOANE C. AQUINO, RN, MPA</span>
                    <span class="text-md">LDRRMO</span>
                </div>
            </div>
        </div>
    </div>
</body>

</html>