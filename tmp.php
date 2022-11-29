const comment = document.querySelector('input[name="comment"]').value;
Array.prototype.slice.call(columns).forEach(function (column) {
let inputs = column.querySelectorAll('input');
if (inputs.length == 0) return; //ToDo не знаю, откуда берется пустой inputs
arr[i] = {};
Array.prototype.slice.call(inputs).forEach(function (input) {
arr[i][input.name] = input.value;
});
i++;
});
// let form = document.querySelector('form');
// form.submit();
let request = new XMLHttpRequest();
let url = "/spec/create";
let params = "spec=" + JSON.stringify(arr) + "&" + "comment=" + comment;
