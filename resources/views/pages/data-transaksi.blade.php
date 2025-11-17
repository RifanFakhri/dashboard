@extends('layout.home') 

@section('content')

    {{-- ... (Bagian Form Search & Alert masih sama) ... --}}
    
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 gap-4">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Data Transaksi</h1>
        <form action="{{ route('transaksi.index') }}" method="GET" class="w-full sm:w-auto">
            <label for="search" class="sr-only">Cari</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-500 dark:text-gray-400" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"></path></svg>
                </div>
                <input type="search" name="search" id="search"
                       class="block w-full p-2.5 pl-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white"
                       placeholder="Cari (Order ID/User/Wisata)"
                       value="{{ request('search') }}">
            </div>
        </form>
    </div>

    @if(session('success'))
        <div id="success-alert" class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-800 dark:text-green-200" role="alert">
            <span class="font-medium">Berhasil!</span> {{ session('success') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-800 dark:text-red-200" role="alert">
            <span class="font-medium">Gagal!</span>
            <ul class="mt-1.5 list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    {{-- TABEL TRANSAKSI --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-gray-800">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">

            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Order ID</th>
                    <th scope="col" class="px-6 py-3">Nama User</th>
                    <th scope="col" class="px-6 py-3">Nama Wisata</th>
                    <th scope="col" class="px-6 py-3">Tgl. Kunjungan</th>
                    <th scope="col" class="px-6 py-3">Total Tiket</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transactions as $index => $transaction)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $transactions->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $transaction->order_id }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $transaction->user_name }} 
                        </td>
                        <td class="px-6 py-4">
                            {{-- === PERUBAHAN DI SINI === --}}
                            {{-- Langsung panggil kolomnya --}}
                            {{ $transaction->wisata_name }} 
                        </td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($transaction->visit_date)->isoFormat('DD MMM YYYY') }}
                        </td>
                         <td class="px-6 py-4">
                            {{ $transaction->total_tickets }}
                        </td>
                        <td class="px-6 py-4">
                            {{-- (Badge status masih sama) --}}
                            @if($transaction->status == 'success')
                                <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Sukses</span>
                            @elseif($transaction->status == 'pending')
                                <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Batal</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            {{-- (Tombol Aksi masih sama) --}}
                            <button type="button"
                                    data-modal-target="edit-transaksi-modal-{{ $transaction->id }}"
                                    data-modal-toggle="edit-transaksi-modal-{{ $transaction->id }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                Edit
                            </button>
                            <form action="{{ route('transaksi.destroy', $transaction->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- MODAL EDIT TRANSAKSI --}}
                    <div id="edit-transaksi-modal-{{ $transaction->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full max-h-full">
                        <div class="relative p-4 w-full max-w-lg max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Transaksi: {{ $transaction->order_id }}</h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit-transaksi-modal-{{ $transaction->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                    </button>
                                </div>
                                
                                <form action="{{ route('transaksi.update', $transaction->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-4 md:p-5 grid grid-cols-2 gap-4">

                                        {{-- Info statis (tidak bisa diedit) --}}
                                        <div class="col-span-2 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">User: <span class="font-normal">{{ $transaction->user->name ?? 'N/A' }}</span></p>
                                            {{-- === PERUBAHAN DI SINI === --}}
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Wisata: <span class="font-normal">{{ $transaction->wisata_name }}</span></p>
                                        </div>

                                        {{-- Input yang bisa diedit --}}
                                        <div class="col-span-1">
                                            <label for="visit_date-edit-{{ $transaction->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tgl. Kunjungan</label>
                                            <input type="date" name="visit_date" id="visit_date-edit-{{ $transaction->id }}" value="{{ old('visit_date', $transaction->visit_date) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                        </div>
                                        <div class="col-span-1">
                                            <label for="total_tickets-edit-{{ $transaction->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Total Tiket</label>
                                            <input type="number" name="total_tickets" id="total_tickets-edit-{{ $transaction->id }}" value="{{ old('total_tickets', $transaction->total_tickets) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label for="status-edit-{{ $transaction->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</slabel>
                                            <select name="status" id="status-edit-{{ $transaction->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                <option value="pending" {{ old('status', $transaction->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                <option value="sukses" {{ old('status', $transaction->status) == 'sukses' ? 'selected' : '' }}>Sukses</option>
                                                <option value="batal" {{ old('status', $transaction->status) == 'batal' ? 'selected' : '' }}>Batal</option>
                                            </select>
                                        </div>

                                    </div>
                                    {{-- Modal Footer --}}
                                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update Data</button>
                                        <button type="button" data-modal-hide="edit-transaksi-modal-{{ $transaction->id }}" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data transaksi ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- LINK PAGINATION --}}
    <div class="mt-4">
        {{ $transactions->links() }}
    </div>

    {{-- ... (Script alert masih sama) ... --}}
    <script>
        const successAlert = document.getElementById("success-alert");
        if (successAlert) {
            setTimeout(() => successAlert.classList.add("hidden"), 3000);
        }
    </script>
@endsection