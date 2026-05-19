<form action="{{ route('2fa.post') }}" method="POST" id="2fa-form">
    @csrf
    <input type="hidden" name="one_time_password" id="otp_hidden">

    <div class="inputs">
        <input type="text" class="otp-input" maxlength="1">
        </div>

    <button type="submit">Verifikasi Sekarang</button>
</form>

<script>
document.getElementById('2fa-form').addEventListener('submit', function(e) {
    let combinedCode = "";
    document.querySelectorAll('.otp-input').forEach(function(input) {
        combinedCode += input.value;
    });

    // MASUKKAN HASIL GABUNGAN KE HIDDEN INPUT
    document.getElementById('otp_hidden').value = combinedCode;

    // HAPUS alert("Kode yang dimasukkan: " + combinedCode); <--- HAPUS INI
});
</script>