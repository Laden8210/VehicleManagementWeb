<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gate Pass</title>
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
        <h5 class="text-xs font-bold" style="color:red"><b><i>GATE PASS</i></b></h5>
        <h5 class="text-xs font-bold" style="color:red"><b><i>No:&nbsp;{{ $borrowerRequest->GTNumber ?? '' }}</i></b>
        </h5>

    </header>
    <div class="max-w-2xl mx-auto p-4">
        <div class="mb-4">
            <div class="flex justify-between mb-2">
                <div>
                    <label class="block font-semibold text-sm">To Guard on Duty:
                        <label class="block font-semibold text-sm">Please allow:
                </div>
                <div class="flex items-center">
                    <label class="block font-semibold text-sm mr-2">Date:</label>
                    <b class="text-sm">{{ \Carbon\Carbon::parse($borrowerRequest->RequestDate)->format('F j, Y') }}</b>
                </div>
            </div>
        </div>

        <div class="mb-4">
            <table class="w-full">
                <thead>
                    <tr>
                        <th class="border border-gray-400 p-2 font-semibold text-xs w-1/6">Quantity</th>
                        <th class="border border-gray-400 p-2 font-semibold text-xs w-1/6">Unit</th>
                        <th class="border border-gray-400 p-2 font-semibold text-sm w-2/3">Description</th>
                    </tr>
                </thead>
                <tbody class="text-center">
                    <tr>
                        <td class="border border-gray-400 p-2 font-semibold text-xs w-1/6">
                            {{ $borrowerRequest->NumberOfItems ?? '' }}
                        </td>
                        <td class="border border-gray-400 p-2 font-semibold text-xs w-1/6">
                            {{ $borrowerRequest->inventory->ItemUnit ?? '' }}
                        </td>
                        <td class="border border-gray-400 p-2 font-semibold text-sm w-2/3">
                            {{ $borrowerRequest->inventory->ItemName ?? '' }}
                        </td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="border border-gray-400 p-2 text-sm text-left">
                            <strong>Purpose:</strong> {{ $borrowerRequest->Purpose ?? 'N/A' }}
                        </td>
                    </tr>
                </tfoot>
            </table>
            <h6 class="text-xs"><i>NOTE: Borrower is responsible for any loss/damages of the borrowed item(s).</i>
            </h6>
            <br>


            <div class="mb-4 flex space-x-4">
                <div class="mb-2 flex-1">
                    <label class="block font-semibold">Conformed:</label>
                    <br>
                    <span
                        class="block font-sm font-semibold uppercase">{{ $borrowerRequest->borrower->BorrowerName ?? '' }}</span>
                    <span class="text-sm">Borower</span>
                </div>
                <div class="mb-2 flex-1">
                    <label class="block font-semibold">Approved By:</label>
                    <br>
                    <span class="block font-sm font-semibold">ROLLY DOANE C. AQUINO, RN, MPA</span>
                    <span class="text-sm">LDRRMO</span>
                </div>
            </div>

            <div class="mb-4 flex space-x-4">
                <div class="mb-2 flex-1">
                    <h6 class="block text-sm">
                        <b>Address:</b>&nbsp;<u>{{ $borrowerRequest->borrower->BorrowerAddress ?? '' }}</u>
                    </h6>
                    <h6 class="block text-sm"><b>Contact
                            Number:</b>&nbsp;<u>{{ $borrowerRequest->borrower->BorrowerNumber ?? '' }}</u></h6>
                    <h6 class="block text-sm"><b>Date to be
                            Returned:</b>&nbsp;<u>{{ \Carbon\Carbon::parse($borrowerRequest->ReturnDate)->format('F j, Y') }}</u>
                    </h6>
                    <br>
                    <br>
                    <br>
                    <h6 class="block text-sm"><b>BORROWER's COPY</b></u>
                    </h6>
                </div>
            </div>
        </div>
    </div>
</body>

</html>