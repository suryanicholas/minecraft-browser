import './bootstrap';
import './jquery';

var logs = JSON.parse(localStorage.getItem("consoleLog") || "[]");
var lS = -1;
var cC = null;
const ws = io('http://127.0.0.1:3000');
const cP = {
    success: 'text-green-400',
    pending: 'text-gray-300',
    error: "text-red-500",
    warn: "text-yellow-400",
    none: 'text-white'
};

ws.on('console', (data) => {
    $('#console').prepend(`<div class="${cP[data.type]}">${data.message}</div>`);
});

$('#clients').click(function (e) {
    $('aside.group').toggleClass('hidden');
    $('aside.group').toggleClass('flex');
});

$('button.clients').click(function (e) {
    let username = $(this).data('client-username');
    if(cC != username){
        cC = username;

        $('button.clients').removeClass('bg-[#510000a5]');
        $('button.clients').addClass('bg-[#3100058e]');
        $(this).toggleClass('bg-[#3100058e]');
        $(this).toggleClass('bg-[#510000a5]');

        $('#console').prepend(`<div class="text-white">${username} ditampilkan.</div>`);
    } else {
        $('#console').prepend(`<div class="${cP.warn}">${username} sedang ditampilkan saat ini.</div>`);
    }
});

$('#cli').submit(function (e) {
    e.preventDefault();

    let input = $('#cli input').val();
    if(input.trim()){
        ws.emit('cli', "testuser",input);
        $('#cli input').val('');

        if(logs[0] != input){
            logs.unshift(input);
            
            if(logs.length > 10){
                logs.length = 10;
            }
            localStorage.setItem('consoleLog', JSON.stringify(logs));
        }
        lS = -1;
    }
});

$('#cli input').keydown(function (e) {
    let lL = logs.length - 1;
    switch (e.key) {
        case "ArrowUp":
            e.preventDefault();
            if(!(lS >= lL)){
                if(lS < lL){
                    lS++;
                }
                $('#cli input').val(logs[lS]);
            }
            break;
        
        case "ArrowDown":
            if(lS > -1){
                lS--;
            }
            if(lS <= -1){
                $('#cli input').val('');
            } else {
                $('#cli input').val(logs[lS]);
                
            }
        break;
    
        default:
            break;
    }

});
