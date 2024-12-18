<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

    <div class="flex justify-center items-center min-h-screen">
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-sm">
            <h2 class="text-2xl font-bold mb-4">Login</h2>

            <form action="{{ url('/login') }}" method="POST">
    @csrf
    <!-- Input for Email or Phone Number -->
    <div class="mb-4">
        <label for="email_or_phone" class="block text-sm font-medium text-gray-700">Email or Phone Number</label>
        <input 
            type="text" 
            id="email_or_phone" 
            name="email_or_phone" 
            placeholder="Enter email or phone number" 
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" 
            required
        >
    </div>

    <!-- Input for Password -->
    <div class="mb-6">
        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
        <input 
            type="password" 
            id="password" 
            name="password" 
            placeholder="Enter password" 
            class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md" 
            required
        >
    </div>

    <!-- Remember Me Checkbox -->
    <div class="mb-4 flex items-center">
        <input 
            type="checkbox" 
            id="remember" 
            name="remember" 
            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
        >
        <label for="remember" class="ml-2 block text-sm text-gray-900">
            Remember me
        </label>
    </div>

    <!-- Submit Button -->
    <div class="flex items-center justify-between">
        <button 
            type="submit" 
            class="px-6 py-2 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-700"
        >
            Login
        </button>
    </div>

    <!-- Forgot Password and Verification -->
    <div class="mt-4 text-sm text-gray-600">
        <p>
            <a href="{{ route('password.request') }}" class="text-blue-500 hover:underline">
                Forgot your password?
            </a>
        </p>
        <p class="mt-2">
            <a href="{{ route('verification.notice') }}" class="text-blue-500 hover:underline">
                Didn't verify your email yet?
            </a>
        </p>
    </div>
</form>
        </div>
    </div>

</body>
</html>
