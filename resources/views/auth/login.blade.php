<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f3f4f6; }
        .focus\:outline-indigo-600:focus {
            --tw-ring-color: #4f46e5;
        }
    </style>
</head>
<body>

<div class="flex min-h-screen flex-col justify-center px-6 py-12 lg:px-8 bg-gray-100">
  <div class="sm:mx-auto sm:w-full sm:max-w-sm">
    <img src="https://placehold.co/100x40/4f46e5/ffffff?text=ADMIN" alt="Company Logo" class="mx-auto h-10 w-auto rounded-md" />
    <h2 class="mt-8 text-center text-3xl font-bold tracking-tight text-gray-900">Masuk ke Akun Admin</h2>
  </div>

  <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm bg-white p-8 rounded-xl shadow-lg">
    
    {{-- Display Validation Errors --}}
    @if ($errors->any())
        <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded-lg dark:bg-red-200 dark:text-red-800" role="alert">
            {{ $errors->first('username') }}
        </div>
    @endif
    
    <form action="{{ route('admin.login') }}" method="POST" class="space-y-6">
      @csrf 
      
      <div>
       <label for="username" class="block text-sm font-medium text-gray-900">Username</label>
        <div class="mt-2">
            <input id="username" type="text" name="username" value="{{ old('username') }}" required 
                class="block w-full rounded-md border border-gray-300 px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm" />
        </div>
      </div>

      <div>
        <div class="flex items-center justify-between">
          <label for="password" class="block text-sm font-medium text-gray-900">Password</label>
          <div class="text-sm">
            {{-- Ganti link Forgot Password --}}
            <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Lupa Password?</a>
          </div>
        </div>
        <div class="mt-2">
          <input id="password" type="password" name="password" required autocomplete="current-password" 
                 class="block w-full rounded-md border border-gray-300 px-3 py-2 text-base text-gray-900 placeholder:text-gray-400 focus:ring-indigo-600 focus:border-indigo-600 sm:text-sm" />
        </div>
      </div>

      <div>
        <button type="submit" 
                class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
          Masuk
        </button>
      </div>
    </form>

    <p class="mt-8 text-center text-sm text-gray-500">
      Bukan Admin?
      {{-- Link Register diubah ke Login Biasa jika ada --}}
      <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500">Kembali ke Halaman Utama</a>
    </p>
  </div>
</div>

</body>
</html>