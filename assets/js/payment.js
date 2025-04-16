var timer;
function updateTime() {
    var expireTime = new Date().getTime() + (15 * 60 * 1000); // 15 phút
    timer = setInterval(function() {
        var now = new Date().getTime();
        var distance = expireTime - now;
        
        var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        var seconds = Math.floor((distance % (1000 * 60)) / 1000);
        
        if (distance < 0) {
            clearInterval(timer);
            window.location.href = BASE_URL + '/packages';
        }
    }, 1000);
}

$(document).ready(function() {
    if ($('.payment-timer').length) {
        updateTime();
    }
});