<x-app-layout>
    <x-slot name="header">
        @if(session('success'))
        <div class="max-w-7xl mx-auto mt-4 px-6 lg:px-8">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative">
                {{ session('success') }}
            </div>
        </div>
        @endif
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Admin AmbisQuest') }}
        </h2>
    </x-slot>
    <div class="py-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-indigo-600"></div>
                    <div class="p-6">
                        <p class="text-xs font-black text-indigo-600 uppercase tracking-widest mb-1">Total Omzet</p>
                        <p class="text-3xl font-extrabold text-slate-800">Rp{{ number_format($totalOmzet, 0, ',', '.') }}</p>
                        <div class="mt-2 flex items-center text-xs text-green-600 font-bold">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                            </svg>
                            <span>Pendapatan Real-time</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-blue-500"></div>
                    <div class="p-6">
                        <p class="text-xs font-black text-blue-500 uppercase tracking-widest mb-1">Total Siswa</p>
                        <p class="text-3xl font-extrabold text-slate-800">{{ $totalSiswa }} <span class="text-lg font-medium text-slate-400">Orang</span></p>
                        <p class="mt-2 text-xs text-slate-500 font-medium italic">Siswa terdaftar di database</p>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm rounded-2xl border border-gray-100 relative">
                    <div class="absolute top-0 left-0 w-2 h-full bg-amber-500"></div>
                    <div class="p-6">
                        <p class="text-xs font-black text-amber-500 uppercase tracking-widest mb-1">Order Pending</p>
                        <p class="text-3xl font-extrabold text-slate-800">{{ $pendingOrders }} <span class="text-lg font-medium text-slate-400">Antrean</span></p>
                        <p class="mt-2 text-xs text-amber-600 font-bold flex items-center">
                            <svg class="w-4 h-4 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Perlu Konfirmasi
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">

                <div class="lg:col-span-2 bg-white shadow-sm rounded-2xl border border-gray-100 flex flex-col overflow-hidden">
                    <div class="p-5 border-b border-gray-50 bg-slate-50/50">
                        <h3 class="text-sm font-black text-slate-700 uppercase tracking-tighter">Riwayat Transaksi</h3>
                    </div>
                    <div class="p-0 flex-grow">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($latestTransactions as $order)
                                    <tr class="hover:bg-indigo-50/30 transition">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-slate-800">{{ $order->user->name ?? 'User Hilang' }}</p>
                                            <p class="text-[10px] text-slate-400 font-mono">{{ $order->order_id }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($order->status == 'pending')
                                            <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-lg shadow-indigo-200 transition">
                                                    Konfirmasi
                                                </button>
                                            </form>
                                            @else
                                            <span class="px-3 py-1 rounded-full text-[10px] font-black uppercase bg-emerald-100 text-emerald-700 border border-emerald-200">
                                                SUCCESS
                                            </span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-3 bg-white shadow-sm rounded-2xl border border-gray-100 flex flex-col overflow-hidden">
                    <div class="p-5 border-b border-gray-50 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="text-sm font-black text-slate-700 uppercase tracking-tighter">Kelola Siswa</h3>

                        <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center bg-white border border-gray-200 rounded-xl p-1 shadow-inner focus-within:border-indigo-500 transition-all w-full md:w-auto">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Cari nama/email..."
                                class="border-none focus:ring-0 text-xs text-slate-600 bg-transparent flex-grow px-3">
                            <button type="submit" class="bg-slate-900 text-white px-4 py-2 rounded-lg text-xs font-bold hover:bg-black transition shrink-0">
                                Cari
                            </button>
                            @if(request('search'))
                            <a href="{{ route('admin.dashboard') }}" class="px-2 text-red-500 hover:text-red-700">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                            </a>
                            @endif
                        </form>
                    </div>

                    <div class="p-0 flex-grow">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left">
                                <thead>
                                    <tr class="bg-gray-50/50 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b">
                                        <th class="px-6 py-3">Info Siswa</th>
                                        <th class="px-6 py-3 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-50">
                                    @foreach($allUsers as $siswa)
                                    <tr class="hover:bg-blue-50/30 transition">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-slate-800">{{ $siswa->name }}</p>
                                            <p class="text-xs text-slate-500">{{ $siswa->email }}</p>
                                            <p class="text-[10px] text-slate-300 mt-1">Gabung: {{ $siswa->created_at->format('d M Y') }}</p>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <form action="{{ route('admin.users.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Hapus siswa ini selamanya?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 text-slate-300 hover:text-red-600 hover:bg-red-50 rounded-xl transition-all">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 bg-white shadow-sm rounded-2xl border border-gray-100 overflow-hidden">
                <div class="px-6 py-5 bg-gradient-to-r from-red-600 to-rose-500 flex justify-between items-center">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest">Siswa Belum Aktivasi</h3>
                    <span class="bg-white/20 text-white px-3 py-1 rounded-full text-[10px] font-black backdrop-blur-sm">
                        {{ $unpaidStudents->count() }} Orang
                    </span>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead class="bg-gray-50 text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                <tr>
                                    <th class="px-8 py-4">Nama Lengkap</th>
                                    <th class="px-8 py-4">Alamat Email</th>
                                    <th class="px-8 py-4 text-right">Follow Up</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach($unpaidStudents as $item)
                                <tr class="hover:bg-red-50/20 transition">
                                    <td class="px-8 py-5 font-bold text-slate-800">{{ $item->name }}</td>
                                    <td class="px-8 py-5 text-slate-500">{{ $item->email }}</td>
                                    <td class="px-8 py-5 text-right">
                                        <a href="https://wa.me/?text=Halo%20{{ $item->name }},%20pembayaran%20AmbisQuest%20kamu%20masih%20pending..."
                                            target="_blank" class="inline-flex items-center bg-emerald-500 hover:bg-emerald-600 text-white px-4 py-2 rounded-xl text-xs font-black shadow-lg shadow-emerald-100 transition transform hover:-translate-y-0.5">
                                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L0 24l6.335-1.662c1.72.937 3.659 1.432 5.631 1.433h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                            </svg>
                                            WhatsApp
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>