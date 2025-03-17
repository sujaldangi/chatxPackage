<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Laravel 11 Custom Reset Password Functions</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
    crossorigin="anonymous">
  <style type="text/css">
    body {
      background-color: #333;
      /* Dark background for the body */
      color: #ffffff;
      /* White text color for contrast */
    }

    .card {
      background-color: aliceblue;
      /* Dark background for the card */
      color: #fff;
      /* White text inside the card */
    }

    .form-control {

      border: 1px solid black;
      /* Subtle border for input fields */
    }

    .form-control:focus {
      border-color: #333;
      /* Blue border when input is focused */

    }

    .btn-primary {
      background-color: black;
      /* Primary button color */
      border-color: blueviolet;
      color: azure;
    }

    .btn-primary:hover {
      background-color: darkgray;
      /* Darker shade for hover effect */
      border-color: #004085;
      /* Darker border on hover */
    }

    .form-label {
      color: #bbb;
      /* Light gray text for labels */
    }

    /* HTML: <div class="loader"></div> */
    .loader {
      width: fit-content;
      font-size: 17px;
      font-family: monospace;
      line-height: 1.4;
      font-weight: bold;
      --c: no-repeat linear-gradient(#000 0 0);
      background: var(--c), var(--c), var(--c), var(--c), var(--c), var(--c), var(--c);
      background-size: calc(1ch + 1px) 100%;
      border-bottom: 10px solid #0000;
      position: relative;
      animation: l8-0 3s infinite linear;
      clip-path: inset(-20px 0);
      margin: auto;
      margin-top: 25%;
    }

    .loader::before {
      content: "Loading";
    }

    .loader::after {
      content: "";
      position: absolute;
      width: 10px;
      height: 14px;
      background: #25adda;
      left: -10px;
      bottom: 100%;
      animation: l8-1 3s infinite linear;
    }

    #loaderDiv {
      display: block;
      position: fixed;
      margin: 0px;
      padding: 0px;
      right: 0px;
      top: 0px;
      width: 100%;
      height: 100%;
      background-color: #ddd;
      z-index: 30001;
      opacity: 0.8;
    }

    @keyframes l8-0 {

      0%,
      12.5% {
        background-position: calc(0*100%/6) 0, calc(1*100%/6) 0, calc(2*100%/6) 0, calc(3*100%/6) 0, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
      }

      25% {
        background-position: calc(0*100%/6) 40px, calc(1*100%/6) 0, calc(2*100%/6) 0, calc(3*100%/6) 0, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
      }

      37.5% {
        background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 0, calc(3*100%/6) 0, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
      }

      50% {
        background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 0, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
      }

      62.5% {
        background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 40px, calc(4*100%/6) 0, calc(5*100%/6) 0, calc(6*100%/6) 0
      }

      75% {
        background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 40px, calc(4*100%/6) 40px, calc(5*100%/6) 0, calc(6*100%/6) 0
      }

      87.4% {
        background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 40px, calc(4*100%/6) 40px, calc(5*100%/6) 40px, calc(6*100%/6) 0
      }

      100% {
        background-position: calc(0*100%/6) 40px, calc(1*100%/6) 40px, calc(2*100%/6) 40px, calc(3*100%/6) 40px, calc(4*100%/6) 40px, calc(5*100%/6) 40px, calc(6*100%/6) 40px
      }
    }

    @keyframes l8-1 {
      100% {
        left: 115%
      }
    }
  </style>
</head>

<body>
  <div id="loaderDiv">
    <div id="loader" class="loader"></div>
  </div>

  <section class="py-3 py-md-5">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-12 col-sm-10 col-md-8 col-lg-6 col-xl-5 col-xxl-4">
          <div class="card border border-light-subtle rounded-3 shadow-sm mt-5">
            <div class="card-body p-3 p-md-4 p-xl-5">

              <h2 class="fs-6 fw-normal text-center text-secondary mb-4">Reset Password</h2>
              <form method="POST" action="{{ route('forget.password.post') }}">
                @csrf

                @if (Session::has('message'))
          <div class="alert alert-success" role="alert">
            {{ Session::get('message') }}
          </div>
        @endif

                @error('email')
          <div class="alert alert-danger" role="alert">
            <strong>
            {{ $message }}
            </strong>
          </div>
        @enderror

                <div class="row gy-2 overflow-hidden">

                  <div class="col-12">
                    <div class="form-floating mb-3">
                      <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                        id="email" placeholder="name@example.com">
                      <label for="email" class="form-label">
                        {{ __('Email Address') }}
                      </label>
                    </div>
                  </div>

                  <div class="col-12">
                    <div class="d-grid my-3">
                      <button class="btn btn-primary btn-lg" type="submit">
                        {{ __('Send Password Reset Link') }}
                      </button>
                    </div>
                  </div>

                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    window.onload = function () {
      setTimeout(function () {
        document.getElementById('loaderDiv').style.display = 'none';
      }, 2000);
    };


  </script>

</body>


</html>