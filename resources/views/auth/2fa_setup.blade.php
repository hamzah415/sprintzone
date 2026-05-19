<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>2-Step Verification</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f9;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
        }

        .card {
            background: white;
            padding: 40px;
            border-radius: 16px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.05);
            width: 100%;
            max-width: 400px;
            text-align: center;
        }

        .icon-box {
            background-color: #eef5ff;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .icon-box svg {
            width: 30px;
            height: 30px;
            color: #3b82f6;
        }

        h2 {
            margin: 0 0 10px;
            font-weight: 700;
            color: #1f2937;
        }

        p {
            font-size: 14px;
            color: #6b7280;
            line-height: 1.5;
            margin-bottom: 30px;
        }

        .otp-container {
            display: flex;
            justify-content: space-between;
            gap: 8px;
            margin-bottom: 25px;
        }

        .otp-input {
            width: 50px;
            height: 60px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #1f2937;
            background-color: #f9fafb;
            transition: all 0.2s;
        }

        .otp-input:focus {
            outline: none;
            border-color: #3b82f6;
            background-color: #fff;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }

        .btn-verify {
            width: 100%;
            background-color: #2563eb;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-verify:hover {
            background-color: #1d4ed8;
        }

        .error-message {
            color: #ef4444;
            font-size: 13px;
            margin-top: 15px;
        }

        .other-method {
            margin-top: 25px;
            font-size: 13px;
            color: #9ca3af;
        }

        .other-method a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 600;
        }

	.link-cancel {
    		display: inline-block;
    		color: #dc2626; /* Warna merah */
    		text-decoration: none;
    		font-size: 14px;
    		font-weight: 600;
    		margin-top: 15px;
    		transition: color 0.2s;
	}

	.link-cancel:hover {
    		color: #991b1b; /* Merah lebih gelap saat hover */
    		text-decoration: underline;
	}
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center"><strong>Setup Two-Factor Authentication</strong></div>

                <div class="card-body text-center">
                    <p>Open the **Google Authenticator** app on your phone and scan the QR Code below:</p>

                    <div class="mb-4 d-flex justify-content-center">
                        {{-- Menampilkan QR Code SVG --}}
                        {!! $qrCodeUrl !!}
                    </div>

                    <div class="alert alert-info">
                        <p class="mb-0">If you can't scan, enter this code manually:</p>
                        <strong>{{ $secret }}</strong>
                    </div>

                    <hr>
			<p>Enter 6-Digit Verification Code to Confirm:</p>
                    <form method="POST" action="{{ route('2fa.setup.post') }}">
                        @csrf
                        <div class="form-group">
                            <input type="hidden" name="one_time_password" id="otp_full">

        <div class="otp-container">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
        </div>

                            @error('one_time_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mt-3">
                            <button type="submit" class="btn btn-primary btn-verify">
                                Enable 2FA Now
                            </button>
                        </div>
			<div class="text-center">
    				<a href="#" 
       					class="link-cancel"
       					onclick="event.preventDefault(); document.getElementById('cancel-2fa-form').submit();">
        				Cancel
    				</a>
			</div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<form id="cancel-2fa-form" action="{{ route('2fa.cancel') }}" method="POST" style="display: none;">
    @csrf
</form>
<script>
    const inputs = document.querySelectorAll('.otp-input');
    const fullInput = document.getElementById('otp_full');

    inputs.forEach((input, index) => {
        // Fokus ke input pertama saat halaman dimuat
        if (index === 0) input.focus();

        input.addEventListener('keyup', (e) => {
            const currentInput = input;
            const nextInput = input.nextElementSibling;
            const prevInput = input.previousElementSibling;

            // Hapus karakter non-angka
            currentInput.value = currentInput.value.replace(/[^0-9]/g, '');

            // Pindah ke input berikutnya jika diisi
            if (currentInput.value && nextInput && nextInput.classList.contains('otp-input')) {
                nextInput.focus();
            }

            // Pindah ke input sebelumnya jika dihapus (Backspace)
            if (e.key === "Backspace" && prevInput && prevInput.classList.contains('otp-input')) {
                prevInput.focus();
            }

            updateHiddenInput();
        });

        // Dukungan Paste (Tempel) Kode
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const data = e.clipboardData.getData('text').slice(0, 6);
            if (!/^\d+$/.test(data)) return;

            const digits = data.split('');
            digits.forEach((digit, i) => {
                if (inputs[i]) inputs[i].value = digit;
            });
            updateHiddenInput();
            inputs[Math.min(digits.length, 5)].focus();
        });
    });

    function updateHiddenInput() {
        let code = "";
        inputs.forEach(input => {
            code += input.value;
        });
        fullInput.value = code;
    }

    document.getElementById('form-2fa').addEventListener('submit', function(e) {
        updateHiddenInput(); // Pastikan data terisi sebelum kirim
    });
</script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>