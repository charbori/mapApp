// Example starter JavaScript for disabling form submissions if there are invalid fields
(function () {
    'use strict'

    // Fetch all the forms we want to apply custom Bootstrap validation styles to
    var forms = document.querySelectorAll('.needs-validation')

    // Loop over them and prevent submission
    Array.prototype.slice.call(forms)
      .forEach(function (form) {
        form.addEventListener('submit', function (event) {
          if (!form.checkValidity()) {
            event.preventDefault()
            event.stopPropagation()
          }

          form.classList.add('was-validated')
        }, false)
      })
})()

var id = 2;
function addImage(id) {
    jQuery('<svg class="bd-placeholder-img img-thumbnail" width="150" height="150" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="A generic square placeholder image with a white border around it, making it resemble a photograph taken with an old instant camera: 200x200" preserveAspectRatio="xMidYMid slice" focusable="false"><text x="50%" y="50%" fill="#dee2e6" dy=".3em">+</text><text x="90%" y="10%" class="btn-close" dy=".3em">x</text><image href="" height="85" width="85"/></svg>').insertAfter('#img_' + (id - 1));
}
function setImage(id) {
    jQuery('.real-upload').click();
    jQuery('svg[id*=img_]');
}
$(function() {
    $('.real-upload').change(function(e) {
        const files = e.currentTarget.files;

        if ([...files].length >= 7) {
          alert('이미지는 최대 6개까지 업로드가 가능합니다.');
          return;
        }
        jQuery('#img_' + id + ' image').attr('src', e.target.result);
        jQuery('#img_' + id + ' image').attr('src', files[0].name);
    });
});
