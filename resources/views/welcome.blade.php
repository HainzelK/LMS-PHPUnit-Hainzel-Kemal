<!-- resources/views/welcome.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Library!</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
    <div class="min-h-screen flex flex-col justify-center items-center">
        <div class="text-center mb-4">
            <h1 class="text-4xl font-bold text-blue-600">Welcome to Library!</h1>
        </div>

        <!-- Button to Login -->
        <div class="mt-6">
            <a href="{{ route('login') }}" class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-all duration-300">
                Login to your account
            </a>
        </div>
    </div>
</body>
</html>
