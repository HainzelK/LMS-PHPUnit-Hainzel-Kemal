<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
            <!-- Check if the user is authenticated -->
            <h2 class="text-2xl font-bold mb-4">
                @auth
                    Welcome to Your Dashboard, {{ auth()->user()->name }}
                @else
                    Welcome to Your Dashboard
                @endauth
            </h2>

            <p class="text-gray-700 mb-4">You are successfully logged in!</p>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-2 bg-red-500 text-white font-semibold rounded-md hover:bg-red-700">Logout</button>
            </form>
        </div>
    </div>

</body>
</html>
