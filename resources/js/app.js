import './bootstrap';
import './jquery';

var logs = JSON.parse(localStorage.getItem("consoleLog") || "[]");
var lS = -1;
var cC = 0;

$('#cli').submit(function (e) {
    e.preventDefault();

    let input = $('#cli input').val();
    if(input.trim()){
        $('#console').prepend(`<div>${input}</div>`);
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

$('#connect').click(function (e) {
    e.preventDefault();

    $(this).toggleClass('bg-red-500');
    $(this).toggleClass('bg-orange-400');
    setTimeout(() => {
        $(this).toggleClass('bg-orange-400');
        $(this).toggleClass('bg-lime-500');
        
    }, 1500);

    $(`button[data-client-index="${cC}"] .indicator`).toggleClass('bg-red-500');
    $(`button[data-client-index="${cC}"] .indicator`).toggleClass('bg-orange-400');
    setTimeout(() => {
        $(`button[data-client-index="${cC}"] .indicator`).toggleClass('bg-orange-400');
        $(`button[data-client-index="${cC}"] .indicator`).toggleClass('bg-lime-500');
        
    }, 1500);
});