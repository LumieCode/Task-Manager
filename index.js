// interferes with the submit process, making changing a value to 'no' if the checkbox wasnt checked and then manually submits
window.onload = function(){
    document.getElementById('myForm').addEventListener('submit', function(event) {

    var checkboxes = document.getElementsByClassName('checkbox');

    for (var i = 0; i < checkboxes.length; i++) {
        var checkbox = checkboxes[i];

        if (checkbox.checked) {
            document.getElementsByName(checkbox.name)[1].value = 'yes';
        } else {
            document.getElementsByName(checkbox.name)[1].value = 'no';
        }
    }
    this.submit();
    });
}