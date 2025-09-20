require("dotenv").config();

const { Server } = require('socket.io');
const mineflayer = require('mineflayer');
const fs = require('fs');
const Vec3 = require("vec3");
const tpsPlugin = require('mineflayer-tps')(mineflayer);
const { evaluate } = require('mathjs');

const io = new Server(4001, {
    cors: { origin: "*" },
    host: '127.0.0.1'
});

/**
 * Pendefenisian variabel penyimpanan Informasi Client
 */
let clients = JSON.parse(fs.readFileSync("config.json", 'utf-8'))['clients'];
clients.forEach((client) => {
    client['connected'] = false;
    client['afk'] = false;
    client['autoSell'] = false;
    client['onTransfer'] = false;
    client['module'] = false;
    client['attack'] = false;
    client['agility'] = false;
    client['inventory'] = [];
    client['manualDisconnect'] = false; // BARU: Flag untuk disconnect manual
});

let events = {
    'afk': {},
    'autoSell': {},
    'spawner': {},
    'attack': {},
    'agility': {}
};
let instance = {};

// --- BARU: Variabel untuk sistem Antrian & Auto Reconnect ---
let reconnectQueue = [];
let isQueueProcessing = false;
const RECONNECT_DELAY = 15000; // 15 detik delay
// -----------------------------------------------------------

function cleanup(client, socket) {
    if (!client.onTransfer) {
        delete instance[client.username];
        client.connected = false;
        client.afk = false;
        client.autoSell = false;
        if (client.spawner) client.spawner.active = false;
        client.module = false;
        client.attack = false;
        client.agility = false;
        clearInterval(events.afk[client.username]);
        clearInterval(events.autoSell[client.username]);
        clearInterval(events.spawner[client.username]);
        clearInterval(events.agility[client.username]);
        clearInterval(events.attack[client.username]);
        delete events.afk[client.username];
        delete events.autoSell[client.username];
        delete events.spawner[client.username];
        delete events.agility[client.username];
        delete events.attack[client.username];
    }
    socket.emit('clients:update', clients);
}

// --- BARU: Fungsi untuk memproses antrian koneksi ---
function processReconnectQueue(io, socket) {
    if (reconnectQueue.length === 0 || isQueueProcessing) {
        return;
    }

    isQueueProcessing = true;
    const clientIndex = reconnectQueue.shift();
    const client = clients[clientIndex];

    if (client.connected) { // Jika ternyata sudah terhubung, lewati
        isQueueProcessing = false;
        processReconnectQueue(io, socket);
        return;
    }

    console.log(`[Queue] Connecting ${client.username}...`);
    io.emit('clients:reconnect:status', { username: client.username, message: `Connecting now...` });
    instance[client.username] = createBotInstance(client, io, socket);
}
// ----------------------------------------------------

function createBotInstance(client, io, socket) {
    const bot = mineflayer.createBot({
        host: process.env.SERVER_HOST,
        port: process.env.SERVER_PORT,
        username: client.username,
        auth: client.auth,
        version: process.env.CLIENT_VERSION
    });

    bot.loadPlugin(tpsPlugin);

    bot.once('login', () => {
        client.connected = true;
        client.manualDisconnect = false; // Reset flag saat berhasil login

        console.log(`[Login] ${client.username} has successfully connected.`);

        setTimeout(() => {
            if (client.connected && client.auth === "offline") bot.chat(`/login ${client.password}`);
            if (client.server) {
                setTimeout(() => {
                    if (client.connected) bot.chat(`/move ${client.server}`);
                }, 2000);
            }
        }, 1500);

        socket.emit('clients:update', clients);

        // MODIFIKASI: Selesaikan proses saat ini dan panggil proses antrian berikutnya
        isQueueProcessing = false;
        processReconnectQueue(io, socket);
    });

    bot.on('spawn', () => {
        client.inventory = bot.inventory;
        socket.emit('clients:update', clients);
    });

    bot.on('serverAuth', () => {
        client.onTransfer = true;
        socket.emit('clients:update', clients);
    });

    bot.on('move', () => {
        if (client.onTransfer) client.onTransfer = false;
        socket.emit('clients:update', clients);
    });
    
    bot.on('health', async () => {
        const hunger = bot.food;
        if (hunger < 6) {
            const items = bot.inventory.items();
            const foodItem = items.find(item =>
                item.name.includes('cooked') || item.name.includes('bread') || item.name.includes('apple')
            );
            if (foodItem) {
                try {
                    await bot.equip(foodItem, 'hand');
                    await bot.consume();
                    io.emit('clients:eating',{ username: client.username, status: 'success', messages: 'Consuming done!' });
                } catch (err) {
                    io.emit('clients:eating',{ username: client.username, status: 'fail', messages: err });
                }
            } else {
                io.emit('clients:eating',{ username: client.username, status: 'fail', messages: 'Food not found!' });
            }
        }
    });

    bot.on('message', (msg) => {
        io.emit('clients:chat', {
            username: client.username,
            messageHTML: msg.toHTML(),
            messageText: msg.toString()
        });
    });

    // MODIFIKASI: Logika 'end' untuk auto-reconnect
    bot.on('end', (reason) => {
        console.log(`[Disconnect] ${client.username} disconnected. Reason: ${reason}`);
        cleanup(client, socket);

        if (client.manualDisconnect) {
            console.log(`[Queue] ${client.username} was manually disconnected. Won't reconnect.`);
            client.manualDisconnect = false; // Reset flag
            isQueueProcessing = false; // Buka kunci antrian jika terputus manual
            return;
        }

        const clientIndex = clients.findIndex(c => c.username === client.username);
        if (clientIndex !== -1 && !reconnectQueue.includes(clientIndex)) {
            reconnectQueue.push(clientIndex);
            console.log(`[Queue] Added ${client.username} to reconnect queue.`);
            io.emit('clients:reconnect:status', { username: client.username, message: `Added to queue. Reconnecting in ${RECONNECT_DELAY / 1000}s...` });
        }
        
        // Buka kunci antrian, dan jadwalkan proses berikutnya setelah delay
        isQueueProcessing = false;
        setTimeout(() => {
            processReconnectQueue(io, socket);
        }, RECONNECT_DELAY);
    });
    
    bot.on('kicked', (reason) => {
        console.log(`[Kicked] ${client.username}. Reason: ${reason}`);
    });
    bot.on('error', (err) => {
        console.log(`[Error] ${client.username}: ${err}`);
        isQueueProcessing = false; // Buka kunci antrian jika terjadi error
    });

    return bot;
}


io.on('connection', (socket) => {

    socket.emit('clients:list', clients);
    socket.on('clients:list', () => {
        socket.emit('clients:update', clients);
    });

    // MODIFIKASI: Koneksi manual sekarang menggunakan antrian
    socket.on('clients:connect', (index) => {
        let client = clients[index];
        cleanup(client, socket); // Selalu bersihkan state sebelum koneksi baru

        if (!reconnectQueue.includes(index)) {
            reconnectQueue.push(index);
            console.log(`[Queue] Manually adding ${client.username} to connection queue.`);
        }
        processReconnectQueue(io, socket); // Langsung proses tanpa delay
    });

    // MODIFIKASI: Disconnect manual akan mencegah auto-reconnect
    socket.on('clients:disconnect', (index) => {
        const client = clients[index];
        if (instance[client.username]) {
            client.manualDisconnect = true; // Set flag sebelum disconnect
            instance[client.username].end();
        }
    });
    
    socket.on('clients:chat', (index, msg) => {
        let client = clients[index];
        if (client.connected && instance[client.username]) {
            if (msg.includes('!module') && client.module) {
                let modules = msg.trim().split('!module ');
                if (modules[1]) {
                    let arg = modules[1].trim().split(' ');
                    switch (arg[0]) {
                        case "check-tps":
                            let tps = instance[client.username].getTps();
                            instance[client.username].chat(`TPS Server saat ini: ${tps}`);
                            break;
                        case 'check-time':
                            let getTime = new Date();
                            let currentDate = getTime.toLocaleString('id-ID', { dateStyle: 'long' });
                            let currentTime = getTime.toLocaleString('id-ID', { timeStyle: 'long' });
                            let wrapper = `${currentTime} | ${currentDate}`;
                            instance[client.username].chat(wrapper);
                            break;
                        default:
                            console.log('Default Module');
                            break;
                    }
                }
            } else {
                instance[client.username].chat(msg);
            }
        }
    });

    socket.on('clients:afk', (index) => {
        let client = clients[index].username;
        if(!clients[index].afk){
            clients[index].afk = true;
            events.afk[client] = setInterval(() => {
                if (!instance[client]) return;
                instance[client].setControlState('left', true);
                setTimeout(() => {
               if (!instance[client]) return;
                         instance[client].setControlState('left', false);
                    instance[client].setControlState('right', true);
                    setTimeout(() => {
                        if (!instance[client]) return;
                        instance[client].setControlState('right', false);
                    }, 200);
                }, 200);
            }, 15000);
        } else {
            clients[index].afk = false;
            clearInterval(events.afk[client]);
        }
        socket.emit('clients:update', clients);
    });

    socket.on('clients:autoSell', (index, delay = 60000 * 3) => {
        let client = clients[index].username;
        if(!clients[index].autoSell){
            clients[index].autoSell = true;
            if (instance[client]) instance[client].chat('/sell all');
            events.autoSell[client] = setInterval(() => {
                if (!instance[client]) return;
                instance[client].chat('/sell all');
                setTimeout(() => {
                    if (!instance[client]) return;
                    instance[client].chat('/money')
                }, 150);
            }, delay);
        } else {
            clients[index].autoSell = false;
            clearInterval(events.autoSell[client]);
        }
        socket.emit('clients:update', clients);
    });
    
    socket.on('clients:spawner', (index, delay = 60000 * 10) => {
            let client = clients[index].username;
    
            if(!clients[index].spawner.active){
                clients[index].spawner.active = true;
                if(clients[index].spawner.locations.length){
                    clients[index].spawner.locations.forEach((sp, i) => {
                        setTimeout(() => {
                            let xyz = new Vec3(sp.x, sp.y, sp.z);
                            let block = instance[client].blockAt(xyz);
    
                            if(block){
                                instance[client].openBlock(block).then((sp) => {
                                    instance[client].clickWindow(22, 0, 0, (err) => {
                                    if (err) console.error(err);
                                    });
                                }).catch(console.error);
                            } else {
                                console.log('Null');
                            }
    
                        }, 4000 * i);
                    });
                    events.spawner[client] = setInterval((container, i = 0) => {
                        if(clients[index].afk){
                            io.emit('clients:afk', index);
                        }
                        container.forEach((sp, i) => {
                            setTimeout(() => {
                                let xyz = new Vec3(sp.x, sp.y, sp.z);
    
                                try {
                                    instance[client].openBlock(instance[client].blockAt(xyz)).then((sp) => {
                                        instance[client].clickWindow(22, 0, 0, (err) => {
                                        if (err) console.error(err);
                                        });
                                    }).catch(console.error);
                                } catch (err) {
                                    console.log(err);
                                }
                            }, 4000 * i);
    
                            setTimeout(() => {
                                io.emit('clients:afk', index);
                            }, 4000 * container.length);
                        });
                    }, delay, clients[index].spawner.locations);
                }
            } else {
                clients[index].spawner.active = false;
                clearInterval(events.spawner[client]);
            }
            socket.emit('clients:update', clients);
        });
    
    
        socket.on('clients:module', (index) => {
            if(!clients[index].module) {
                clients[index].module = true;
                
            } else {
                clients[index].module = false;
            }
    
            socket.emit('clients:update', clients);
        });
    
        socket.on('clients:control', (index, actions, state) => {
            let client = clients[index];
    
            instance[client.username].setControlState(actions, state);
        });
    
        socket.on('inventory:update', (index) => {
            let client = clients[index];
            client.inventory = instance[client.username].inventory;
            socket.emit('clients:update', clients);
        });
    
        socket.on('inventory:toss', (index, slot) => {
            let client = clients[index];
    
            let item = instance[client.username].inventory.slots[slot];
    
            if(item){
                instance[client.username].tossStack(item);
            }
        });
    
        socket.on('inventory:equip', (index, slot) => {
            let client = clients[index];
    
            let item = instance[client.username].inventory.slots[slot];
    
            if(item){
                instance[client.username].equip(item, 'hand');
            }
        });
    
        socket.on('clients:attack', (index) => {
            let client = clients[index];
            if(!client.attack){
                client.attack = true;
                let target = Object.values(instance[client.username].entities).find(e => e.username === 'ZEINZH');
                
                if(!target) return instance[client.username].chat(`${username} dimana? Masa nyerang angin..`);
                
                events.attack[client.username] = setInterval(() => {
                    if(instance[client.username].entity.position.distanceTo(target.position) < 4){
                        instance[client.username].lookAt(target.position.offset(0, target.height, 0), true);
                        instance[client.username].attack(target);
                    }
    
                }, 800);
            } else {
                client.attack = false;
                clearInterval(events.attack[client.username]);
            }
            socket.emit('clients:update', clients);
        });
    
        socket.on('clients:agility', (index) => {
            let client = clients[index];
    
            if(!client.agility){
                client.agility = true;
                events.agility[client.username] = setInterval(() => {
                    instance[client.username].chat('/homes fall');
                }, 8000);
            } else {
                client.agility = false;
                clearInterval(events.agility[client.username]);
            }
            socket.emit('clients:update', clients);
        });
    
        socket.on('virtual:click', (index, slot) => {
            let client = clients[index];
    
            instance[client.username].clickWindow(slot, 0, 0);
        });
    
        socket.on('sign:update', (index, signText) => {
            let client = clients[index];
            
            let cp = instance[client.username].entity.position;
    
            instance[client.username]._client.write('update_sign', {
                location: {x: cp.x, y: cp.y + 4, z: cp.z},
                text1: signText[0],
                text2: signText[1],
                text3: signText[2],
                text4: signText[3],
            });
        });
});