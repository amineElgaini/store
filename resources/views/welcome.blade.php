<!doctype html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
  </head>
  <body>
    <h1 class="text-3xl text-red-600 font-bold underline">
      Hello world!
    </h1>
    <body class="bg-gray-100 text-center p-10">
        <div x-data="{ open: false }">
            <button @click="open = !open" class="px-4 py-2 bg-indigo-500 text-white rounded">
                Toggle Box
            </button>
            <div x-show="open" class="mt-4 p-4 bg-white border rounded">
                Hello from Alpine.js!
            </div>
        </div>
    </body>
    
  </body>
</html>