<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="icon" href="{{ asset(config('app.settings.favicon')) }}" type="image/png">
    <title>{{ __('Add Funds to Wallet') }} - {{ config('app.settings.name') }}</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family={{ config('app.settings.font_family') }}:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/vendor.css') }}">
    <link rel="stylesheet" href="{{ asset('css/common.css') }}">
    <style>
        body {
            font-family: "{{ config('app.settings.font_family') }}", sans-serif;
        }
        .bg-primary {
            background-color: {{ config('app.settings.colors.primary') }};
        }
        .text-primary {
            color: {{ config('app.settings.colors.primary') }};
        }
        .border-primary {
            border-color: {{ config('app.settings.colors.primary') }};
        }
    </style>
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypal['client_id'] }}&currency={{ $paypal['currency'] }}"></script>
</head>
<body class="bg-gray-200">
    <div class="container mx-auto flex items-center justify-center m-10">
        <div class="bg-white w-full max-w-xl p-10 shadow-lg rounded-2xl flex flex-col items-center justify-center gap-5">
            <img class="max-w-logo" src="{{ asset(config('app.settings.logo')) }}" alt="logo">
            <div class="flex items-center w-full">
                <h3 class="uppercase font-bold p-2 text-lg md:text-xl text-white bg-primary border-8 border-primary">{{ config('app.settings.currency.code') }}</h3>
                <h2 class="flex-1 font-bold text-xl p-2 lg:text-2xl xl:text-3xl text-primary border-4 border-primary">{{ config('app.settings.currency.symbol') }}{{ number_format($paypal['amount'], 2) }}</h2>
            </div>
            <div class="w-full" id="paypal-button-container"></div>
            <a class="mt-5 block bg-red-600 text-white rounded-lg w-full text-center py-2 text-sm font-bold" href="{{ route('wallet.process', ['id' => $paypal['transaction_id'], 'status' => 'cancel']) }}">{{ __('Cancel Transaction') }}</a>
        </div>
    </div>
    <script>
      paypal.Buttons({
        createOrder: (data, actions) => {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: "{{ number_format($paypal['amount'], 2) }}"
              }
            }]
          });
        },
        onError: (err) => {
          window.location.href = "{{ route('wallet.process', ['id' => $paypal['transaction_id'], 'status' => 'error']) }}"
        },
        onApprove: (data, actions) => {
          return actions.order.capture().then(function(orderData) {
            actions.redirect("{{ route('wallet.process', ['id' => $paypal['transaction_id'], 'status' => 'pending']) }}?paypal_order_id="+orderData.id)
          });
        }
      }).render('#paypal-button-container');
    </script>
</body>
</html>