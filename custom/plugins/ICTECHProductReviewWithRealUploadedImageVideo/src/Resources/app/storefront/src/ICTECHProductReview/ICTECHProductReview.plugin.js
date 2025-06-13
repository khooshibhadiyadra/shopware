import Plugin from 'src/plugin-system/plugin.class';
export default class ICTECHProductReview extends Plugin {
    init() {
        this.addEventListeners();
    }
    addEventListeners() {
        document.addEventListener('click', event => {

            if (event.target.closest('.remove-image')) {
                event.preventDefault();
                var imagePreview =  this.el.querySelector('.image-preview-block');

                if(imagePreview){
                    imagePreview.innerHTML = '<input type="hidden" name="removePreviewImage" value="true">';
                }
            }

            if (event.target.closest('.remove-video')) {
                event.preventDefault();
                var videoPreview =  this.el.querySelector('.product-detail-review-form-preview-video');

                if(videoPreview){
                    videoPreview.innerHTML = '<input type="hidden" name="removePreviewVideo" value="true">';
                }
            }
        });

        this.el.querySelector('.btn-review-submit').addEventListener('click', event => {

            let hasValidationError = false;

            const imageData = this.el.querySelector('.product-review-image');
            const videoData = this.el.querySelector('.product-review-video');
            const maxUploadImageSize = this.el.querySelector('.max-upload-image-size');
            if (!maxUploadImageSize) {
                console.error('max-upload-image-size element not found!');
                return;
            }
            const maxUploadVideoSize = this.el.querySelector('.max-upload-video-size');
            const imageErrorElement = this.el.querySelector('.error-message-image');
            const videoErrorElement = this.el.querySelector('.error-message-video');
            const imageErrorMaxUploadElement = this.el.querySelector('.error-message-max-upload-image');
            const videoErrorMaxUploadElement = this.el.querySelector('.error-message-max-upload-video');
            const allowImageFileExtension = this.el.querySelector('.max-upload-image-extension');
            const allowVideoFileExtension = this.el.querySelector('.max-upload-video-extension');

            const imageUrlInput = document.querySelector('.preview-image');
            const imageInput = document.querySelector('input[name="image"]');

            const videoUrlInput = document.querySelector('.preview-video');
            const videoInput = document.querySelector('input[name="video"]');

            if (imageData) {
                if (imageUrlInput != null) {
                    imageInput.required = false;
                    imageErrorElement.style.display = 'none';
                    imageErrorMaxUploadElement.style.display = 'none';
                } else {
                    if (imageInput) {
                        imageInput.required = true;
                    }

                }

                if (imageData.files.length > 0) {

                    const file = imageData.files[0];
                    const extension = file.name.toLowerCase().split('.').pop();
                    const allowedExtensions = allowImageFileExtension.value.split(',').map(e => e.trim().toLowerCase());
                    const maxSizeBytes = parseFloat(maxUploadImageSize.value) * 1048576;

                    if (!allowedExtensions.includes(extension)) {
                        imageErrorElement.style.display = 'block';
                        imageErrorMaxUploadElement.style.display = 'none';
                        imageData.classList.add("is-invalid");
                        hasValidationError = true;
                    } else if (file.size > maxSizeBytes) {
                        imageErrorElement.style.display = 'none';
                        imageErrorMaxUploadElement.style.display = 'block';
                        imageData.classList.add("is-invalid");
                        hasValidationError = true;
                    } else {
                        imageErrorElement.style.display = 'none';
                        imageErrorMaxUploadElement.style.display = 'none';
                        imageData.classList.remove("is-invalid");
                    }
                }
            }

            if (videoData) {
                if (videoUrlInput != null) {
                    videoInput.required = false;

                    videoErrorElement.style.display = 'none';
                    videoErrorMaxUploadElement.style.display = 'none';
                } else {
                    if (videoInput) {
                        videoInput.required = true;
                    }
                }

                if (videoData.files.length > 0) {
                    const file = videoData.files[0];
                    const extension = file.name.toLowerCase().split('.').pop();
                    const allowedExtensions = allowVideoFileExtension.value.split(',').map(e => e.trim().toLowerCase());
                    const maxSizeBytes = parseFloat(maxUploadVideoSize.value) * 1048576;

                    if (!allowedExtensions.includes(extension)) {
                        videoErrorElement.style.display = 'block';
                        videoErrorMaxUploadElement.style.display = 'none';
                        videoData.classList.add("is-invalid");
                        hasValidationError = true;
                    } else if (file.size > maxSizeBytes) {
                        videoErrorElement.style.display = 'none';
                        videoErrorMaxUploadElement.style.display = 'block';
                        videoData.classList.add("is-invalid");
                        hasValidationError = true;
                    } else {
                        videoErrorElement.style.display = 'none';
                        videoErrorMaxUploadElement.style.display = 'none';
                        videoData.classList.remove("is-invalid");
                    }
                }
            }

            if (hasValidationError) {
                event.preventDefault(); // This blocks the form submission
            }
        });

    }
}