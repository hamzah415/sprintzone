use Illuminate\Http\Request;

public function register(Request $request)
    {
        //validasi request
        $this->validator($request->all())->validate();
?
        // Initialise the 2FA class
        $google2fa = app('pragmarx.google2fa');
?
        // Menyimpan data registrasi kedalam Array
        $registration_data = $request->all();
?
        // Menambahkan secret key kedalam data registrasi
        $registration_data["google2fa_secret"] = $google2fa->generateSecretKey();
?
        // Menyimpan data registrasi kedalam session user 
        $request->session()->flash('registration_data', $registration_data);
?
        // Generate the QR image. User dapat men-scan QR Code tersebut menggunakan app yang telah di install
        // Untuk mengatur two factor authentication
        $QR_Image = $google2fa->getQRCodeInline(
            config('app.name'),
            $registration_data['email'],
            $registration_data['google2fa_secret']
        );
?
        // Passing the QR barcode image to view
        return view('google2fa.register', ['QR_Image' => $QR_Image, 'secret' => $registration_data['google2fa_secret']]);
    }