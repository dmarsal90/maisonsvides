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

function previewFiles(event) {
    var previewImages = document.getElementById("previewImages");
    var previewDocuments = document.getElementById("previewDocuments");

    var imageFiles = [];
    var docFiles = [];

    var files = event.target.files;
    for (var i = 0; i < files.length; i++) {
        var file = files[i];
        var fileType = file.type.split("/")[0];

        if (fileType === "image") {
            imageFiles.push(file);
        } else {
            docFiles.push(file);
        }
    }

    for (var i = 0; i < imageFiles.length; i++) {
        var img = document.createElement("img");
        img.src = URL.createObjectURL(imageFiles[i]);
        img.classList.add("preview-image");
        previewImages.appendChild(img);
    }

    for (var i = 0; i < docFiles.length; i++) {
        var p = document.createElement("p");
        p.innerText = docFiles[i].name;
        previewDocuments.appendChild(p);
    }
}
