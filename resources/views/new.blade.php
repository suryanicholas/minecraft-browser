<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mineweb</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif

</head>
<body>
    <div class="size-full flex flex-col overflow-hidden py-2 transition bg-[radial-gradient(at_20%_80%,_#efe1da,_#c29680,_#7d384e,_#310001)] lg:bg-[radial-gradient(at_70%_30%,_#fff,_#fcddcc,_#a3839d,_#270031)]">
        <header class="flex items-center px-4 pt-1 pb-3">
            <div class="">
                <span class="font-[Mojang] text-gray-200">Mineweb</span>
            </div>
        </header>
        <div class="relative flex flex-1 rounded overflow-hidden">
            <aside class="0x0 group flex flex-col p-3 bg-[#31000534] hover:bg-[#31000575] w-[60px] hover:w-[240px]  h-full rounded-e-xl shadow transition-all overflow-y-auto">
                <div class="flex mb-2">
                    <button class="clients relative text-white font-['Mojang'] flex justify-center group-hover:justify-start items-center h-[36px] cursor-pointer px-2 bg-[#3100058e] bg-cover flex-1 rounded-full group-hover:rounded hover:bg-[#4500077b] transition group-hover:overflow-hidden" data-client-index="0">
                        <span class="group-hover:hidden flex">I</span>
                        <span class="hidden group-hover:block text-xs truncate">Nama Akun</span>
                        <span class="indicator absolute h-[10px] w-[10px] bg-red-500 border border-red-950 rounded-full bottom-0 right-0"></span>
                    </button>
                    {{-- Client List --}}
                </div>
            </aside>
            <main class="group mx-2 p-2 rounded-xl shadow bg-[#31000534] gap-2 hover:bg-[#31000575] flex flex-1 transition">
                <div class="font-[Mojang] w-[480px] flex flex-col bg-[#03030371] rounded-lg transition overflow-hidden">
                    <div id="console" class="flex text-xs gap-2 py-3 px-2 flex-col-reverse flex-1 overflow-auto">
                        {{-- Chat --}}
                    </div>
                    <form id="cli" class="border-t border-[#f097a284] flex" autocomplete="off">
                        <input type="text" class="flex-1 outline-none text-white text-xs px-2 py-1">
                    </form>
                </div>
                <div class="flex flex-col py-3">
                    <div class="flex">
                        <button id="connect" class="w-[32px] h-[32px] bg-red-500 border-2 border-red-900 cursor-pointer rounded-full transition"></button>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>