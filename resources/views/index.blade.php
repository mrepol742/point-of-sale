<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, maximum-scale=1.0, minimum-scale=1.0"
    />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>{{ config('app.name') }}</title>
    <link rel="manifest" href="/manifest.json" />
    <link rel="shortcut icon" href="/favicon.ico" />
    @viteReactRefresh
</head>
<body>
    <noscript>
        <strong
            >We're sorry but this application doesn't work properly without JavaScript enabled.
            Please enable it to continue.</strong
        >
    </noscript>
    @vite ('resources/react/index.jsx')
</body>
</html>
