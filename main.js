function loadImages(){
    $.post( './index.php',{
        isGetImgs: true
    }, (data)=>{
        var imgsList = JSON.parse(data);   // <-- Тут были изменения
        console.log(imgsList)
        var holder = document.getElementById('gallery-area')
        holder.innerHTML = ''
        imgsList.forEach(img => {
            holder.innerHTML += `
                <div class="grid-el" style="background-image: url('./uploads/${img[0]}')">
                    <p class="caption">${img[1]}</p>
                </div>
            `
        });
    });
}

$(document).on('click', '.grid-el', (event) => {
    event.stopPropagation();
    event.stopImmediatePropagation();

    const target = $(event.currentTarget);
    if (target.hasClass('change-fs')) {
        target.removeClass('change-fs');
    } else {
        target.addClass('change-fs');
    }
});


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
    $('#confirmUpload').show(); // Показываем кнопку загрузки
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
    formData.append('caption', $("#caption").val());

    $.ajax({
        url: './index.php', // Адрес PHP-скрипта
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function (response) {
            console.log(response);
            $('#output').hide();
            $('#confirmUpload').hide();
            loadImages();
        },
        error: function (xhr, status, error) {
            alert('File upload failed: ' + error);
        }
    });
    
});