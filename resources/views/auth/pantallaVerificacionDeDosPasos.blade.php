<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>
            Verificaciòn de 2 Pasos
        </title>
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
        <!-- Bootstrap CSS -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    </head>
    <style>
        body{
            background:#eee;
        }
        .card {
            box-shadow: 0 20px 27px 0 rgb(0 0 0 / 5%);
        }
        .card {
            position: relative;
            display: flex;
            flex-direction: column;
            min-width: 0;
            word-wrap: break-word;
            background-color: #fff;
            background-clip: border-box;
            border: 0 solid rgba(0,0,0,.125);
            border-radius: 1rem;
        }
        .img-thumbnail {
            padding: .25rem;
            background-color: #ecf2f5;
            border: 1px solid #dee2e6;
            border-radius: .25rem;
            max-width: 100%;
            height: auto;
        }
        .avatar-lg {
            height: 150px;
            width: 150px;
        }
        .qr-wrapper svg {
            width: 200px !important;
            height: 200px !important;
            display: block;
            margin: 0 auto;
        }
        input[name="codigoParaVerificar2FA[]"] {
            font-size: 2rem;
            font-weight: bold;
            color: #000;
            text-align: center;
        }
    </style>
    <body>
        <div class="container">
            <br>
            <div class="row">
                <div class="col-lg-5 col-md-7 mx-auto my-auto">
                    <div class="card">
                        <div class="card-body px-lg-5 py-lg-5 text-center">
                            <div class="qr-wrapper">
                                {!! $codigoQr !!}
                            </div>
                            <p class="font-weight-light mb-4">Si ya habías escaneado el código QR, no tienes que volverlo a hacer.</p>
                            <h2 class="text-info">Autentificación de Dos factores</h2>
                            <p class="mb-4">Ingresa el código de 6 dígitos que GoogleAuthenticator genera.</p>
                            <form action="{{ route('login.verificar.2FA') }}" method="POST">
                                @csrf
                                <div class="row mb-4">
                                    <div class="col-2 px-1">
                                        <input id="numero2FA1" type="text" class="form-control text-lg text-center" name="codigoParaVerificar2FA[]" inputmode="numeric" pattern="[0-9]*" maxlength="1" placeholder="_" aria-label="input2FA1" autocomplete="off" required>
                                    </div>
                                    <div class="col-2 px-1">
                                        <input id="numero2FA2" type="text" class="form-control text-lg text-center" name="codigoParaVerificar2FA[]" inputmode="numeric" pattern="[0-9]*" maxlength="1" placeholder="_" aria-label="input2FA2" autocomplete="off" required>
                                    </div>
                                    <div class="col-2 px-1">
                                        <input id="numero2FA3" type="text" class="form-control text-lg text-center" name="codigoParaVerificar2FA[]" inputmode="numeric" pattern="[0-9]*" maxlength="1" placeholder="_" aria-label="input2FA3" autocomplete="off" required>
                                    </div>
                                    <div class="col-2 px-1">
                                        <input id="numero2FA4" type="text" class="form-control text-lg text-center" name="codigoParaVerificar2FA[]" inputmode="numeric" pattern="[0-9]*" maxlength="1" placeholder="_" aria-label="input2FA4" autocomplete="off" required>
                                    </div>
                                    <div class="col-2 px-1">
                                        <input id="numero2FA5" type="text" class="form-control text-lg text-center" name="codigoParaVerificar2FA[]" inputmode="numeric" pattern="[0-9]*" maxlength="1" placeholder="_" aria-label="input2FA5" autocomplete="off" required>
                                    </div>
                                    <div class="col-2 px-1">
                                        <input id="numero2FA6" type="text" class="form-control text-lg text-center" name="codigoParaVerificar2FA[]" inputmode="numeric" pattern="[0-9]*" maxlength="1" placeholder="_" aria-label="input2FA6" autocomplete="off" required>
                                    </div>
                                </div>
                                <div class="text-center">
                                    <button type="submit" class="btn bg-info btn-lg my-4" aria-label="Continuar con el proceso">Continuar</button>
                                    <a href="{{ route('login.tickets') }}" class="btn btn-secondary btn-lg my-4">Cancelar</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                const inputs2FA = document.querySelectorAll('input[name="codigoParaVerificar2FA[]"]');

                inputs2FA[0].focus();

                inputs2FA.forEach((input, index) => {
                    input.addEventListener("input", function () {
                        if (this.value.length === 1 && index < inputs2FA.length - 1) {
                            inputs2FA[index + 1].focus();
                        }
                    });

                    input.addEventListener("keydown", function (e) {
                        if (e.key === "Backspace" && this.value === "" && index > 0) {
                            inputs2FA[index - 1].focus();
                        }
                    });
                });
            });
        </script>
    </body>
</html>