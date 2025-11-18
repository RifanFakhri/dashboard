@extends('layout.home') 

@section('content')

    <div class="mb-6">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6">Data Booking Parkir</h1>

        {{-- === BAGIAN KARTU STATISTIK (BARU) === --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            
            {{-- KARTU 1: Total Pendapatan (Biru) --}}
            <div class="p-4 rounded-lg shadow-md bg-blue-500 text-white flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium opacity-80">Total Pendapatan</p>
                    <p class="text-2xl font-bold">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    {{-- Icon Uang --}}
                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 0 0-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 0 1-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 0 0 3 15h-.75M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm3 0h.008v.008H18V10.5Zm-12 0h.008v.008H6V10.5Z" />
                    </svg>
                </div>
            </div>

            {{-- KARTU 2: Transaksi Sukses (Hijau) --}}
            <div class="p-4 rounded-lg shadow-md bg-green-500 text-white flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium opacity-80">Transaksi Sukses</p>
                    <p class="text-2xl font-bold">{{ $totalSukses }} <span class="text-sm font-normal">Transaksi</span></p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    {{-- Icon Centang --}}
                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>

            {{-- KARTU 3: Menunggu Pembayaran (Merah) --}}
            <div class="p-4 rounded-lg shadow-md bg-red-500 text-white flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium opacity-80">Belum Bayar</p>
                    <p class="text-2xl font-bold">{{ $totalPending }} <span class="text-sm font-normal">Transaksi</span></p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    {{-- Icon Jam / Pending --}}
                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
            </div>

            {{-- KARTU 4: Dibatalkan (Ungu) --}}
            <div class="p-4 rounded-lg shadow-md bg-purple-500 text-white flex justify-between items-center">
                <div>
                    <p class="text-sm font-medium opacity-80">Dibatalkan</p>
                    <p class="text-2xl font-bold">{{ $totalBatal }} <span class="text-sm font-normal">Transaksi</span></p>
                </div>
                <div class="p-3 bg-white/20 rounded-lg">
                    {{-- Icon X / Shield --}}
                    <svg class="w-6 h-6 text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                    </svg>
                </div>
            </div>

        </div>
        {{-- === AKHIR BAGIAN KARTU STATISTIK === --}}

        {{-- FORM FILTER & PENCARIAN --}}
        <form action="{{ route('parkir.index') }}" method="GET" class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-4 items-end">
                
                {{-- 1. Input Search (Nama User / Order ID / Plat) --}}
                <div class="lg:col-span-3">
                    <label for="search" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cari Data</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white" placeholder="User / Order ID / Plat">
                    </div>
                </div>

                {{-- 2. Dropdown Tipe Parkir (Dinamis dari Database) --}}
                <div class="lg:col-span-2">
                    <label for="parking_type" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tipe Parkir</label>
                    <select name="parking_type" id="parking_type" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Semua Tipe</option>
                        @foreach($parkingTypes as $type)
                            <option value="{{ $type }}" {{ request('parking_type') == $type ? 'selected' : '' }}>
                                {{ $type }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- 3. Dropdown Status --}}
                <div class="lg:col-span-2">
                    <label for="status" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                    <select name="status" id="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                        <option value="">Semua Status</option>
                        {{-- PENTING: Value gunakan Bahasa Inggris (sesuai DB), Teks Bahasa Indonesia --}}
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }}>Sukses</option>
                        <option value="canceled" {{ request('status') == 'canceled' ? 'selected' : '' }}>Batal</option>
                    </select>
                </div>

                {{-- 4. Range Tanggal --}}
                <div class="lg:col-span-3 flex gap-2">
                    <div class="w-full">
                        <label for="tgl_awal" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Dari</label>
                        <input type="date" name="tgl_awal" id="tgl_awal" value="{{ request('tgl_awal') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                    <div class="w-full">
                        <label for="tgl_akhir" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sampai</label>
                        <input type="date" name="tgl_akhir" id="tgl_akhir" value="{{ request('tgl_akhir') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white">
                    </div>
                </div>

                {{-- Tombol Filter & Reset --}}
                <div class="lg:col-span-2 flex gap-2">
                    <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Filter
                    </button>
                    <a href="{{ route('parkir.index') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm w-full sm:w-auto px-5 py-2.5 text-center dark:bg-gray-800 dark:text-white dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:border-gray-600">
                        Reset
                    </a>
                </div>
            </div>
        </form>
    </div>

    {{-- Alert Messages --}}
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

    {{-- TABEL DATA --}}
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg bg-white dark:bg-gray-800 mt-4">
        <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">No</th>
                    <th scope="col" class="px-6 py-3">Order ID</th>
                    <th scope="col" class="px-6 py-3">Nama User</th>
                    <th scope="col" class="px-6 py-3">Tipe Parkir</th>
                    <th scope="col" class="px-6 py-3">Plat Nomor</th>
                    <th scope="col" class="px-6 py-3">Tgl. Booking</th>
                    <th scope="col" class="px-6 py-3">Jumlah</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($parkirBookings as $index => $parkirBooking)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                        <td class="px-6 py-4">{{ $parkirBookings->firstItem() + $index }}</td>
                        <td class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $parkirBooking->order_id }}
                        </td>
                        <td class="px-6 py-4">
                            {{-- Menggunakan username (atau nama_lengkap) sesuai controller --}}
                            {{ $parkirBooking->user->username ?? $parkirBooking->user->nama_lengkap ?? 'N/A' }} 
                        </td>
                        <td class="px-6 py-4">
                            {{ $parkirBooking->parking_type }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $parkirBooking->plat_nomor }}
                        </td>
                        <td class="px-6 py-4">
                            {{ \Carbon\Carbon::parse($parkirBooking->tanggal_booking)->isoFormat('DD MMM YYYY') }}
                        </td>
                         <td class="px-6 py-4">
                            {{ $parkirBooking->jumlah }}
                        </td>
                        <td class="px-6 py-4">
                            {{-- Badge logic yang support status Inggris & Indo --}}
                            @if(in_array($parkirBooking->status, ['success', 'sukses']))
                                <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">Sukses</span>
                            @elseif($parkirBooking->status == 'pending')
                                <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">Pending</span>
                            @elseif(in_array($parkirBooking->status, ['canceled', 'batal']))
                                <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">Batal</span>
                            @else
                                <span class="px-3 py-1 text-xs font-semibold rounded-lg bg-gray-100 text-gray-800">{{ $parkirBooking->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <button type="button"
                                    data-modal-target="edit-parkir-modal-{{ $parkirBooking->id }}"
                                    data-modal-toggle="edit-parkir-modal-{{ $parkirBooking->id }}"
                                    class="font-medium text-blue-600 dark:text-blue-500 hover:underline">
                                Edit
                            </button>
                            <form action="{{ route('parkir.destroy', $parkirBooking->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data parkir ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="font-medium text-red-600 dark:text-red-500 hover:underline ms-3">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- MODAL EDIT PARKIR --}}
                    <div id="edit-parkir-modal-{{ $parkirBooking->id }}" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-full max-h-full">
                        <div class="relative p-4 w-full max-w-lg max-h-full">
                            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Edit Parkir: {{ $parkirBooking->order_id }}</h3>
                                    <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="edit-parkir-modal-{{ $parkirBooking->id }}">
                                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/></svg>
                                    </button>
                                </div>
                                
                                <form action="{{ route('parkir.update', $parkirBooking->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="p-4 md:p-5 grid grid-cols-2 gap-4">
                                        <div class="col-span-2 p-3 bg-gray-100 dark:bg-gray-800 rounded-lg">
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">User: <span class="font-normal">{{ $parkirBooking->user->username ?? $parkirBooking->user->nama_lengkap ?? 'N/A' }}</span></p>
                                            <p class="text-sm font-medium text-gray-900 dark:text-white">Tipe Parkir: <span class="font-normal">{{ $parkirBooking->parking_type }}</span></p>
                                        </div>
                                        <div class="col-span-1">
                                            <label for="tanggal_booking-edit-{{ $parkirBooking->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tgl. Booking</label>
                                            <input type="date" name="tanggal_booking" id="tanggal_booking-edit-{{ $parkirBooking->id }}" value="{{ old('tanggal_booking', $parkirBooking->tanggal_booking) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                        </div>
                                        <div class="col-span-1">
                                            <label for="jumlah-edit-{{ $parkirBooking->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Jumlah</label>
                                            <input type="number" name="jumlah" id="jumlah-edit-{{ $parkirBooking->id }}" value="{{ old('jumlah', $parkirBooking->jumlah) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label for="plat_nomor-edit-{{ $parkirBooking->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Plat Nomor</label>
                                            <input type="text" name="plat_nomor" id="plat_nomor-edit-{{ $parkirBooking->id }}" value="{{ old('plat_nomor', $parkirBooking->plat_nomor) }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white" required>
                                        </div>
                                        <div class="col-span-2">
                                            <label for="status-edit-{{ $parkirBooking->id }}" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Status</label>
                                            <select name="status" id="status-edit-{{ $parkirBooking->id }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-600 focus:border-blue-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white">
                                                <option value="pending" {{ old('status', $parkirBooking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                                {{-- Simpan value Inggris di database --}}
                                                <option value="success" {{ old('status', $parkirBooking->status) == 'success' ? 'selected' : '' }}>Sukses</option>
                                                <option value="canceled" {{ old('status', $parkirBooking->status) == 'canceled' ? 'selected' : '' }}>Batal</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end p-4 md:p-5 border-t border-gray-200 rounded-b dark:border-gray-600">
                                        <button type="submit" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Update Data</button>
                                        <button type="button" data-modal-hide="edit-parkir-modal-{{ $parkirBooking->id }}" class="ms-3 text-gray-500 bg-white hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-blue-300 rounded-lg border border-gray-200 text-sm font-medium px-5 py-2.5 hover:text-gray-900 focus:z-10 dark:bg-gray-700 dark:text-gray-300 dark:border-gray-500 dark:hover:text-white dark:hover:bg-gray-600">Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                            Tidak ada data booking parkir ditemukan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $parkirBookings->links() }}
    </div>

    <script>
        const successAlert = document.getElementById("success-alert");
        if (successAlert) {
            setTimeout(() => successAlert.classList.add("hidden"), 3000);
        }
    </script>
@endsection