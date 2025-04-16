
function scrollLeft(id) {
    const container = document.getElementById(id);
    container.scrollBy({ left: -300, behavior: 'smooth' });
}

function scrollRight(id) {
    const container = document.getElementById(id);
    container.scrollBy({ left: 300, behavior: 'smooth' });
}

let modalImages = [];
let currentIndex = 0;

function openModal(images, index) {
    modalImages = images;
    currentIndex = index;
    document.getElementById('modalImage').src = modalImages[currentIndex];
    document.getElementById('imageModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('imageModal').classList.add('hidden');
}

function nextImage() {
    currentIndex = (currentIndex + 1) % modalImages.length;
    document.getElementById('modalImage').src = modalImages[currentIndex];
}

function prevImage() {
    currentIndex = (currentIndex - 1 + modalImages.length) % modalImages.length;
    document.getElementById('modalImage').src = modalImages[currentIndex];
}

function scrollLeft(id) {
    const container = document.getElementById(id);
    container.scrollBy({ left: -300, behavior: 'smooth' });
}

function scrollRight(id) {
    const container = document.getElementById(id);
    container.scrollBy({ left: 300, behavior: 'smooth' });
}
