<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg">
            <h2 class="text-2xl font-bold mb-4">Register</h2>

            <form action="{{ route('register') }}" method="POST">
    @csrf

    <!-- Name Field -->
    <div class="mb-4">
        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
        <input type="text" id="name" name="name" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('name') }}" required>
    </div>

    <!-- Email Field -->
    <div class="mb-4">
        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
        <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('email') }}" required>
        @if($errors->has('email'))
            <div class="text-red-500 text-xs mt-1">{{ $errors->first('email') }}</div>
        @endif
    </div>

    <!-- Phone Number Field -->
    <div class="mb-4">
        <label for="phone_number" class="block text-sm font-medium text-gray-700">Phone Number</label>
        <input type="text" id="phone_number" name="phone_number" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" value="{{ old('phone_number') }}" required>
    </div>

    <!-- Password Field -->
    <div class="mb-4">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input type="password" id="password" name="password" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
    </div>

    <!-- Confirm Password Field -->
    <div class="mb-6">
        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirm Password</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" required>
    </div>

    <button type="submit" class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-700">
        Register
    </button>
</form>

        </div>
    </div>

</body>
</html>
