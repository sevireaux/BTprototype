const menuBtn = document.getElementById('menuBtn');
const closeBtn = document.getElementById('closeBtn');
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebarOverlay');
       
function openSidebar() {
sidebar.classList.remove('translate-x-full');
document.body.style.overflow = 'hidden';
}
        
function closeSidebar() {
sidebar.classList.add('translate-x-full');
document.body.style.overflow = '';
}
   
menuBtn.addEventListener('click', openSidebar);
closeBtn.addEventListener('click', closeSidebar);
sidebarOverlay.addEventListener('click', closeSidebar);
   
// Close sidebar on escape key

document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') {
        closeSidebar();
    }
});
