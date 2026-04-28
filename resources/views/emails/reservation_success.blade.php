<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket Reservasi</title>
</head>
<body style="background-color: #f8fafc; font-family: 'Helvetica', 'Arial', sans-serif; padding: 40px 0; margin: 0;">

    <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="center">
                
                <div style="background-color: #ffffff; width: 100%; max-width: 400px; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
                    
                    <div style="height: 8px; width: 100%; background: linear-gradient(90deg, #DC2626 0%, #E11D48 100%); background-color: #DC2626;"></div>

                    <div style="padding: 40px 30px; text-align: center;">
                        
                        <h1 style="color: #0f172a; font-size: 24px; margin: 0 0 5px 0; font-weight: 800; letter-spacing: -0.5px;">E-Visitor Pass</h1>
                        
                        <p style="color: #64748b; font-size: 14px; margin: 0 0 20px 0;">
                            Hai, <span style="color: #DC2626; font-weight: bold;">{{ $visitor->name }}</span>
                        </p>

                        {{-- [LOGIKA 1] Tampilkan Pesan Sukses Update (Hanya jika Update) --}}
                        @if(isset($isUpdate) && $isUpdate)
                            <div style="background-color: #ecfdf5; border: 1px solid #10b981; color: #064e3b; padding: 15px; border-radius: 12px; margin-bottom: 25px; text-align: left;">
                                <p style="margin: 0; font-size: 14px; line-height: 1.5;">
                                    <strong>✅ Data Berhasil Diperbarui!</strong><br>
                                    Data kunjungan Anda telah berhasil diubah. Silakan gunakan QR Code terbaru di bawah ini. Selamat berkunjung!
                                </p>
                            </div>
                        @endif

                        <div style="background-color: #ffffff; padding: 20px; border: 2px dashed #cbd5e1; border-radius: 16px; display: inline-block;">
                            @if(str_starts_with($qrCodeImage, 'data:image'))
                                @php
                                    // 1. Buang string awalan agar tersisa murni karakter Base64-nya saja
                                    $cleanBase64 = str_replace('data:image/png;base64,', '', $qrCodeImage);
                                @endphp
                                
                                <img src="{{ $message->embedData(base64_decode($cleanBase64), 'qr-visitor-'.$visitor->uuid.'.png', 'image/png') }}" alt="QR Code E-Visitor" style="width: 300px; height: 300px;">
                            @else
                                <img src="{{ $qrCodeImage }}" alt="QR Code E-Visitor" style="width: 300px; height: 300px;">
                            @endif
                        </div>

                        <p style="color: #94a3b8; font-size: 12px; margin: 25px 0 30px 0; line-height: 1.6;">
                            Scan QR Code ini di gerbang masuk utama.<br>
                            Berlaku untuk satu kali kunjungan.
                        </p>


                        {{-- [LOGIKA 2] Tampilkan Tombol Reschedule (Hanya jika BUKAN Update / Create Baru) --}}
                        @if(!isset($isUpdate) || !$isUpdate)
                        <div style="margin-top: 20px; padding: 15px; background-color: #fff3cd; border: 1px solid #ffeeba; border-radius: 5px;">
                            <p style="margin: 0; color: #856404;">
                                <strong>Perlu merubah jadwal atau data tidak sesuai?</strong><br>
                                Silakan klik tautan di bawah ini untuk melakukan Reschedule atau Edit Data:
                            </p>
                            <p style="margin-top: 10px;">
                                <a href="{{ route('reservasi.edit', $visitor->uuid) }}" 
                                style="background-color: #ffc107; color: #000; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block;">
                                Reschedule / Edit Data
                                </a>
                            </p>
                        </div> 
                        @endif
                        
                    </div>

                    <div style="background-color: #f8fafc; padding: 15px; text-align: center; border-top: 1px solid #f1f5f9;">
                        <p style="color: #94a3b8; font-size: 10px; margin: 0; text-transform: uppercase; font-weight: 600; letter-spacing: 1px;">
                            PT. Charoen Pokphand Indonesia
                        </p>
                    </div>

                </div>

                <p style="color: #94a3b8; font-size: 11px; margin-top: 20px;">
                    Email ini dibuat secara otomatis. Mohon jangan membalas.
                </p>

            </td>
        </tr>
    </table>

</body>
</html>