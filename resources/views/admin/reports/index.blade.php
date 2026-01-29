@extends('layouts.admin')

@section('header', 'Reports')

@section('content')
    <div class="mb-6 flex justify-end">
        <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition duration-300 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Download PDF / Print
        </button>
    </div>

    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6" id="printableArea">
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Relay Library Report</h2>
            <p class="text-gray-500">Generated on {{ now()->format('d M Y, H:i') }}</p>
        </div>

        <div class="mb-6 grid grid-cols-2 gap-4">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                <p class="text-sm text-gray-500">Total Borrow Transactions</p>
                <p class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $borrows->count() }}</p>
            </div>
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                <p class="text-sm text-gray-500">Books Currently Out</p>
                <p class="text-2xl font-bold text-yellow-600">{{ $borrows->where('status', 'active')->count() }}</p>
            </div>
        </div>

        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b-2 border-gray-200 dark:border-gray-600">
                    <th class="py-2">Date</th>
                    <th class="py-2">Borrower</th>
                    <th class="py-2">Book Title</th>
                    <th class="py-2">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($borrows as $borrow)
                    <tr class="border-b border-gray-100 dark:border-gray-700">
                        <td class="py-2 text-sm">{{ \Carbon\Carbon::parse($borrow->borrow_date)->format('d/m/Y') }}</td>
                        <td class="py-2 text-sm">{{ $borrow->user->name }}</td>
                        <td class="py-2 text-sm">{{ $borrow->book->title }}</td>
                        <td class="py-2 text-sm">
                            <span class="px-2 py-0.5 rounded text-xs {{ $borrow->status == 'active' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                                {{ ucfirst($borrow->status) }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
@endsection
