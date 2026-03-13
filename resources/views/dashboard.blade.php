<x-app-layout>
    <x-slot name="header">
        <h2 class="font-medium text-lg text-slate-700 leading-tight">
            {{ __('Dashboard Siswa') }}
        </h2>
    </x-slot>

    <div class="py-20 bg-slate-50 min-h-screen flex items-center justify-center">
        <div class="w-full max-w-md mx-auto px-6">

            <div class="bg-white rounded-[3.5rem] shadow-[0_40px_80px_-15px_rgba(0,0,0,0.08)] border border-slate-100 p-12 text-center">

                @if($order && $order->status == 'success')
                <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-8 ring-8 ring-emerald-50/30">
                    <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                    </svg>
                </div>
                <h3 class="text-2xl font-bold text-slate-900 mb-2">Akses Terbuka</h3>
                <p class="text-slate-500 text-sm mb-10">Selamat belajar! Fokus dan raih kampus impianmu.</p>

                <div class="flex justify-center">
                    <a href="/ujian/mulai/{{ $order->order_id }}"
                        style="background-color: #10b981; color: white; border-radius: 9999px; display: inline-flex; align-items: center; justify-content: center; text-decoration: none; font-weight: 800; font-size: 18px; transition: 0.3s; 
              padding: 22px 70px; letter-spacing: 1.5px; box-shadow: 0 20px 40px -10px rgba(16, 185, 129, 0.4);"
                        onmouseover="this.style.backgroundColor='#059669'; this.style.transform='translateY(-2px)'"
                        onmouseout="this.style.backgroundColor='#10b981'; this.style.transform='translateY(0)'">

                        <span>LANJUTKAN UJIAN</span>

                        <svg style="margin-left: 15px; width: 24px; height: 24px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </a>
                </div>
                @else
                <div class="mb-10">
                    <span class="inline-block px-5 py-2 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-[0.3em] mb-8">
                        Elite Access
                    </span>
                    <h3 class="text-3xl font-extrabold text-slate-900 leading-tight tracking-tighter">
                        Investasi Masa <br> Depan PTN
                    </h3>
                </div>

                <div class="py-8 mb-10 border-y border-slate-50">
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Biaya Aktivasi</p>
                    <span class="text-4xl font-black text-slate-900">Rp 5.000.000</span>
                </div>

                <button id="pay-button"
                    class="group relative inline-flex items-center justify-center w-full py-5 bg-indigo-600 text-white font-bold rounded-full transition-all duration-500 hover:bg-indigo-700 hover:shadow-[0_20px_40px_-10px_rgba(79,70,229,0.5)] active:scale-95">
                    <span class="text-lg">Aktivasi Sekarang</span>
                </button>

                @if($order && $order->status == 'pending')
                <div class="mt-8 border-t border-slate-50 pt-8">
                    <form action="{{ route('order.cancel') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-xs font-bold text-slate-400 hover:text-indigo-600 transition-colors uppercase tracking-widest">
                            ← Pilih Metode Lain
                        </button>
                    </form>
                </div>
                @endif

                <p class="mt-10 text-[9px] text-slate-300 font-bold tracking-[0.2em] uppercase">
                    Secure Payment by Midtrans
                </p>
                @endif

            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script type="text/javascript">
        const payBtn = document.getElementById('pay-button');
        if (payBtn) {
            payBtn.onclick = function() {
                this.innerHTML = "Memproses...";
                this.disabled = true;

                axios.post("{{ route('checkout') }}")
                    .then(r => {
                        window.snap.pay(r.data.snapToken, {
                            onSuccess: function(result) {
                                // Beri teks loading agar siswa tahu sedang diproses
                                payBtn.innerHTML = "Memverifikasi Pembayaran...";

                                // Beri jeda 3-5 detik agar Webhook punya waktu untuk update DB
                                setTimeout(function() {
                                    window.location.href = "/ujian/mulai/" + r.data.order_id;
                                }, 3000);
                            },
                            onPending: function(result) {
                                // PEMBAYARAN PENDING (VA muncul tapi belum dibayar)
                                // Reload agar Laravel memunculkan tombol "Ganti Metode"
                                location.reload();
                            },
                            onError: function(result) {
                                location.reload();
                            },
                            onClose: function() {
                                // JIKA SISWA KLIK 'X' (Menutup popup)
                                // Tetap Reload agar tampilan sinkron dengan database
                                location.reload();
                            }
                        });
                    })
                    .catch(err => {
                        alert("Gagal memuat pembayaran.");
                        location.reload();
                    });
            };
        }
    </script>
</x-app-layout>