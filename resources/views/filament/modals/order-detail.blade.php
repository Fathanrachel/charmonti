<div style="font-family: system-ui, -apple-system, sans-serif; color: #334155; font-size: 14px; line-height: 1.6;">

    {{-- 1. Status & Meta Order --}}
    <div style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; padding: 16px; display: flex; flex-wrap: wrap; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px;">
        <div>
            <span style="font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; color: #94a3b8; display: block;">ID Transaksi</span>
            <span style="font-size: 18px; font-weight: 800; color: #0f172a;">Order #{{ $order->id }}</span>
            <span style="font-size: 12px; color: #64748b; display: block; margin-top: 2px;">📅 {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y, H:i') }} WIB</span>
        </div>
        <div style="display: flex; gap: 8px; align-items: center; flex-wrap: wrap;">
            @php
                $statusStyles = [
                    'pending'  => 'background: #fef3c7; color: #92400e; border: 1px solid #fde68a;',
                    'diproses' => 'background: #e0f2fe; color: #0369a1; border: 1px solid #bae6fd;',
                    'dikirim'  => 'background: #e0e7ff; color: #3730a3; border: 1px solid #c7d2fe;',
                    'selesai'  => 'background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;',
                    'batal'    => 'background: #ffe4e6; color: #9f1239; border: 1px solid #fecdd3;',
                ];
                $statusLabels = [
                    'pending'  => 'Menunggu Pembayaran',
                    'diproses' => 'Sedang Diproses',
                    'dikirim'  => 'Dalam Pengiriman 🚚',
                    'selesai'  => 'Pesanan Selesai ✨',
                    'batal'    => 'Dibatalkan',
                ];
                $payStatus = $order->payment?->payment_status ?? 'pending';
                $payStyle = ($payStatus === 'paid')
                    ? 'background: #dcfce7; color: #166534; border: 1px solid #bbf7d0;'
                    : 'background: #fef3c7; color: #92400e; border: 1px solid #fde68a;';
            @endphp
            <span style="padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; {{ $statusStyles[$order->status] ?? 'background:#f1f5f9;color:#475569;' }}">
                {{ $statusLabels[$order->status] ?? ucfirst($order->status) }}
            </span>
            <span style="padding: 4px 12px; border-radius: 9999px; font-size: 12px; font-weight: 700; {{ $payStyle }}">
                💳 Status Bayar: {{ strtoupper($payStatus) }}
            </span>
        </div>
    </div>

    {{-- 2. Detail Pemesan & Alamat Pengiriman --}}
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 16px; margin-bottom: 16px;">
        {{-- Pelanggan --}}
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="font-weight: 700; color: #0f172a; font-size: 13px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 10px;">
                👤 Informasi Pelanggan
            </div>
            <div style="font-size: 13px; display: flex; flex-direction: column; gap: 4px;">
                <div><strong style="color: #64748b;">Nama:</strong> {{ $order->profile?->name ?? 'Guest' }}</div>
                <div><strong style="color: #64748b;">No. HP/WA:</strong> {{ $order->profile?->phone ?? '-' }}</div>
                <div><strong style="color: #64748b;">Metode Bayar:</strong> {{ strtoupper($order->payment_method ?? 'Midtrans') }}</div>
            </div>
        </div>

        {{-- Pengiriman --}}
        <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; padding: 16px; box-shadow: 0 1px 2px rgba(0,0,0,0.03);">
            <div style="font-weight: 700; color: #0f172a; font-size: 13px; border-bottom: 1px solid #f1f5f9; padding-bottom: 8px; margin-bottom: 10px;">
                🚚 Alamat & Pengiriman
            </div>
            <div style="font-size: 13px; display: flex; flex-direction: column; gap: 4px;">
                <div><strong style="color: #64748b;">Ekspedisi:</strong> {{ $order->shipping?->expedition?->name_expedition ?? 'Kurir' }}</div>
                <div><strong style="color: #64748b;">Alamat Lengkap:</strong> {{ $order->profile?->address_line ?? '-' }}</div>
                @if($order->shipping?->tracking_number)
                    <div><strong style="color: #64748b;">No. Resi:</strong> <span style="font-family: monospace; font-weight: 700; color: #2563eb;">{{ $order->shipping->tracking_number }}</span></div>
                @endif
            </div>
        </div>
    </div>

    {{-- 3. Daftar Produk Reguler & Gelang Custom --}}
    <div style="background: #ffffff; border-radius: 12px; border: 1px solid #e2e8f0; overflow: hidden; margin-bottom: 16px;">
        <div style="background: #f8fafc; padding: 12px 16px; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #0f172a; font-size: 13px;">
            🛍️ Rincian Produk & Manik-Manik Dibeli
        </div>
        <div style="padding: 16px;">

            {{-- Produk Katalog --}}
            @if($order->orderItems && $order->orderItems->isNotEmpty())
                <div style="margin-bottom: 16px;">
                    <div style="font-size: 11px; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 8px;">Produk Katalog</div>
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                        <thead>
                            <tr style="background: #f8fafc; color: #64748b; text-align: left; border-bottom: 1px solid #e2e8f0;">
                                <th style="padding: 8px 12px;">Produk</th>
                                <th style="padding: 8px 12px; text-align: center;">Jumlah</th>
                                <th style="padding: 8px 12px; text-align: right;">Harga Satuan</th>
                                <th style="padding: 8px 12px; text-align: right;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                                <tr style="border-bottom: 1px solid #f1f5f9;">
                                    <td style="padding: 10px 12px; font-weight: 600; color: #0f172a;">{{ $item->product?->product_name ?? 'Produk' }}</td>
                                    <td style="padding: 10px 12px; text-align: center; font-weight: 700; color: #475569;">{{ $item->qty }}x</td>
                                    <td style="padding: 10px 12px; text-align: right; color: #64748b;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                    <td style="padding: 10px 12px; text-align: right; font-weight: 700; color: #0f172a;">Rp {{ number_format($item->price * $item->qty, 0, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            {{-- Gelang Custom --}}
            @if($order->customBahanOrders && $order->customBahanOrders->isNotEmpty())
                <div>
                    <div style="font-size: 11px; font-weight: 700; color: #f43f5e; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 10px;">Rincian Gelang Custom & Manik-Manik/Bahan</div>
                    
                    @foreach($order->customBahanOrders as $index => $customOrder)
                        @php
                            $isNoStrap = ($customOrder->warna === 'tanpa_strap');
                            $strapText = $isNoStrap ? 'Tanpa Strap (Hanya Charm)' : 'Tali Strap ' . ucfirst($customOrder->warna);

                            // Agregasi manik-manik/charms yang sama agar tampil ringkas (misal: Diamond 15x, bukan Diamond 1x sebanyak 15 baris)
                            $groupedCharms = $customOrder->customBahanOrderItems
                                ->groupBy('bahan_id')
                                ->map(function ($items) {
                                    $first = $items->first();
                                    return [
                                        'nama_bahan' => $first->bahan?->nama_bahan ?? 'Charm',
                                        'total_qty'  => $items->sum('qty'),
                                    ];
                                });
                        @endphp
                        <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 12px; padding: 14px; margin-bottom: 12px;">
                            <div style="font-weight: 700; color: #9f1239; font-size: 13px; border-bottom: 1px solid #ffe4e6; padding-bottom: 6px; margin-bottom: 8px;">
                                🎀 Gelang Custom #{{ $index + 1 }} — {{ $strapText }}
                            </div>

                            @if($customOrder->request_note)
                                <div style="background: #ffffff; padding: 10px; border-radius: 8px; border: 1px solid #fecdd3; font-size: 12px; color: #475569; font-style: italic; margin-bottom: 10px;">
                                    📝 <strong>Catatan Desain/Variasi:</strong> "{{ $customOrder->request_note }}"
                                </div>
                            @endif

                            @if($groupedCharms->isNotEmpty())
                                <div>
                                    <div style="font-size: 11px; font-weight: 700; color: #e11d48; margin-bottom: 6px;">💎 Manik-Manik (Charms) Terpilih:</div>
                                    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 8px;">
                                        @foreach($groupedCharms as $charmGroup)
                                            <div style="background: #ffffff; border: 1px solid #fda4af; border-radius: 8px; padding: 8px 12px; display: flex; justify-content: space-between; align-items: center; font-size: 12px; box-shadow: 0 1px 2px rgba(0,0,0,0.02);">
                                                <span style="font-weight: 600; color: #334155;">• {{ $charmGroup['nama_bahan'] }}</span>
                                                <span style="background: #ffe4e6; color: #e11d48; font-weight: 800; font-size: 12px; padding: 3px 10px; border-radius: 12px;">{{ $charmGroup['total_qty'] }}x</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif

        </div>
    </div>

    {{-- 4. Ringkasan Total Pembayaran --}}
    <div style="background: #f8fafc; border-radius: 12px; border: 1px solid #e2e8f0; padding: 16px;">
        <div style="display: flex; justify-content: space-between; font-size: 13px; color: #64748b; margin-bottom: 6px;">
            <span>Subtotal Produk & Bahan:</span>
            <span style="font-weight: 600; color: #0f172a;">Rp {{ number_format($order->total_price - ($order->shipping?->shipping_cost ?? 0), 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13px; color: #64748b; margin-bottom: 8px;">
            <span>Ongkos Kirim:</span>
            <span style="font-weight: 600; color: #0f172a;">Rp {{ number_format($order->shipping?->shipping_cost ?? 0, 0, ',', '.') }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 15px; font-weight: 800; color: #0f172a; border-top: 1px dashed #cbd5e1; padding-top: 8px;">
            <span>TOTAL PEMBAYARAN:</span>
            <span style="color: #e11d48; font-size: 17px;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

</div>
