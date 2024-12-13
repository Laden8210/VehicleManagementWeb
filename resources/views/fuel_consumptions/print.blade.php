<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fuel Consumption Report - {{ $month }}-{{ $year }}</title>
    <!-- Include Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* General styles */
        body {
            font-family: 'Times New Roman', Times, serif;
            /* Set font to Times New Roman */
        }

        /* Print Styles */
        @media print {

            /* General styles for print */
            body {
                margin: 0;
                padding: 0;
                font-size: 10pt;
                /* Adjust body font size */
                font-family: 'Times New Roman', Times, serif;
                /* Ensure print also uses Times New Roman */
            }

            .inline-container {
                display: flex;
                align-items: center;
                justify-content: space-between;
                width: 100%;
            }

            th,
            td {
                font-size: 10pt;
                /* Set header and data cell font size for print */
                padding: 4px;
                /* Reduce padding for more compact appearance */
                text-align: center;
                /* Center text */
            }

            th {
                border-bottom: 2px solid black;
                /* Ensure bottom border is applied when printing */
            }

            /* Additional styles to reduce margins for A4 */
            @page {
                size: A4 portrait;
                /* A4 paper size */
                margin: 1mm;
                /* Adjust margins as necessary */
            }
        }
    </style>
</head>

<body class="bg-gray-100 p-8 print-container">
    <h1 class="text-xl font-bold text-center">
        PROVINCIAL DISASTER RISK REDUCTION AND MANAGEMENT OFFICE
    </h1>
    <h4 class="text-sm font-semibold text-center">
        Koronadal City, South Cotabato
    </h4>
    <h1 class="text-xl font-bold text-center">
        Monthly Consumption Report
    </h1>
    <h4 class="text-sm font-semibold text-center">
        For the Month of
        {{ \Carbon\Carbon::createFromDate($year, $month, 1)->format('F')  }},&nbsp;{{ $year }}
    </h4>
    @if($fuelConsumptions->isNotEmpty())
        <h1 class="text-lg font-bold text-left">
            PDRRMO DIESEL -
            &nbsp;{{ $fuelConsumptions->first()->PONum ?? '-' }}
        </h1>
    @endif

    <table class="min-w-full border-collapse">
        <thead>
            <tr>
                <th class="text-center border-b-2 border-black">DATE</th>
                <th class="text-center border-b-2 border-black">Ref</th>
                <th class="text-center border-b-2 border-black">PO No.</th>
                <th class="text-center border-b-2 border-black">Plate No.</th>
                <th class="text-center border-b-2 border-black">Particulars</th>
                <th class="text-center border-b-2 border-black">QTY</th>
                <th class="text-center border-b-2 border-black">Price</th>
                <th class="text-center border-b-2 border-black">Amount</th>
                <th class="text-center border-b-2 border-black">ADJ</th>
                <th class="text-center border-b-2 border-black">Debit</th>
                <th class="text-center border-b-2 border-black">Credit</th>
                <th class="text-center border-b-2 border-black">Balance</th>
            </tr>
        </thead>

        <tbody>
            @foreach($fuelConsumptions as $fuel)
                <tr class="text-xs font-thin">
                    <td class="text-center">
                        {{ \Carbon\Carbon::parse($fuel->RequestDate)->format('m/d/y') }}
                    </td>
                    <td class="text-center">{{ $fuel->ReferenceNumber ?? '-' }}</td>
                    <td class="text-center"></td>
                    <td class="text-center">{{ $fuel->tripTicket->vehicle->PlateNumber ?? '-' }}</td>
                    <td class="text-center">FS {{ $fuel->tripTicket->vehicle->Fuel ?? '-' }}</td>
                    <td class="text-center">{{ number_format($fuel->Quantity, 3, '.', ',') }}</td>
                    <td class="text-center">{{ number_format($fuel->Price, 2, '.', ',') }}</td>
                    <td class="text-center">{{ number_format($fuel->Amount, 2, '.', ',') }}</td>
                    <td class="text-center"></td>
                    <td class="text-center">{{ number_format($fuel->Amount, 2, '.', ',') }}</td>
                    <td class="text-center"></td>
                    <td class="text-center">
                        ( {{ number_format($fuel->RemainingBalance, 2, '.', ',') }} )
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <br>
    <div class="mb-4 flex space-x-4"> <!-- Flex container for Requested By and Approved By -->
        <div class="mb-2 flex-1"> <!-- Flex item for Requested By -->
            <label class="block font-semibold">Prepared By:</label>
            <br>
            <br>
            <span class="block font-sm font-semibold">Michael O. Natividad</span> <!-- Name -->
            <span class="text-sm">Administrative Aide IV (Clerk II)</span> <!-- Title -->
        </div>
        <div class="mb-2 flex-1"> <!-- Flex item for Approved By -->
            <label class="block font-semibold">Approved By:</label>
            <br>
            <br>
            <span class="block font-sm font-semibold">ROLLY DOANE C. AQUINO, RN, MPA</span> <!-- Name -->
            <span class="text-sm">LDRRMO</span>
            <!-- Title -->
        </div>
    </div>



</body>

</html>