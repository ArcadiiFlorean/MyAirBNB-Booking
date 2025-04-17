let modalImages = [];
let currentIndex = 0;
function scrollLeft(id) {
    const container = document.getElementById(id);
    const image = container.querySelector('img');
    if (container && image) {
      container.scrollBy({ left: -(image.offsetWidth + 8), behavior: 'smooth' }); // 8 = gap
    }
  }
  
  function scrollRight(id) {
    const container = document.getElementById(id);
    const image = container.querySelector('img');
    if (container && image) {
      container.scrollBy({ left: image.offsetWidth + 8, behavior: 'smooth' });
    }
  }
  
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



function openFullGallery() {
    document.getElementById('fullGalleryModal').classList.remove('hidden');
  }
  
  function closeFullGallery() {
    document.getElementById('fullGalleryModal').classList.add('hidden');
  }
  