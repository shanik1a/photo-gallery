function loadImages(){
    $.post( './index.php',{
        isGetImgs: true
    }, (data)=>{
        var imgsList = data.slice(4, -4).split("\"],[\"");
        var holder = document.getElementById('gallery-area')
        holder.innerHTML = ''
        imgsList.forEach(img => {
            holder.innerHTML += `
                <img class="gallery-unit" src="./uploads/${img}"><br>
            `
        });
    });
}

loadImages()

$("#exit-button").on("click", () => {
    $.post("./index.php", {
        isExit:true
    }, ()=>{document.location.reload()})
})

// Обработчик выбора файла
$('#file').on('change', function (event) {
    var image = document.getElementById('output');
    selectedFile = event.target.files[0];
    image.src = URL.createObjectURL(selectedFile);
    $('#uploadButton').show(); // Показываем кнопку загрузки
    $('#output').show();
});

// Обработчик загрузки файла
$('#uploadButton').on('click', function () {
    if (!selectedFile) {
        alert('Please select a file first!');
        return;
    }

    var formData = new FormData();
    formData.append('image', selectedFile);

    $.ajax({
        url: './index.php', // Адрес PHP-скрипта
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            $('#output').hide();
            $('#uploadButton').hide();
            loadImages();
        },
        error: function (xhr, status, error) {
            alert('File upload failed: ' + error);
        }
    });
    
});