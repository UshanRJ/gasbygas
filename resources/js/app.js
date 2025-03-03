import './bootstrap';
import 'preline';
import Alpine from 'alpinejs';

document.addEventListener('livewire:navigated', () => { 
    window.HSStaticMethods.autoInit();
})

window.Alpine = Alpine;
Alpine.start();