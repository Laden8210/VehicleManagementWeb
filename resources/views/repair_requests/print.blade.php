<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Request for Pre-Repair Inspection</title>
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
    <div class="max-w-2xl mx-auto border border-gray-400 p-4">
        <h1 class="text-center font-bold text-lg mb-4"><u>REQUEST FOR PRE-REPAIR INSPECTION</u></h1>

        <div class="mb-4">
            <div class="flex justify-between mb-2">
                <div>
                    <label class="block font-semibold text-sm">Office:
                        <b class="text-md font-bold">PDRRMO</b>
                </div>
                <div class="flex items-center">
                    <label class="block font-semibold text-sm mr-2">Date:</label>
                    <b class="text-sm">{{ \Carbon\Carbon::parse($repairRequest->RequestDate)->format('F j, Y') }}</b>
                </div>
            </div>
            <div class="flex items-center">
                <label class="block font-semibold text-sm mr-2">Type of Vehicle / Equipment:</label>
                <b class="text-sm">{{ $repairRequest->vehicle->Make }} {{ $repairRequest->vehicle->Series }}</b>
            </div>
            <div class="flex items-center">
                <label class="block font-semibold text-sm mr-2">Plate Number / Serial Number:</label>
                <b class="text-sm">{{ $repairRequest->vehicle->MvfileNo }}</b>
            </div>
        </div>

        <div class="mb-4">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border border-gray-400 p-2 font-semibold text-sm w-3/5">
                            Defects/Complaints/Materials/Spare
                            Parts Needed:</th>
                        <th class="border border-gray-400 p-2 font-semibold text-sm w-2/5">Inspector Findings</th>
                    </tr>
                </thead>
                <tbody>
                <tbody>

                    @if(!empty($repairRequest->Issues))
                                        @php
                                            $decodedIssues = json_decode($repairRequest->Issues, true);
                                            $issueCount = is_array($decodedIssues) ? count($decodedIssues) : 0;
                                        @endphp

                                        @for ($index = 0; $index < 10; $index++)
                                            <tr>
                                                @if ($index < $issueCount)
                                                    <td class="border border-gray-400 p-2">
                                                        {{ $index + 1 }}.&nbsp;{{ $decodedIssues[$index]['IssueDescription'] }}
                                                    </td>
                                                @else
                                                    <td class="border border-gray-400 p-2">{{ $index + 1 }}.</td>
                                                @endif
                                                <td class="border border-gray-400 p-2">{{ $index + 1 }}.</td>
                                            </tr>
                                        @endfor
                    @else
                        @for ($index = 0; $index < 10; $index++)
                            <tr>
                                <td class="border border-gray-400 p-2">{{ $index + 1 }}.</td>
                                <td class="border border-gray-400 p-2">{{ $index + 1 }}.</td>
                            </tr>
                        @endfor
                    @endif

                </tbody>
            </table>

            </tbody>
            </table>
        </div>

        <div class="mb-4 flex space-x-4"> <!-- Flex container for Requested By and Approved By -->
            <div class="mb-2 flex-1"> <!-- Flex item for Requested By -->
                <label class="block font-semibold">Prepared By:</label>
                <br>
                <br>
                <span class="block font-sm font-semibold">JERICK JAY S. VERBAL</span> <!-- Name -->
                <span class="text-sm">LDRRM Assistant</span> <!-- Title -->
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
    </div>
</body>

</html>