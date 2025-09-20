<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Minecraft | Control Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+Symbols:wght@100..900&display=swap" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>
    <div class="size-full flex p-3" style="background: radial-gradient(at top right, #000107, #0c001b, #0b0241)">
        <aside class="w-2/12 p-2">
            <div id="clients" class="size-full flex flex-col p-2 bg-[#4230a7a3] border-2 border-[#104ac6] rounded shadow-lg shadow-[#543dd55c]">
                <div class="font-bold text-white border-b mb-2">
                    <div class="">Client List</div>
                </div>
            </div>
        </aside>
        <main class="w-6/12 flex flex-col gap-2 p-2">
            <div id="console" class="bg-[#4230a7a3] overflow-auto flex flex-col-reverse px-1 flex-1 border-2 border-[#104ac6] rounded shadow-lg shadow-[#543dd55c]">
                
            </div>
            <div class="bg-[#4230a7a3] flex border-2 border-[#104ac6] rounded shadow-lg shadow-[#543dd55c]">
                <input form="chat" name="messages" class="outline-none flex-1 text-white px-2 my-1" type="text">
                <form id="chat" autocomplete="off"></form>
            </div>
            <div class="relative bg-[#4230a7a3] flex px-2 pt-2 pb-5 border-2 border-[#104ac6] rounded shadow-lg shadow-[#543dd55c]">
                <div class="w-2/12 flex flex-col">
                    @for ($i = 1; $i <= 4; $i++)
                    <div class="w-1/2 h-[36px] flex p-1">
                        <button class="flex-1 cursor-pointer border-2 hover:bg-[#104ac68c] transition border-[#bebebe] rounded shadow-lg shadow-[#543dd55c]"></button>
                    </div>
                        
                    @endfor
                </div>
                <div class="w-1/12 flex flex-col-reverse">
                    <div class="h-[36px] flex p-1">
                        <button class="inventory cursor-pointer flex-1 border-2 hover:bg-[#104ac68c] transition border-[#c67710] rounded shadow-lg shadow-[#543dd55c]" data-slot="45"></button>
                    </div>
                </div>
                <div class="w-9/12 flex flex-wrap">
                    @for ($i = 9; $i <= 35; $i++)
                    <div class="w-1/9 h-[36px] flex p-1">
                        <button class="inventory cursor-pointer flex-1 border-2 hover:bg-[#104ac68c] transition border-[#104ac6] rounded shadow-lg shadow-[#543dd55c]" data-slot="{{ $i }}"></button>
                    </div>
                        
                    @endfor
                    @for ($i = 36; $i <= 44; $i++)
                    <div class="w-1/9 h-[36px] flex p-1">
                        <button class="inventory flex-1 cursor-pointer border-2 hover:bg-[#104ac68c] transition border-[#c6a210] rounded shadow-lg shadow-[#543dd55c]" data-slot="{{ $i }}"></button>
                    </div>
                        
                    @endfor
                </div>
                <div class="absolute bottom-0 flex">
                    <div class="inventory-cursor text-white text-sm"></div>
                </div>
            </div>
        </main>
        <div class="control w-4/12 p-2">
            <div class="relative size-full p-2 flex flex-col bg-[#4230a7a3] border-2 border-[#104ac6] rounded shadow-lg shadow-[#543dd55c]">
                <div class="flex flex-wrap gap-2 mb-3">
                    <button id="connect" class="text-white px-2  mb-2 cursor-pointer bg-[#ff00007c] border-[#fc4e4ec8] hover:bg-[#fd6f6fa8] transition flex text-sm border-2 rounded">Offline</button>
                    <button id="afk" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">AFK</button>
                    <button id="auto-sell" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">Auto Sell</button>
                    <button id="spawner" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">Spawner</button>
                    <button id="module" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">Module</button>
                    <button id="control" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">Control</button>
                    <button id="attack" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">Attack</button>
                </div>
                <div class="text-sm text-white mb-1 border-b">Server Shortcut</div>
                <div class="flex flex-wrap gap-2 mb-4">
                    <button class="server-shortcut text-white px-2 mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded" data-command="move survival-1">Survival 1</button>
                    <button class="server-shortcut text-white px-2 mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded" data-command="move survival-2">Survival 2</button>
                </div>
                <div class="text-sm text-white mb-1 border-b">Inventory</div>
                <div class="flex flex-wrap gap-2 mb-3">
                    <button id="inventory" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">Update</button>
                    <button id="dropItem" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">Drop Item</button>
                    <button id="equip" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">Equip Item</button>
                </div>
                <div class="text-sm text-white mb-1 border-b">Module</div>
                <div class="flex flex-wrap gap-2 mb-3">
                    <button id="agility" class=" text-white px-2  mb-2 cursor-pointer border-[#5c88e8] hover:bg-[#104ac6] transition flex text-sm border-2 rounded">Agility</button>
                </div>
                <div class="text-sm text-white mb-1 border-b">Sign Editor</div>
                <form id="sign" class="flex flex-col mb-3" autocomplete="off">
                    <button type="submit"></button>
                    <input  type="text" name="text-line-0" class="outline-none border-b text-[#d5e0f9] text-center border-[#5c88e8]">
                    <input  type="text" name="text-line-1" class="outline-none border-b text-[#d5e0f9] text-center border-[#5c88e8]">
                    <input  type="text" name="text-line-2" class="outline-none border-b text-[#d5e0f9] text-center border-[#5c88e8]">
                    <input  type="text" name="text-line-3" class="outline-none border-b text-[#d5e0f9] text-center border-[#5c88e8]">
                </form>
                <div class="text-sm text-white mb-1 border-b">Virtual GUI</div>
                <div class="rounded overflow-hidden flex-1 flex flex-col">
                    @php
                        $vx = 0;
                    @endphp
                    @for ($i = 1; $i <= 6; $i++)
                    <div class="flex flex-1">
                        @for ($j = 1; $j <= 9; $j++)
                            <div class="w-1/9 flex justify-center items-center">
                                <button class="virtual cursor-pointer w-[90%] h-[90%] bg-[#4230a7] hover:bg-[#4230a7a3] transition rounded" data-slot="{{ $vx++ }}"></button>
                            </div>
                        @endfor
                    </div>
                    @endfor
                </div>
            </div>
        </div>
    </div>
    <script src="/socket.io/socket.io.js"></script>
    <script type="module">
        const socket = io();
        let active = 0;
        let clients = {};
        let control = false;
        let dropItem = false;
        let equipItem = false;

        socket.emit('clients:list');
        socket.once('clients:list', (data) => {
            clients = data;

            data.forEach((client, index) => {
                $("#clients").append(`<button class="text-start mb-1 text-white cursor-pointer hover:bg-[#104ac68c] px-1 rounded transition" data-client-index="${index}">${client.username}</button>`);
            });

            $('#clients button').click(function () {
                active = $(this).data('client-index');

                if(clients[active].connected){
                    $('button#connect').removeClass('bg-[#ff00007c] border-[#fc4e4ec8] hover:bg-[#fd6f6fa8]');
                    $('button#connect').addClass('bg-[#00ff337c] border-[#94fc4ec8] hover:bg-[#71fd6fa8]');
                    $('button#connect').text('Online');
                } else {
                    $('button#connect').addClass('bg-[#ff00007c] border-[#fc4e4ec8] hover:bg-[#fd6f6fa8]');
                    $('button#connect').removeClass('bg-[#00ff337c] border-[#94fc4ec8] hover:bg-[#71fd6fa8]');
                    $('button#connect').text('Offline');
                }

                if(clients[active].afk){
                    $('button#afk').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#afk').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                } else {
                    $('button#afk').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#afk').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                }

                if(clients[active].autoSell){
                    $('button#auto-sell').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#auto-sell').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                } else {
                    $('button#auto-sell').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#auto-sell').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                }

                if(clients[active].spawner.active){
                    $('button#spawner').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#spawner').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                } else {
                    $('button#spawner').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#spawner').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                }

                if(clients[active].attack){
                    $('button#attack').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#attack').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                } else {
                    $('button#attack').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#attack').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                }

                if(clients[active].agility){
                    $('button#agility').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#agility').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                } else {
                    $('button#agility').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                    $('button#agility').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                }
            });
        });

        socket.on('clients:update', (data) => {
            clients = data;

            clients.forEach((client , index) => {
                if(client.connected){
                    $(`#clients button[data-client-index="${index}"]`).addClass('bg-[#00ff1e67]');
                } else {
                    $(`#clients button[data-client-index="${index}"]`).removeClass('bg-[#00ff1e67]');
                    
                }
            });

            if(clients[active].connected){
                $('button#connect').removeClass('bg-[#ff00007c] border-[#fc4e4ec8] hover:bg-[#fd6f6fa8]');
                $('button#connect').addClass('bg-[#00ff337c] border-[#94fc4ec8] hover:bg-[#71fd6fa8]');
                $('button#connect').text('Online');
            } else {
                $('button#connect').addClass('bg-[#ff00007c] border-[#fc4e4ec8] hover:bg-[#fd6f6fa8]');
                $('button#connect').removeClass('bg-[#00ff337c] border-[#94fc4ec8] hover:bg-[#71fd6fa8]');
                $('button#connect').text('Offline');
            }

            if(clients[active].afk){
                $('button#afk').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#afk').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            } else {
                $('button#afk').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#afk').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }

            if(clients[active].autoSell){
                $('button#auto-sell').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#auto-sell').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            } else {
                $('button#auto-sell').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#auto-sell').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }

            if(clients[active].spawner.active){
                $('button#spawner').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#spawner').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            } else {
                $('button#spawner').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#spawner').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }

            if(clients[active].module){
                $('button#module').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#module').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            } else {
                $('button#module').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#module').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }

            if(clients[active].attack){
                $('button#attack').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#attack').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            } else {
                $('button#attack').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#attack').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }

            if(clients[active].agility){
                $('button#agility').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#agility').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            } else {
                $('button#agility').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#agility').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }
            
        });

        $('button#connect').click(function () {
            let x = clients[active].connected;
            if(!x){
                socket.emit('clients:connect', active);
            } else {
                socket.emit('clients:disconnect', active);
            }
        });

        $('button#afk').click(function () {
            let x = clients[active].afk;
            if(!x){
                socket.emit('clients:afk', active);
            } else {
                socket.emit('clients:afk', active);
            }
        });

        $('button#auto-sell').click(function () {
            let x = clients[active].autoSell;
            if(!x){
                socket.emit('clients:autoSell', active);
            } else {
                socket.emit('clients:autoSell', active);
            }
        });

        $('button#spawner').click(function () {
            let x = clients[active].spawner.active;
            if(!x){
                socket.emit('clients:spawner', active);
            } else {
                socket.emit('clients:spawner', active);
            }
        });

        $('button#attack').click(function () {
            let x = clients[active].attack;
            if(!x){
                socket.emit('clients:attack', active);
            } else {
                socket.emit('clients:attack', active);
            }
        });

        $('button#agility').click(function () {
            let x = clients[active].agility;
            if(!x){
                socket.emit('clients:agility', active);
            } else {
                socket.emit('clients:agility', active);
            }
        });

        $('button#module').click(function () {
            let x = clients[active].module;
            if(!x){
                socket.emit('clients:module', active);
            } else {
                socket.emit('clients:module', active);
            }
        });

        $('button#control').click(function () {
            if(!control){
                control = true;
                $('button#control').removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#control').addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                
            } else {
                control = false;
                $('button#control').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#control').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }
        });

        $('button#dropItem').click(function () {
            if(equipItem){
                equipItem = false;
                $('button#equip').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#equip').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }
            if(!dropItem){
                dropItem = true;
                $(this).removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $(this).addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                
            } else {
                dropItem = false;
                $(this).addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $(this).removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }
        });

        $('button#equip').click(function () {
            if(dropItem){
                dropItem = false;
                $('button#dropItem').addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $('button#dropItem').removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }
            if(!equipItem){
                equipItem = true;
                $(this).removeClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $(this).addClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
                
            } else {
                equipItem = false;
                $(this).addClass('border-[#5c88e8] hover:bg-[#104ac6]');
                $(this).removeClass('bg-[#fff200b0] border-[#e8e65c] hover:bg-[#f4eb41]');
            }
        });

        $('button#inventory').click(function () {
            socket.emit('inventory:update', active);
        });

        $('button.server-shortcut').click(function () {
            let command = $(this).data('command');
            socket.emit('clients:chat', active, `/${command}`);
        });
        
        socket.on('clients:chat', (data) => {
            if(clients[active].connected && data.username === clients[active].username){
                if(data.messageText.includes('!module') && clients[active].module){
                    socket.emit('clients:chat', active, data.messageText);
                }
                $('#console').prepend(data.messageHTML);
            }
        });

        $('#chat').submit(function (e) {
            e.preventDefault();

            let msg = $('input[name="messages"]').val();
            if(!msg.trim()) return;
            
            socket.emit('clients:chat', active, msg);
            $('input[name="messages"]').val('');
        });

        $('#sign').submit(function(e) {
            e.preventDefault();

            let signText = [
                $('input[name="text-line-0"]').val(),
                $('input[name="text-line-1"]').val(),
                $('input[name="text-line-2"]').val(),
                $('input[name="text-line-3"]').val()
            ];

            socket.emit('sign:update', active, signText);
        });

        /**
         * Control State
         */

        

        $(document).keydown(function (e) {
            if(control){
                switch (e.key) {
                    case "w":
                        socket.emit('clients:control', active, 'forward', true);
                        break;

                    case "a":
                        socket.emit('clients:control', active, 'left', true);
                        break;
                        
                    case "s":
                        socket.emit('clients:control', active, 'back', true);
                        break;
                        
                    case "d":
                        socket.emit('clients:control', active, 'right', true);
                        break;
                        
                    case " ":
                        socket.emit('clients:control', active, 'jump', true);
                        break;
                        
                    case "Shift":
                        socket.emit('clients:control', active, 'sneak', true);
                        break;
                    
                    default:
                        break;
                }
            }
        });

        $(document).keyup(function (e) {
            if(control){
                switch (e.key) {
                    case "w":
                        socket.emit('clients:control', active, 'forward', false);
                        break;

                    case "a":
                        socket.emit('clients:control', active, 'left', false);
                        break;
                        
                    case "s":
                        socket.emit('clients:control', active, 'back', false);
                        break;
                        
                    case "d":
                        socket.emit('clients:control', active, 'right', false);
                        break;
                        
                    case " ":
                        socket.emit('clients:control', active, 'jump', false);
                        break;
                        
                    case "Shift":
                        socket.emit('clients:control', active, 'sneak', false);
                        break;
                    
                    default:
                        break;
                }
            }
        });

        $('button.inventory').hover(function () {
                let index = $(this).data('slot');
                let item = clients[active].inventory.slots[index];
                
                if(item){
                    $('.inventory-cursor').text(`${item.name} x${item.count}`);
                }
            }, function () {
                $('.inventory-cursor').text('');
            }
        );

        $('button.inventory').click(function () {
            let slot = $(this).data('slot');

            if(dropItem){
                socket.emit('inventory:toss', active, slot);
            }

            if(equipItem){
                socket.emit('inventory:equip', active, slot);
            }
        });

        $('button.virtual').click(function () {
            let slot = $(this).data('slot');

            socket.emit('virtual:click', active, slot);
        });

    </script>
</body>
</html>