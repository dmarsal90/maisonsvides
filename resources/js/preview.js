function previewFile() {
    var preview = document.querySelector('#imagePreview');
    var file = document.querySelector('input[type=file]').files[0];
    var reader = new FileReader();

    reader.addEventListener("load", function() {
        var image = new Image();
        image.src = reader.result;
        preview.innerHTML = '';
        preview.appendChild(image);
    }, false);

    if (file) {
        reader.readAsDataURL(file);
    }
}
