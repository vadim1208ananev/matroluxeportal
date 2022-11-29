$(document).ready(function () {

    $('.datepicker_order').on('changeDate', function (e) {
        window.location.href = window.location.origin + '/backend/orders?date=' + $(this).val();
    });
    $('.datepicker_bonus').on('changeDate', function (e) {
        window.location.href = window.location.origin + '/backend/bonuses?date=' + $(this).val();
    });

});


