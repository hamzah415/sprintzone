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
    </style>
</head>
<body>

<div class="card">
    <div class="icon-box">
        <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
        </svg>
    </div>

    <h2>2-Step Verification</h2>
    <p>Open your <strong>Google Authenticator app</strong> and enter the 6-digit verification code below.</p>

    <form action="{{ route('2fa.post') }}" method="POST" id="form-2fa">
        @csrf
        <input type="hidden" name="one_time_password" id="otp_full">

        <div class="otp-container">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]*">
        </div>

        @if($errors->has('one_time_password'))
            <div class="error-message">
                {{ $errors->first('one_time_password') }}
            </div>
        @endif

        <button type="submit" class="btn-verify">Verify now</button>
    </form>

    <div class="other-method">
        Can't access the code?<br>
        <a href="#">Use another verification method</a>
    </div>
	<div class="other-method">
        	<br>Sprintzone 2026
    	</div>
</div>

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

</body>
</html>