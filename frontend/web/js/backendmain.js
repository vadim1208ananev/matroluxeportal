$(document).ready(function () {

    $('.datepicker_order').on('changeDate', function (e) {
        window.location.href = window.location.origin + '/backend/orders?date=' + $(this).val();
    });
    $('.datepicker_bonus').on('changeDate', function (e) {
        window.location.href = window.location.origin + '/backend/bonuses?date=' + $(this).val();
    });

});
//alert(yii.getCsrfToken());
var btn_send = document.querySelectorAll('.sendto1c');

btn_send.forEach(function (btn) {
    btn.addEventListener('click', function (e) {
        e.target.innerText='Loading...'
        var complaint_id = e.target.getAttribute('data-id');
        var formdata = new FormData();
        var csrf_token = yii.getCsrfToken();
        formdata.append("complaint_id", complaint_id)
        formdata.append("_csrf-frontend", csrf_token)
        var requestOptions = {
            method: 'POST',
            body: formdata
        }
        fetch('/backend/complaints/send', requestOptions)
            .then(res =>
                res.json()
            ).then(res => {
                if(res.status=='ok')
                {
                    e.target.innerText=res.text
                } else {
                    e.target.innerText='error sending' 
                }
                console.log(res)
            }
            )

        console.log(complaint_id)
    })
})




