require("dotenv").config();
const { Server } = require('socket.io');
const mineflayer = require('mineflayer');
const Vec3 = require("vec3");
const { data } = require("jquery");

const server = new Server(3000, {
    cors: {origin: "*"}
});

class Client {

    constructor(username, client)
    {

    }

}

server.on('connection', (client) => {
    
    /**
     * Handling CLI-based requests from clients.
     */
    client.on('cli', (username, data) => {
        let input = data.trim().split(' ');

        if(input[0].startsWith('!')){
            
            switch (input[0]) {
            case '!connect':
                client.emit('console', {
                    username: username,
                    type: 'pending',
                    message: 'Connecting...'
                });
                break;
                
            case '!disconnect':
                client.emit('console', {
                    username: username,
                    type: 'none',
                    message: 'Disconnect Protocol'
                });
                break;

            case '!create':
                client.emit('console', {
                    username: username,
                    type: 'none',
                    message: 'Create New Client Protocol'
                });
                break;

            default:

                break;
            }

        } else {
            client.emit('console', {
                username: username,
                type: 'none',
                message: 'Chat Protocol'
            });
        }
    });
});